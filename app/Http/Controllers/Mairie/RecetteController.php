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
// use Maatwebsite\Excel\Facades\Excel;
// use App\Exports\RecettesExport;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RecetteController extends Controller
{
    /**
     * Affiche la page du journal des recettes et gère les recherches AJAX.
     */
    public function index(Request $request)
    {
        $mairieConnectee = Auth::guard('mairie')->user();
        $mairieIdsDeLaZone = Mairie::where('region', $mairieConnectee->region)
                                  ->where('commune', $mairieConnectee->commune)
                                  ->pluck('id');

        $taxes = Taxe::whereIn('mairie_id', $mairieIdsDeLaZone)->get(['id', 'nom']);
        $secteurs = Secteur::whereIn('mairie_id', $mairieIdsDeLaZone)->get(['id', 'nom']);
        
        // Si la requête est une requête AJAX (recherche)
        if ($request->ajax()) {
            // On ne récupère les données que si au moins un filtre est présent
            if ($request->has('taxe_id') || $request->has('secteur_id')) {
                $data = $this->getRecetteData($request, $mairieIdsDeLaZone);
                return response()->json([
                    'html' => view('mairie.comptabilite.recette.partials.resultats_recette', $data)->render(),
                ]);
            }
            // Si aucun filtre n'est appliqué en AJAX, on ne renvoie rien.
            return response()->json(['html' => '']);
        }
        
        // Pour le chargement initial de la page, on ne passe que les filtres
        return view('mairie.comptabilite.recette.index', compact('taxes', 'secteurs'));
    }
    
    /**
     * NOUVELLE MÉTHODE PRIVÉE: Récupère et prépare les données de recette.
     * Centralise la logique pour la vue, le PDF et l'Excel.
     */
    private function getRecetteData(Request $request, $mairieIdsDeLaZone)
    {
        $baseQuery = $this->buildRecetteQuery($request, $mairieIdsDeLaZone);

        $totalTaxesCollectees = (clone $baseQuery)->sum('montant');
        
        // On récupère tous les résultats pour les exports et DataTables, pas de pagination ici.
        $paiements = (clone $baseQuery)->get();

        $agentsData = collect();
        if ($paiements->isNotEmpty()) {
            $numCommerces = $paiements->pluck('commercant.num_commerce')->unique()->filter();
            $taxeIdsFiltres = $paiements->pluck('taxe_id')->unique()->filter();

            $agentsData = $this->getAgentData($mairieIdsDeLaZone, $numCommerces, $taxeIdsFiltres);
            $this->enrichPaiementsData($paiements, $mairieIdsDeLaZone, $numCommerces, $taxeIdsFiltres);
        }

        return [
            'paiements' => $paiements,
            'agentsData' => $agentsData,
            'totalTaxesCollectees' => $totalTaxesCollectees,
        ];
    }

    /**
     * Construit la requête de base pour les recettes.
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
     * Enrichit les paiements avec les données d'encaissement.
     */
    private function enrichPaiementsData(&$paiements, $mairieIds, $numCommerces, $taxeIds) {
        if ($numCommerces->isEmpty() || $taxeIds->isEmpty()) return;

        $encaissements = Encaissement::whereIn('mairie_id', $mairieIds)
            ->whereIn('num_commerce', $numCommerces)
            ->whereIn('taxe_id', $taxeIds)
            ->with('agent:id,name')
            ->get()->keyBy(fn($item) => $item->num_commerce . '-' . $item->taxe_id);

        foreach ($paiements as $paiement) {
            $key = optional($paiement->commercant)->num_commerce . '-' . $paiement->taxe_id;
            $encaissement = $encaissements->get($key);

            if ($encaissement) {
                $paiement->statut_encaissement = 'Encaissé';
                $paiement->agent_encaisseur = optional($encaissement->agent)->name ?? 'N/A';
            } else {
                $paiement->statut_encaissement = 'Payé (Autre)';
                $paiement->agent_encaisseur = 'N/A';
            }
        }
    }

    /**
     * Récupère les données synthétisées par agent.
     */
    private function getAgentData($mairieIds, $numCommerces, $taxeIds) {
        if ($numCommerces->isEmpty() || $taxeIds->isEmpty()) {
            return collect();
        }
        return Agent::whereIn('mairie_id', $mairieIds)
            ->whereHas('encaissements', fn($q) => $q->whereIn('num_commerce', $numCommerces)->whereIn('taxe_id', $taxeIds))
            ->with(['encaissements' => fn($q) => $q->whereIn('num_commerce', $numCommerces)->whereIn('taxe_id', $taxeIds), 'versement'])
            ->get()->map(function ($agent) {
                $totalEncaisse = $agent->encaissements->sum('montant_percu');
                $totalVerse = $agent->versement->sum('montant_verse');
                return (object) ['nom' => $agent->name, 'total_encaisse' => $totalEncaisse, 'doit_verser' => max(0, $totalEncaisse - $totalVerse)];
            })->filter(fn($agent) => $agent->total_encaisse > 0);
    }
    
    /**
     * Marque les paiements sélectionnés comme "recette effectuée".
     */
    public function store(Request $request)
    {
        $request->validate([
            'paiement_ids' => 'required|array|min:1',
            'paiement_ids.*' => 'exists:paiement_taxes,id',
        ]);

        PaiementTaxe::where('mairie_id', Auth::guard('mairie')->id()) 
            ->whereIn('id', $request->paiement_ids)
            ->update(['recette_effectuee' => true]);

        return redirect()->route('mairie.recette.index', $request->only('taxe_id', 'secteur_id'))
                         ->with('success', 'La recette a été marquée comme effectuée avec succès.');
    }

    /**
     * Export des résultats en PDF.
     */
public function exportPdf(Request $request)
{
    $mairieConnectee = Auth::guard('mairie')->user();
    $mairieIdsDeLaZone = Mairie::where('region', $mairieConnectee->region)
                              ->where('commune', $mairieConnectee->commune)
                              ->pluck('id');
    
    $data = $this->getRecetteData($request, $mairieIdsDeLaZone);

    $pdf = Pdf::loadView('mairie.comptabilite.exports.recette_pdf', $data);
    return $pdf->download('journal-recettes-' . now()->format('Y-m-d') . '.pdf');
}



    public function exportExcel(Request $request)
    {
        $mairieConnectee = Auth::guard('mairie')->user();
        $mairieIdsDeLaZone = Mairie::where('region', $mairieConnectee->region)
                                  ->where('commune', $mairieConnectee->commune)
                                  ->pluck('id');

        // 1. On récupère les données exactement comme avant
        $data = $this->getRecetteData($request, $mairieIdsDeLaZone);
        $paiements = $data['paiements'];
        
        $filename = 'journal-recettes-' . now()->format('Y-m-d') . '.csv';

        // 2. On prépare les en-têtes de la réponse HTTP pour le téléchargement
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        // 3. On utilise une StreamedResponse pour envoyer le contenu ligne par ligne
        return new StreamedResponse(function () use ($paiements) {
            // Ouvre le flux de sortie PHP
            $handle = fopen('php://output', 'w');

            // Ajoute l'en-tête du fichier CSV (les titres des colonnes)
            fputcsv($handle, [
                'Date du Paiement',
                'Commerçant',
                'Numéro de Commerce',
                'Taxe Concernée',
                'Période',
                'Montant (FCFA)',
                'Statut Encaissement',
                'Agent Encaisseur',
            ]);

            // Parcourt chaque paiement et l'ajoute comme une ligne dans le fichier
            foreach ($paiements as $paiement) {
                fputcsv($handle, [
                    $paiement->created_at->format('d/m/Y H:i'),
                    optional($paiement->commercant)->nom,
                    optional($paiement->commercant)->num_commerce,
                    $paiement->taxe->nom,
                    \Carbon\Carbon::parse($paiement->periode)->isoFormat('MMMM YYYY'),
                    $paiement->montant,
                    $paiement->statut_encaissement,
                    $paiement->agent_encaisseur,
                ]);
            }

            // Ferme le flux
            fclose($handle);
        }, 200, $headers);
    }


}