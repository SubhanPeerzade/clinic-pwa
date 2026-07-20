<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicineMaster extends Model
{
    protected $fillable = [
        'name',
        'medicine_category_id',
        'dose_master_id',
        'start_time_id',
        'is_active'
    ];

    public function category()
    {
        return $this->belongsTo(MedicineCategory::class, 'medicine_category_id');
    }

    public function dose()
    {
        return $this->belongsTo(DoseMaster::class, 'dose_master_id');
    }

    public function startTime()
    {
        return $this->belongsTo(StartTime::class, 'start_time_id');
    }
}
