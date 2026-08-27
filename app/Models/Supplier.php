<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_person',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'website',
        'tax_number',
        'payment_terms',
        'status',
        'notes',
        'user_id'
    ];

    // Supplier Status
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_BLACKLISTED = 'blacklisted';

    const STATUSES = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_INACTIVE => 'Inactive',
        self::STATUS_BLACKLISTED => 'Blacklisted'
    ];

    // Payment Terms
    const PAYMENT_IMMEDIATE = 'immediate';
    const PAYMENT_NET_7 = 'net_7';
    const PAYMENT_NET_15 = 'net_15';
    const PAYMENT_NET_30 = 'net_30';
    const PAYMENT_NET_60 = 'net_60';

    const PAYMENT_TERMS = [
        self::PAYMENT_IMMEDIATE => 'Immediate',
        self::PAYMENT_NET_7 => 'Net 7 Days',
        self::PAYMENT_NET_15 => 'Net 15 Days',
        self::PAYMENT_NET_30 => 'Net 30 Days',
        self::PAYMENT_NET_60 => 'Net 60 Days'
    ];

    // Scope for active suppliers
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    // Get status name
    public function getStatusNameAttribute()
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    // Get payment terms name
    public function getPaymentTermsNameAttribute()
    {
        return self::PAYMENT_TERMS[$this->payment_terms] ?? $this->payment_terms;
    }

    // Relationship with medicines supplied
    public function medicines()
    {
        return $this->hasMany(Medicine::class);
    }

    // Check if supplier is active
    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }
    // Count medicines supplied by this supplier
    public function getMedicinesCountAttribute()
    {
        return $this->medicines()->count();
    }

    // Get active medicines count
    public function getActiveMedicinesCountAttribute()
    {
        return $this->medicines()->active()->count();
    }

}
