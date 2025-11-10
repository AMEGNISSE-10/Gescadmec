<?php

namespace Database\Seeders;

use App\Models\Secretary;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SecretarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Secretary::create([
            'name' => 'Secrétaire Test',
            'email' => 'secretary@example.com',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);
    }
}