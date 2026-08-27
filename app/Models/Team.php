<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'role',
        'image',
        'order',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
        'order' => 'integer'
    ];

    // Scope for ordered team members
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

    // Scope for active team members
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    // Accessor for full image URL
    public function getImageUrlAttribute()
    {
        return $this->image ? asset($this->image) : null;
    }
}
