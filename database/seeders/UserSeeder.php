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
        User::create([
            'name' => 'System Admin',
            'email' => 'admin@iuea.ac.ug',
            'student_id' => null,
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create Test Student
        User::create([
            'name' => 'John Doe',
            'email' => 'student@iuea.ac.ug',
            'student_id' => '21/U/1234/PS',
            'password' => Hash::make('password'),
            'role' => 'student',
        ]);
    }
}
