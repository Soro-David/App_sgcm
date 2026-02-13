<?php

namespace App\Http\Controllers\Mairie;

use App\Exports\RecettesExport;
use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Encaissement;
use App\Models\Mairie;
use App\Models\PaiementTaxe;
use App\Models\Secteur;
use App\Models\Taxe;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class RecetteController extends Controller
{
    /**
     * Affiche la page du journal des recettes et gère les recherches AJAX.
     */
    public function index(Request $request)
    {
        $mairieConnectee = Auth::guard('mairie')->user() ?: Auth::guard('finance')->user();
        if (! $mairieConnectee) {
            return redirect()->route('login.mairie');
        }
        $mairieRefDeLaZone = Mairie::where('region', $mairieConnectee->region)
            ->where('commune', $mairieConnectee->commune)
            ->pluck('mairie_ref');

        $taxes = Taxe::whereIn('mairie_ref', $mairieRefDeLaZone)->get(['id', 'nom']);
        $secteurs = Secteur::whereIn('mairie_ref', $mairieRefDeLaZone)->get(['id', 'nom']);

        // Si la requête est une requête AJAX (recherche)
        if ($request->ajax()) {
            // On ne récupère les données que si au moins un filtre est présent
            if ($request->has('taxe_id') || $request->has('secteur_id')) {
                $data = $this->getRecetteData($request, $mairieRefDeLaZone);

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
    private function getRecetteData(Request $request, $mairieRefDeLaZone)
    {
        $baseQuery = $this->buildRecetteQuery($request, $mairieRefDeLaZone);

        $totalTaxesCollectees = (clone $baseQuery)->sum('montant');

        // On récupère tous les résultats pour les exports et DataTables, pas de pagination ici.
        $paiements = (clone $baseQuery)->get();

        $agentsData = collect();
        if ($paiements->isNotEmpty()) {
            $numCommerces = $paiements->pluck('commercant.num_commerce')->unique()->filter();
            $taxeIdsFiltres = $paiements->pluck('taxe_id')->unique()->filter();

            $agentsData = $this->getAgentData($mairieRefDeLaZone, $numCommerces, $taxeIdsFiltres);
            $this->enrichPaiementsData($paiements, $mairieRefDeLaZone, $numCommerces, $taxeIdsFiltres);
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
    private function buildRecetteQuery(Request $request, $mairieRefDeLaZone)
    {
        $query = PaiementTaxe::query()
            ->whereIn('mairie_ref', $mairieRefDeLaZone)
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
    private function enrichPaiementsData(&$paiements, $mairieRefIds, $numCommerces, $taxeIds)
    {
        if ($numCommerces->isEmpty() || $taxeIds->isEmpty()) {
            return;
        }

        $encaissements = Encaissement::whereIn('mairie_ref', $mairieRefIds)
            ->whereIn('num_commerce', $numCommerces)
            ->whereIn('taxe_id', $taxeIds)
            ->with(['agent:id,name', 'recorder:id,name'])
            ->get()->keyBy(fn ($item) => $item->num_commerce.'-'.$item->taxe_id);

        foreach ($paiements as $paiement) {
            $key = optional($paiement->commercant)->num_commerce.'-'.$paiement->taxe_id;
            $encaissement = $encaissements->get($key);

            if ($encaissement) {
                $paiement->statut_encaissement = 'Encaissé';
                if ($encaissement->agent) {
                    $paiement->agent_encaisseur = $encaissement->agent->name;
                    // dd($paiement->agent_encaisseur);
                } elseif ($encaissement->recorder) {
                    $paiement->agent_encaisseur = $encaissement->recorder->name.' (Caisse)';
                } else {
                    $paiement->agent_encaisseur = 'N/A';
                }
            } else {
                $paiement->statut_encaissement = 'Payé (Autre)';
                $paiement->agent_encaisseur = 'N/A';
            }
        }
    }

    /**
     * Récupère les données synthétisées par agent.
     */
    private function getAgentData($mairieRefIds, $numCommerces, $taxeIds)
    {
        if ($numCommerces->isEmpty() || $taxeIds->isEmpty()) {
            return collect();
        }

        return Agent::whereIn('mairie_ref', $mairieRefIds)
            ->whereHas('encaissements', fn ($q) => $q->whereIn('num_commerce', $numCommerces)->whereIn('taxe_id', $taxeIds))
            ->with(['encaissements' => fn ($q) => $q->whereIn('num_commerce', $numCommerces)->whereIn('taxe_id', $taxeIds), 'versements'])
            ->get()->map(function ($agent) {
                $totalEncaisse = $agent->encaissements->sum('montant_percu');
                $totalVerse = $agent->versements->sum('montant_verse');

                return (object) ['nom' => $agent->name, 'total_encaisse' => $totalEncaisse, 'doit_verser' => max(0, $totalEncaisse - $totalVerse)];
            })->filter(fn ($agent) => $agent->total_encaisse > 0);
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

        $user = Auth::guard('mairie')->user() ?: Auth::guard('finance')->user();
        PaiementTaxe::where('mairie_ref', $user ? $user->mairie_ref : null)
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
        $mairieConnectee = Auth::guard('mairie')->user() ?: Auth::guard('finance')->user();
        if (! $mairieConnectee) {
            return response()->json(['error' => 'Non authentifié'], 401);
        }
        $mairieRefDeLaZone = Mairie::where('region', $mairieConnectee->region)
            ->where('commune', $mairieConnectee->commune)
            ->pluck('mairie_ref');

        $data = $this->getRecetteData($request, $mairieRefDeLaZone);

        $pdf = Pdf::loadView('mairie.pdfExport.journal_recettes_pdf', $data);

        return $pdf->download('journal-recettes-'.now()->format('Y-m-d').'.pdf');
    }

    public function exportExcel(Request $request)
    {
        $mairieConnectee = Auth::guard('mairie')->user() ?: Auth::guard('finance')->user();
        if (! $mairieConnectee) {
            return response()->json(['error' => 'Non authentifié'], 401);
        }
        $mairieRefDeLaZone = Mairie::where('region', $mairieConnectee->region)
            ->where('commune', $mairieConnectee->commune)
            ->pluck('mairie_ref');

        // 1. On récupère les données
        $data = $this->getRecetteData($request, $mairieRefDeLaZone);
        $paiements = $data['paiements'];

        $filename = 'journal-recettes-'.now()->format('Y-m-d').'.xlsx';

        return Excel::download(new RecettesExport($paiements), $filename);
    }
}
