<?php
// app/Models/FreeCampPharmacy.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FreeCampPharmacy extends Model
{
    use SoftDeletes;

    protected $table = 'free_camp_pharmacy';

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
