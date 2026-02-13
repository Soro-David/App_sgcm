<?php

namespace App\Http\Controllers\Commercant;

use App\Http\Controllers\Controller;
use App\Models\Commercant;
use App\Models\PaiementTaxe;
use App\Models\Solde;
use App\Models\Taxe;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PayementController extends Controller
{
    public function index(Request $request)
    {
        $commercant = $request->user();
        $taxes = $commercant->taxes()->get();

        return view('commercant.payement.index', compact('taxes'));
    }

    public function create(Request $request)
    {
        $commercant = Auth::guard('commercant')->user();

        $commercant->load('mairie', 'secteur', 'taxes', 'typeContribuable');

        // $commercant = $request->user();
        $taxes = $commercant->taxes()->get();

        // dd($commercant);
        return view('commercant.payement.create', compact('taxes', 'commercant'));
    }

    public function historique(Request $request): JsonResponse
    {
        $commercant = $request->user();
        $historique = PaiementTaxe::where('num_commerce', $commercant->num_commerce)
            ->with('taxe:id,nom')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['data' => $historique]);
    }

    public function effectuer_paiement(Request $request): JsonResponse
    {
        $commercant = $request->user();

        $validator = Validator::make($request->all(), [
            'taxe_id' => 'required|integer|exists:taxes,id',
            'nombre_periodes' => 'required|integer|min:1|max:240',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Les données fournies sont invalides.', 'errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();
        $taxe = Taxe::find($validatedData['taxe_id']);

        if (! $commercant->taxes()->where('taxes.id', $validatedData['taxe_id'])->exists()) {
            return response()->json(['message' => 'Vous n\'êtes pas autorisé à payer cette taxe.'], 403);
        }

        // Calcul du montant total
        $montantTotal = $taxe->montant * $validatedData['nombre_periodes'];

        // Vérification du solde
        $solde = Solde::firstOrCreate(
            ['commercant_id' => $commercant->id],
            ['montant' => 0]
        );

        if ($solde->montant < $montantTotal) {
            return response()->json([
                'status' => 'error',
                'message' => 'Votre solde est insuffisant pour effectuer ce paiement.',
            ], 400);
        }

        $periodesAPayer = [];
        try {
            DB::transaction(function () use ($commercant, $taxe, $validatedData, $montantTotal, $solde, &$periodesAPayer) {

                $periodesAPayer = $this->getUnpaidPeriodsAsDates($taxe, $commercant, $validatedData['nombre_periodes']);

                // dd($periodesAPayer);
                // Déduction du solde
                $solde->montant -= $montantTotal;
                $solde->save();

                foreach ($periodesAPayer as $periode) {
                    PaiementTaxe::create([
                        'mairie_ref' => $commercant->mairie_ref,
                        'secteur_id' => $commercant->secteur_id,
                        'taxe_id' => $taxe->id,
                        'num_commerce' => $commercant->num_commerce,
                        'montant' => $taxe->montant,
                        'statut' => 'payé',
                        'periode' => \Carbon\Carbon::parse($periode)->toDateString(),
                    ]);
                }
            });
        } catch (\Exception $e) {
            return response()->json(['message' => 'Une erreur technique est survenue lors du paiement.'], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Paiement de '.count($periodesAPayer).' période(s) effectué avec succès !',
        ]);
    }

    public function periodes_impayees(Request $request, $taxeId): JsonResponse
    {
        $commercant = $request->user();
        $taxe = Taxe::findOrFail($taxeId);

        $limit = $request->has('nombre_periodes') ? intval($request->input('nombre_periodes')) : null;

        $dates = $this->getUnpaidPeriodsAsDates($taxe, $commercant, $limit);
        $nombreTotalPeriodes = count($dates);

        $frequenceMap = [
            'mensuel' => 'mois',
            'mois' => 'mois',
            'annuel' => 'an',
            'an' => 'an',
            'journalier' => 'jour',
            'jour' => 'jour',
        ];
        $unite = $frequenceMap[$taxe->frequence] ?? $taxe->frequence;
        if ($nombreTotalPeriodes > 1 && $unite !== 'an') {
            $unite .= 's';
        }

        $summaryText = $nombreTotalPeriodes > 0 ? "$nombreTotalPeriodes $unite à payer" : 'Aucune période à payer.';
        if ($limit === null && $nombreTotalPeriodes > 0) {
            $summaryText = "<b>$nombreTotalPeriodes $unite impayé(s)</b> ont été calculés :";
        }

        $periodesFormatees = array_map(function ($date) use ($taxe) {
            switch ($taxe->frequence) {
                case 'mensuel':
                case 'mois':
                    return $date->format('m/Y');
                case 'annuel':
                case 'an':
                    return $date->format('Y');
                case 'journalier':
                case 'jour':
                    return $date->format('d/m/Y');
                default:
                    return $date->format('Y-m-d');
            }
        }, $dates);

        // dd($periodesFormatees);

        return response()->json([
            'count' => $nombreTotalPeriodes,
            'summary_text' => $summaryText,
            'periods_list' => $periodesFormatees,
            'montant_par_periode' => $taxe->montant,
        ]);
    }

    private function getUnpaidPeriodsAsDates(Taxe $taxe, Commercant $commercant, ?int $limit): array
    {
        $dernierPaiement = PaiementTaxe::where('taxe_id', $taxe->id)
            ->where('num_commerce', $commercant->num_commerce)
            ->orderBy('periode', 'desc')
            ->first();

        if ($dernierPaiement) {
            $dateDernierPaiement = Carbon::parse($dernierPaiement->periode);
            $periodeCourante = $dateDernierPaiement->copy();

            // On utilise les clés de fréquence cohérentes (mensuel, annuel, journalier)
            match ($taxe->frequence) {
                'mensuel', 'mois' => $periodeCourante->addMonth(),
                'annuel', 'an' => $periodeCourante->addYear(),
                'journalier', 'jour' => $periodeCourante->addDay(),
                default => $periodeCourante->addMonth(),
            };
        } else {
            // Le paiement commence à la date de création du contribuable,
            // mais on s'assure qu'on ne remonte pas avant la date de création de la taxe elle-même.
            $dateDebut = $commercant->created_at->gt($taxe->created_at)
                ? $commercant->created_at
                : $taxe->created_at;

            $periodeCourante = Carbon::parse($dateDebut);
        }

        $periodeCourante->startOfDay();

        $periodes = [];
        $now = Carbon::now()->startOfDay();

        if ($limit !== null && $limit > 0) {
            for ($i = 0; $i < $limit; $i++) {
                $periodes[] = $periodeCourante->copy();
                match ($taxe->frequence) {
                    'mensuel', 'mois' => $periodeCourante->addMonth(),
                    'annuel', 'an' => $periodeCourante->addYear(),
                    'journalier', 'jour' => $periodeCourante->addDay(),
                    default => $periodeCourante->addMonth(),
                };
            }

            return $periodes;
        }

        while ($periodeCourante <= $now) {
            $periodes[] = $periodeCourante->copy();
            match ($taxe->frequence) {
                'mensuel', 'mois' => $periodeCourante->addMonth(),
                'annuel', 'an' => $periodeCourante->addYear(),
                'journalier', 'jour' => $periodeCourante->addDay(),
                default => $periodeCourante->addMonth(),
            };
        }

        return $periodes;
    }

    /**
     * Traite les débits automatiques selon la fréquence des taxes.
     * Cette méthode peut être appelée via une tâche planifiée (Cron).
     */
    public function traiterPaiementsAutomatiques(?Request $request = null)
    {
        $now = Carbon::now();
        Log::info('Début du traitement automatique : '.$now);

        $stats = [
            'traites' => 0,
            'succes' => 0,
            'deja_paye' => 0,
            'solde_insuffisant' => 0,
        ];

        $frequencesATraiter = [];

        // Extraction des paramètres
        $isForce = false;
        $frequenceSpecifique = null;

        if ($request instanceof Request) {
            $isForce = $request->has('force') || $request->input('force') === true;
            $frequenceSpecifique = $request->input('frequence');
        }

        // Détermination des fréquences à traiter
        // Par défaut, on traite toujours le journalier chaque jour
        if ($isForce || ! $frequenceSpecifique || in_array($frequenceSpecifique, ['journalier', 'jour'])) {
            $frequencesATraiter = array_merge($frequencesATraiter, ['journalier', 'jour']);
        }

        // Mensuel : Dernier jour du mois ou forcé/demandé
        if ($now->isLastOfMonth() || $isForce || in_array($frequenceSpecifique, ['mensuel', 'mois'])) {
            $frequencesATraiter = array_merge($frequencesATraiter, ['mensuel', 'mois']);
        }

        // Annuel : 31 décembre ou forcé/demandé
        if (($now->month == 12 && $now->day == 31) || $isForce || in_array($frequenceSpecifique, ['annuel', 'an'])) {
            $frequencesATraiter = array_merge($frequencesATraiter, ['annuel', 'an']);
        }

        $frequencesATraiter = array_unique($frequencesATraiter);

        if (empty($frequencesATraiter)) {
            return response()->json([
                'status' => 'success',
                'message' => 'Aucune fréquence à traiter pour aujourd\'hui.',
                'stats' => $stats,
            ]);
        }

        $commercants = Commercant::whereHas('taxes', function ($q) use ($frequencesATraiter) {
            $q->whereIn('frequence', $frequencesATraiter);
        })->with(['taxes', 'solde'])->get();

        foreach ($commercants as $commercant) {
            $taxesAParcourir = $commercant->taxes->filter(
                fn ($taxe) => in_array($taxe->frequence, $frequencesATraiter)
            );

            if ($taxesAParcourir->isEmpty()) {
                continue;
            }

            DB::beginTransaction();

            try {
                $payementsAEffectuer = [];
                $montantTotalGlobal = 0;

                foreach ($taxesAParcourir as $taxe) {
                    // Utilisation de la méthode de calcul robuste pour trouver les périodes impayées
                    $periodesDates = $this->getUnpaidPeriodsAsDates($taxe, $commercant, null);

                    if (empty($periodesDates)) {
                        $stats['traites']++;
                        $stats['deja_paye']++;

                        continue;
                    }

                    foreach ($periodesDates as $date) {
                        $payementsAEffectuer[] = [
                            'taxe' => $taxe,
                            'periode' => $date->toDateString(),
                        ];
                        $montantTotalGlobal += $taxe->montant;
                    }
                }

                if (empty($payementsAEffectuer)) {
                    DB::commit();

                    continue;
                }

                // Vérification du solde
                $solde = $commercant->solde;
                if (! $solde || $solde->montant < $montantTotalGlobal) {
                    $stats['solde_insuffisant'] += count($payementsAEffectuer);
                    $stats['traites'] += count($payementsAEffectuer);
                    DB::rollBack();

                    continue;
                }

                // Débit Global
                $solde->decrement('montant', $montantTotalGlobal);

                // Enregistrement
                foreach ($payementsAEffectuer as $p) {
                    PaiementTaxe::create([
                        'mairie_ref' => $commercant->mairie_ref,
                        'secteur_id' => $commercant->secteur_id,
                        'taxe_id' => $p['taxe']->id,
                        'num_commerce' => $commercant->num_commerce,
                        'montant' => $p['taxe']->montant,
                        'statut' => 'payé',
                        'periode' => $p['periode'],
                    ]);
                    $stats['traites']++;
                    $stats['succes']++;
                }

                DB::commit();

            } catch (\Throwable $e) {
                DB::rollBack();
                Log::error("Erreur commerçant #{$commercant->id} : ".$e->getMessage());
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Traitement automatique terminé.',
            'stats' => $stats,
        ]);
    }
}
