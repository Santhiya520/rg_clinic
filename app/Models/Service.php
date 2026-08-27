<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'order',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
        'order' => 'integer'
    ];

    // Scope for ordered services
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

    // Scope for active services
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    // Accessor for full image URL
    public function getImageUrlAttribute()
    {
        return $this->image ? asset($this->image) : null;
    }

    // Accessor for shortened description - FIXED VERSION
    public function getShortDescriptionAttribute()
    {
        $length = 100; // You can adjust this value
        if (strlen($this->description) > $length) {
            return substr($this->description, 0, $length) . '...';
        }
        return $this->description;
    }
}
