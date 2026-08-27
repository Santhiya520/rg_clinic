<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicinePurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', // Add this at the beginning
        'payment_type',
        'invoice_number',
        'purchase_date',
        'supplier_id',
        'supplier_name',
        'supplier_phone',
        'supplier_address',
        'total_amount',
        'paid_amount',
        'due_amount',
        'payment_status',
        'notes',
        'user_id'
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'due_amount' => 'decimal:2',
    ];

    // Purchase Types - ADD THESE CONSTANTS
    const TYPE_REGULAR = 'regular';
    const TYPE_BULK_ORDER = 'bulk_order';

    const TYPES = [
        self::TYPE_REGULAR => 'Regular Purchase',
        self::TYPE_BULK_ORDER => 'Bulk Order'
    ];

    public function items()
    {
        return $this->hasMany(MedicinePurchaseItem::class);
    }

    // Add supplier relationship
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Scope for bulk orders - ADD THIS
    public function scopeBulkOrders($query)
    {
        return $query->where('type', self::TYPE_BULK_ORDER);
    }

    // Scope for regular purchases - ADD THIS
    public function scopeRegular($query)
    {
        return $query->where('type', self::TYPE_REGULAR);
    }

    // Generate invoice number for regular purchases
    public static function generateInvoiceNumber()
    {
        $prefix = 'MED-INV-';
        $lastInvoice = static::latest()->first();

        if ($lastInvoice) {
            $lastNumber = intval(str_replace($prefix, '', $lastInvoice->invoice_number));
            return $prefix . str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
        }

        return $prefix . '000001';
    }

    // Generate invoice number for bulk orders - ADD THIS METHOD
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

    // Get type name - ADD THIS
    public function getTypeNameAttribute()
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    // Check if it's a bulk order - ADD THIS
    public function isBulkOrder()
    {
        return $this->type === self::TYPE_BULK_ORDER;
    }

    // Check if it's a regular purchase - ADD THIS
    public function isRegular()
    {
        return $this->type === self::TYPE_REGULAR;
    }
}
