<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Mairie;
use App\Models\Commercant;
use App\Models\Agent;
use App\Models\Encaissement;
use App\Models\PaiementTaxe;
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
        // On récupère uniquement les comptes Mairie qui ont le rôle 'financié'
        $mairiesData = Mairie::where('role', 'financié')
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
}
