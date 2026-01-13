<?php

namespace Database\Seeders;

use App\Models\Taxe;
use App\Models\Mairie;
use Illuminate\Database\Seeder;

class TaxeSeeder extends Seeder
{
    public function run()
    {
        $mairieIds = Mairie::pluck('id')->toArray();

        for ($i = 1; $i <= 10; $i++) {
            Taxe::create([
                'nom' => "Taxe $i",
                'description' => "Description pour taxe $i",
                'montant' => rand(1000, 5000),
                'mairie_ref' => $mairieIds[array_rand($mairieIds)],
            ]);
        }
    }
}
