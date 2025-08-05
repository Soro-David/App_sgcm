<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Financier;           // ✅ Import du modèle
use Illuminate\Support\Facades\Hash; // ✅ Import pour Hash::make

class FinancierTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Financier::create([
            'name' => 'Test Financier',
            'email' => 'finance@finance.com',
            'region' => 'Abidjan',
            'commune' => 'Cocody',
            'password' => Hash::make('12345678'),
            'status' => 'active',
        ]);
    }
}
