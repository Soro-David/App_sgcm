<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\MailRegistrationController;
use App\Http\Controllers\SuperAdmin\MairieController;
use App\Http\Controllers\SuperAdmin\TaxeController as SuperAdminTaxe;
use App\Http\Controllers\Mairie\TaxeController;
use App\Http\Controllers\Mairie\AgentController;
use App\Http\Controllers\Mairie\TacheController;
use App\Http\Controllers\Mairie\SecteurController;
use App\Http\Controllers\Mairie\CommerceController;
use App\Http\Controllers\Agent\CommerceController as AgentCommerce;
use App\Http\Controllers\Agent\AgentController as AgentCommercants;
use App\Http\Controllers\Agent\EncaissementController;
use App\Http\Controllers\Mairie\EncaissementController as EncaissementMairie;
use App\Http\Controllers\Mairie\PaiementController as PaiementMairie;
use App\Http\Controllers\Mairie\VersementController;
use App\Http\Controllers\Mairie\DashboardController;
use App\Http\Controllers\Mairie\ComptabiliteController;
use App\Http\Controllers\Commercant\PayementController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RoleController; 


/*
|--------------------------------------------------------------------------
| Routes publiques
|--------------------------------------------------------------------------
*/

// Authentification
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/logout', fn(Request $request) =>
    app(AuthController::class)->logout($request, 'web')
)->name('logout');


Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);


Route::get('/login-mairie', [AuthController::class, 'showLoginMairie'])->name('login.mairie');
Route::post('/login-mairie', [AuthController::class, 'login_mairie'])->name('login.mairie');
Route::get('/mairie-logout', fn(Request $request) => 
    app(AuthController::class)->logout($request, 'mairie')
)->name('logout.mairie');

Route::get('/login-commercant', [AuthController::class, 'showLoginFinancier'])->name('login.commercant');
Route::post('/login-commercant', [AuthController::class, 'login_commercant'])->name('login.commercant');
Route::get('/commercant-logout', fn(Request $request) => 
    app(AuthController::class)->logout($request, 'commercant')
)->name('logout.commercant');

Route::get('/login-agent', [AuthController::class, 'showLoginAgent'])->name('login.agent');
Route::post('/login-agent', [AuthController::class, 'login_agent'])->name('login.agent');
Route::get('/agent-logout', fn(Request $request) =>
    app(AuthController::class)->logout($request, 'agent')
)->name('logout.agent');




// Finalisation inscription 
Route::get('/mairie/finaliser-inscription/{email}', [MailRegistrationController::class, 'showCompletionForm'])->name('mairie.complete-registration.show');
Route::get('/agent/finaliser-inscription/{email}', [MailRegistrationController::class, 'showCompletionFormAgent'])->name('agent.complete-registration.show');
Route::post('/mairie/finaliser-inscription', [MailRegistrationController::class, 'completeRegistration'])->name('mairie.complete-registration.store');
Route::post('/agent/finaliser-inscription', [MailRegistrationController::class, 'completeRegistrationAgent'])->name('agent.complete-registration.store');

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
    } elseif (Auth::guard('mairie')->check()) {
        return redirect()->route('mairie.dashboard');
    } elseif (Auth::guard('agent')->check()) {
        return redirect()->route('agent.dashboard');
    }

    return redirect('/lfogin')->withErrors('Vous devez être connecté.');
});


// Utilisateur simple (guard: web)
Route::middleware(['auth:web', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', fn() => view('user.dashboard'))->name('dashboard');

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::post('/update', [ProfileController::class, 'update'])->name('update');
    });

    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{id}', [OrderController::class, 'show'])->name('show');
    });
});

// // Mairie
// Route::middleware(['auth:mairie'])->prefix('mairie')->name('mairie.')->group(function () {
//     // Route::get('/dashboard', fn() => view('mairie.dashboard'))->name('dashboard');
//     // Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

//     Route::prefix('dashboard')->name('dashboard.')->group(function () {
        
//         Route::get('/', [DashboardController::class, 'index'])->name('index');
        
//         // CORRECTION : On retire le /dashboard redondant et on utilise le bon nom de méthode
//         Route::get('/users-status', [DashboardController::class, 'getUsersStatus'])->name('users_status');

//     });


//     Route::prefix('agents')->name('agents.')->group(function () {
//         Route::get('/', [AgentController::class, 'index'])->name('index');
//         Route::get('/create', [AgentController::class, 'create'])->name('create');

//         //agent de recouvrement et agent de recenssement
//         Route::get('/list-agent', [AgentController::class, 'list_agent'])->name('list_agent');
//         Route::get('/add-agent', [AgentController::class, 'add_agent'])->name('add_agent');
//          Route::post('/recensement-recouvrement', [AgentController::class, 'store_agent'])->name('store_agent');
//          Route::get('/liste/agent', [AgentController::class, 'get_list_agent'])->name('get_list_agent');

//         Route::get('/programmer-agent', [AgentController::class, 'programer_agent'])->name('programme_agent');
//         Route::post('/programmer-agent/store', [AgentController::class, 'storeProgramme'])->name('store_programme_agent');
//         Route::get('/programme-liste', [AgentController::class, 'get_list_programmes'])->name('list_programmes');

//         Route::post('/', [AgentController::class, 'store'])->name('store');
//         Route::get('/{mairie}/edit', [AgentController::class, 'edit'])->name('edit');
//         Route::put('/{mairie}', [AgentController::class, 'update'])->name('update');
//         Route::delete('/{mairie}', [AgentController::class, 'destroy'])->name('destroy');
//         Route::get('/get-communes-by-region/{region}', [AgentController::class, 'get_communes'])->name('get_communes');
//         Route::get('/liste/data', [AgentController::class, 'get_list_mairie'])->name('get_list_mairie');
//     });

//      Route::prefix('commerce')->name('commerce.')->group(function () {
//         Route::get('/', [CommerceController::class, 'index'])->name('index');
//         Route::get('/create', [CommerceController::class, 'create'])->name('create');
//         Route::post('/', [CommerceController::class, 'store'])->name('store');
        
//         Route::put('/{mairie}', [CommerceController::class, 'update'])->name('update');

//         Route::get('{id}', [CommerceController::class, 'show'])->name('show');
//         Route::get('{id}/edit', [CommerceController::class, 'edit'])->name('edit');
//         Route::delete('{id}', [CommerceController::class, 'destroy'])->name('destroy');

//         Route::get('/commerce/list', [CommerceController::class, 'get_list_commercants'])->name('list_commercant');
//     });

//     Route::prefix('users')->name('users.')->group(function () {
//         Route::get('/', [UserController::class, 'index'])->name('index');
//         Route::get('/create', [UserController::class, 'create'])->name('create');
//         Route::post('/', [UserController::class, 'store'])->name('store');
//     });

//     Route::prefix('taxe')->name('taxe.')->group(function () {
//         Route::resource('/', TaxeController::class);
       
//     });
//     Route::prefix('encaissement')->name('encaissement.')->group(function () {
//         // Route pour afficher la page avec la table 
//         Route::get('/', [EncaissementMairie::class, 'index'])->name('index');
//         Route::get('/get-list', [EncaissementMairie::class, 'get_list_encaissement'])->name('get_list');
//     });

//     Route::prefix('paiement')->name('paiement.')->group(function () {
//         // Route pour afficher la page avec la table
//         Route::get('/', [PaiementMairie::class, 'index'])->name('index');
//         Route::get('/get-list', [PaiementMairie::class, 'get_list_paiement'])->name('get_list');
//     });
//      Route::prefix('taches')->name('taches.')->group(function () {
//         Route::get('/', [TacheController::class, 'index'])->name('index');
//         Route::resource('/', TacheController::class);

//         Route::get('/list', [TacheController::class, 'list_tache'])->name('list_tache');
        
//         Route::get('/liste/data', [TacheController::class, 'get_list_taches'])->name('get_list_tache');
//         Route::get('/shwo', [TacheController::class, 'show'])->name('show');

//         Route::get('/secteurs', [TacheController::class, 'index'])->name('secteurs.index');

//         // Traite la soumission du formulaire pour créer un secteur
//         Route::post('/secteurs', [TacheController::class, 'store'])->name('secteurs.store');

//         // Route pour la récupération des données par DataTables
//         Route::get('/secteurs/liste', [TacheController::class, 'get_list_secteurs'])->name('secteurs.list');

//         // Route pour la génération du code via AJAX
//         Route::get('/secteurs/generer-code', [TacheController::class, 'genererCodeSecteurAjax'])->name('secteurs.genererCode');

//         Route::get('/list/secteurs', [TacheController::class, 'get_list_secteurs'])->name('get_list_secteurs');

//         Route::get('/create', [TacheController::class, 'create'])->name('create');
//         Route::post('/', [TacheController::class, 'store'])->name('store');
//         Route::post('/secteur-store', [TacheController::class, 'store_secteur'])->name('store_secteur');
//     });
//     Route::prefix('secteurs')->name('secteurs.')->group(function () {
//         Route::resource('/', SecteurController::class);
//         Route::get('/liste', [SecteurController::class, 'get_list_secteurs'])->name('list');
//         Route::get('/generer-code', [SecteurController::class, 'genererCodeSecteurAjax'])->name('genererCode');
//     });


//     Route::prefix('versements')->name('versements.')->group(function () {
//         Route::get('/', [VersementController::class, 'index'])->name('index');
//         Route::get('/create', [VersementController::class, 'create'])->name('create');
//         Route::post('/store', [VersementController::class, 'store'])->name('store');
//         // Route AJAX pour récupérer la liste des versements (pour DataTables) 
//         Route::get('/liste-ajax', [VersementController::class, 'get_liste_versement'])->name('versements_liste');
        
//         // Route AJAX pour récupérer les montants d'un agent
//         Route::get('/get-montant/agent/{agent}', [VersementController::class, 'get_montant_non_verse'])->name('montant_nonverse');
        
//     });

//     Route::prefix('comptabilite')->name('comptabilite.')->group(function () {
//         Route::get('/', [ComptabiliteController::class, 'index'])->name('index');
//         Route::get('/journal-depense', [ComptabiliteController::class, 'journal_depense'])->name('journal_depense');
//         Route::get('/journal-recette', [ComptabiliteController::class, 'journal_recette'])->name('journal_recette');

//         Route::post('/store/journal-depense', [ComptabiliteController::class, 'store_journal_depense'])->name('store_journal_depense');
//         Route::post('/store/journal-recette', [ComptabiliteController::class, 'store_journal_recette'])->name('store_journal_recette');

//         Route::post('/journal-recette/recette-effectuee', [ComptabiliteController::class, 'recette_effectuee'])->name('recette_effectuee');

//         Route::post('/store', [ComptabiliteController::class, 'store'])->name('store');
//         Route::get('/liste-ajax', [ComptabiliteController::class, 'get_liste_versement'])->name('versements_liste');
        
//         // Route AJAX pour récupérer les montants d'un agent
//         Route::get('/get-montant/agent/{agent}', [ComptabiliteController::class, 'get_montant_non_verse'])->name('montant_nonverse');
        
//         Route::get('/journal-recette/export-pdf', [ComptabiliteController::class, 'exportPdf'])->name('journal_recette.export_pdf');
//         Route::get('/journal-recette/export-excel', [ComptabiliteController::class, 'exportExcel'])->name('journal_recette.export_excel');
//     });


//     Route::prefix('roles')->name('roles.')->group(function () {
//         Route::get('/', [RoleController::class, 'index'])->name('index');
//         Route::post('/assign', [RoleController::class, 'assign'])->name('assign');
//     });

//     Route::post('/logout', function (Request $request) {
//         Auth::guard('mairie')->logout();
//         $request->session()->invalidate();
//         $request->session()->regenerateToken();
//         return redirect('/')->with('success', 'Déconnecté.');
//     })->name('logout');
// });

// On garde le groupe principal qui vérifie que l'utilisateur est authentifié
Route::middleware(['auth:mairie'])->prefix('mairie')->name('mairie.')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Routes communes (accessibles à tous les rôles : admin, financé, caisse)
    |--------------------------------------------------------------------------
    */
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('index');
        Route::get('/users-status', [DashboardController::class, 'getUsersStatus'])->name('users_status');
    });

    Route::prefix('commerce')->name('commerce.')->group(function () {
        Route::get('/', [CommerceController::class, 'index'])->name('index');
        Route::get('{id}', [CommerceController::class, 'show'])->name('show');
    });

    /*
    |--------------------------------------------------------------------------
    | Routes réservées au rôle 'admin'
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:admin'])->group(function () {
        Route::prefix('agents')->name('agents.')->group(function () {
            Route::get('/', [AgentController::class, 'index'])->name('index');
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
        
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
        });

        Route::prefix('roles')->name('roles.')->group(function () {
            Route::get('/', [RoleController::class, 'index'])->name('index');
            Route::post('/assign', [RoleController::class, 'assign'])->name('assign');
        });
    });


    /*
    |--------------------------------------------------------------------------
    | Routes réservées au rôle 'financié'
    |--------------------------------------------------------------------------
    | Note : Si un admin doit aussi y accéder, on utilisera ->middleware(['role:admin,financié'])
    */
    Route::middleware(['role:financié'])->group(function () {
        Route::prefix('secteurs')->name('secteurs.')->group(function () {
            Route::resource('/', SecteurController::class);
            Route::get('/liste', [SecteurController::class, 'get_list_secteurs'])->name('list');
            Route::get('/generer-code', [SecteurController::class, 'genererCodeSecteurAjax'])->name('genererCode');
        });

        Route::prefix('encaissement')->name('encaissement.')->group(function () {
            Route::get('/', [EncaissementMairie::class, 'index'])->name('index');
            Route::get('/get-list', [EncaissementMairie::class, 'get_list_encaissement'])->name('get_list');
        });

        Route::prefix('paiement')->name('paiement.')->group(function () {
            Route::get('/', [PaiementMairie::class, 'index'])->name('index');
            Route::get('/get-list', [PaiementMairie::class, 'get_list_paiement'])->name('get_list');
        });
        
        Route::prefix('taxe')->name('taxe.')->group(function () {
            Route::resource('/', TaxeController::class);
        });

        Route::prefix('versements')->name('versements.')->group(function () {
            Route::get('/', [VersementController::class, 'index'])->name('index');
            Route::get('/create', [VersementController::class, 'create'])->name('create');
            Route::post('/store', [VersementController::class, 'store'])->name('store');
            Route::get('/liste-ajax', [VersementController::class, 'get_liste_versement'])->name('versements_liste');
            Route::get('/get-montant/agent/{agent}', [VersementController::class, 'get_montant_non_verse'])->name('montant_nonverse');
        });

        Route::prefix('comptabilite')->name('comptabilite.')->group(function () {
            Route::get('/', [ComptabiliteController::class, 'index'])->name('index');
            Route::get('/journal-depense', [ComptabiliteController::class, 'journal_depense'])->name('journal_depense');
            Route::get('/journal-recette', [ComptabiliteController::class, 'journal_recette'])->name('journal_recette');

            Route::post('/store/journal-depense', [ComptabiliteController::class, 'store_journal_depense'])->name('store_journal_depense');
            Route::post('/store/journal-recette', [ComptabiliteController::class, 'store_journal_recette'])->name('store_journal_recette');

            Route::post('/journal-recette/recette-effectuee', [ComptabiliteController::class, 'recette_effectuee'])->name('recette_effectuee');

            Route::post('/store', [ComptabiliteController::class, 'store'])->name('store');
            Route::get('/liste-ajax', [ComptabiliteController::class, 'get_liste_versement'])->name('versements_liste');
            
            // Route AJAX pour récupérer les montants d'un agent
            Route::get('/get-montant/agent/{agent}', [ComptabiliteController::class, 'get_montant_non_verse'])->name('montant_nonverse');
            
            Route::get('/journal-recette/export-pdf', [ComptabiliteController::class, 'exportPdf'])->name('journal_recette.export_pdf');
            Route::get('/journal-recette/export-excel', [ComptabiliteController::class, 'exportExcel'])->name('journal_recette.export_excel');
        });
        
        // Routes pour la programmation des agents (rôle financé)
        Route::get('/agents/programmer-agent', [AgentController::class, 'programer_agent'])->name('agents.programme_agent');
        Route::post('/agents/programmer-agent/store', [AgentController::class, 'storeProgramme'])->name('agents.store_programme_agent');
        Route::get('/agents/programme-liste', [AgentController::class, 'get_list_programmes'])->name('agents.list_programmes');
    });

    /*
    |--------------------------------------------------------------------------
    | Routes réservées au rôle 'caisse'
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:caisse'])->group(function () {
        //ici les routes pour le rôle 'caisse'
        
    });


    // La route de déconnexion est accessible à tous les utilisateurs connectés
    Route::post('/logout', function (Request $request) {
        Auth::guard('mairie')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Déconnecté.');
    })->name('logout');

    //  les routes qui ne sont pas spécifiques à un rôle mais qui nécessitent une logique particulière.
    Route::get('/get-communes-by-region/{region}', [AgentController::class, 'get_communes'])->name('agents.get_communes');
});

// financier
Route::middleware(['auth:commercant'])->prefix('commercant')->name('commercant.')->group(function () {
    // Route::get('/dashboard', fn() => view('commercant.dashboard'))->name('dashboard');
     Route::get('/dashboard', [AuthController::class, 'showDashboard'])->name('dashboard');

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

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
    });

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
    Route::prefix('secteurs')->name('secteurs.')->group(function () {
        Route::resource('/', SecteurController::class);
        Route::get('/liste', [SecteurController::class, 'get_list_secteurs'])->name('list');
        Route::get('/generer-code', [SecteurController::class, 'genererCodeSecteurAjax'])->name('genererCode');
    });

    Route::prefix('versements')->name('versements.')->group(function () {
        Route::resource('/', VersementController::class);
        Route::get('/{agent_id}', [VersementController::class, 'get_montant_non_verse'])->name('montant_nonverse');
        Route::get('/liste', [VersementController::class, 'get_liste_versement'])->name('versements_liste');

    });


    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::post('/assign', [RoleController::class, 'assign'])->name('assign');
    });

    Route::post('/logout', function (Request $request) {
        Auth::guard('mairie')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Déconnecté.');
    })->name('logout');
});


// Agent
Route::middleware(['auth:agent'])->prefix('agent')->name('agent.')->group(function () {
    Route::get('/dashboard', fn() => view('agent.dashboard'))->name('dashboard');

    Route::prefix('commerce')->name('commerce.')->group(function () {
        Route::resource('/', AgentCommercants::class);
        Route::get('carte-virtuelle/{commercant}', [AgentCommercants::class, 'show_virtual_card'])->name('virtual_card');
        Route::get('/commerce/{commercant}/edit', [AgentCommercants::class, 'edit_commercant'])->name('commerce_edit');
        Route::put('/commerce/{commercant}', [AgentCommercants::class, 'update_commercant'])->name('commerce_update');


        Route::get('carte-virtuelle/edit/{commercant}', [AgentCommercants::class, 'edit_virtual_card'])->name('virtual_card');
        

        Route::post('/type-contribuable/ajouter', [AgentCommercants::class, 'ajouter_contribuable'])->name('ajouter_contribuable');

        Route::get('/commerce/list', [AgentCommercants::class, 'get_list_commercants'])->name('list_commercant');
        Route::get('/get-communes-by-region/{region}', [AgentCommerce::class, 'get_communes'])->name('get_communes');
        Route::get('/liste/data', [AgentCommerce::class, 'get_list_mairie'])->name('get_list_mairie');
    });

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
    });

    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::post('/assign', [RoleController::class, 'assign'])->name('assign');
    });


    Route::prefix('encaissement')->name('encaissement.')->group(function () {
        Route::get('/liste-commercants', [EncaissementController::class, 'get_list_commercant'])->name('get_list_commercant');
        Route::get('/details-taxe/{commercantId}/{taxeId}', [EncaissementController::class, 'getTaxeDetails'])->name('get_taxe_details');
        Route::resource('/', EncaissementController::class)->parameters(['' => 'encaissement']);
    });

    Route::post('/logout', function (Request $request) {
        Auth::guard('agent')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Déconnecté.');
    })->name('logout');
});

// Super Admin (utilise par défaut auth:web, mais on peut créer un guard "superadmin" si tu veux)
Route::middleware(['auth:web', 'role:superadmin'])->prefix('super/admin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', fn() => view('superAdmin.dashboard'))->name('dashboard');

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
