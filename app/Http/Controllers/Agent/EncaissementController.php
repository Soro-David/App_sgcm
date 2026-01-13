<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Commercant;
use App\Models\Encaissement;
use App\Models\PaiementTaxe;
use App\Models\Taxe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class EncaissementController extends Controller
{
   
    public function index()
    {
        return view('agent.encaissement.index');
    }

    /**
     * Fournit les données formatées pour la requête AJAX de DataTables.
     */
    public function get_list_commercant()
    {
        $agent = Auth::guard('agent')->user();
        $secteurIds = $agent->secteur_id ?? [];

        $commercants = Commercant::whereIn('secteur_id', $secteurIds)
            ->select('id', 'num_commerce', 'nom', 'telephone');

        return datatables()->of($commercants)
            ->addColumn('dernier_paiement', function ($commercant) {
                $dernierPaiement = PaiementTaxe::where('num_commerce', $commercant->num_commerce)
                    ->orderBy('periode', 'desc')
                    ->first();
                return $dernierPaiement ? Carbon::parse($dernierPaiement->periode)->isoFormat('MMMM YYYY') : 'Aucun';
            })
            ->addColumn('statut_paiement', function ($commercant) {
                $payeCeMois = PaiementTaxe::where('num_commerce', $commercant->num_commerce)
                    ->whereYear('periode', Carbon::now()->year)
                    ->whereMonth('periode', Carbon::now()->month)
                    ->exists();
                return $payeCeMois
                    ? '<span class="badge bg-success">À jour</span>'
                    : '<span class="badge bg-danger">En attente</span>';
            })
            ->addColumn('action', function($row) {
                return '<a href="' . route('agent.encaissement.show', $row->id) . '" class="btn btn-sm btn-info me-1">Détails</a>' .
                       '<a href="' . route('agent.encaissement.edit', $row->id) . '" class="btn btn-sm btn-primary">Encaisser</a>';
            })
            ->rawColumns(['statut_paiement', 'action'])
            ->toJson();
    }
    
    /**
     * Affiche les détails d'un contribuable et son historique de paiements.
     */
    public function show(string $id)
    {
        $commercant = Commercant::with('secteur')->findOrFail($id);
        $this->authorizeAgentAccess($commercant);

        $paiements = PaiementTaxe::where('num_commerce', $commercant->num_commerce)
            ->with('taxe')
            ->orderBy('periode', 'desc')
            ->get();

        return view('agent.encaissement.show', compact('commercant', 'paiements'));
    }

    public function edit(string $id)
    {
        $commercant = Commercant::with('secteur')->findOrFail($id);
        $this->authorizeAgentAccess($commercant);
        
        $agent = Auth::guard('agent')->user();
        // Assurez-vous que les taxes de l'agent sont bien chargées
        $taxesAgent = Taxe::whereIn('id', $agent->taxe_id ?? [])->get();

        return view('agent.encaissement.edit', compact('commercant', 'taxesAgent'));
    }

    /**
     * Enregistre le paiement pour une ou plusieurs périodes.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'taxe_id' => 'required|integer|exists:taxes,id',
            'nombre_periodes' => 'required|integer|min:1',
            'num_commerce' => 'required|string',
        ]);

        $commercant = Commercant::findOrFail($id);
        $agent = Auth::guard('agent')->user();
        $this->authorizeAgentAccess($commercant);

        if ($commercant->num_commerce !== $validatedData['num_commerce']) {
            return response()->json(['success' => false, 'message' => 'Erreur: Le numéro de commerce ne correspond pas.'], 400);
        }

        $taxe = Taxe::find($validatedData['taxe_id']);
        $nombrePeriodesAPayer = $validatedData['nombre_periodes'];

        DB::beginTransaction();
        try {
            // Récupère la liste des périodes à payer
            $periodesAPayer = $this->getUnpaidPeriodsAsDates($taxe, $commercant, $nombrePeriodesAPayer);

            if (count($periodesAPayer) < $nombrePeriodesAPayer) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Le nombre de périodes à payer dépasse le nombre de périodes réellement impayées.'], 400);
            }

            $montantTotalEncaisse = 0;

            // Crée un enregistrement de paiement pour chaque période
            foreach ($periodesAPayer as $periode) {
                PaiementTaxe::create([
                    'secteur_id' => $commercant->secteur_id,
                    'taxe_id' => $taxe->id,
                    'mairie_ref' => $agent->mairie_ref,
                    'num_commerce' => $commercant->num_commerce,
                    'montant' => $taxe->montant, // Montant par période
                    'statut' => 'payé',
                    'periode' => $periode->toDateString(),
                ]);
                $montantTotalEncaisse += $taxe->montant;
            }

            // Crée un seul enregistrement d'encaissement pour le total perçu par l'agent
            if ($montantTotalEncaisse > 0) {
                Encaissement::create([
                    'montant_percu' => $montantTotalEncaisse,
                    'agent_id' => $agent->id,
                    'mairie_ref' => $agent->mairie_ref,
                    'taxe_id' => $taxe->id,
                    'num_commerce' => $commercant->num_commerce,
                    'statut' => 'encaisse'
                ]);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Paiement de ' . count($periodesAPayer) . ' période(s) enregistré avec succès.']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur d'encaissement: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Une erreur interne est survenue.'], 500);
        }
    }
    
    /**
     * Fournit les détails d'une taxe pour un commerçant (via AJAX).
     */
    public function getTaxeDetails(Request $request, $commercantId, $taxeId): JsonResponse
    {
        $commercant = Commercant::findOrFail($commercantId);
        $this->authorizeAgentAccess($commercant);
        $taxe = Taxe::findOrFail($taxeId);

        $unpaidPeriods = $this->getUnpaidPeriodsAsDates($taxe, $commercant, null);

        return response()->json([
            'success' => true,
            'montant' => $taxe->montant,
            'frequence' => $taxe->frequence,
            'unpaid_count' => count($unpaidPeriods),
        ]);
    }

    /**
     * Calcule et retourne les périodes impayées pour une taxe donnée.
     * NOTE: Idéalement, cette méthode devrait être dans un Trait ou une classe Service
     * pour éviter la duplication de code entre PayementController et EncaissementController.
     */
    private function getUnpaidPeriodsAsDates(Taxe $taxe, Commercant $commercant, ?int $limit): array
    {
        $dernierPaiement = PaiementTaxe::where('taxe_id', $taxe->id)
            ->where('num_commerce', $commercant->num_commerce)
            ->orderBy('periode', 'desc')
            ->first();

        $periodeCourante;

        if ($dernierPaiement) {
            $dateDernierPaiement = Carbon::parse($dernierPaiement->periode);
            $periodeCourante = $dateDernierPaiement->copy();
            
            match ($taxe->frequence) {
                'mensuel'   => $periodeCourante->addMonth(),
                'annuel'    => $periodeCourante->addYear(),
                'journalier'=> $periodeCourante->addDay(),
                default     => $periodeCourante->addMonth(),
            };
        } else {
            $periodeCourante = Carbon::parse($commercant->created_at);
        }
        
        $periodeCourante->startOfDay();
        $periodes = [];
        $now = Carbon::now()->startOfDay();

        if ($limit !== null && $limit > 0) {
            for ($i = 0; $i < $limit; $i++) {
                if ($periodeCourante > $now && $taxe->frequence !== 'annuel') {
                    break;
                }
                $periodes[] = $periodeCourante->copy();
                match ($taxe->frequence) {
                    'mensuel'   => $periodeCourante->addMonth(),
                    'annuel'    => $periodeCourante->addYear(),
                    'journalier'=> $periodeCourante->addDay(),
                    default     => $periodeCourante->addMonth(),
                };
            }
            return $periodes;
        }

        while ($periodeCourante <= $now) {
            $periodes[] = $periodeCourante->copy();
            match ($taxe->frequence) {
                'mensuel'   => $periodeCourante->addMonth(),
                'annuel'    => $periodeCourante->addYear(),
                'journalier'=> $periodeCourante->addDay(),
                default     => $periodeCourante->addMonth(),
            };
        }

        return $periodes;
    }

    private function authorizeAgentAccess(Commercant $commercant)
    {
        $agent = Auth::guard('agent')->user();
        if (!in_array($commercant->secteur_id, $agent->secteur_id ?? [])) {
            abort(403, "ACCÈS INTERDIT. Ce contribuable n'est pas dans votre secteur.");
        }
    }
}