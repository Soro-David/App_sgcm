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
use App\Http\Controllers\Mairie\TaxeController;
use App\Http\Controllers\Mairie\AgentController;
use App\Http\Controllers\Mairie\TacheController;
use App\Http\Controllers\Mairie\SecteurController;
use App\Http\Controllers\Mairie\CommerceController;
use App\Http\Controllers\Agent\CommerceController as AgentCommerce;
use App\Http\Controllers\Agent\AgentController as Commercants;
use App\Http\Controllers\Mairie\VersementController;
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

/*
|--------------------------------------------------------------------------
| Routes protégées
|--------------------------------------------------------------------------
*/

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

// Mairie
Route::middleware(['auth:mairie'])->prefix('mairie')->name('mairie.')->group(function () {
    Route::get('/dashboard', fn() => view('mairie.dashboard'))->name('dashboard');

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

    Route::prefix('taxe')->name('taxe.')->group(function () {
        Route::resource('/', TaxeController::class);
       
    });
     Route::prefix('taches')->name('taches.')->group(function () {
        Route::get('/', [TacheController::class, 'index'])->name('index');
        Route::resource('/', TacheController::class);

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

// financier
Route::middleware(['auth:commercant'])->prefix('commercant')->name('commercant.')->group(function () {
    Route::get('/dashboard', fn() => view('commercant.dashboard'))->name('dashboard');

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
        // Route::get('/', [AgentCommerce::class, 'index'])->name('index');
        // Route::get('/create', [AgentCommerce::class, 'create'])->name('create');
        // Route::post('/', [AgentCommerce::class, 'store'])->name('store');
        // Route::put('/{mairie}', [AgentCommerce::class, 'update'])->name('update');
        Route::resource('/', Commercants::class);
        // Route::get('/liste/commercant', [Commercants::class, 'get_list_commercants'])->name('get_list_commercants');
        Route::post('/type-contribuable/ajouter', [Commercants::class, 'ajouter_contribuable'])->name('ajouter_contribuable');

        Route::get('/commerce/list', [Commercants::class, 'get_list_commercants'])->name('list_commercant');
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
        Route::get('/', [TaxeController::class, 'index'])->name('index');
        Route::get('/create', [TaxeController::class, 'create'])->name('create');
        Route::post('/', [TaxeController::class, 'store'])->name('store');
        Route::get('/{mairie}/edit', [TaxeController::class, 'edit_taxe'])->name('edit');
        Route::get('/mairies/{id}/infos', [TaxeController::class, 'get_infos_mairie'])->name('infos.mairie');
        Route::put('/{mairie}', [TaxeController::class, 'update'])->name('update');
        Route::delete('/{mairie}', [TaxeController::class, 'destroy'])->name('destroy');
        Route::get('/get-communes-by-region/{region}', [TaxeController::class, 'get_communes'])->name('get_communes');
        Route::get('/liste/data', [TaxeController::class, 'get_list_taxes'])->name('get_list_taxes');
        Route::get('/mairie-list', [TaxeController::class, 'get_mairie_taxe_list'])->name('mairie.list');

    });
});
