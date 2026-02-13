<?php

use App\Http\Controllers\Commercant\PayementController;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Planification du traitement des paiements automatiques
Schedule::command('app:process-merchant-payments')->dailyAt('11:07');

