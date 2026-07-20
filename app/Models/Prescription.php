<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $fillable = [
        'appointment_id',
        'patient_id',
        'doctor_id',
        'prescription_date',
        'diagnosis',
        'treatment'
    ];

    // ✅ ADD THIS
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    // ✅ ADD THIS
    public function medicines()
    {
        return $this->hasMany(PrescriptionMedicine::class);
    }

    public function appointment()
{
    return $this->belongsTo(Appointment::class);
}

}
