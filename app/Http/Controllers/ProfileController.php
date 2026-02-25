<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        $user = $this->getCurrentUser();
        $guard = $this->getCurrentGuard();

        if (! $user) {
            return redirect('/login');
        }

        // Déterminer le layout à utiliser
        $layout = match ($guard) {
            'agent' => 'agent.layouts.app',
            'commercant' => 'commercant.layouts.app',
            'mairie', 'finance', 'financier' => 'mairie.layouts.app',
            'web' => 'superAdmin.layouts.app',
            default => 'mairie.layouts.app',
        };

        return view('profile.show', compact('user', 'guard', 'layout'));
    }

    public function update(Request $request)
    {
        $user = $this->getCurrentUser();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Non autorisé'], 403);
        }

        $isCommercant = $user instanceof \App\Models\Commercant;
        $nameField = $isCommercant ? 'nom' : 'name';

        $rules = [
            $nameField => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:191',
                Rule::unique($user->getTable())->ignore($user->id),
            ],
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
            'matricule' => 'nullable|string|max:100',
            'filiation' => 'nullable|string|max:255',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
            'photo_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        $request->validate($rules);

        // Update basic info
        $user->$nameField = $request->input($nameField);
        $user->email = $request->email;
        
        // Handle adresse if column exists
        if ($request->has('adresse') && \Schema::hasColumn($user->getTable(), 'adresse')) {
            $user->adresse = $request->adresse;
        }

        // Handle telephone based on available columns (telephone1 or telephone)
        if ($request->has('telephone')) {
            if (\Schema::hasColumn($user->getTable(), 'telephone1')) {
                $user->telephone1 = $request->telephone;
            } elseif (\Schema::hasColumn($user->getTable(), 'telephone')) {
                $user->telephone = $request->telephone;
            }
        }

        // Additional fields for Agents/Mairies/Finances/etc.
        if ($request->has('matricule') && \Schema::hasColumn($user->getTable(), 'matricule')) {
            $user->matricule = $request->matricule;
        }
        if ($request->has('filiation') && \Schema::hasColumn($user->getTable(), 'filiation')) {
            $user->filiation = $request->filiation;
        }

        // Update password
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
            }
            $user->password = Hash::make($request->new_password);
        }

        // Update photo
        if ($request->hasFile('photo_profil')) {
            if ($user->photo_profil && Storage::disk('public')->exists($user->photo_profil)) {
                Storage::disk('public')->delete($user->photo_profil);
            }
            $user->photo_profil = $request->file('photo_profil')->store('profiles', 'public');
        }

        $user->save();

        return back()->with('success', 'Profil mis à jour avec succès.');
    }

    private function getCurrentUser()
    {
        $guard = $this->getCurrentGuard();
        return Auth::guard($guard)->user();
    }

    private function getCurrentGuard()
    {
        $routeName = \Route::currentRouteName();

        // Si on est sur une route préfixée, on privilégie le guard correspondant
        if ($routeName) {
            if (str_starts_with($routeName, 'commercant.')) {
                return 'commercant';
            }
            if (str_starts_with($routeName, 'agent.')) {
                return 'agent';
            }
            if (str_starts_with($routeName, 'mairie.')) {
                foreach (['mairie', 'finance', 'financier'] as $guard) {
                    if (Auth::guard($guard)->check()) {
                        return $guard;
                    }
                }
                return 'mairie'; // Fallback for mairie prefix
            }
            if (str_starts_with($routeName, 'superadmin.')) {
                return 'web';
            }
        }

        // Fallback sur l'URL si le nom de la route n'est pas disponible ou ne matche pas
        if (request()->is('commercant/*')) {
            return 'commercant';
        } elseif (request()->is('agent/*')) {
            return 'agent';
        } elseif (request()->is('mairie/*')) {
             foreach (['mairie', 'finance', 'financier'] as $guard) {
                if (Auth::guard($guard)->check()) {
                    return $guard;
                }
            }
            return 'mairie';
        } elseif (request()->is('super/admin/*')) {
            return 'web';
        }

        // En dernier recours, on cherche le premier guard connecté
        foreach (['commercant', 'agent', 'mairie', 'finance', 'financier', 'web'] as $guard) {
            if (Auth::guard($guard)->check()) {
                return $guard;
            }
        }

        return 'web';
    }
}
