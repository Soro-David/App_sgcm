<?php

namespace Database\Seeders;

use App\Models\Versement;
use App\Models\Commercant;
use Illuminate\Database\Seeder;

class VersementSeeder extends Seeder
{
    public function run(): void
    {
        $commercants = Commercant::inRandomOrder()->get();
        $versementsCrees = 0;
        $maxVersements = 20;

        while ($versementsCrees < $maxVersements) {
            foreach ($commercants as $commercant) {
                if ($versementsCrees >= $maxVersements) {
                    break;
                }

                // Décoder le champ JSON si c'est une chaîne 
                $taxes = $commercant->taxe_id;
                if (is_string($taxes)) {
                    $taxes = json_decode($taxes, true);
                }

                // Vérifie que c'est bien un tableau et qu'il n'est pas vide
                if (!is_array($taxes) || empty($taxes)) {
                    continue;
                }

                // Sélectionne une taxe au hasard 
                $taxeId = $taxes[array_rand($taxes)];

                $montantPercu = rand(5000, 20000);
                $montantVerse = rand(1000, $montantPercu);
                $reste = $montantPercu - $montantVerse;

                Versement::create([
                    'montant_percu' => $montantPercu,
                    'montant_verse' => $montantVerse,
                    'reste' => $reste,
                    'agent_id' => $commercant->agent_id,
                    'mairie_ref' => $commercant->mairie_ref,
                    'taxe_id' => $taxeId,
                    'created_at' => now()->subDays(rand(1, 30)),
                ]);

                $versementsCrees++;
            }
        }
    }
}
