<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Commercant;
use Illuminate\Support\Facades\Hash;
use App\Models\Taxe; 
use App\Models\PaiementTaxe;
use Illuminate\Support\Facades\Validator;


class CommercantController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'num_commerce' => 'required',
            'mot_de_passe' => 'required',
        ]);

        $commercant = Commercant::where('num_commerce', $request->num_commerce)->first();

        if (!$commercant || !Hash::check($request->mot_de_passe, $commercant->mot_de_passe)) {
            return response()->json(['message' => 'Identifiants incorrects'], 401);
        }

        // Création du token avec la bonne "ability"
        $token = $commercant->createToken('commercant-token', ['commercant'])->plainTextToken;

        return response()->json([
            'token' => $token,
            'commercant' => $commercant
        ]);
    }
    

    public function list_taxes_a_payer(Request $request)
    {
        $commercant = $request->user();

        $taxeIds = is_array($commercant->taxe_id) ? $commercant->taxe_id : json_decode($commercant->taxe_id, true);

        if (empty($taxeIds) || !is_array($taxeIds)) {
            return response()->json([
                'message' => 'Aucune taxe n\'est actuellement assignée à votre commerce.',
                'taxes' => []
            ]);
        }

        // Requête pour récupérer les taxes concernées
        $taxes = Taxe::whereIn('id', $taxeIds)->get(['id', 'nom', 'montant']);

        return response()->json([
            'message' => 'Liste des taxes à payer.',
            'taxes' => $taxes
        ]);
    }



    public function effectuer_paiement(Request $request)
    {
        $commercant = $request->user();

        $validator = Validator::make($request->all(), [
            'taxe_id' => 'required|integer|exists:taxes,id',
            'montant' => 'required|numeric|min:0',
        ]);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();


        // On s'assure que c'est bien un tableau
        $assignedTaxeIds = json_decode($commercant->taxe_id, true) ?? [];



        if (!in_array($validatedData['taxe_id'], $assignedTaxeIds)) {
            return response()->json(['message' => 'Vous n\'êtes pas autorisé à payer cette taxe.'], 403);
        }

        $paiement = PaiementTaxe::create([
            'mairie_id' => $commercant->mairie_id,
            'secteur_id' => $commercant->secteur_id,
            'taxe_id' => $validatedData['taxe_id'],
            'num_commerce' => $commercant->num_commerce,
            'montant' => $validatedData['montant'],
            'statut' => 'payé',
        ]);


        return response()->json([
            'message' => 'Paiement effectué avec succès !',
            'paiement' => $paiement
        ], 201);
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
    
    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Déconnexion réussie']);
    }
}