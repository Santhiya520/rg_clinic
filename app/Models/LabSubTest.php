<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabSubTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'lab_test_id',
        'name',
        'unit',
        'normal_range',
        'result',
        'order'
    ];

    protected $hidden = [
        'result' // Hide from serialization
    ];

    public function labTest()
    {
        return $this->belongsTo(LabTest::class);
    }
}
