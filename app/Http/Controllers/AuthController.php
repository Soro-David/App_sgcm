<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Déconnexion de tous les guards et régénération de la session.
     */
    protected function logoutAllGuards(Request $request): void
    {
        foreach (['web', 'commercant', 'mairie', 'agent', 'finance', 'financier'] as $guard) {
            Auth::guard($guard)->logout();
        }
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function showLoginMairie()
    {
        return view('auth.login_mairie');
    }

    public function showLoginFinancier()
    {
        return view('auth.login_commercant');
    }

    public function showLoginAgent()
    {
        return view('auth.login_agent');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $this->logoutAllGuards($request);

        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();

            if (Auth::user()->role === 'superadmin') {
                return redirect()->route('superadmin.dashboard');
            }

            return redirect()->route('user.dashboard');
        }

        return back()->withErrors(['email' => 'Identifiants incorrects.']);
    }

    public function login_mairie(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $this->logoutAllGuards($request);

        // Tentative avec le guard mairie (table mairies)
        if (Auth::guard('mairie')->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('mairie.dashboard.index');
        }

        // Si échec, tentative avec le guard finance (table finance)
        if (Auth::guard('finance')->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('mairie.dashboard.index');
        }

        // Si échec, tentative avec le guard financier (table financiers)
        if (Auth::guard('financier')->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('mairie.dashboard.index');
        }

        return back()->withErrors(['email' => 'Identifiants mairie ou finance incorrects.']);
    }

    public function login_commercant(Request $request)
    {
        $credentials = $request->validate([
            'num_commerce' => 'required|string',
            'password' => 'required',
        ]);

        if (Auth::guard('commercant')->attempt($credentials)) {
            $request->session()->regenerate();

            /** @var \App\Models\Commercant $user */
            $user = Auth::guard('commercant')->user();
            $user->update(['last_activity' => now()]);

            return redirect()->intended(route('commercant.dashboard'));
        }

        return back()->withErrors([
            'num_commerce' => 'Le numéro de commerce ou le mot de passe est incorrect.',
        ])->withInput($request->only('num_commerce'));
    }

    public function showDashboard()
    {
        $commercant = Auth::guard('commercant')->user();

        $commercant->load('mairie', 'secteur', 'taxes', 'typeContribuable', 'solde');

        // Récupérer les derniers paiements de l'année en cours
        $derniersPaiements = $commercant->paiementTaxes()
            ->with('taxe')
            ->whereYear('created_at', date('Y'))
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Calculer le nombre de taxes et le montant cumulé (dette potentielle ou montant des taxes assignées)
        $nombreTaxes = $commercant->taxes->count();
        $montantTotalTaxes = $commercant->taxes->sum('montant');

        // Solde du compte
        $soldeCompte = $commercant->solde ? $commercant->solde->montant : 0;

        return view('commercant.dashboard', compact('commercant', 'derniersPaiements', 'nombreTaxes', 'montantTotalTaxes', 'soldeCompte'));
    }

    public function showVirtualCard()
    {
        $commercant = Auth::guard('commercant')->user();
        $commercant->load('mairie', 'secteur', 'taxes', 'typeContribuable');

        return view('commercant.virtual_card', compact('commercant'));
    }

    public function login_agent(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $this->logoutAllGuards($request);

        if (Auth::guard('agent')->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('agent.dashboard');
        }

        return back()->withErrors(['email' => 'Identifiants agent incorrects.']);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'role' => 'required|in:superadmin',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'password' => Hash::make($data['password']),
        ]);

        $this->logoutAllGuards($request);
        Auth::guard('web')->login($user);

        return redirect()->route('superadmin.dashboard');
    }

    /**
     * Déconnexion spécifique à un guard (utilisé par les routes).
     */
    // app/Http/Controllers/AuthController.php

    public function logout(Request $request, ?string $guard = null)
    {
        // Si aucun guard explicitement passé, on détecte celui en session
        if (! $guard) {
            foreach (['web', 'mairie', 'finance', 'commercant', 'agent', 'financier'] as $g) {
                if (Auth::guard($g)->check()) {
                    $guard = $g;
                    break;
                }
            }
        }

        if (! in_array($guard, ['web', 'mairie', 'finance', 'commercant', 'agent', 'financier'])) {
            abort(403);
        }

        Auth::guard($guard)->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $redirectRoutes = [
            'web' => 'login',
            'mairie' => 'login.mairie',
            'finance' => 'login.mairie',
            'financier' => 'login.mairie',
            'agent' => 'login.agent',
            'commercant' => 'login.commercant',
        ];

        return redirect()->route($redirectRoutes[$guard])
            ->with('success', 'Déconnexion réussie.');
    }

    // Gestion du mot de passe Commerçant
    public function showDefinePasswordCommercant(Request $request, \App\Models\Commercant $commercant)
    {
        return view('auth.define_password_commercant', compact('commercant'));
    }

    public function definePasswordCommercant(Request $request, \App\Models\Commercant $commercant)
    {
        $request->validate([
            'password' => 'required|confirmed|min:6',
        ]);

        $commercant->update([
            'password' => Hash::make($request->password),
        ]);

        // Connexion automatique après définition du mot de passe
        Auth::guard('commercant')->login($commercant);
        $commercant->update(['last_activity' => now()]);

        return redirect()->route('commercant.dashboard')->with('success', 'Votre mot de passe a été défini avec succès.');
    }

    // Mot de passe oublié (Universal)
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'guard' => 'required',
        ]);

        $email = $request->email;
        $guard = $request->guard;

        $providers = [
            'web' => \App\Models\User::class,
            'mairie' => \App\Models\Mairie::class,
            'finance' => \App\Models\Finance::class,
            'financier' => \App\Models\Financier::class,
            'agent' => \App\Models\Agent::class,
            'commercant' => \App\Models\Commercant::class,
        ];

        // Si le guard est 'mairie', on cherche aussi dans 'finance' et 'financier'
        // car ils partagent la même page de connexion Mairie
        $modelsToSearch = [$providers[$guard] ?? null];
        if ($guard === 'mairie') {
            $modelsToSearch[] = \App\Models\Finance::class;
            $modelsToSearch[] = \App\Models\Financier::class;
        }

        foreach ($modelsToSearch as $modelClass) {
            if (! $modelClass) {
                continue;
            }

            $user = $modelClass::where('email', $email)->first();
            if ($user) {
                // Déterminer le vrai guard du modèle trouvé
                $actualGuard = $guard;
                if ($modelClass === \App\Models\Finance::class) {
                    $actualGuard = 'finance';
                }
                if ($modelClass === \App\Models\Financier::class) {
                    $actualGuard = 'financier';
                }

                $token = \Illuminate\Support\Str::random(64);

                $url = \Illuminate\Support\Facades\URL::temporarySignedRoute(
                    'password.reset',
                    now()->addMinutes(60),
                    ['token' => $token, 'email' => $email, 'guard' => $actualGuard]
                );

                $user->notify(new \App\Notifications\PasswordResetNotification($url));

                return back()->with('status', 'Le lien de réinitialisation a été envoyé à votre adresse email.');
            }
        }

        return back()->withErrors(['email' => 'Aucun utilisateur trouvé avec cette adresse email dans cet espace.']);
    }

    public function showResetPassword(Request $request, $token)
    {
        if (! $request->hasValidSignature()) {
            abort(403, 'Le lien est invalide ou a expiré.');
        }

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
            'guard' => $request->guard,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'guard' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        $providers = [
            'web' => \App\Models\User::class,
            'mairie' => \App\Models\Mairie::class,
            'finance' => \App\Models\Finance::class,
            'financier' => \App\Models\Financier::class,
            'agent' => \App\Models\Agent::class,
            'commercant' => \App\Models\Commercant::class,
        ];

        $modelClass = $providers[$request->guard] ?? null;

        if (! $modelClass) {
            return back()->withErrors(['email' => 'Guard invalide.']);
        }

        $user = $modelClass::where('email', $request->email)->first();

        if (! $user) {
            return back()->withErrors(['email' => 'Utilisateur non trouvé.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Connexion automatique
        Auth::guard($request->guard)->login($user);

        // Redirection selon le guard
        $redirectRoutes = [
            'web' => 'superadmin.dashboard',
            'mairie' => 'mairie.dashboard.index',
            'finance' => 'mairie.dashboard.index',
            'financier' => 'mairie.dashboard.index',
            'agent' => 'agent.dashboard',
            'commercant' => 'commercant.dashboard',
        ];

        return redirect()->route($redirectRoutes[$request->guard])->with('status', 'Votre mot de passe a été réinitialisé et vous êtes maintenant connecté.');
    }
}
