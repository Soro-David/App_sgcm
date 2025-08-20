<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class UpdateLastActivity
{
    public function handle($request, Closure $next)
    {
        if (Auth::guard('agent')->check()) {
            $user = Auth::guard('agent')->user();
            $user->update(['last_activity' => now()]);
        }

        if (Auth::guard('mairie')->check()) {
            $user = Auth::guard('mairie')->user();
            $user->update(['last_activity' => now()]);
        }

        return $next($request);
    }
}