<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Candidate;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks for clean seed
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Candidate::truncate();
        Category::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Create Users if they don't exist
        $admin = User::firstOrCreate(
            ['email' => 'admin@iuea.ac.ug'],
            [
                'name' => 'Election Commission',
                'student_id' => 'ADMIN-001',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        $student = User::firstOrCreate(
            ['email' => 'john.doe@iuea.ac.ug'],
            [
                'name' => 'John Doe',
                'student_id' => 'STU-123456',
                'password' => Hash::make('password'),
                'role' => 'student',
            ]
        );

        // 2. Create Categories with beautiful mock data
        $categories = [
            [
                'name' => 'Président du Guild',
                'description' => 'Le représentant suprême des étudiants pour l\'année 2025.',
                'status' => 'voting',
                'is_active' => true,
                'candidates' => [
                    [
                        'name' => 'John Musoke',
                        'faculty' => 'Computing & Engineering',
                        'biography' => 'Leadership pour tous. Ensemble, transformons notre campus.',
                        'photo_path' => 'https://randomuser.me/api/portraits/men/1.jpg',
                        'status' => 'approved'
                    ],
                    [
                        'name' => 'Sarah Mutesi',
                        'faculty' => 'Business & Management',
                        'biography' => 'Votre voix compte. Vers une gestion transparente et inclusive.',
                        'photo_path' => 'https://randomuser.me/api/portraits/women/2.jpg',
                        'status' => 'approved'
                    ],
                    [
                        'name' => 'James Kato',
                        'faculty' => 'Law',
                        'biography' => 'Unité dans la diversité. Défendre les droits de chaque étudiant.',
                        'photo_path' => 'https://randomuser.me/api/portraits/men/4.jpg',
                        'status' => 'approved'
                    ]
                ]
            ],
            [
                'name' => 'Représentant de Faculté',
                'description' => 'Porte-parole pour les questions académiques et administratives.',
                'status' => 'voting',
                'is_active' => true,
                'candidates' => [
                    [
                        'name' => 'David Akello',
                        'faculty' => 'Science & Technology',
                        'biography' => 'L’innovation d’abord. Priorité aux ressources de laboratoire.',
                        'photo_path' => 'https://randomuser.me/api/portraits/men/3.jpg',
                        'status' => 'approved'
                    ],
                    [
                        'name' => 'Esther Nabatanzi',
                        'faculty' => 'Science & Technology',
                        'biography' => 'Ensemble on peut. Améliorer la communication entre profs et étudiants.',
                        'photo_path' => 'https://randomuser.me/api/portraits/women/4.jpg',
                        'status' => 'approved'
                    ]
                ]
            ],
            [
                'name' => 'Trésorier du Guild',
                'description' => 'Gestionnaire des fonds alloués aux activités étudiantes.',
                'status' => 'voting',
                'is_active' => true,
                'candidates' => [
                    [
                        'name' => 'Michael Ochieng',
                        'faculty' => 'Business',
                        'biography' => 'Finances transparentes. Chaque shilling compte.',
                        'photo_path' => 'https://randomuser.me/api/portraits/men/5.jpg',
                        'status' => 'approved'
                    ],
                    [
                        'name' => 'Grace Kimani',
                        'faculty' => 'Economics',
                        'biography' => 'Responsabilité budgétaire. Plus d\'événements, moins de gaspillage.',
                        'photo_path' => 'https://randomuser.me/api/portraits/women/5.jpg',
                        'status' => 'approved'
                    ]
                ]
            ]
        ];

        foreach ($categories as $catData) {
            $candidates = $catData['candidates'];
            unset($catData['candidates']);
            
            $category = Category::create($catData);
            
            foreach ($candidates as $candData) {
                $category->candidates()->create($candData);
            }
        }

        // 3. Update System Settings
        if (class_exists('\App\Models\SystemSetting')) {
            \App\Models\SystemSetting::updateOrCreate(
                ['key' => 'nomination_enabled'],
                ['value' => '1']
            );
        }
    }
}
