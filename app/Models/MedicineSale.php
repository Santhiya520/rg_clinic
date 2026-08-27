<?php
// app/Models/MedicineSale.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;

class MedicineSale extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'type',
        'customer_name',
        'customer_phone',
        'customer_address',
        'department',
        'sale_date',
        'sub_total',
        'total_discount',
        'total_tax',
        'tax_percentage',           // Added
        'injection_fees',           // Added
        'procedure_fees',           // Added
        'overall_discount_percent', // Added
        'overall_discount_amount',  // Added
        'grand_total',
        'paid_amount',
        'due_amount',
        'payment_status',
        'payment_method',
        'notes',
        'user_id'
    ];

    protected $casts = [
        'sale_date' => 'date',
        'sub_total' => 'decimal:2',
        'total_discount' => 'decimal:2',
        'total_tax' => 'decimal:2',
        'tax_percentage' => 'decimal:2',
        'injection_fees' => 'decimal:2',
        'procedure_fees' => 'decimal:2',
        'overall_discount_percent' => 'decimal:2',
        'overall_discount_amount' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'due_amount' => 'decimal:2',
    ];

    // Accessors
    public function getIsInternalAttribute()
    {
        return in_array($this->type, ['radiology-use', 'lab-use', 'other']);
    }

    public function getDepartmentNameAttribute()
    {
        if ($this->type == 'radiology-use') return 'Radiology';
        if ($this->type == 'lab-use') return 'Laboratory';
        if ($this->type == 'other') return $this->department ?? 'Other';
        return null;
    }

    // Get medicine amount after all discounts
    public function getMedicineAmountAfterDiscountAttribute()
    {
        return $this->sub_total - $this->total_discount;
    }

    // Get GST amount (for display)
    public function getGstAmountAttribute()
    {
        return $this->total_tax ?? 0;
    }

    // Get total fees (injection + procedure)
    public function getTotalFeesAttribute()
    {
        return ($this->injection_fees ?? 0) + ($this->procedure_fees ?? 0);
    }

    // Get balance amount
    public function getBalanceAttribute()
    {
        return $this->grand_total - $this->paid_amount;
    }

    // Relationships
    public function items()
    {
        return $this->hasMany(MedicineSaleItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeCustomerSales($query)
    {
        return $query->where('type', 'customer');
    }

    public function scopeInternalUse($query)
    {
        return $query->whereIn('type', ['radiology-use', 'lab-use', 'other']);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('sale_date', today());
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('sale_date', now()->month)
                     ->whereYear('sale_date', now()->year);
    }

    // Boot method to handle dynamic fillable (optional)
    protected static function boot()
    {
        parent::boot();

        // Dynamically add columns to fillable if they exist in database
        static::retrieved(function ($model) {
            // This ensures compatibility if columns are added later
            $existingColumns = Schema::getColumnListing($model->getTable());
            $dynamicColumns = ['tax_percentage', 'injection_fees', 'procedure_fees',
                               'overall_discount_percent', 'overall_discount_amount'];

            foreach ($dynamicColumns as $column) {
                if (in_array($column, $existingColumns) && !in_array($column, $model->fillable)) {
                    $model->fillable[] = $column;
                }
            }
        });
    }
}
