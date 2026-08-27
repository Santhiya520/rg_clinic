<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    use HasFactory;

    protected $fillable = [
        'description'
    ];

    // Scope for latest notices
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
