<?php
// app/Models/OperationRegister.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OperationRegister extends Model
{
    protected $fillable = [
        'patient_id',
        'operation_theatre_type',
        'date_of_admission',
        'hospital_ip_no',
        'provisional_diagnosis',
        'investigations',
        'operation_performed',
        'operating_surgeon_id',
        'assistant_surgeon_id',
        'anaesthetist_id',
        'staff_reception_id',
        'operation_start_time',
        'operation_end_time',
        'operation_notes',
        'transferred_to_ward',
        'additional_information',
        'medical_officer_id',
        'user_id'
    ];

    protected $casts = [
        'date_of_admission' => 'date',
        'operation_start_time' => 'datetime:H:i',
        'operation_end_time' => 'datetime:H:i',
    ];

    /**
     * Get the patient associated with the operation register.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the operating surgeon (user).
     */
    public function operatingSurgeon(): BelongsTo
    {
        return $this->belongsTo(User::class, 'operating_surgeon_id');
    }

    /**
     * Get the assistant surgeon (user).
     */
    public function assistantSurgeon(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assistant_surgeon_id');
    }

    /**
     * Get the anaesthetist (user).
     */
    public function anaesthetist(): BelongsTo
    {
        return $this->belongsTo(User::class, 'anaesthetist_id');
    }

    /**
     * Get the staff reception (user).
     */
    public function staffreception(): BelongsTo
    {
        return $this->belongsTo(User::class, 'staff_reception_id');
    }

    /**
     * Get the medical officer (user).
     */
    public function medicalOfficer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'medical_officer_id');
    }

    /**
     * Get the operation duration in hours.
     */
    public function getOperationDurationAttribute(): string
    {
        $start = \Carbon\Carbon::parse($this->operation_start_time);
        $end = \Carbon\Carbon::parse($this->operation_end_time);

        $duration = $start->diff($end);

        if ($duration->h > 0) {
            return $duration->h . ' hours ' . $duration->i . ' minutes';
        }

        return $duration->i . ' minutes';
    }
}
