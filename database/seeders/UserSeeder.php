<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin
        User::updateOrCreate(
            ['email' => 'admin@iuea.ac.ug'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('123456789'),
                'role' => 'admin',
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        // Create Student (Joseph)
        User::updateOrCreate(
            ['email' => 'josephtshim9@gmail.com'],
            [
                'name' => 'Joseph Tshimanga',
                'student_id' => '23/U/1234/EVE', // Example ID
                'password' => Hash::make('123456789'),
                'role' => 'student',
                'faculty' => 'Technology',
                'year_of_study' => 3,
                'email_verified_at' => now(),
            ]
        );
    }
}
