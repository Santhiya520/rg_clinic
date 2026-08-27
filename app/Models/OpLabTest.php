<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpLabTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'op_register_id',
        'lab_test_id',
        'price',
        'notes',
        'result',
        'result_document',
        'paid_amount',
        'status',
        'completed_at',
        'user_id'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'completed_at' => 'datetime'
    ];

    public function subTests()
    {
        return $this->hasMany(OpLabSubTest::class)->orderBy('order');
    }


    // Relationships
    public function opRegister()
    {
        return $this->belongsTo(OpRegister::class);
    }

    public function labTest()
    {
        return $this->belongsTo(LabTest::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }
}
