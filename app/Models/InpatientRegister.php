<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InpatientRegister extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'hospital_ip_no',
        'date_of_admission',
        'provisional_diagnosis',
        'investigations',
        'final_diagnosis',
        'treatment',
        'date_of_discharge',
        'result',
        'additional_info',
        'medical_officer_id',
        'status',
        'overall_discount_percentage',
        'overall_discount_amount',
        'paid_amount',
        'user_id',
        // New payment fields
        'payment_type',
        'payment_reference',
        'paid_status',
        'paid_at',
        'pharmacy_amount',
        'lab_total_amount',
        'radiology_total_amount',
        'total_discount',
        'total'
    ];

    protected $casts = [
        'date_of_admission' => 'date',
        'date_of_discharge' => 'date',
        'overall_discount_percentage' => 'decimal:2',
        'overall_discount_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'pharmacy_amount' => 'decimal:2',
        'lab_total_amount' => 'decimal:2',
        'radiology_total_amount' => 'decimal:2',
        'total_discount' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    // Relationships
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'medical_officer_id');
    }

    public function medicalOfficer()
    {
        return $this->doctor();
    }

    public function medicines()
    {
        return $this->hasMany(IpMedicine::class);
    }

    public function ipLabTests()
    {
        return $this->hasMany(IpLabTest::class, 'inpatient_register_id');
    }

    /**
     * Get all radiology tests for this inpatient register
     */
    public function ipRadiologies()
    {
        return $this->hasMany(IpRadiology::class, 'inpatient_register_id');
    }

    public function radiologyTests()
    {
        return $this->hasMany(IpRadiology::class);
    }

    public function labTests()
    {
        return $this->hasMany(IpLabTest::class);
    }

    // Generate IP Number
    public static function generateIpNumber()
    {
        $latest = self::latest()->first();
        $number = $latest ? intval(substr($latest->hospital_ip_no, 3)) + 1 : 1;
        return 'IP-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    // New helper methods for pharmacy
    public function getPharmacyPaidStatusAttribute()
    {
        return $this->paid_status ?? 'pending';
    }

    public function getPharmacyTotalAttribute()
    {
        return $this->paid_amount ?? 0;
    }

    public function getPharmacyIssuedAtAttribute()
    {
        return $this->paid_at;
    }

    /**
     * Get today's medicines count
     */
    public function getTodayMedicinesCountAttribute()
    {
        return $this->medicines()
            ->whereDate('created_at', today())
            ->count();
    }

    /**
     * Get today's lab tests count
     */
    public function getTodayLabTestsCountAttribute()
    {
        return $this->ipLabTests()
            ->whereDate('created_at', today())
            ->count();
    }

    /**
     * Get today's radiology tests count
     */
    public function getTodayRadiologyTestsCountAttribute()
    {
        return $this->ipRadiologies()
            ->whereDate('created_at', today())
            ->count();
    }

    /**
     * Check if patient has any today's items
     */
    public function getHasTodayItemsAttribute()
    {
        return $this->today_medicines_count > 0 ||
               $this->today_lab_tests_count > 0 ||
               $this->today_radiology_tests_count > 0;
    }

    /**
     * Get total bill amount (for display purposes)
     */
    public function getTotalBillAmountAttribute()
    {
        return $this->total ?? 0;
    }

    /**
     * Get balance amount
     */
    public function getBalanceAmountAttribute()
    {
        $total = $this->total ?? 0;
        $paid = $this->paid_amount ?? 0;
        return max(0, $total - $paid);
    }

    /**
     * Check if fully paid
     */
    public function getIsFullyPaidAttribute()
    {
        return $this->paid_status == 'paid';
    }
}
