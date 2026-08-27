<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpRadiology extends Model
{
    use HasFactory;

    protected $table = 'ip_radiologies';

    protected $fillable = [
        'inpatient_register_id',
        'radiology_test_id',
        'price',
        'notes',
        'result',
        'result_document',
        'status',
        'paid_amount',
        'completed_at',
        'user_id',
        // New fields from migration
        'issued_by',
        'issued_at'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'completed_at' => 'datetime',
        'issued_at' => 'datetime',
    ];

    // Relationships
    public function inpatientRegister()
    {
        return $this->belongsTo(InpatientRegister::class, 'inpatient_register_id');
    }

    public function radiologyTest()
    {
        return $this->belongsTo(RadiologyTest::class, 'radiology_test_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship for issued_by
    public function issuedBy()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    /**
     * Scope for today's tests
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Scope for issued by not 1
     */
    public function scopeNotIssuedByOne($query)
    {
        return $query->where('issued_by', '!=', 1)->orWhereNull('issued_by');
    }

    /**
     * Get remaining amount to pay
     */
    public function getRemainingAmountAttribute()
    {
        return max(0, $this->price - $this->paid_amount);
    }

    /**
     * Check if test is fully paid
     */
    public function getIsFullyPaidAttribute()
    {
        return $this->paid_amount >= $this->price;
    }

    /**
     * Check if test is partially paid
     */
    public function getIsPartiallyPaidAttribute()
    {
        return $this->paid_amount > 0 && $this->paid_amount < $this->price;
    }

    /**
     * Check if test is unpaid
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
            'pending' => '<span class="badge bg-warning">Pending</span>',
            'completed' => '<span class="badge bg-success">Completed</span>',
            'cancelled' => '<span class="badge bg-danger">Cancelled</span>',
            'partial' => '<span class="badge bg-info">Partial</span>',
        ];

        return $badges[$this->status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }
}
