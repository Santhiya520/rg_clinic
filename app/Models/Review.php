<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'review',
        'star_count',
        'order',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
        'order' => 'integer',
        'star_count' => 'integer'
    ];

    // Scope for ordered reviews
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

    // Scope for active reviews
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    // Scope for high rated reviews
    public function scopeHighRated($query, $minStars = 4)
    {
        return $query->where('star_count', '>=', $minStars);
    }

    // Get star percentage for display
    public function getStarPercentageAttribute()
    {
        return ($this->star_count / 5) * 100;
    }
}
