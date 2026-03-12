<?php

use App\Http\Controllers\Api\AgentMairieController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommercantController;
use App\Http\Controllers\Api\Contribuable\PaiementController;
use App\Http\Controllers\Api\Contribuable\RechargeController;
use App\Http\Controllers\Api\Recensement\RecensementController;
use App\Http\Controllers\Api\Recouvrement\RecouvrementController;
use Illuminate\Support\Facades\Route;

// Authentification unifiée
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::post('/commercant/register', [CommercantController::class, 'definePassword'])->name('commercant.register');

Route::middleware(['auth:sanctum'])->group(function () {
    // Les routes de profil et logout sont maintenant gérées à l'intérieur de chaque groupe par type d'utilisateur

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
    Route::middleware('abilities:agent-recouvrement')->prefix('recouvrement')->name('recouvrement.')->group(function () {
        Route::get('/me', [RecouvrementController::class, 'me']);
        // Route scan QR code : l'agent scanne le QR du contribuable et récupère ses infos + taxes dues
        Route::post('/scan-qrcode', [RecouvrementController::class, 'scanQrCode'])->name('scan_qrcode');
        // Route pour encaisser le paiement
        Route::post('/encaissement', [RecouvrementController::class, 'encaisserPaiement'])->name('encaisser');
        // route pour récupérer les périodes dues
        Route::post('/paiement/periodes-dues', [RecouvrementController::class, 'dernierPaiementEtDues'])->name('periodes_dues');
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/contribuable/{id}', [RecouvrementController::class, 'showContribuable']);
        Route::post('/contribuable/{id}', [RecouvrementController::class, 'updateContribuable']);
    });

    // Routes pour les Agents de Recensement
    Route::middleware('abilities:agent-recensement')->prefix('recensement')->name('recensement.')->group(function () {
        Route::get('/contribuable', [RecensementController::class, 'index']);
        Route::get('/contribuables-liste', [RecensementController::class, 'listContribuables']);
        Route::post('/contribuable', [RecensementController::class, 'store']);
        Route::get('/contribuable/{id}', [RecensementController::class, 'show']);
        Route::post('/contribuable/{id}', [RecensementController::class, 'update']);
        Route::get('/generate-num-commerce', [RecensementController::class, 'generateNumCommerce']);
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });

    // Routes pour les Commerçants
    Route::middleware('abilities:commercant')->prefix('contribuable')->name('contribuable.')->group(function () {
        Route::get('/me', [CommercantController::class, 'me'])->name('me');
        Route::get('/solde', [RechargeController::class, 'get_solde'])->name('solde');
        Route::post('/recharger', [RechargeController::class, 'recharger_compte'])->name('recharger');
        Route::get('/rechargements', [RechargeController::class, 'historique_recharges'])->name('rechargements');
        Route::get('/taxes', [PaiementController::class, 'list_taxes_a_payer'])->name('taxes');
        Route::get('/taxes/{taxeId}/periodes', [PaiementController::class, 'periodes_impayees'])->name('taxes.periodes');
        Route::post('/paiement', [PaiementController::class, 'effectuer_paiement'])->name('paiement');
        Route::get('/paiements', [PaiementController::class, 'historique_paiements'])->name('paiements');
        Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});
