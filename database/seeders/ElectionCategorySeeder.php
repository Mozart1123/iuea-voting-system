<?php

namespace Database\Seeders;

use App\Models\ElectionCategory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ElectionCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create an admin user to be the creator
        $admin = User::where('is_admin', true)->first() ?? User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@iuea.edu',
            'is_admin' => true,
            'role' => 'admin',
        ]);

        $categories = [
            [
                'name' => 'Guild President 2025',
                'description' => 'Elect the next Guild President to lead student affairs and represent the student body.',
                'icon' => 'fa-user-tie',
                'application_deadline' => Carbon::now()->addDays(15)->setTime(17, 0),
                'is_active' => true,
            ],
            [
                'name' => 'Faculty Representative',
                'description' => 'Represent the Faculty of Computing & Engineering in the Guild Council.',
                'icon' => 'fa-users',
                'application_deadline' => Carbon::now()->addDays(20)->setTime(17, 0),
                'is_active' => true,
            ],
            [
                'name' => 'Constitutional Referendum',
                'description' => 'Vote on proposed changes to the IUEA Guild Constitution.',
                'icon' => 'fa-gavel',
                'application_deadline' => Carbon::now()->addDays(25)->setTime(17, 0),
                'is_active' => true,
            ],
            [
                'name' => 'Guild Treasurer',
                'description' => 'Manage Guild finances, budgets, and financial accountability.',
                'icon' => 'fa-coins',
                'application_deadline' => Carbon::now()->addDays(12)->setTime(17, 0),
                'is_active' => true,
            ],
            [
                'name' => 'Academic Affairs Officer',
                'description' => 'Advocate for students on academic matters and quality of education.',
                'icon' => 'fa-book',
                'application_deadline' => Carbon::now()->addDays(18)->setTime(17, 0),
                'is_active' => true,
            ],
            [
                'name' => 'Sports Director',
                'description' => 'Oversee all sports activities, tournaments, and recreational programs.',
                'icon' => 'fa-basketball',
                'application_deadline' => Carbon::now()->addDays(14)->setTime(17, 0),
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            ElectionCategory::updateOrCreate(
                ['name' => $category['name']],
                [
                    ...$category,
                    'created_by' => $admin->id,
                ]
            );
        }

        $this->command->info('Election categories seeded successfully.');
    }
}
