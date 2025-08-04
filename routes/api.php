<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AgentMairieController;
use App\Http\Controllers\Api\AgentRecouvrementController;
use App\Http\Controllers\Api\CommercantController;

Route::post('/agent-mairie/login', [AgentMairieController::class, 'login']);
Route::post('/agent-recouvrement/login', [AgentRecouvrementController::class, 'login']);
Route::post('/commercant/login', [CommercantController::class, 'login']);
Route::post('/commercant/register', [CommercantController::class, 'definePassword'])->name('commercant.register');

Route::middleware('auth:sanctum')->group(function () {

    // Routes pour les Agents de Mairie
    Route::middleware('abilities:agent-mairie')->prefix('agent-mairie')->name('agent.mairie.')->group(function () {
    Route::get('/agent/me', [AgentMairieController::class, 'me']);
    Route::post('/agent/logout', [AgentMairieController::class, 'logout']);
    // Routes pour la gestion des commerçants
    Route::prefix('/commercants')->group(function () {
        Route::get('/generate-number', [AgentMairieController::class, 'generate_num_commerce']);
        Route::get('/', [AgentMairieController::class, 'list_commercants']);
        Route::post('/', [AgentMairieController::class, 'store_commercant']);
        Route::get('/{id}', [AgentMairieController::class, 'show_commercant']);
        Route::put('/{id}', [AgentMairieController::class, 'update_commercant']);
        Route::delete('/{id}', [AgentMairieController::class, 'destroy_commercant']);
    });
});

    // Routes pour les Agents de Recouvrement
    Route::middleware('abilities:agent-recouvrement')->prefix('agent-recouvrement')->name('agent.recouvrement.')->group(function () {
        Route::get('/me', [AgentRecouvrementController::class, 'me']);
        // Route pour encaisser le paiement
        Route::post('/encaissement', [AgentRecouvrementController::class, 'encaisserPaiement'])->name('encaisser');
        // route pour récupérer les périodes dues
        Route::post('/paiement/periodes-dues', [AgentRecouvrementController::class, 'dernierPaiementEtDues'])->name('periodes_dues');
        Route::post('/logout', [AgentRecouvrementController::class, 'logout']);
    });



    // Routes pour les Commerçants
   Route::middleware('abilities:commercant')->prefix('commercant')->name('commercant')->group(function () {
    Route::get('/me', [CommercantController::class, 'me']);
    Route::get('/taxes', [CommercantController::class, 'list_taxes_a_payer']);
    Route::post('/paiement', [CommercantController::class, 'effectuer_paiement']);
    Route::get('/paiements', [CommercantController::class, 'historique_paiements']);
    Route::post('/logout', [CommercantController::class, 'logout']);
});
});