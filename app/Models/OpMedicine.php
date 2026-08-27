<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpMedicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'op_register_id',
        'medicine_id',
        'morning',
        'afternoon',
        'night',
        'sos',
        'ml',
        'im_route',
        'iv_route',
        'id_route',
        'sub_q_route',
        'no_of_days',
        'quantity',
        'price',
        'instructions',
        'user_id',
        'status',
        'discount_percentage',
        'discount_amount',
        'issued_at',
        'issued_by'
    ];

    protected $casts = [
        'morning' => 'boolean',
        'afternoon' => 'boolean',
        'night' => 'boolean',
        'price' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'issued_at' => 'datetime',
        'sos' => 'boolean',
        'ml' => 'boolean',
        'im_route' => 'boolean',
        'iv_route' => 'boolean',
        'id_route' => 'boolean',
        'sub_q_route' => 'boolean',
    ];

    public function opRegister()
    {
        return $this->belongsTo(OpRegister::class);
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    // Calculate discounted price
    public function getDiscountedPriceAttribute()
    {
        return $this->price - $this->discount_amount;
    }

    // Calculate total price with discount
    public function getTotalAttribute()
    {
        return $this->quantity * $this->getDiscountedPriceAttribute();
    }

    // Calculate original total (without discount)
    public function getOriginalTotalAttribute()
    {
        return $this->quantity * $this->price;
    }

    // Check if medicine is issued
    public function getIsIssuedAttribute()
    {
        return $this->status === 'issued';
    }

    // Check if medicine is pending
    public function getIsPendingAttribute()
    {
        return $this->status === 'pending';
    }

    // Check if medicine is cancelled
    public function getIsCancelledAttribute()
    {
        return $this->status === 'cancelled';
    }

    // Calculate discount percentage if amount is set
    public function getCalculatedDiscountPercentageAttribute()
    {
        if ($this->price > 0 && $this->discount_amount > 0) {
            return ($this->discount_amount / $this->price) * 100;
        }
        return $this->discount_percentage;
    }
}
