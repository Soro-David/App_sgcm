<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$tables = ['paiement_taxes', 'encaissements'];
foreach ($tables as $table) {
    echo "Table: $table\n";
    try {
        foreach (DB::select("DESCRIBE $table") as $col) {
            echo "{$col->Field}: {$col->Type}\n";
        }
    } catch (\Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
    echo "\n";
}
