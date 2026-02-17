<?php

namespace App\Http\Controllers\Mairie;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Commercant;
use App\Models\Encaissement;
use App\Models\Finance;
use App\Models\Financier;
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
    public function index(Request $request)
    {
        // On récupère l'utilisateur en priorisant les guards spécifiques (pour éviter les conflits de session en test)
        $user = Auth::guard('agent')->user() ?: 
                Auth::guard('financier')->user() ?: 
                Auth::guard('finance')->user() ?: 
                Auth::guard('mairie')->user();

        if (! $user) {
            return redirect()->route('login.mairie')->with('error', 'Session expirée.');
        }

        $mairieRef = $user->mairie_ref;
        $filter = $request->get('filter', 'tout');

        // Base statistics for everyone
        $stats = [
            'onlineAgentsCount' => Agent::where('mairie_ref', $mairieRef)
                ->where('last_activity', '>=', Carbon::now()->subMinutes(5))
                ->count(),
            'onlineContribuablesCount' => Commercant::where('mairie_ref', $mairieRef)
                ->where('last_activity', '>=', Carbon::now()->subMinutes(5))
                ->count(),
            'totalContribuables' => Commercant::where('mairie_ref', $mairieRef)->count(),
            'countRecouvrement' => 0,
            'countRecensement' => 0,
            'countCaissier' => 0,
            'countAgentFinancier' => 0,
            'countAdminFinancier' => 0,
            'progressionLabels' => [],
            'progressionValues' => [],
            'currentFilter' => $filter,
        ];

        // Specific statistics for Admin Mairie and Admin Financier
        $role = strtolower($user->role ?? ($user->type ?? ''));
        if (($user instanceof Mairie && ($role === 'admin' || $role === 'financiers' || $role === 'responsable_financier')) || 
            ($user instanceof Finance && ($role === 'finance' || $role === 'agent_finance')) || 
            ($user instanceof Financier) || 
            $role === 'financiers' || 
            $role === 'responsable_financier') {

            // Counts by type (These are generally totals regardless of time filter)
            $stats['countRecouvrement'] = Agent::where('mairie_ref', $mairieRef)->where('type', 'recouvrement')->count();
            $stats['countRecensement'] = Agent::where('mairie_ref', $mairieRef)->where('type', 'recensement')->count();

            // Caissiers can be in Mairie, Finance or Agent (if we add type caissier)
            $stats['countCaissier'] = Mairie::where('mairie_ref', $mairieRef)->where('role', 'caisié')->count() +
                                     Finance::where('mairie_ref', $mairieRef)->where('role', 'caissier')->count() +
                                     Agent::where('mairie_ref', $mairieRef)->where('type', 'caissier')->count();

            $stats['countAgentFinancier'] = Finance::where('mairie_ref', $mairieRef)->where('role', 'finance')->count();
            $stats['countAdminFinancier'] = Financier::where('mairie_ref', $mairieRef)->count();

            // Payment Progression (dynamic based on filter)
            $query = \App\Models\PaiementTaxe::where('mairie_ref', $mairieRef);

            if ($filter === 'jour') {
                $query->whereDate('created_at', today());
                $progressionData = $query->selectRaw('HOUR(created_at) as period, SUM(montant) as total')
                    ->groupBy('period')
                    ->orderBy('period', 'asc')
                    ->get();
                $stats['progressionLabels'] = $progressionData->pluck('period')->map(fn ($h) => $h . 'h')->toArray();
            } elseif ($filter === 'mois') {
                $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                $progressionData = $query->selectRaw('DAY(created_at) as period, SUM(montant) as total')
                    ->groupBy('period')
                    ->orderBy('period', 'asc')
                    ->get();
                $stats['progressionLabels'] = $progressionData->pluck('period')->toArray();
            } elseif ($filter === 'annee') {
                $query->whereYear('created_at', now()->year);
                $progressionData = $query->selectRaw('MONTH(created_at) as period, SUM(montant) as total')
                    ->groupBy('period')
                    ->orderBy('period', 'asc')
                    ->get();
                $stats['progressionLabels'] = $progressionData->pluck('period')->map(fn ($m) => Carbon::create()->month($m)->translatedFormat('M'))->toArray();
            } else {
                $progressionData = $query->where('created_at', '>=', Carbon::now()->subDays(7))
                    ->selectRaw('DATE(created_at) as date, SUM(montant) as total')
                    ->groupBy('date')
                    ->orderBy('date', 'asc')
                    ->get();
                $stats['progressionLabels'] = $progressionData->pluck('date')->map(fn ($d) => Carbon::parse($d)->format('d/m'))->toArray();
            }

            $stats['progressionValues'] = $progressionData->pluck('total')->toArray();
        }

        // Financial data (Apply filter)
        $queryPaiement = \App\Models\PaiementTaxe::where('mairie_ref', $mairieRef);
        $queryEncaissement = Encaissement::where('mairie_ref', $mairieRef);
        $queryVersement = \App\Models\Versement::where('mairie_ref', $mairieRef);

        if ($filter === 'jour') {
            $queryPaiement->whereDate('created_at', today());
            $queryEncaissement->whereDate('created_at', today());
            $queryVersement->whereDate('created_at', today());
        } elseif ($filter === 'mois') {
            $queryPaiement->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
            $queryEncaissement->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
            $queryVersement->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
        } elseif ($filter === 'annee') {
            $queryPaiement->whereYear('created_at', now()->year);
            $queryEncaissement->whereYear('created_at', now()->year);
            $queryVersement->whereYear('created_at', now()->year);
        }

        $stats['montantPaye'] = $queryPaiement->sum('montant');
        
        // Calcul de la dette totale (somme des restes des derniers versements de chaque agent)
        $stats['totalDette'] = \App\Models\Versement::where('mairie_ref', $mairieRef)
            ->whereIn('id', function($query) use ($mairieRef) {
                $query->selectRaw('MAX(id)')
                    ->from('versements')
                    ->where('mairie_ref', $mairieRef)
                    ->groupBy('agent_id');
            })->sum('reste');
            
        $totalEncaisse = $queryEncaissement->sum('montant_percu');
        $totalVerse = $queryVersement->sum('montant_verse');
        $stats['montantNonPaye'] = max(0, $totalEncaisse - $totalVerse);

        // Redirect to specific views or pass specific data
        $role = strtolower($user->role ?? ($user->type ?? ''));

        if ($role === 'caisié' || $role === 'caissier') {
            // Dashboard for Cashier (Personal activities)
            $stats['mesEncaissements'] = Encaissement::where('mairie_ref', $mairieRef)
                ->where('recorded_by', $user->id)
                ->when($filter === 'jour', fn($q) => $q->whereDate('created_at', today()))
                ->when($filter === 'mois', fn($q) => $q->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year))
                ->when($filter === 'annee', fn($q) => $q->whereYear('created_at', now()->year))
                ->latest()->take(10)->get();

            return view('mairie.dashboards.caissier', compact('stats'));
        }

        if (($user instanceof Finance && $role === 'finance') || $role === 'agent_finance') {
            $stats['monTotalVersements'] = \App\Models\Versement::where('mairie_ref', $mairieRef)
                ->where('recorded_by', $user->name)
                ->sum('montant_verse');

            $stats['countRecouvrement'] = Agent::where('mairie_ref', $mairieRef)->where('type', 'recouvrement')->count();
            $stats['countRecensement'] = Agent::where('mairie_ref', $mairieRef)->where('type', 'recensement')->count();
            $stats['countCaissier'] = Mairie::where('mairie_ref', $mairieRef)->where('role', 'caisié')->count() +
                                     Finance::where('mairie_ref', $mairieRef)->where('role', 'caissier')->count() +
                                     Agent::where('mairie_ref', $mairieRef)->where('type', 'caissier')->count();

            return view('mairie.dashboards.agent_finance', compact('stats'));
        }

        if ($user instanceof Financier || 
            $role === 'financiers' || 
            $role === 'responsable_financier' || 
            ($user instanceof Finance && $role === 'admin') ||
            ($user instanceof Mairie && $role === 'financiers')) {
            return view('mairie.dashboards.admin_finance', compact('stats'));
        }

        return view('mairie.dashboard', compact('stats'));
    }

    /**
     * Récupère les statuts des utilisateurs pour l'affichage en temps réel.
     */
    public function getUsersStatus(): JsonResponse
    {
        $currentMairie = Auth::guard('mairie')->user() ?: Auth::guard('finance')->user();

        if (! $currentMairie) {
            return response()->json(['error' => 'Non authentifié'], 401);
        }

        $onlineThreshold = Carbon::now()->subMinutes(5);
        $mairieRef = $currentMairie->mairie_ref;

        $agents = Agent::with(['logs' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])
            ->where('mairie_ref', $mairieRef)
            ->whereDate('last_activity', today())
            ->get();

        $mairies = Mairie::with(['logs' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])
            ->where('mairie_ref', $mairieRef)
            ->where('id', '!=', $currentMairie->id)
            ->whereDate('last_activity', today())
            ->get();

        $finances = Finance::with(['logs' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])
            ->where('mairie_ref', $mairieRef)
            ->whereDate('last_activity', today())
            ->get();

        $users = $mairies->merge($agents)->merge($finances);

        $data = $users->map(function ($user) use ($onlineThreshold) {
            $isOnline = $user->last_activity && $user->last_activity >= $onlineThreshold;

            $latestLogin = $user->logs->where('event', 'login')->first();
            $latestLogout = $user->logs->where('event', 'logout')->first();

            $role = 'Autre';
            if ($user instanceof Agent) {
                $role = $user->type;
            } elseif ($user instanceof Mairie) {
                $role = $user->role;
            } elseif ($user instanceof Finance) {
                $role = $user->role;
            }

            return [
                'name' => $user->name,
                'role' => ucwords($role),
                'status' => $isOnline ? 'En ligne' : 'Déconnecté',
                'login_time' => $latestLogin ? $latestLogin->created_at->format('d/m/Y H:i') : 'N/A',
                'logout_time' => ! $isOnline ? ($latestLogout ? $latestLogout->created_at->format('d/m/Y H:i') : ($user->last_activity ? $user->last_activity->format('d/m/Y H:i') : 'N/A')) : '-',
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
