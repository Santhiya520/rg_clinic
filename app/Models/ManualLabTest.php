<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManualLabTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'reference_no',
        'notes',
        'total_amount',
        'paid_amount',
        'payment_status',
        'payment_type',
        'test_status',
        'user_id'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'completed_at' => 'datetime'
    ];

    
    // Generate reference number
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->reference_no = 'MLT' . date('Ymd') . str_pad(static::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
        });
    }

    // Relationships
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function items()
    {
        return $this->hasMany(ManualLabTestItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('test_status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('test_status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('test_status', 'cancelled');
    }

    // Calculate due amount
    public function getDueAmountAttribute()
    {
        return $this->total_amount - $this->paid_amount;
    }

    // Update total amount from items
    public function updateTotalAmount()
    {
        $total = $this->items()->sum('price');
        $this->update(['total_amount' => $total]);
        return $this;
    }
}
