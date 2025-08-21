<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Gère une requête entrante.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles  // Accepte un ou plusieurs rôles en paramètre
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::guard('mairie')->check()) {
            return redirect()->route('login.mairie');
        }

        $user = Auth::guard('mairie')->user();

        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        
        abort(403, 'Accès non autorisé.');
    }
}