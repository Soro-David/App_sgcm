<?php

namespace Database\Seeders;

use App\Models\Commercant;
use App\Models\Mairie;
use App\Models\Agent;
use App\Models\Taxe;
use App\Models\Secteur;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CommercantSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 150; $i++) {
            $mairie = Mairie::inRandomOrder()->first();
            $agent = Agent::where('mairie_id', $mairie->id)->inRandomOrder()->first();

            $taxes = Taxe::inRandomOrder()->limit(rand(1, 3))->pluck('id');
            $secteurs = Secteur::where('mairie_id', $mairie->id)->inRandomOrder()->limit(rand(1, 2))->pluck('id');

            Commercant::create([
                'nom' => "Commercant $i",
                'email' => "commercant$i@example.com",
                'telephone' => '010101010' . rand(0, 9),
                'adresse' => "Adresse $i",
                'num_commerce' => strtoupper(Str::random(10)),
                'mot_de_passe' => null, // mot de passe laissé vide
                'agent_id' => $agent->id,
                'mairie_id' => $mairie->id,
                'taxe_id' => json_encode($taxes),
                'secteur_id' => json_encode($secteurs),
            ]);
        }
    }
}
