<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpRegister extends Model
{
    use HasFactory;

    protected $fillable = [
        'injection_fees', // Add this
        'gst_percentage', // Add this
        'gst_amount', // Add this
        'patient_id',
        'token_number',
        'date',
        'provisional_diagnosis',
        'investigations',
        'final_diagnosis',
        'treatment',
        'result',
        'additional_information',
        'medical_officer_id',
        'status',
        'doctor_fees',
        'pharmacy_amount',
        'total',
        'paid_status',
        'pharmacy_issued_at',
        'overall_discount_percentage',
        'overall_discount_amount',
        'paid_amount',
        'user_id',
        // NEW FIELDS
        'op_no',
        'weight',
        'height',
        'pluse',
        'spo2',
        'bp',
        'temparature',
        'comorbidities',
        'history',
        // Payment related fields
        'payment_type',
        'payment_reference',
        'paid_at',
        // Lab and radiology totals
        'lab_total_amount',
        'radiology_total_amount',
        'total_discount', // Add this if missing
        'procedure_amount'
    ];

    protected $casts = [
        'date' => 'datetime',
        'overall_discount_percentage' => 'decimal:2',
        'overall_discount_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'pharmacy_issued_at' => 'datetime',
        'paid_at' => 'datetime',
        'doctor_fees' => 'decimal:2',
        'pharmacy_amount' => 'decimal:2',
        'lab_total_amount' => 'decimal:2',
        'radiology_total_amount' => 'decimal:2',
        'total_discount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    // Relationship with patient
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    // Relationship with medical officer (doctor)
    public function medicalOfficer()
    {
        return $this->belongsTo(User::class, 'medical_officer_id');
    }

    // Relationship with medicines
    public function medicines()
    {
        return $this->hasMany(OpMedicine::class);
    }

    // Relationship with radiology tests
    public function radiologies()  // Changed from radiologyTests to match controller
    {
        return $this->hasMany(OpRadiology::class, 'op_register_id');
    }

    public function radiologyTests()
    {
        return $this->hasMany(OpRadiology::class);
    }

    // Relationship with lab tests
    public function labTests()
    {
        return $this->hasMany(OpLabTest::class, 'op_register_id');
    }

    // Generate token number for today
    public static function generateTokenNumber()
    {
        $today = now()->format('Y-m-d');
        $lastToken = static::whereDate('created_at', $today)->max('token_number');

        return $lastToken ? $lastToken + 1 : 15;
    }

    // Generate OP number
    public static function generateOpNo()
    {
        $lastOp = static::latest()->first();
        $counter = $lastOp ? intval(substr($lastOp->op_no, -3)) + 1 : 1;

        return 'OP' . date('Ymd') . str_pad($counter, 3, '0', STR_PAD_LEFT);
    }

    // Result options
    public static function getResultOptions()
    {
        return [
            'cured' => 'Cured',
            'same_condition' => 'Same Condition',
            'referred' => 'Referred',
            'expired' => 'Expired'
        ];
    }

    // Status options
    public static function getStatusOptions()
    {
        return [
            'registered' => 'Registered',
            'in_progress' => 'In Progress',
            'completed' => 'Completed'
        ];
    }

    // Get total bill amount
    public function getTotalBillAttribute()
    {
        $medicineTotal = $this->medicines->sum(function ($medicine) {
            return ($medicine->quantity * $medicine->price) - ($medicine->discount_amount ?? 0);
        });

        $labTotal = $this->labTests->sum('paid_amount') ?: $this->lab_total_amount;
        $radiologyTotal = $this->radiologies->sum('paid_amount') ?: $this->radiology_total_amount;
        $doctorFees = $this->doctor_fees ?? 0;

        return $medicineTotal + $labTotal + $radiologyTotal + $doctorFees;
    }

    // Check if payment is completed
    public function getIsPaidAttribute()
    {
        return $this->paid_status === 'paid' && $this->paid_amount >= $this->total;
    }
}
