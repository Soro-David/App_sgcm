<?php

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

// use Throwable;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Alias des middlewares
        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'mairie_finance_bridge' => \App\Http\Middleware\EnsureMairieOrFinanceUser::class,

            'nocache' => \App\Http\Middleware\NoCache::class,
            'abilities' => \Laravel\Sanctum\Http\Middleware\CheckAbilities::class,
            'ability' => \Laravel\Sanctum\Http\Middleware\CheckForAnyAbility::class,
        ]);

        // Groupe middleware WEB
        $middleware->group('web', [
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \App\Http\Middleware\NoCache::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\UpdateLastActivity::class,
        ]);

        // Groupe middleware API
        $middleware->group('api', [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $e, Request $request) {
            // Gestion des erreurs de méthode HTTP non autorisée (ex: session expirée sur un POST)
            if ($e instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
                if ($request->is('mairie/*')) {
                    return redirect()->route('login.mairie');
                }
                if ($request->is('agent/*')) {
                    return redirect()->route('login.agent');
                }
                if ($request->is('commercant/*')) {
                    return redirect()->route('login.commercant');
                }
                if ($request->is('super/admin/*')) {
                    return redirect()->route('login');
                }

                return redirect()->route('login');
            }

            if ($request->is('api/*') || $request->expectsJson()) {
                if ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException) {
                    return response()->json(['message' => 'Ressource introuvable.'], 404);
                }

                if ($e instanceof AuthenticationException) {
                    return response()->json(['message' => 'Non authentifié.'], 401);
                }

                if ($e instanceof AuthorizationException) {
                    return response()->json(['message' => 'Accès non autorisé.'], 403);
                }

                if ($e instanceof ValidationException) {
                    return response()->json([
                        'message' => 'Les données fournies sont invalides.',
                        'errors' => $e->errors(),
                    ], 422);
                }

                if ($e instanceof HttpException) {
                    return response()->json([
                        'message' => $e->getMessage(),
                    ], $e->getStatusCode());
                }

                if (config('app.debug')) {
                    return response()->json([
                        'message' => $e->getMessage(),
                        'exception' => get_class($e),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => $e->getTrace(),
                    ], 500);
                }

                return response()->json(['message' => 'Erreur interne du serveur.'], 500);
            }

            return null;
        });
    })
    ->create();
