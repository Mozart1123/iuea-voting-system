<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Candidate;
use App\Models\Category;
use App\Models\Vote;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class StressTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting stress test data generation...');

        $category = Category::where('status', 'voting')->first();
        if (!$category) {
            $category = Category::create([
                'name' => 'Guild President 2025',
                'description' => 'Stress Test Election',
                'status' => 'voting',
                'is_active' => true,
                'start_time' => now(),
                'end_time' => now()->addDays(7),
            ]);
        }

        $candidates = $category->candidates;
        if ($candidates->count() === 0) {
            $category->candidates()->createMany([
                ['name' => 'Candidate Alpha', 'faculty' => 'Science', 'status' => 'approved', 'position_number' => 1],
                ['name' => 'Candidate Beta', 'faculty' => 'Law', 'status' => 'approved', 'position_number' => 2],
            ]);
            $candidates = $category->candidates;
        }

        $totalToCreate = 2500;
        $chunkSize = 500;

        for ($i = 0; $i < $totalToCreate; $i += $chunkSize) {
            $this->command->info("Seeding chunk " . (($i / $chunkSize) + 1) . " de " . ($totalToCreate / $chunkSize) . "...");
            
            User::factory()->count($chunkSize)->create()->each(function ($user) use ($category, $candidates) {
                Vote::create([
                    'user_id' => $user->id,
                    'candidate_id' => $candidates->random()->id,
                    'category_id' => $category->id,
                ]);
            });
        }

        $this->command->info('Success! 1000 more users and votes have been generated.');
    }
}
