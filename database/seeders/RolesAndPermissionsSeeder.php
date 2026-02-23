<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Permissions
        $permissions = [
            'manage_users',
            'manage_candidates',
            'manage_system',
            'view_results',
            'monitor_system',
            'process_voter',
        ];

        foreach ($permissions as $p) {
            Permission::updateOrCreate(['name' => $p], ['display_name' => ucwords(str_replace('_', ' ', $p))]);
        }

        // 2. Create Roles and Assign Permissions
        
        // Super Admin
        $superAdmin = Role::updateOrCreate(['name' => 'super_admin'], ['display_name' => 'Super Admin']);
        $superAdmin->permissions()->sync(Permission::all());

        // System Admin
        $systemAdmin = Role::updateOrCreate(['name' => 'system_admin'], ['display_name' => 'System Admin']);
        $systemAdmin->permissions()->sync(Permission::whereIn('name', ['monitor_system', 'view_results', 'manage_users'])->get());

        // Normal Admin (Supervisor)
        $normalAdmin = Role::updateOrCreate(['name' => 'normal_admin'], ['display_name' => 'Normal Admin (Supervisor)']);
        $normalAdmin->permissions()->sync(Permission::whereIn('name', ['process_voter', 'monitor_system'])->get());

        // 3. Create initial Super Admin if not exists
        if (!User::where('email', 'superadmin@iuea.ac.ug')->exists()) {
            User::create([
                'name' => 'IUEA Super Admin',
                'email' => 'superadmin@iuea.ac.ug',
                'password' => Hash::make('Security#2026'),
                'role_id' => $superAdmin->id,
                'is_admin' => true
            ]);
        }
    }
}
