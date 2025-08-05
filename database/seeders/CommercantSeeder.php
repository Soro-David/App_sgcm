<?php

namespace Database\Seeders;

use App\Models\Commercant;
use App\Models\Mairie;
use App\Models\Agent;
use App\Models\Taxe;
use App\Models\Secteur;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class CommercantSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 150; $i++) {
            $mairie = Mairie::inRandomOrder()->first();
            if (!$mairie) continue;

            $agent = Agent::where('mairie_id', $mairie->id)->inRandomOrder()->first();
            if (!$agent) continue;

            $taxes = Taxe::inRandomOrder()->limit(rand(1, 3))->pluck('id');
            $secteurs = Secteur::where('mairie_id', $mairie->id)->inRandomOrder()->limit(rand(1, 2))->pluck('id');

            Commercant::create([
                'nom' => "Commercant $i",
                'email' => "commercant$i@example.com",
                'telephone' => '01010101' . rand(10, 99),
                'adresse' => "Adresse $i",
                'num_commerce' => strtoupper(Str::random(10)),
                'password' => Hash::make('12345678'),

                // Données supplémentaires
                'type_piece' => 'cni',
                'numero_piece' => 'CNI-' . rand(100000, 999999),
                'autre_type_piece' => null,
                'photo_profil' => 'images/default_avatar.jpg',
                'photo_recto' => 'images/default_piece_recto.png',
                'photo_verso' => 'images/idrecto.jpg',
                'autre_images' => json_encode([]),

                'agent_id' => $agent->id,
                'mairie_id' => $mairie->id,
                'taxe_id' => json_encode($taxes->toArray()),
                'secteur_id' => json_encode($secteurs->toArray()),
            ]);
        }
    }
}
