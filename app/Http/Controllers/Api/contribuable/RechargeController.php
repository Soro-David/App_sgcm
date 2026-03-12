<?php

namespace App\Http\Controllers\Api\Contribuable;

use App\Http\Controllers\Controller;
use App\Models\HistoriqueRecharge;
use App\Models\Solde;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RechargeController extends Controller
{
    public function get_solde(Request $request)
    {
        $commercant = $request->user();
        $solde = Solde::firstOrCreate(
            ['commercant_id' => $commercant->id],
            ['montant' => 0]
        );

        return response()->json([
            'solde' => $solde->montant,
        ]);
    }

    public function recharger_compte(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'montant' => 'required|numeric|min:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $commercant = $request->user();

        $solde = Solde::firstOrCreate(
            ['commercant_id' => $commercant->id],
            ['montant' => 0]
        );

        $solde->increment('montant', $request->montant);

        $recharge = HistoriqueRecharge::create([
            'commercant_id' => $commercant->id,
            'montant' => $request->montant,
            'reference' => 'RECH-'.Str::upper(Str::random(10)),
            'mode_paiement' => 'Mobile Money (Simulation)',
            'statut' => 'réussi',
        ]);

        return response()->json([
            'message' => 'Votre compte a été rechargé avec succès.',
            'nouveau_solde' => $solde->montant,
            'recharge' => $recharge,
        ]);
    }

    public function historique_recharges(Request $request)
    {
        $commercant = $request->user();

        $recharges = HistoriqueRecharge::where('commercant_id', $commercant->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json($recharges);
    }
}
