<?php

namespace Database\Seeders;

use App\Models\Secretary;
use App\Models\LanguageLevel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Créer une secrétaire
        Secretary::create([
            'name' => 'Marie Dupont',
            'email' => 'secretary@academie.fr',
            'password' => Hash::make('secret123'),
            'is_active' => true
        ]);

        // Créer les niveaux de langue
        LanguageLevel::create([
            'name' => 'Débutant A1',
            'description' => 'Niveau initiation - Débutant',
            'price' => 300.00,
            'duration_days' => 90
        ]);

        LanguageLevel::create([
            'name' => 'Intermédiaire B1',
            'description' => 'Niveau intermédiaire - Seuil',
            'price' => 450.00,
            'duration_days' => 120
        ]);

        LanguageLevel::create([
            'name' => 'Avancé C1',
            'description' => 'Niveau avancé - Autonome',
            'price' => 600.00,
            'duration_days' => 150
        ]);

        LanguageLevel::create([
            'name' => 'Conversation',
            'description' => 'Cours de conversation',
            'price' => 200.00,
            'duration_days' => 60
        ]);
    }
}