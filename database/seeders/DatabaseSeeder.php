<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            MairieSeeder::class,
            TaxeSeeder::class,
            AgentSeeder::class,
            SecteurSeeder::class,
            CommercantSeeder::class,
            VersementSeeder::class,
            EncaissementSeeder::class,
        ]);
    }

}
