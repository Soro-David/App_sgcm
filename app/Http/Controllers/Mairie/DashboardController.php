<?php

namespace App\Http\Controllers\Mairie;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Commercant;
use App\Models\Encaissement;
use App\Models\Mairie;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentMairie = Auth::guard('mairie')->user();

        // 1. Nombre d'agents connectés (actifs dans les 5 dernières minutes)
        $onlineAgentsCount = Agent::where('mairie_ref', $currentMairie->id)
            ->where('last_activity', '>=', Carbon::now()->subMinutes(5))
            ->count();

        // 2. Nombre de contribuables connectés
        // Note: La table commercants n'a pas de colonne last_activity pour l'instant.
        // On met 0 ou on pourrait implémenter une logique via UserLog si disponible.
        // $onlineContribuablesCount = 0;

        $onlineContribuablesCount = Commercant::where('mairie_ref', $currentMairie->mairie_ref)
            ->where('last_activity', '>=', Carbon::now()->subMinutes(5))
            ->count();
        // dd($onlineContribuablesCount);

        // 3. Montant Payé (Total des taxes payées par les contribuables pour cette mairie)
        // On suppose ici que PaiementTaxe représente les recettes validées/payées par le contribuable.
        $montantPaye = \App\Models\PaiementTaxe::where('mairie_ref', $currentMairie->mairie_ref)
            ->sum('montant');

        // 4. Montant Non Payé (Ici interprété comme "Reste à verser par les agents" ou "Argent chez les agents")
        // Total encaissé par les agents MOINS Total versé à la mairie
        $totalEncaisse = Encaissement::where('mairie_ref', $currentMairie->mairie_ref)->sum('montant_percu');
        $totalVerse = \App\Models\Versement::where('mairie_ref', $currentMairie->mairie_ref)->sum('montant_verse');
        $montantNonPaye = max(0, $totalEncaisse - $totalVerse);

        return view('mairie.dashboard', compact(
            'onlineAgentsCount',
            'onlineContribuablesCount',
            'montantPaye',
            'montantNonPaye'
        ));
    }

    /**
     * Récupère les statuts des utilisateurs pour l'affichage en temps réel.
     */
    public function getUsersStatus(): JsonResponse
    {
        $currentMairie = Auth::guard('mairie')->user();
        $onlineThreshold = Carbon::now()->subMinutes(5);

        $agents = Agent::with(['logs' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])
            ->whereHas('mairie', function ($query) use ($currentMairie) {
                $query->where('commune', $currentMairie->commune)
                    ->where('region', $currentMairie->region);
            })
            ->whereDate('last_activity', today())
            ->get();

        $mairies = Mairie::with(['logs' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])
            ->where('commune', $currentMairie->commune)
            ->where('region', $currentMairie->region)
            ->where('id', '!=', $currentMairie->id)
            ->whereDate('last_activity', today())
            ->get();

        $users = $mairies->merge($agents);

        $data = $users->map(function ($user) use ($onlineThreshold) {
            $isOnline = $user->last_activity && $user->last_activity >= $onlineThreshold;

            $latestLogin = $user->logs->where('event', 'login')->first();
            $latestLogout = $user->logs->where('event', 'logout')->first();

            return [
                'name' => $user->name,
                'role' => $user instanceof Agent ? 'Agent' : 'Mairie',
                'status' => $isOnline ? 'En ligne' : 'Déconnecté',
                'login_time' => $latestLogin ? $latestLogin->created_at->format('d/m/Y H:i') : 'N/A',
                'logout_time' => ! $isOnline ? ($latestLogout ? $latestLogout->created_at->format('d/m/Y H:i') : $user->last_activity->format('d/m/Y H:i')) : '-',
            ];
        });

        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
