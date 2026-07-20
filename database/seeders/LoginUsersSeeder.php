<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin account
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Doctor account
        User::updateOrCreate(
            ['email' => 'doctor@example.com'],
            [
                'name' => 'Doctor User',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'doctor',
            ]
        );

        // Receptionist account
        User::updateOrCreate(
            ['email' => 'reception@example.com'],
            [
                'name' => 'Receptionist User',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'receptionist',
            ]
        );
    }
}
