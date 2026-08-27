<?php
// app/Models/MedicineSaleItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineSaleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'medicine_sale_id',
        'medicine_id',
        'quantity',
        'unit_price',
        'discount_percent',
        'discount_amount',
        'final_amount'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_amount' => 'decimal:2',
    ];

    // Relationships
    public function sale()
    {
        return $this->belongsTo(MedicineSale::class, 'medicine_sale_id');
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    // Accessor for calculated fields
    public function getOriginalAmountAttribute()
    {
        return $this->quantity * $this->unit_price;
    }
}
