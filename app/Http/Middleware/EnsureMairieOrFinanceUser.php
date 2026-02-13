<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureMairieOrFinanceUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Si l'utilisateur est connecté via le guard 'finance' mais pas 'mairie',
        // on "injecte" l'utilisateur finance dans le guard 'mairie' pour assurer la compatibilité
        // avec les contrôleurs existants qui utilisent Auth::guard('mairie')->user().
        if (!Auth::guard('mairie')->check()) {
            if (Auth::guard('finance')->check()) {
                Auth::guard('mairie')->setUser(Auth::guard('finance')->user());
            } elseif (Auth::guard('financier')->check()) {
                Auth::guard('mairie')->setUser(Auth::guard('financier')->user());
            }
        }

        return $next($request);
    }
}
