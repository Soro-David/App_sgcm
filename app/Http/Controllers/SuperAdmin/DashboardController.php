<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Mairie;
use App\Models\Commercant;
use App\Models\Agent;
use App\Models\Encaissement;
use App\Models\PaiementTaxe;
use App\Models\Financier;
use App\Models\Finance;
use App\Models\UserLog;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_mairies' => Mairie::count(),
            'total_commercants' => Commercant::count(),
            'total_agents' => Agent::count(),
        ];

        // Mairies par région pour un graphique
        $mairiesByRegion = Mairie::select('region', DB::raw('count(*) as total'))
            ->groupBy('region')
            ->get();
        
        // Dernières mairies inscrites
        $recentMairies = Mairie::latest()->take(5)->get();

        return view('superAdmin.dashboard', compact('stats', 'mairiesByRegion', 'recentMairies'));
    }

    public function bilan()
    {
        // On récupère uniquement les comptes Mairie qui ont le rôle 'financiers'
        $mairiesData = Mairie::where('role', 'financiers')
            ->withCount(['agents', 'taxes'])
            ->get();

        foreach ($mairiesData as $mairie) {
            // Nombre de contribuables (Commercant) liés à cette mairie
            $mairie->contribuables_count = Commercant::where('mairie_ref', $mairie->mairie_ref)->count();
            
            // Total des encaissements pour cette mairie
            $mairie->total_recettes = Encaissement::where('mairie_ref', $mairie->mairie_ref)->sum('montant_percu');

            // L'admin financier est le nom de ce compte
            $mairie->admin_financier_name = $mairie->name;

            // On récupère le nom de l'entité Mairie (le compte admin) pour l'affichage
            $adminMairie = Mairie::where('mairie_ref', $mairie->mairie_ref)->where('role', 'admin')->first();
            $mairie->municipality_name = $adminMairie ? $adminMairie->name : $mairie->name;
        }

        return view('superAdmin.bilan', compact('mairiesData'));
    }

    public function recapitulatif()
    {
        // On récupère toutes les mairies admins pour la liste principale
        $mairies = Mairie::where('role', 'admin')->get();

        return view('superAdmin.recapitulatif', compact('mairies'));
    }

    public function recapitulatifDetails($id)
    {
        $mairie = Mairie::where('role', 'admin')->findOrFail($id);

        // Liste des contribuables par mairie avec l'agent qui les a ajoutés
        $mairie->contribuables = Commercant::where('mairie_ref', $mairie->mairie_ref)
            ->with('agent')
            ->get();

        // Personnel de la mairie (Comptes mairie qui ne sont pas l'admin principal)
        $mairie->personnel = Agent::where('mairie_ref', $mairie->mairie_ref)
            ->get();

        // Agents financiers (de la table financiers et finances)
        $mairie->agents_financiers = Financier::where('mairie_ref', $mairie->mairie_ref)->get();
        $mairie->ag_finances = Finance::where('mairie_ref', $mairie->mairie_ref)->get();

        $mairie_ref = $mairie->mairie_ref;
        
        $mairie->logs = UserLog::where(function($query) use ($mairie_ref) {
            $query->whereHasMorph('user', [Agent::class], function($q) use ($mairie_ref) {
                $q->where('mairie_ref', $mairie_ref);
            })
            ->orWhereHasMorph('user', [Mairie::class], function($q) use ($mairie_ref) {
                $q->where('mairie_ref', $mairie_ref);
            })
            ->orWhereHasMorph('user', [Financier::class], function($q) use ($mairie_ref) {
                $q->where('mairie_ref', $mairie_ref);
            })
            ->orWhereHasMorph('user', [Finance::class], function($q) use ($mairie_ref) {
                $q->where('mairie_ref', $mairie_ref);
            });
        })
        ->with('user')
        ->latest()
        ->take(50)
        ->get();

        return view('superAdmin.recapitulatif_details', compact('mairie'));
    }
}
