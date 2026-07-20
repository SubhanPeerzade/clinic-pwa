<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppointmentCounter extends Model
{
    protected $fillable = ['day', 'last_token'];

    protected $casts = [
        'day' => 'date',
    ];

    // If you don't use created_at/updated_at, you can disable timestamps.
    // public $timestamps = false;
}
