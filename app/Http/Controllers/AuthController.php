<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Commercant;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Déconnexion de tous les guards et régénération de la session.
     */
    protected function logoutAllGuards(Request $request): void
    {
        foreach (['web','commercant', 'mairie', 'agent'] as $guard) {
            Auth::guard($guard)->logout();
        }
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    public function showLogin()        { return view('auth.login'); }
    public function showLoginMairie()  { return view('auth.login_mairie'); }
    public function showLoginFinancier()  { return view('auth.login_commercant'); }
    public function showLoginAgent()   { return view('auth.login_agent'); }
    public function showRegister()     { return view('auth.register'); }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $this->logoutAllGuards($request);

        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('superadmin.dashboard');
        }

        return back()->withErrors(['email' => 'Identifiants superadmin incorrects.']);
    }

    public function login_mairie(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $this->logoutAllGuards($request);

        if (Auth::guard('mairie')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('mairie.dashboard.index');
        }

        return back()->withErrors(['email' => 'Identifiants mairie incorrects.']);
    }



    public function login_commercant(Request $request)
    {
        $credentials = $request->validate([
            'num_commerce' => 'required|string',
            'password' => 'required',
        ]);

        if (Auth::guard('commercant')->attempt($credentials)) {
            $request->session()->regenerate();
            
            return redirect()->intended(route('commercant.dashboard'));
        }

        return back()->withErrors([
            'num_commerce' => 'Le numéro de commerce ou le mot de passe est incorrect.',
        ])->withInput($request->only('num_commerce'));
    }


    public function showDashboard()
    {
        $commercant = Auth::guard('commercant')->user();

        $commercant->load('mairie', 'secteur', 'taxes', 'typeContribuable');

        return view('commercant.dashboard', compact('commercant'));
    }

    public function login_agent(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
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
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'role'     => 'required|in:superadmin',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'role'     => $data['role'],
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

    public function logout(Request $request, string $guard = null)
    {
        // Si aucun guard explicitement passé, on détecte celui en session
        if (! $guard) {
            foreach (['web','mairie','commercant','agent'] as $g) {
                if (Auth::guard($g)->check()) {
                    $guard = $g;
                    break;
                }
            }
        }

        if (! in_array($guard, ['web', 'mairie','commercant', 'agent'])) {
            abort(403);
        }

        Auth::guard($guard)->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $redirectRoutes = [
            'web'    => 'login',
            'mairie' => 'login.mairie',
            'agent'  => 'login.agent',
            'commercant'  => 'login.commercant',
        ];

        return redirect()->route($redirectRoutes[$guard])
                        ->with('success', 'Déconnexion réussie.');
    }

}
