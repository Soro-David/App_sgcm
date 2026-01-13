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
                    'mairie_ref' => $mairie->mairie_ref,
                    'code' => "S{$mairie->mairie_ref}-$i",
                    'nom' => "Secteur {$mairie->mairie_ref}-$i"
                ]);
            }
        }
    }
}
