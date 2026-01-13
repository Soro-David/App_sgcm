<?php

namespace Database\Seeders;

use App\Models\Encaissement;
use App\Models\Commercant;
use Illuminate\Database\Seeder;

class EncaissementSeeder extends Seeder
{
    public function run(): void
    {
        $commercants = \App\Models\Commercant::inRandomOrder()->limit(20)->get();

        foreach ($commercants as $commercant) {
            Encaissement::create([
                            'montant_verse' => rand(3000, 15000),
                            'statut' => 'payé',
                            'taxe_id' => 1, // Ou choisir un id existant
                            'agent_id' => $commercant->agent_id,
                            'mairie_ref' => $commercant->mairie_ref,
                            'num_commerce' => $commercant->num_commerce,
                            'created_at' => now()->subDays(rand(1, 30)),
                        ]);

        }
    }
}
