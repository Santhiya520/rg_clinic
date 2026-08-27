<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'is_read',
        'is_replied'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_replied' => 'boolean'
    ];

    // Scope for unread enquiries
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    // Scope for read enquiries
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    // Scope for today's enquiries
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    // Get formatted created date
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('M d, Y h:i A');
    }
}
