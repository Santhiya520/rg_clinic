<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManualLabTestSubTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'manual_lab_test_item_id',
        'test_name',
        'unit',
        'normal_range',
        'result'
    ];

    public function item()
    {
        return $this->belongsTo(ManualLabTestItem::class, 'manual_lab_test_item_id');
    }

    public function manualLabTestItem()
    {
        return $this->belongsTo(ManualLabTestItem::class, 'manual_lab_test_item_id');
    }

    // Scope for completed sub-tests
    public function scopeCompleted($query)
    {
        return $query->whereNotNull('result');
    }

    // Check if result is abnormal
    public function isAbnormal()
    {
        if (!$this->normal_range || !$this->result) {
            return false;
        }

        // Simple range check logic (you can customize this)
        $range = $this->normal_range;
        $result = (float) $this->result;

        if (strpos($range, '-') !== false) {
            [$min, $max] = explode('-', $range);
            return $result < (float) $min || $result > (float) $max;
        }

        return false;
    }
}
