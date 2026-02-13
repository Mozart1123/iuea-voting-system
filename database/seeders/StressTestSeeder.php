<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Candidate;
use App\Models\Category;
use App\Models\Vote;
use Illuminate\Support\Facades\Hash;

class StressTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting stress test data generation (1000 users & 1000 votes)...');

        // Ensure we have at least one voting category and candidates
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

        if ($category->candidates()->count() === 0) {
            $category->candidates()->createMany([
                ['name' => 'Candidate Alpha', 'faculty' => 'Science', 'status' => 'approved', 'position_number' => 1],
                ['name' => 'Candidate Beta', 'faculty' => 'Law', 'status' => 'approved', 'position_number' => 2],
            ]);
        }

        $candidates = $category->candidates;

        // Create 1000 users and let them vote
        User::factory()->count(1000)->create()->each(function ($user) use ($category, $candidates) {
            // Check if user already voted in this category (optional check)
            if (!Vote::where('user_id', $user->id)->where('category_id', $category->id)->exists()) {
                Vote::create([
                    'user_id' => $user->id,
                    'candidate_id' => $candidates->random()->id,
                    'category_id' => $category->id,
                ]);
            }
        });

        $this->command->info('Success! 1000 users and 1000 votes have been generated.');
    }
}
