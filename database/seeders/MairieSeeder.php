<?php

namespace Database\Seeders;

use App\Models\Mairie;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MairieSeeder extends Seeder
{
    public function run()
{
    for ($i = 1; $i <= 10; $i++) {
        Mairie::create([
            'name' => "Mairie $i",
            'region' => "Région $i",
            'commune' => "Commune $i",
            'role' => 'admin', // valeur par défaut
            'email' => "mairie$i@example.com",
            'password' => Hash::make('12345678'), // mot de passe crypté
            'status' => 'active', // statut modifié
        ]);
    }
}
}
