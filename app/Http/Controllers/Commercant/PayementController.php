<?php

namespace App\Http\Controllers\Commercant;

use App\Http\Controllers\Controller;
use App\Models\Taxe;
use App\Models\PaiementTaxe;
use App\Models\Commercant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
        return view('commercant.payement.create',compact('taxes','commercant'));
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

        // dd($request);
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

        if (!$commercant->taxes()->where('taxes.id', $validatedData['taxe_id'])->exists()) {
            return response()->json(['message' => 'Vous n\'êtes pas autorisé à payer cette taxe.'], 403);
        }

        $periodesAPayer = []; // On initialise pour pouvoir l'utiliser dans la réponse
        try {
            DB::transaction(function () use ($commercant, $taxe, $validatedData, &$periodesAPayer) {
                
                $periodesAPayer = $this->getUnpaidPeriodsAsDates($taxe, $commercant, $validatedData['nombre_periodes']);
                
                foreach ($periodesAPayer as $periode) {
                    PaiementTaxe::create([
                        'mairie_ref'    => $commercant->mairie_ref,
                        'secteur_id'   => $commercant->secteur_id,
                        'taxe_id'      => $taxe->id,
                        'num_commerce' => $commercant->num_commerce,
                        'montant'      => $taxe->montant,
                        'statut'       => 'payé',
                        'periode'      => \Carbon\Carbon::parse($periode)->toDateString(),
                    ]);

                }
            });
        } catch (\Exception $e) {
            // Laissez cette ligne pendant que vous développez pour voir l'erreur exacte si elle se reproduit.
            dd($e); 
            return response()->json(['message' => 'Une erreur technique est survenue lors du paiement.'], 500);
        }

        return response()->json(['message' => 'Paiement de ' . count($periodesAPayer) . ' période(s) effectué avec succès !']);
    }


    public function periodes_impayees(Request $request, $taxeId): JsonResponse
    {
        $commercant = $request->user();
        $taxe = Taxe::findOrFail($taxeId);

        $limit = $request->has('nombre_periodes') ? intval($request->input('nombre_periodes')) : null;

        $dates = $this->getUnpaidPeriodsAsDates($taxe, $commercant, $limit);
        $nombreTotalPeriodes = count($dates);

        $frequenceMap = ['mensuel' => 'mois', 'annuel' => 'an', 'journalier' => 'jour'];
        $unite = $frequenceMap[$taxe->frequence] ?? $taxe->frequence;
        if ($nombreTotalPeriodes > 1 && $unite !== 'an') {
            $unite .= 's';
        }

        $summaryText = $nombreTotalPeriodes > 0 ? "$nombreTotalPeriodes $unite à payer" : "Aucune période à payer.";
        if ($limit === null && $nombreTotalPeriodes > 0) {
            $summaryText = "<b>$nombreTotalPeriodes $unite impayé(s)</b> ont été calculés :";
        }

        $periodesFormatees = array_map(function ($date) use ($taxe) {
            switch ($taxe->frequence) {
                case 'mensuel': return $date->format('m/Y'); 
                
                case 'annuel': return $date->format('Y');
                case 'journalier': return $date->format('d/m/Y');
                default: return $date->format('Y-m-d');
            }
        }, $dates);

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

        $periodeCourante;

        if ($dernierPaiement) {
            $dateDernierPaiement = Carbon::parse($dernierPaiement->periode);
            $periodeCourante = $dateDernierPaiement->copy();
            
            // On utilise les clés de fréquence cohérentes (mensuel, annuel, journalier)
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
}