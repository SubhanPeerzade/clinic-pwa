<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'patient_name',
        'patient_phone',
        'appointment_date',
        'token',
        'status',
        'doctor_id',
        'called_at',
        'is_not_present',
        'remark',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'called_at' => 'datetime',
        'is_not_present' => 'boolean',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    // ✅ ADD THIS
    public function prescription()
    {
        return $this->hasOne(Prescription::class);
    }
}
