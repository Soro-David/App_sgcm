<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Agent;
use App\Models\Mairie;

$agent = Agent::first();
if ($agent) {
    echo "Agent ID: " . $agent->id . "\n";
    echo "Agent mairie_ref: " . $agent->mairie_ref . "\n";
    
    $mairie = Mairie::find($agent->mairie_ref);
    if ($mairie) {
        echo "Found Mairie ID: " . $mairie->id . "\n";
        echo "Mairie name: " . $mairie->name . "\n";
        echo "Mairie ref: " . $mairie->mairie_ref . "\n";
    } else {
        echo "Mairie not found for ID " . $agent->mairie_ref . "\n";
    }
} else {
    echo "No agents found.\n";
}
