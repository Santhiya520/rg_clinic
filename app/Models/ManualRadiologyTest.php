<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManualRadiologyTest extends Model
{
    protected $fillable = [
        'reference_no', 'patient_id', 'notes', 'total_amount',
        'paid_amount', 'payment_status', 'payment_type', 'test_status', 'user_id', 'completed_at'
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(ManualRadiologyTestItem::class);
    }

    public function updateTotalAmount()
    {
        $total = $this->items()->sum('price');
        $this->update(['total_amount' => $total]);
    }

    // Generate reference number
    public static function generateReferenceNo()
    {
        $prefix = 'MRT-';
        $date = now()->format('Ymd');
        $lastTest = self::where('reference_no', 'like', $prefix . $date . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastTest) {
            $lastNumber = intval(substr($lastTest->reference_no, -3));
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return $prefix . $date . $newNumber;
    }
}
