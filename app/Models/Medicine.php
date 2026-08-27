<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'price',
        'stock',
        'description',
        'expiry_date',
        'status',
        'supplier_id',
        'user_id'
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];

    // Medicine Categories
    const CATEGORIES = [
        'TABLET' => 'TABLET',
        'CAPSULE' => 'CAPSULE',
        'SYRUP' => 'SYRUP',
        'SUSPENSION' => 'SUSPENSION',
        'INJECTION' => 'INJECTION',
        'OINTMENT' => 'OINTMENT',
        'DROPS' => 'DROPS',
        'INHALER' => 'INHALER',
        'VACCINE' => 'VACCINE',
        'ANTIBIOTIC' => 'ANTIBIOTIC',
        'ANALGESIC' => 'ANALGESIC',
        'ANTACID' => 'ANTACID',
        'VITAMIN' => 'VITAMIN',
        'SUPPLEMENT' => 'SUPPLEMENT',
        'OTHER' => 'OTHER',
        'CREAM' => 'CREAM',
        'INSULIN' => 'INSULIN',
        'NEEDLE' => 'NEEDLE',
        'SOLUTION' => 'SOLUTION',
        'GEL' => 'GEL',
        'GARGLE SOLUTION' => 'GARGLE SOLUTION',
        'LOTION' => 'LOTION',
        'SYRINGE' => 'SYRINGE',
        'RESPULES' => 'RESPULES',
        'STRIP' => 'STRIP',
        'SACHET' => 'SACHET',
        'SOAP' => 'SOAP',
        'SHAMPOO' => 'SHAMPOO',
        'POWDER' => 'POWDER',
        'CHOCOLATE' => 'CHOCOLATE',
        'AMPULE' => 'AMPULE',
    ];

    // Status options
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_DISCONTINUED = 'discontinued';

    const STATUSES = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_INACTIVE => 'Inactive',
        self::STATUS_DISCONTINUED => 'Discontinued'
    ];

    public static function generateBulkOrderInvoiceNumber()
    {
        $prefix = 'BULK-ORD-';
        $lastOrder = static::bulkOrders()->latest()->first();

        if ($lastOrder) {
            $lastNumber = intval(str_replace($prefix, '', $lastOrder->invoice_number));
            return $prefix . str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
        }

        return $prefix . '000001';
    }


    // Relationship with supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Relationship with user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope for active medicines
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    // Scope for low stock
    public function scopeLowStock($query)
    {
        return $query->where('stock', '<=', 10);
    }

    // Scope for expired medicines
    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<', now());
    }

    // Scope for available medicines
    public function scopeAvailable($query)
    {
        return $query->active()
            ->where('expiry_date', '>', now())
            ->where('stock', '>', 0);
    }

    // Scope for medicines by supplier
    public function scopeBySupplier($query, $supplierId)
    {
        return $query->where('supplier_id', $supplierId);
    }

    // Check if medicine is expired
    public function isExpired()
    {
        return $this->expiry_date && $this->expiry_date < now();
    }

    // Check if low stock
    public function isLowStock()
    {
        return $this->stock <= 10;
    }

    // Check if out of stock
    public function isOutOfStock()
    {
        return $this->stock <= 0;
    }

    // Get category name
    public function getCategoryNameAttribute()
    {
        return self::CATEGORIES[$this->category] ?? $this->category;
    }

    // Get status name
    public function getStatusNameAttribute()
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    // Get supplier name
    public function getSupplierNameAttribute()
    {
        return $this->supplier ? $this->supplier->name : 'N/A';
    }

    // Reduce stock method
    public function reduceStock($quantity)
    {
        if ($this->stock >= $quantity) {
            $this->decrement('stock', $quantity);
            return true;
        }
        return false;
    }

    // Increase stock method
    public function increaseStock($quantity)
    {
        $this->increment('stock', $quantity);
    }

    // Check if sufficient stock is available
    public function hasSufficientStock($quantity)
    {
        return $this->stock >= $quantity;
    }

    // In your Medicine model
    public function restoreStock($quantity)
    {
        $this->stock += $quantity;
        $this->save();
        return $this;
    }

    // Accessor for decoded name
    public function getDecodedNameAttribute()
    {
        return \App\Helpers\StringHelper::decodeQuotes($this->name);
    }
}
