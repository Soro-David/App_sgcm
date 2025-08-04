<?php

namespace Database\Seeders;
use App\Models\Secteur;
use App\Models\Mairie;
use Illuminate\Database\Seeder;

class SecteurSeeder extends Seeder
{
    public function run()
    {
        foreach (Mairie::all() as $mairie) {
            for ($i = 1; $i <= 10; $i++) {
                Secteur::create([
                    'mairie_id' => $mairie->id,
                    'code' => "S{$mairie->id}-$i",
                    'nom' => "Secteur {$mairie->id}-$i"
                ]);
            }
        }
    }
}
