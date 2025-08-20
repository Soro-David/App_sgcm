<?php

namespace App\Services;

use App\Models\Agent;
use App\Models\Encaissement;
use App\Models\Mairie;
use App\Models\PaiementTaxe;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class RecetteService
{
    private array $mairieIds;

    public function __construct()
    {
        $mairieConnectee = Auth::guard('mairie')->user();
        // Gérer le cas où il n'y a pas d'utilisateur connecté si nécessaire
        if ($mairieConnectee) {
            $this->mairieIds = Mairie::where('region', $mairieConnectee->region)
                                     ->where('commune', $mairieConnectee->commune)
                                     ->pluck('id')
                                     ->all();
        } else {
            $this->mairieIds = [];
        }
    }

    /**
     * Récupère les données complètes pour le journal des recettes basé sur les filtres.
     *
     * @param Request $request
     * @return array ['paiements', 'agentsData', 'totalTaxesCollectees']
     */
    public function getJournalData(Request $request, bool $paginate = true)
    {
        $query = $this->buildRecetteQuery($request);
        
        $paiementsQuery = clone $query;

        if ($paginate) {
            $paiements = $paiementsQuery->paginate(15)->withQueryString();
        } else {
            $paiements = $paiementsQuery->get();
        }
        
        $totalTaxesCollectees = (clone $query)->sum('montant');

        if ($paiements->isEmpty()) {
            return [
                'paiements' => $paiements,
                'agentsData' => collect(),
                'totalTaxesCollectees' => 0,
            ];
        }

        $numCommerces = $paiements->pluck('commercant.num_commerce')->unique()->filter()->all();
        $taxeIdsFiltres = $paiements->pluck('taxe_id')->unique()->filter()->all();

        $this->enrichPaiementsData($paiements, $numCommerces, $taxeIdsFiltres);
        $agentsData = $this->getAgentData($numCommerces, $taxeIdsFiltres);
        
        return compact('paiements', 'agentsData', 'totalTaxesCollectees');
    }

    /**
     * Construit la requête de base pour les paiements.
     */
    public function buildRecetteQuery(Request $request)
    {
        $query = PaiementTaxe::query()
            ->whereIn('mairie_id', $this->mairieIds)
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
     * Ajoute les informations d'encaissement (statut, agent) aux paiements.
     */
    private function enrichPaiementsData(Collection $paiements, array $numCommerces, array $taxeIds): void
    {
        $encaissements = Encaissement::whereIn('mairie_id', $this->mairieIds)
            ->whereIn('num_commerce', $numCommerces)
            ->whereIn('taxe_id', $taxeIds)
            ->with('agent:id,name')
            ->get()
            ->keyBy(fn($item) => $item->num_commerce . '-' . $item->taxe_id);

        foreach ($paiements as $paiement) {
            $key = optional($paiement->commercant)->num_commerce . '-' . $paiement->taxe_id;
            $encaissement = $encaissements->get($key);
            
            if ($encaissement) {
                $paiement->statut_encaissement = 'Encaissé';
                $paiement->agent_encaisseur = optional($encaissement->agent)->name ?? 'N/A';
            } else {
                $paiement->statut_encaissement = 'Payé (Autre méthode)';
                $paiement->agent_encaisseur = 'N/A';
            }
        }
    }

    /**
     * Calcule le résumé des encaissements par agent.
     */
    private function getAgentData(array $numCommerces, array $taxeIds): Collection
    {
        if (empty($numCommerces) || empty($taxeIds)) {
            return collect();
        }

        return Agent::whereIn('mairie_id', $this->mairieIds)
            ->whereHas('encaissements', fn($q) => $q->whereIn('num_commerce', $numCommerces)->whereIn('taxe_id', $taxeIds))
            ->with(['encaissements' => fn($q) => $q->whereIn('num_commerce', $numCommerces)->whereIn('taxe_id', $taxeIds), 'versement'])
            ->get()
            ->map(function ($agent) {
                $totalEncaisse = $agent->encaissements->sum('montant_percu');
                $totalVerse = $agent->versement->sum('montant_verse');
                return (object) [
                    'nom' => $agent->name, 
                    'total_encaisse' => $totalEncaisse, 
                    'doit_verser' => max(0, $totalEncaisse - $totalVerse)
                ];
            })
            ->filter(fn($agent) => $agent->total_encaisse > 0);
    }
}