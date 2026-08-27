<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpMedicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'inpatient_register_id',
        'medicine_id',
        'morning',
        'afternoon',
        'night',
        'no_of_days',
        'quantity',
        'price',
        'instructions',
        'user_id',
        'status',
        'discount_percentage',
        'discount_amount',
        'issued_at',
        'issued_by',
        // New field from migration
        'paid_amount'
    ];

    protected $casts = [
        'morning' => 'boolean',
        'afternoon' => 'boolean',
        'night' => 'boolean',
        'price' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'issued_at' => 'datetime',
    ];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function inpatientRegister()
    {
        return $this->belongsTo(InpatientRegister::class);
    }

    // Relationship for issued_by
    public function issuedByUser()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    /**
     * Scope for today's medicines
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
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
        return $this->status === 'issued' || $this->status === 'active';
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

    /**
     * Get remaining amount to pay
     */
    public function getRemainingAmountAttribute()
    {
        $total = $this->quantity * ($this->price - $this->discount_amount);
        return max(0, $total - $this->paid_amount);
    }

    /**
     * Check if medicine is fully paid
     */
    public function getIsFullyPaidAttribute()
    {
        $total = $this->quantity * ($this->price - $this->discount_amount);
        return $this->paid_amount >= $total;
    }

    /**
     * Check if medicine is partially paid
     */
    public function getIsPartiallyPaidAttribute()
    {
        $total = $this->quantity * ($this->price - $this->discount_amount);
        return $this->paid_amount > 0 && $this->paid_amount < $total;
    }

    /**
     * Check if medicine is unpaid
     */
    public function getIsUnpaidAttribute()
    {
        return $this->paid_amount == 0;
    }

    /**
     * Get formatted status
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'active' => '<span class="badge bg-success">Active</span>',
            'inactive' => '<span class="badge bg-secondary">Inactive</span>',
            'pending' => '<span class="badge bg-warning">Pending</span>',
            'cancelled' => '<span class="badge bg-danger">Cancelled</span>',
        ];

        return $badges[$this->status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }
}
