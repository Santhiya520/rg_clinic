<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = ['image', 'order'];

    // Scope for ordered galleries
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }
}
