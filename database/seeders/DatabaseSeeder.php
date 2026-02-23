<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\Category;
use App\Models\Candidate;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Roles
        $superAdminRole = Role::create(['name' => 'super_admin', 'display_name' => 'Super Administrator']);
        $systemAdminRole = Role::create(['name' => 'system_admin', 'display_name' => 'System Monitor']);
        $normalAdminRole = Role::create(['name' => 'normal_admin', 'display_name' => 'Kiosk Supervisor']);

        // 2. Seed Default Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@iuea.ac.ug',
            'password' => Hash::make('Admin@2026!'), // Strong default password
            'role_id' => $superAdminRole->id,
        ]);

        // 3. Seed some Categories (Optional but good for demo)
        $president = Category::create([
            'name' => 'Guild President',
            'description' => 'The highest student representative'
        ]);

        $facultyRep = Category::create([
            'name' => 'Faculty Representative (BIT)',
            'description' => 'Representative for BIT students',
            'faculty_restriction' => 'BIT'
        ]);

        // 4. Seed some Candidates
        Candidate::create([
            'category_id' => $president->id,
            'name' => 'MUSOKE JOHN',
            'registration_number' => '21/U/100/PS',
            'faculty' => 'IT'
        ]);

        Candidate::create([
            'category_id' => $president->id,
            'name' => 'NAMONO SARAH',
            'registration_number' => '21/U/101/PS',
            'faculty' => 'Engineering'
        ]);

        Candidate::create([
            'category_id' => $facultyRep->id,
            'name' => 'OKELLO PETER',
            'registration_number' => '22/U/500/PS',
            'faculty' => 'IT'
        ]);
    }
}
