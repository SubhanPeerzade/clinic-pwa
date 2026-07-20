<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'patient_id',
        'first_name',
        'last_name',
        'date_of_birth',
        'age',
        'phone',
        'email',
        'address'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    // Auto-calculate age from DOB
    public function getAgeAttribute()
    {
        return $this->date_of_birth
            ? $this->date_of_birth->age
            : null;
    }
}

