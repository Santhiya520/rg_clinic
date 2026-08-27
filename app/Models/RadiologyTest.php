<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RadiologyTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'status',
        'user_id'
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    // Scope for active tests
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope for expensive tests (above 1000)
    public function scopeExpensive($query)
    {
        return $query->where('price', '>', 1000);
    }
}
