<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'description',
        'image',
        'order',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
        'order' => 'integer'
    ];

    // Scope for ordered donors
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

    // Scope for active donors
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    // Scope for category filter
    public function scopeOfCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Accessor for full image URL
    public function getImageUrlAttribute()
    {
        return $this->image ? asset($this->image) : null;
    }

    // Get all unique categories
    public static function getCategories()
    {
        return self::select('category')->distinct()->pluck('category');
    }
}
