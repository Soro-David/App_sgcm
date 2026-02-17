<?php

use App\Http\Controllers\Agent\AgentController as AgentContribuable;
use App\Http\Controllers\Agent\EncaissementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Commercant\PayementController;
use App\Http\Controllers\Commercant\RechargeController;
use App\Http\Controllers\MailRegistrationController;
use App\Http\Controllers\Mairie\AgentController;
use App\Http\Controllers\Mairie\AgentFinanceController;
use App\Http\Controllers\Mairie\CommerceController;
use App\Http\Controllers\Mairie\DashboardController;
use App\Http\Controllers\Mairie\DepenseController;
use App\Http\Controllers\Mairie\EncaissementController as EncaissementMairie;
use App\Http\Controllers\Mairie\PaiementController as PaiementMairie;
use App\Http\Controllers\Mairie\RecetteController;
use App\Http\Controllers\Mairie\SecteurController;
use App\Http\Controllers\Mairie\TacheController;
use App\Http\Controllers\Mairie\TaxeController;
use App\Http\Controllers\Mairie\VersementController;
// use App\Http\Controllers\OrderController;
// use App\Http\Controllers\ProfileController;
// use App\Http\Controllers\RoleController;
use App\Http\Controllers\SuperAdmin\MairieController;
use App\Http\Controllers\SuperAdmin\TaxeController as SuperAdminTaxe;
// use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes publiques
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Authentification
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
// Route::match(['get', 'post'], '/logout', fn (Request $request) => app(AuthController::class)->logout($request, 'web')
// )->name('logout');

Route::get('/logout', function (Request $request) {
    return app(AuthController::class)->logout($request, 'web');
})->name('logout.get');

Route::post('/logout', function (Request $request) {
    return app(AuthController::class)->logout($request, 'web');
})->name('logout');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login-mairie', [AuthController::class, 'showLoginMairie'])->name('login.mairie');
Route::post('/login-mairie', [AuthController::class, 'login_mairie']);
Route::match(['get', 'post'], '/mairie-logout', fn (Request $request) => app(AuthController::class)->logout($request, 'mairie')
)->name('logout.mairie');

Route::get('/login-commercant', [AuthController::class, 'showLoginFinancier'])->name('login.commercant');
Route::post('/login-commercant', [AuthController::class, 'login_commercant']);
// Route::match(['get', 'post'], '/commercant-logout', fn (Request $request) => app(AuthController::class)->logout($request, 'commercant')
// )->name('logout.commercant');
Route::get('/commercant-logout', function (Request $request) {
    return app(AuthController::class)->logout($request, 'commercant');
});

Route::post('/commercant-logout', function (Request $request) {
    return app(AuthController::class)->logout($request, 'commercant');
});

Route::get('/login-agent', [AuthController::class, 'showLoginAgent'])->name('login.agent');
Route::post('/login-agent', [AuthController::class, 'login_agent']);
Route::match(['get', 'post'], '/agent-logout', fn (Request $request) => app(AuthController::class)->logout($request, 'agent')
)->name('logout.agent');

// Mot de passe oublié
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Routes Commerçant Auth (Password Definition)
Route::middleware('signed')->group(function () {
    Route::get('/commercant/define-password/{commercant}', [AuthController::class, 'showDefinePasswordCommercant'])->name('commercant.define_password');
    Route::post('/commercant/define-password/{commercant}', [AuthController::class, 'definePasswordCommercant']);
});

// Finalisation inscription
Route::get('/mairie/finaliser-inscription/{email}', [MailRegistrationController::class, 'showCompletionForm'])->name('mairie.complete-registration.show');
Route::get('/agent/finaliser-inscription/{email}', [MailRegistrationController::class, 'showCompletionFormAgent'])->name('agent.complete-registration.show');
Route::get('/commercant/finaliser-inscription/{email}', [MailRegistrationController::class, 'showCompletionFormCommercant'])->name('commercant.complete-registration.show');
Route::post('/mairie/finaliser-inscription', [MailRegistrationController::class, 'completeRegistration'])->name('mairie.complete-registration.store');
Route::post('/agent/finaliser-inscription', [MailRegistrationController::class, 'completeRegistrationAgent'])->name('agent.complete-registration.store');
Route::post('/commercant/finaliser-inscription', [MailRegistrationController::class, 'completeRegistrationCommercant'])->name('commercant.complete-registration.store');

// Test mail
Route::get('/test-mail', function () {
    Mail::raw('Test Zoho Mail depuis Laravel', function ($message) {
        $message->to('sorodavi3@zohomail.com')->subject('Test SMTP Laravel');
    });

    return 'Test de mail envoyé.';
});

// Route dashboard universelle (pas protégée par 'auth')
Route::get('/dashboard', function () {
    if (Auth::guard('web')->check()) {
        return redirect()->route('superadmin.dashboard');
    } elseif (Auth::guard('mairie')->check() || Auth::guard('finance')->check()) {
        return redirect()->route('mairie.dashboard.index');
    } elseif (Auth::guard('agent')->check()) {
        return redirect()->route('agent.dashboard');
    }

    return redirect('/login')->withErrors('Vous devez être connecté.');
});

// Utilisateur simple (guard: web)
/*
Route::middleware(['auth:web', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', fn () => view('user.dashboard'))->name('dashboard');

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::post('/update', [ProfileController::class, 'update'])->name('update');
    });

    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', // [OrderController::class, 'index'])->name('index');
        Route::get('/{id}', // [OrderController::class, 'show'])->name('show');
    });
});
*/

// On garde le groupe principal qui vérifie que l'utilisateur est authentifié
Route::middleware(['auth:mairie,finance,financier', 'mairie_finance_bridge'])->prefix('mairie')->name('mairie.')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | 1. Routes communes (Accessibles à tous les connectés)
    |--------------------------------------------------------------------------
    */
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('index');
        Route::get('/users-status', [DashboardController::class, 'getUsersStatus'])->name('users_status');
    });

    Route::prefix('commerce')->name('commerce.')->group(function () {
        Route::get('/', [CommerceController::class, 'index'])->name('index');
        Route::get('/list', [CommerceController::class, 'get_list_commercants'])->name('list_commercant');
        Route::get('/print-bulk-cards', [CommerceController::class, 'print_bulk_cards'])->name('print_bulk_cards');
        Route::get('/create', [CommerceController::class, 'create'])->name('create');
        Route::post('/', [CommerceController::class, 'store'])->name('store');
        Route::put('/{mairie}', [CommerceController::class, 'update'])->name('update');
        Route::get('{id}', [CommerceController::class, 'show'])->name('show');
        Route::get('{id}/edit', [CommerceController::class, 'edit'])->name('edit');
        Route::delete('{id}', [CommerceController::class, 'destroy'])->name('destroy');
    });

    // Logout commun
    Route::match(['get', 'post'], '/logout', function (Request $request) {
        Auth::guard('mairie')->logout();
        Auth::guard('finance')->logout();
        Auth::guard('financier')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.mairie')->with('success', 'Déconnecté.');
    })->name('logout');

    Route::get('/get-communes-by-region/{region}', [AgentController::class, 'get_communes'])->name('agents.get_communes');

    /*
    |--------------------------------------------------------------------------
    | 2. Routes réservées au rôle 'ADMIN'
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:admin'])->group(function () {
        Route::prefix('agents')->name('agents.')->group(function () {
            Route::get('/index', [AgentController::class, 'index'])->name('index');
            Route::get('/create', [AgentController::class, 'create'])->name('create');
            Route::post('/', [AgentController::class, 'store'])->name('store');
            Route::get('/list-agent', [AgentController::class, 'list_agent'])->name('list_agent');
            Route::get('/add-agent', [AgentController::class, 'add_agent'])->name('add_agent');
            Route::post('/recensement-recouvrement', [AgentController::class, 'store_agent'])->name('store_agent');
            Route::get('/liste/agent', [AgentController::class, 'get_list_agent'])->name('get_list_agent');
            Route::get('/{mairie}/edit', [AgentController::class, 'edit'])->name('edit');
            Route::put('/{mairie}', [AgentController::class, 'update'])->name('update');
            Route::delete('/{mairie}', [AgentController::class, 'destroy'])->name('destroy');
            Route::get('/liste/data', [AgentController::class, 'get_list_mairie'])->name('get_list_mairie');
        });

        /*
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', // [UserController::class, 'index'])->name('index');
            Route::get('/create', // [UserController::class, 'create'])->name('create');
            Route::post('/', // [UserController::class, 'store'])->name('store');
        });

        Route::prefix('roles')->name('roles.')->group(function () {
            Route::get('/', // [RoleController::class, 'index'])->name('index');
            Route::post('/assign', // [RoleController::class, 'assign'])->name('assign');
        });
        */

        // Gestion des secteurs
        Route::get('secteurs/liste-ajax', [SecteurController::class, 'get_list_secteurs'])->name('secteurs.list');
        Route::get('secteurs/generer-code', [SecteurController::class, 'genererCodeSecteurAjax'])->name('secteurs.genererCode');
        Route::resource('secteurs', SecteurController::class)->except(['index']);
        Route::get('secteurs', [SecteurController::class, 'index'])->name('secteurs.index');

    });

    /*
    |--------------------------------------------------------------------------
    | 3. Routes PARTAGÉES (Accessibles par 'financié' ET 'finance/mairie_finance_bridge')
    |--------------------------------------------------------------------------
    | Ici, on met tout ce qui concerne la gestion quotidienne (Recettes, Dépenses, Taxes)
    */
    // On autorise si l'utilisateur a l'un OU l'autre rôle
    Route::middleware(['role:admin,financié,finance,mairie_finance_bridge'])->group(function () {

        // Routes de données partagées pour DataTables (Taxes et Secteurs)
        Route::get('taches/liste/data', [TacheController::class, 'get_list_taches'])->name('taches.get_list_tache');
        Route::get('taches/show/{id}', [TacheController::class, 'show'])->name('taches.show');

        Route::prefix('encaissement')->name('encaissement.')->group(function () {
            Route::get('/index', [EncaissementMairie::class, 'index'])->name('index');
            Route::get('/get-list', [EncaissementMairie::class, 'get_list_encaissement'])->name('get_list');
            Route::get('/get-grouped-list', [EncaissementMairie::class, 'get_grouped_encaissements'])->name('get_grouped_list');
            Route::get('/get-details', [EncaissementMairie::class, 'get_details_encaissement'])->name('get_details');
        });

        Route::prefix('paiement')->name('paiement.')->group(function () {
            Route::get('/', [PaiementMairie::class, 'index'])->name('index');
            Route::get('/get-list', [PaiementMairie::class, 'get_list_paiement'])->name('get_list');
            Route::get('/get-details/{num_commerce}', [PaiementMairie::class, 'get_details_paiement'])->name('get_details');
        });

        Route::prefix('taxe')->name('taxe.')->group(function () {
            Route::get('/liste/data', [TaxeController::class, 'get_list_taxes'])->name('get_list_taxes');
            // Routes explicites pour éviter lse problèmes avec resource('/')
            Route::get('/', [TaxeController::class, 'index'])->name('index');
            Route::post('/', [TaxeController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [TaxeController::class, 'edit'])->name('edit');
            Route::put('/{id}', [TaxeController::class, 'update'])->name('update');
            Route::delete('/{id}', [TaxeController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('versements')->name('versements.')->group(function () {
            Route::get('/index-list', [VersementController::class, 'index'])->name('index');
            Route::get('/created', [VersementController::class, 'create'])->name('create');
            Route::post('/store', [VersementController::class, 'store'])->name('store');
            Route::get('/liste-ajax', [VersementController::class, 'get_liste_versement'])->name('versements_liste');
            Route::get('/get-montant/agent/{agent}', [VersementController::class, 'get_montant_non_verse'])->name('montant_nonverse');
        });

        Route::prefix('recette')->name('recette.')->group(function () {
            Route::get('/', [RecetteController::class, 'index'])->name('index');
            Route::post('/store', [RecetteController::class, 'store'])->name('store');
            // Route AJAX pour récupérer les montants d'un agent
            Route::get('/get-montant/agent/{agent}', [RecetteController::class, 'get_montant_non_verse'])->name('montant_nonverse');
            Route::get('/liste-ajax', [RecetteController::class, 'get_liste_versement'])->name('versements_liste');
            // route de l'export PDF et excel
            Route::get('/journal-recette/export-pdf', [RecetteController::class, 'exportPdf'])->name('export_pdf');
            Route::get('/journal-recette/export-excel', [RecetteController::class, 'exportExcel'])->name('export_excel');
        });

        Route::prefix('depense')->name('depense.')->group(function () {
            Route::get('/', [DepenseController::class, 'index'])->name('index');
            Route::post('/store', [DepenseController::class, 'store'])->name('store');
            Route::get('depenses/list', [DepenseController::class, 'list'])->name('list');
            Route::get('/{depense}', [DepenseController::class, 'show'])->name('show');
            Route::get('/{depense}/edit', [DepenseController::class, 'edit'])->name('edit');
            Route::put('/{depense}', [DepenseController::class, 'update'])->name('update');
        });

    });

    /*
    |--------------------------------------------------------------------------
    | 4. Routes EXCLUSIVES au rôle 'FINANCIÉ'
    |--------------------------------------------------------------------------
    | Ces routes ne sont PAS accessibles par l'agent finance simple.
    | (Gestion des secteurs, Création des agents finances, Programmation)
    */
    Route::middleware(['role:financié'])->group(function () {

        // Gestion des Tâches et Secteurs (Logique métier avancée)
        Route::prefix('taches')->name('taches.')->group(function () {
            Route::get('/', [TacheController::class, 'index'])->name('index');
            Route::get('/list-tache', [TacheController::class, 'list_tache'])->name('list_tache');
            Route::get('/secteurs', [TacheController::class, 'index'])->name('secteurs.index');
            Route::post('/secteurs', [TacheController::class, 'store'])->name('secteurs.store');
            Route::get('/secteurs/liste', [TacheController::class, 'get_list_secteurs'])->name('secteurs.list');
            Route::get('/secteurs/generer-code', [TacheController::class, 'genererCodeSecteurAjax'])->name('secteurs.genererCode');
            Route::get('/list/secteurs', [TacheController::class, 'get_list_secteurs'])->name('get_list_secteurs');
            Route::get('/create', [TacheController::class, 'create'])->name('create');
            Route::post('/', [TacheController::class, 'store'])->name('store');
            Route::post('/secteur-store', [TacheController::class, 'store_secteur'])->name('store_secteur');
        });

        // Gestion des Agents Finance (Le financé crée ses agents)
        Route::prefix('finance')->name('finance.')->group(function () {
            Route::get('/list-finance', [AgentFinanceController::class, 'index'])->name('index');
            Route::get('/get-list', [AgentFinanceController::class, 'get_list'])->name('get_list');
            Route::get('/create/agent-finance', [AgentFinanceController::class, 'create'])->name('create');
            Route::post('/agent-finance', [AgentFinanceController::class, 'store'])->name('store');
        });

        // Programmation des agents
        Route::get('/agents/programmer-agent', [AgentController::class, 'programer_agent'])->name('agents.programme_agent');
        Route::post('/agents/programmer-agent/store', [AgentController::class, 'storeProgramme'])->name('agents.store_programme_agent');
        Route::get('/agents/programme-liste', [AgentController::class, 'get_list_programmes'])->name('agents.list_programmes');
        Route::get('/agents/programme/{id}/edit', [AgentController::class, 'editProgramme'])->name('agents.edit_programme');
        Route::put('/agents/programme/{id}', [AgentController::class, 'updateProgramme'])->name('agents.update_programme');
        Route::delete('/agents/programme/{id}', [AgentController::class, 'destroyProgramme'])->name('agents.destroy_programme');
    });

    /*
    |--------------------------------------------------------------------------
    | 5. Routes réservées au rôle 'CAISSE'
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:caisse,caissier,caisié,Caissier'])->group(function () {
        Route::prefix('caisse')->name('caisse.')->group(function () {
            Route::get('/encaissement/index', [EncaissementMairie::class, 'caisse_index'])->name('index');
            Route::get('/mes-encaissements', [EncaissementMairie::class, 'mes_encaissements'])->name('mes_encaissements');
            Route::get('/get-mes-list', [EncaissementMairie::class, 'get_mes_encaissements'])->name('get_mes_list');
            Route::post('/search-contribuable', [EncaissementMairie::class, 'search_contribuable'])->name('search');
            Route::get('/faire-encaissement/{id}', [EncaissementMairie::class, 'faire_encaissement'])->name('faire_encaissement');
            Route::get('/taxe-details/{commercantId}/{taxeId}', [EncaissementMairie::class, 'getTaxeDetails'])->name('get_taxe_details');
            Route::post('/store-encaissement/{id}', [EncaissementMairie::class, 'store_encaissement'])->name('store_encaissement');
        });
    });

});

// financier
Route::middleware(['auth:commercant'])->prefix('commercant')->name('commercant.')->group(function () {
    // Route::get('/dashboard', fn() => view('commercant.dashboard'))->name('dashboard');
    Route::get('/dashboard', [AuthController::class, 'showDashboard'])->name('dashboard');
    Route::get('/ma-carte', [AuthController::class, 'showVirtualCard'])->name('virtual_card');

    Route::prefix('recharge')->name('recharge.')->group(function () {
        Route::get('/', [RechargeController::class, 'index'])->name('index');
        Route::get('/create', [RechargeController::class, 'create'])->name('create');
        Route::post('/store', [RechargeController::class, 'store'])->name('store');
    });

    Route::prefix('agents')->name('agents.')->group(function () {
        Route::get('/', [AgentController::class, 'index'])->name('index');
        Route::get('/create', [AgentController::class, 'create'])->name('create');

        Route::get('/programmer-agent', [AgentController::class, 'programer_agent'])->name('programme_agent');
        Route::post('/programmer-agent/store', [AgentController::class, 'storeProgramme'])->name('store_programme_agent');
        Route::get('/programme-liste', [AgentController::class, 'get_list_programmes'])->name('list_programmes');

        Route::post('/', [AgentController::class, 'store'])->name('store');
        Route::get('/{mairie}/edit', [AgentController::class, 'edit'])->name('edit');
        Route::put('/{mairie}', [AgentController::class, 'update'])->name('update');
        Route::delete('/{mairie}', [AgentController::class, 'destroy'])->name('destroy');
        Route::get('/get-communes-by-region/{region}', [AgentController::class, 'get_communes'])->name('get_communes');
        Route::get('/liste/data', [AgentController::class, 'get_list_mairie'])->name('get_list_mairie');
    });

    Route::prefix('payement')->name('payement.')->group(function () {
        // Route::get('/', [PayementController::class, 'index'])->name('index');
        Route::resource('/', PayementController::class);
        // Route::get('/', [PayementController::class, 'create'])->name('create');
        Route::get('/taxes', [PayementController::class, 'listTaxes'])->name('taxes');
        Route::post('/effectuer', [PayementController::class, 'effectuer_paiement'])->name('effectuer');
        Route::get('/historique', [PayementController::class, 'historique'])->name('historique');
        // Le paramètre {taxeId} est plus explicite
        Route::get('/periodes/{taxeId}', [PayementController::class, 'periodes_impayees'])->name('periodes_impayees');
        Route::get('/traiter-automatique', [PayementController::class, 'traiterPaiementsAutomatiques'])->name('traiter_automatique');
    });

    Route::prefix('commerce')->name('commerce.')->group(function () {
        Route::get('/', [CommerceController::class, 'index'])->name('index');
        Route::get('/create', [CommerceController::class, 'create'])->name('create');
        Route::post('/', [CommerceController::class, 'store'])->name('store');

        Route::put('/{mairie}', [CommerceController::class, 'update'])->name('update');

        Route::get('{id}', [CommerceController::class, 'show'])->name('show');
        Route::get('{id}/edit', [CommerceController::class, 'edit'])->name('edit');
        Route::delete('{id}', [CommerceController::class, 'destroy'])->name('destroy');

        Route::get('/commerce/list', [CommerceController::class, 'get_list_commercants'])->name('list_commercant');
    });

    /*
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
    });
    */

    Route::prefix('taches')->name('taches.')->group(function () {
        Route::get('/', [TacheController::class, 'index'])->name('index');

        Route::get('/list', [TacheController::class, 'list_tache'])->name('list_tache');
        Route::get('/liste/data', [TacheController::class, 'get_list_taches'])->name('get_list_tache');
        Route::get('/shwo', [TacheController::class, 'show'])->name('show');

        Route::get('/secteurs', [TacheController::class, 'index'])->name('secteurs.index');

        // Traite la soumission du formulaire pour créer un secteur
        Route::post('/secteurs', [TacheController::class, 'store'])->name('secteurs.store');

        // Route pour la récupération des données par DataTables
        Route::get('/secteurs/liste', [TacheController::class, 'get_list_secteurs'])->name('secteurs.list');

        // Route pour la génération du code via AJAX
        Route::get('/secteurs/generer-code', [TacheController::class, 'genererCodeSecteurAjax'])->name('secteurs.genererCode');

        Route::get('/list/secteurs', [TacheController::class, 'get_list_secteurs'])->name('get_list_secteurs');

        Route::get('/create', [TacheController::class, 'create'])->name('create');
        Route::post('/', [TacheController::class, 'store'])->name('store');
        Route::post('/secteur-store', [TacheController::class, 'store_secteur'])->name('store_secteur');
    });
    Route::resource('secteurs', SecteurController::class);
    Route::get('secteurs/liste-donnees', [SecteurController::class, 'get_list_secteurs'])->name('secteurs.list');
    Route::get('secteurs-generer-code-ajax-commercant', [SecteurController::class, 'genererCodeSecteurAjax'])->name('secteurs.genererCode');

    Route::prefix('versements')->name('versements.')->group(function () {
        // Route::resource('/', VersementController::class);
        Route::get('/index', [VersementController::class, 'index'])->name('index');
        Route::get('/create', [VersementController::class, 'create'])->name('create');
        Route::get('/{agent_id}/edit', [VersementController::class, 'edit'])->name('edit');
        Route::put('/{agent_id}', [VersementController::class, 'update'])->name('update');
        Route::delete('/{agent_id}', [VersementController::class, 'destroy'])->name('destroy');

        Route::get('/{agent_id}', [VersementController::class, 'get_montant_non_verse'])->name('montant_nonverse');
        Route::get('/liste', [VersementController::class, 'get_liste_versement'])->name('versements_liste');

    });

    /*
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::post('/assign', [RoleController::class, 'assign'])->name('assign');
    });
    */

    Route::match(['get', 'post'], '/logout', function (Request $request) {
        Auth::guard('commercant')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.commercant')->with('success', 'Déconnecté.');
    })->name('logout');
});

// Agent
Route::middleware(['auth:agent'])->prefix('agent')->name('agent.')->group(function () {
    Route::get('/dashboard', [AgentContribuable::class, 'dashboard'])->name('dashboard');
    Route::get('/mon-compte', [AgentContribuable::class, 'profile'])->name('profile');

    Route::prefix('contribuable')->name('contribuable.')->group(function () {
        Route::get('/index', [AgentContribuable::class, 'index'])->name('index');
        Route::get('/create', [AgentContribuable::class, 'create'])->name('create');
        Route::get('/{commercant}/edit', [AgentContribuable::class, 'edit_commercant'])->name('edit');
        Route::get('/{commercant}/show', [AgentContribuable::class, 'show_virtual_card'])->name('show');
        Route::delete('/{commercant}', [AgentContribuable::class, 'destroy'])->name('destroy');
        Route::post('/store', [AgentContribuable::class, 'store'])->name('store');
        Route::get('carte-virtuelle/{commercant}', [AgentContribuable::class, 'show_virtual_card'])->name('virtual_card');
        Route::get('carte-virtuelle/export/{commercant}', [AgentContribuable::class, 'export_virtual_card'])->name('export_virtual_card');
        Route::get('carte-virtuelle/print-bulk', [AgentContribuable::class, 'print_bulk_cards'])->name('print_bulk_cards');
        Route::put('/commerce/{commercant}', [AgentContribuable::class, 'update_commercant'])->name('commerce_update');

        Route::post('/type-contribuable/ajouter', [AgentContribuable::class, 'ajouter_contribuable'])->name('ajouter_contribuable');

        Route::get('/commerce/list', [AgentContribuable::class, 'get_list_commercants'])->name('list_commercant');
        Route::get('/get-communes-by-region/{region}', [AgentContribuable::class, 'get_communes'])->name('get_communes');
        Route::get('/liste/data', [AgentContribuable::class, 'get_list_mairie'])->name('get_list_mairie');
    });

    /*
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
    });
    */

    /*
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::post('/assign', [RoleController::class, 'assign'])->name('assign');
    });
    */

    Route::prefix('encaissement')->name('encaissement.')->group(function () {
        Route::get('/liste-commercants', [EncaissementController::class, 'get_list_commercant'])->name('get_list_commercant');
        Route::get('/details-taxe/{commercantId}/{taxeId}', [EncaissementController::class, 'getTaxeDetails'])->name('get_taxe_details');

        Route::get('historique/mes-encaissements', [EncaissementController::class, 'history'])->name('history');
        Route::get('/mes-encaissements-list', [EncaissementController::class, 'get_list_encaissement'])->name('get_list_encaissement');
        Route::delete('/mes-encaissements/{id}', [EncaissementController::class, 'destroy_encaissement'])->name('destroy_encaissement');

        Route::get('list/', [EncaissementController::class, 'index'])->name('index');
        Route::get('create/', [EncaissementController::class, 'create'])->name('create');
        Route::post('store/', [EncaissementController::class, 'store'])->name('store');
        Route::get('details/{encaissement}', [EncaissementController::class, 'show'])->name('show');
        Route::get('edit/{encaissement}', [EncaissementController::class, 'edit'])->name('edit');
        Route::put('update/{encaissement}', [EncaissementController::class, 'update'])->name('update');
        Route::delete('destroy/{encaissement}', [EncaissementController::class, 'destroy'])->name('destroy');

    });

    Route::match(['get', 'post'], '/logout', function (Request $request) {
        Auth::guard('agent')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.agent')->with('success', 'Déconnecté.');
    })->name('logout');
});

// Super Admin (utilise par défaut auth:web, mais on peut créer un guard "superadmin" si tu veux)
Route::middleware(['auth:web', 'role:superadmin'])->prefix('super/admin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\SuperAdmin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/bilan', [App\Http\Controllers\SuperAdmin\DashboardController::class, 'bilan'])->name('bilan');

    Route::prefix('mairies')->name('mairies.')->group(function () {
        Route::get('/', [MairieController::class, 'index'])->name('index');
        Route::get('/create', [MairieController::class, 'create'])->name('create');
        Route::post('/', [MairieController::class, 'store'])->name('store');
        Route::get('/{mairie}/edit', [MairieController::class, 'edit'])->name('edit');
        Route::get('/show', [MairieController::class, 'show'])->name('show');
        Route::put('/{mairie}', [MairieController::class, 'update'])->name('update');
        Route::delete('/{mairie}', [MairieController::class, 'destroy'])->name('destroy');
        Route::get('/get-communes-by-region/{region}', [MairieController::class, 'get_communes'])->name('get_communes');
        Route::get('/liste/data', [MairieController::class, 'get_list_mairie'])->name('get_list_mairie');
    });
    Route::prefix('taxes')->name('taxes.')->group(function () {
        Route::get('/', [SuperAdminTaxe::class, 'index'])->name('index');
        Route::get('/create', [SuperAdminTaxe::class, 'create'])->name('create');
        Route::post('/', [SuperAdminTaxe::class, 'store'])->name('store');
        Route::get('/{mairie}/edit', [SuperAdminTaxe::class, 'edit_taxe'])->name('edit');
        Route::get('/mairies/{id}/infos', [SuperAdminTaxe::class, 'get_infos_mairie'])->name('infos.mairie');
        Route::put('/{mairie}', [SuperAdminTaxe::class, 'update'])->name('update');
        Route::delete('/{mairie}', [SuperAdminTaxe::class, 'destroy'])->name('destroy');
        Route::get('/get-communes-by-region/{region}', [SuperAdminTaxe::class, 'get_communes'])->name('get_communes');
        Route::get('/liste/data', [SuperAdminTaxe::class, 'get_list_taxes'])->name('get_list_taxes');
        Route::get('/mairie-list', [SuperAdminTaxe::class, 'get_mairie_taxe_list'])->name('mairie.list');

    });
});
