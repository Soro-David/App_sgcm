<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Obtenir le chemin vers lequel l'utilisateur doit être redirigé
     * lorsqu'il n'est pas authentifié.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }

        if ($request->is('mairie/*')) {
            return route('login.mairie');
        }

        if ($request->is('agent/*')) {
            return route('login.agent');
        }
        if ($request->is('commercant/*')) {
            return route('login.commercant');
        }

        return route('login');
    }
}
