<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Patient extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'name',
        'address',
        'mobile',
        'age',
        'sex',
        'email',
        'password',
        'user_id',
        'comorbidities',
        'history',
        'otp',
        'otp_expires_at',
        'reset_token',
        'reset_token_expires_at',
        'is_verified',
        'remember_token',
        'last_login_at'
    ];

    // Automatically generate patient ID when creating
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($patient) {
            if (empty($patient->patient_id)) {
                $patient->patient_id = static::generatePatientId();
            }
        });
    }

    // Generate unique patient ID with RG prefix
    public static function generatePatientId()
    {
        $latestPatient = static::latest('id')->first();
        $nextId = $latestPatient ? $latestPatient->id + 1 : 1;

        return 'RG' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
    }

    // Relationship with OP registers
    public function opRegisters()
    {
        return $this->hasMany(OpRegister::class);
    }

    public function inpatientRegisters()
    {
        return $this->hasMany(InpatientRegister::class);
    }

    public function operationRegisters()
    {
        return $this->hasMany(OperationRegister::class);
    }
    // Get latest visit
    public function getLatestVisitAttribute()
    {
        return $this->opRegisters()->latest()->first();
    }
}
