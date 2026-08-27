<?php
// app/Models/CampNew.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CampNew extends Model
{
    use SoftDeletes;

    protected $table = 'camp_new';

    protected $fillable = [
        'token_number',
        'patient_name',
        'mobile_number',
        'address',
        'age',
        'gender',
        'medicines',
        'remarks'
    ];

    protected $casts = [
        'medicines' => 'array',
        'age' => 'integer'
    ];
}
