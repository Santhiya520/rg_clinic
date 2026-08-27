<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpLabSubTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_lab_test_id',
        'lab_sub_test_id',
        'test_name',
        'unit',
        'normal_range',
        'result',
        'order'
    ];

    public function ipLabTest()
    {
        return $this->belongsTo(IpLabTest::class);
    }

    public function labSubTest()
    {
        return $this->belongsTo(LabSubTest::class);
    }
}
