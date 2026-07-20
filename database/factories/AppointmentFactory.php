<?php

namespace Database\Factories;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition(): array
    {
        return [
            'patient_name' => $this->faker->name,
            'patient_phone' => $this->faker->phoneNumber,
            'appointment_date' => now()->toDateString(),
            'token' => $this->faker->unique()->numberBetween(1, 1000),
            'status' => 'waiting',
        ];
    }
}
