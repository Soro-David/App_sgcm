<?php

namespace App\Providers;

use App\Models\Agent;
use App\Models\Finance;
use App\Models\Financier;
use App\Models\Mairie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(1050);

        // ViewComposer : injecte le compteur d'agents pending dans le sidebar
        View::composer('mairie.layouts.partials.sidebar', function ($view) {
            $pendingCount = 0;

            try {
                // Récupérer l'utilisateur connecté (mairie admin)
                $user = Auth::guard('mairie')->user()
                    ?? Auth::guard('finance')->user()
                    ?? Auth::guard('financier')->user();

                if ($user && isset($user->mairie_ref)) {
                    $mairie_ref = $user->mairie_ref;
                    $currentId  = $user->id;

                    // Agents terrain (table agents) sans mot de passe
                    $agentsCount = Agent::where('mairie_ref', $mairie_ref)
                        ->whereNull('password')
                        ->count();

                    // Personnel mairie/finance/financier avec status pending ou sans mot de passe
                    $mairieCount = Mairie::where('mairie_ref', $mairie_ref)
                        ->where('id', '!=', $currentId)
                        ->where(function ($q) {
                            $q->where('status', 'pending')->orWhereNull('password');
                        })
                        ->count();

                    $financeCount = Finance::where('mairie_ref', $mairie_ref)
                        ->where(function ($q) {
                            $q->where('status', 'pending')->orWhereNull('password');
                        })
                        ->count();

                    $financierCount = Financier::where('mairie_ref', $mairie_ref)
                        ->where(function ($q) {
                            $q->where('status', 'pending')->orWhereNull('password');
                        })
                        ->count();

                    $pendingCount = $agentsCount + $mairieCount + $financeCount + $financierCount;
                }
            } catch (\Exception $e) {
                $pendingCount = 0;
            }

            $view->with('pendingAgentsCount', $pendingCount);
        });
    }
}

