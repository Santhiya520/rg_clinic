<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpRadiology extends Model
{
    use HasFactory;

    protected $fillable = [
        'op_register_id',
        'radiology_test_id',
        'price',
        'notes',
        'result',
        'result_document',
        'status',
        'paid_amount',
        'completed_at',
        'user_id'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'completed_at' => 'datetime'
    ];

    public function opRegister()
    {
        return $this->belongsTo(OpRegister::class, 'op_register_id');
    }

    public function radiologyTest()
    {
        return $this->belongsTo(RadiologyTest::class, 'radiology_test_id');
    }
}
