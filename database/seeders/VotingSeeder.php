<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VotingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Governance Category
        $governance = \App\Models\Category::create([
            'name' => 'Guild President',
            'description' => 'Select your preferred candidate for the Guild President',
            'is_active' => true,
            'status' => 'voting',
            'start_time' => now(),
            'end_time' => now()->addDays(5),
        ]);

        $governance->candidates()->createMany([
            [
                'name' => 'Mbakka Jonathan',
                'faculty' => 'Science & Technology',
                'biography' => 'Advancing the digital frontier for every student at IUEA.',
                'position_number' => 1,
                'status' => 'approved',
            ],
            [
                'name' => 'Namara Sarah',
                'faculty' => 'Business & Law',
                'biography' => 'Equality, transparency, and welfare for the IUEA community.',
                'position_number' => 2,
                'status' => 'approved',
            ],
        ]);

        // Faculty GRC Category
        $facultyGrc = \App\Models\Category::create([
            'name' => 'Faculty GRC',
            'description' => 'Select your faculty representative',
            'is_active' => true,
            'status' => 'nomination',
        ]);

        $facultyGrc->candidates()->createMany([
            [
                'name' => 'Kidega Robert',
                'faculty' => 'Science & Technology',
                'biography' => 'Innovating through representation.',
                'position_number' => 1,
                'status' => 'approved',
            ],
        ]);
    }
}
