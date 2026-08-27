<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManualRadiologyTestItem extends Model
{
    protected $fillable = [
        'manual_radiology_test_id', 'radiology_test_id', 'price',
        'result', 'status', 'result_document', 'technician_id', 'notes', 'completed_at'
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function manualRadiologyTest()
    {
        return $this->belongsTo(ManualRadiologyTest::class);
    }

    public function radiologyTest()
    {
        return $this->belongsTo(RadiologyTest::class);
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }
}
