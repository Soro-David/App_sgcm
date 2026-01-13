<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "Table: agents\n";
foreach (DB::select('DESCRIBE agents') as $col) {
    echo "{$col->Field}: {$col->Type}\n";
}

echo "\nTable: commercants\n";
foreach (DB::select('DESCRIBE commercants') as $col) {
    echo "{$col->Field}: {$col->Type}\n";
}
