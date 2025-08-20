<?php

namespace App\Http\Controllers\Mairie;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Encaissement;
use App\Models\Mairie;
use App\Models\PaiementTaxe;
use App\Models\Secteur;
use App\Models\Taxe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RecettesExport;

class ComptabiliteController extends Controller
{
    /**
     * Affiche le journal des recettes avec pagination et filtres.
     */
    public function journal_recette(Request $request)
    {
        $mairieConnectee = Auth::guard('mairie')->user();
        if (!$mairieConnectee) {
            return redirect()->route('login.mairie')->with('error', 'Session invalide. Veuillez vous reconnecter.');
        }
        $mairieIdsDeLaZone = Mairie::where('region', $mairieConnectee->region)
                                  ->where('commune', $mairieConnectee->commune)
                                  ->pluck('id');

        $paiements = collect();
        $agentsData = collect();
        $totalTaxesCollectees = 0;

        if ($request->has('taxe_id') || $request->has('secteur_id')) {
            $baseQuery = $this->buildRecetteQuery($request, $mairieIdsDeLaZone);

            $totalTaxesCollectees = (clone $baseQuery)->sum('montant');

            $paiements = (clone $baseQuery)->paginate(15)->withQueryString();

            if ($paiements->isNotEmpty()) {
                
                $numCommerces = $paiements->pluck('commercant.num_commerce')->unique()->filter();
                $taxeIdsFiltres = $paiements->pluck('taxe_id')->unique()->filter();

                $agentsData = $this->getAgentData($mairieIdsDeLaZone, $numCommerces, $taxeIdsFiltres);
                $this->enrichPaiementsData($paiements, $mairieIdsDeLaZone, $numCommerces, $taxeIdsFiltres);
            }
        }

        $taxes = Taxe::whereIn('mairie_id', $mairieIdsDeLaZone)->get(['id', 'nom']);
        $secteurs = Secteur::whereIn('mairie_id', $mairieIdsDeLaZone)->get(['id', 'nom']);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('mairie.comptabilite.partials.resultats_recette', compact('paiements', 'agentsData', 'totalTaxesCollectees'))->render(),
            ]);
        }
        
        return view('mairie.comptabilite.journal_recette', compact('taxes', 'secteurs', 'paiements', 'agentsData', 'totalTaxesCollectees'));
    }

    /**
     * NOUVELLE MÉTHODE PRIVÉE: Construit la requête de base pour éviter la duplication.
     */
    private function buildRecetteQuery(Request $request, $mairieIdsDeLaZone)
    {
        $query = PaiementTaxe::query()
            ->whereIn('mairie_id', $mairieIdsDeLaZone)
            ->where('recette_effectuee', false)
            ->with(['commercant:id,nom,num_commerce', 'taxe:id,nom'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('taxe_id')) {
            $query->where('taxe_id', $request->input('taxe_id'));
        }
        if ($request->filled('secteur_id')) {
            $query->where('secteur_id', $request->input('secteur_id'));
        }
        return $query;
    }

    /**
     * NOUVELLES MÉTHODES PRIVÉES: Pour clarifier la logique de récupération de données.
     */
    private function enrichPaiementsData(&$paiements, $mairieIds, $numCommerces, $taxeIds) {
        $encaissements = Encaissement::whereIn('mairie_id', $mairieIds)->whereIn('num_commerce', $numCommerces)->whereIn('taxe_id', $taxeIds)->with('agent:id,name')->get()->keyBy(fn($item) => $item->num_commerce . '-' . $item->taxe_id);
        foreach ($paiements as $paiement) {
            $key = optional($paiement->commercant)->num_commerce . '-' . $paiement->taxe_id;
            $encaissement = $encaissements[$key] ?? null;
            if ($encaissement) {
                $paiement->statut_encaissement = 'Encaissé';
                $paiement->agent_encaisseur = optional($encaissement->agent)->name ?? 'N/A';
            } else {
                $paiement->statut_encaissement = 'Payé (Autre méthode)';
                $paiement->agent_encaisseur = 'N/A';
            }
        }
    }

    private function getAgentData($mairieIds, $numCommerces, $taxeIds) {
        if (empty($numCommerces) || empty($taxeIds)) {
            return collect();
        }
        return Agent::whereIn('mairie_id', $mairieIds)->whereHas('encaissements', fn($q) => $q->whereIn('num_commerce', $numCommerces)->whereIn('taxe_id', $taxeIds))->with(['encaissements' => fn($q) => $q->whereIn('num_commerce', $numCommerces)->whereIn('taxe_id', $taxeIds), 'versement'])->get()->map(function ($agent) {
            $totalEncaisse = $agent->encaissements->sum('montant_percu');
            $totalVerse = $agent->versement->sum('montant_verse');
            return (object) ['nom' => $agent->name, 'total_encaisse' => $totalEncaisse, 'doit_verser' => max(0, $totalEncaisse - $totalVerse)];
        })->filter(fn($agent) => $agent->total_encaisse > 0);
    }
    
    public function recette_effectuee(Request $request)
    {
        $request->validate([
            'paiement_ids' => 'required|array|min:1',
            'paiement_ids.*' => 'exists:paiement_taxes,id',
        ]);

        $mairieId = Auth::guard('mairie')->id();

        PaiementTaxe::where('mairie_id', $mairieId) 
            ->whereIn('id', $request->paiement_ids)
            ->update(['recette_effectuee' => true]);

        return redirect()->route('mairie.comptabilite.journal_recette', $request->only('taxe_id', 'secteur_id'))
                         ->with('success', 'La recette a été marquée comme effectuée avec succès.');
    }

        /**
     * Export des résultats en PDF.
     */
    public function exportPdf(Request $request)
    {
        $data = $this->recetteService->getJournalData($request, false);

        $pdf = Pdf::loadView('mairie.comptabilite.exports.recette_pdf', $data);
        return $pdf->download('journal-recettes-' . now()->format('Y-m-d') . '.pdf');
    }


    public function exportExcel(Request $request)
    {
        return Excel::download(new RecettesExport($request), 'journal-recettes-' . now()->format('Y-m-d') . '.xlsx');
    }
    

}