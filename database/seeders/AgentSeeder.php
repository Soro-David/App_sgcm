<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\Mairie;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AgentSeeder extends Seeder
{
    public function run()
{
    foreach (Mairie::all() as $mairie) {
        for ($i = 1; $i <= 5; $i++) {
            $plainPassword = '12345678'; // mot de passe fixe

            Agent::create([
                'name' => "Agent {$mairie->id}-$i",
                'email' => "agent{$mairie->id}_$i@example.com",
                'password' => Hash::make($plainPassword),
                'mairie_id' => $mairie->id
            ]);

            echo "Agent {$mairie->id}-$i : email = agent{$mairie->id}_$i@example.com, password = $plainPassword\n";
        }
    }
}

}
