<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManualLabTestItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'manual_lab_test_id',
        'lab_test_id',
        'price',
        'status',
        'result',
        'notes',
        'result_document',
        'technician_id',
        'completed_at'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'completed_at' => 'datetime'
    ];

    public function manualLabTest()
    {
        return $this->belongsTo(ManualLabTest::class);
    }

    public function labTest()
    {
        return $this->belongsTo(LabTest::class);
    }

    public function subTests()
    {
        return $this->hasMany(ManualLabTestSubTest::class);
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    // Helper method to check if test is completed
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    // Auto-set completed_at when status changes to completed
    public static function boot()
    {
        parent::boot();

        static::updating(function ($item) {
            if ($item->isDirty('status') && $item->status === 'completed') {
                $item->completed_at = now();
            }
        });
    }
}
