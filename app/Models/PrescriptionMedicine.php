<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrescriptionMedicine extends Model
{
    protected $fillable = [
        'prescription_id',
        'medicine_name',
        'category',
        'dose',
        'start_time',
        'start_time_mr',
        'days'
    ];

    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }
}
