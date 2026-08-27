<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicinePurchaseItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'medicine_purchase_id',
        'medicine_id',
        'batch_number',
        'expiry_date',
        'quantity',
        'purchase_price',
        'total_amount',
        'user_id'
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function purchase()
    {
        return $this->belongsTo(MedicinePurchase::class);
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}
