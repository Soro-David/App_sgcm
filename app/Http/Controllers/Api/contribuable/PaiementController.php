<?php

namespace App\Http\Controllers\Api\Contribuable;

use App\Http\Controllers\Controller;
use App\Models\PaiementTaxe;
use App\Models\Solde;
use App\Models\Taxe;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PaiementController extends Controller
{
    public function list_taxes_a_payer(Request $request)
    {
        $commercant = $request->user();

        $taxes = $commercant->taxes()->get(['taxes.id', 'taxes.nom', 'taxes.montant']);

        if ($taxes->isEmpty()) {
            return response()->json([
                'message' => 'Aucune taxe n\'est actuellement assignée à votre commerce.',
                'taxes' => []
            ]);
        }

        return response()->json([
            'message' => 'Liste des taxes à payer.',
            'taxes' => $taxes
        ]);
    }

    public function periodes_impayees(Request $request, $taxeId)
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

        return response()->json([
            'count' => $nombreTotalPeriodes,
            'unite' => $unite,
            'periods_list' => $periodesFormatees,
            'montant_par_periode' => $taxe->montant,
            'total_a_payer' => $taxe->montant * $nombreTotalPeriodes
        ]);
    }

    public function effectuer_paiement(Request $request)
    {
        $commercant = $request->user();

        $validator = Validator::make($request->all(), [
            'taxe_id' => 'required|integer|exists:taxes,id',
            'nombre_periodes' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();
        $taxe = Taxe::find($validatedData['taxe_id']);

        if (!$commercant->taxes()->where('taxes.id', $validatedData['taxe_id'])->exists()) {
            return response()->json(['message' => 'Vous n\'êtes pas autorisé à payer cette taxe.'], 403);
        }

        $montantTotal = $taxe->montant * $validatedData['nombre_periodes'];
        
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

                $solde->decrement('montant', $montantTotal);

                foreach ($periodesAPayer as $periode) {
                    PaiementTaxe::create([
                        'mairie_ref' => $commercant->mairie_ref,
                        'secteur_id' => $commercant->secteur_id,
                        'taxe_id' => $taxe->id,
                        'num_commerce' => $commercant->num_commerce,
                        'montant' => $taxe->montant,
                        'statut' => 'payé',
                        'periode' => Carbon::parse($periode)->toDateString(),
                    ]);
                }
            });
        } catch (\Exception $e) {
            return response()->json(['message' => 'Une erreur technique est survenue lors du paiement.', 'error' => $e->getMessage()], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Paiement de '.count($periodesAPayer).' période(s) effectué avec succès !',
            'nouveau_solde' => $solde->montant
        ]);
    }

    public function historique_paiements(Request $request)
    {
        $commercant = $request->user();

        $historique = PaiementTaxe::where('num_commerce', $commercant->num_commerce)
            ->with('taxe:id,nom') 
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json($historique);
    }

    private function getUnpaidPeriodsAsDates(Taxe $taxe, $commercant, ?int $limit): array
    {
        $dernierPaiement = PaiementTaxe::where('taxe_id', $taxe->id)
            ->where('num_commerce', $commercant->num_commerce)
            ->orderBy('periode', 'desc')
            ->first();

        if ($dernierPaiement) {
            $dateDernierPaiement = Carbon::parse($dernierPaiement->periode);
            $periodeCourante = $dateDernierPaiement->copy();

            match ($taxe->frequence) {
                'mensuel', 'mois' => $periodeCourante->addMonth(),
                'annuel', 'an' => $periodeCourante->addYear(),
                'journalier', 'jour' => $periodeCourante->addDay(),
                default => $periodeCourante->addMonth(),
            };
        } else {
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
}
