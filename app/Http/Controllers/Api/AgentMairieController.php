<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Agent;
use App\Models\Commercant;
use App\Models\Mairie;

class AgentMairieController extends Controller
{
    /**
     * Authentifie un agent de mairie et retourne un token.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $agent = Agent::where('email', $request->email)->where('type', 'mairie')->first();

        if (!$agent || !Hash::check($request->password, $agent->password)) {
            return response()->json(['message' => 'Identifiants incorrects'], 401);
        }

        $token = $agent->createToken('agent-mairie-token', ['agent-mairie'])->plainTextToken;

        return response()->json([
            'token' => $token,
            'agent' => $agent // Il peut être utile de retourner les infos de l'agent
        ]);
    }

    /**
     * Retourne les informations de l'agent authentifié.
     */
    public function me(Request $request)
    {
        $user = auth()->user();

        if (!$user || $user->type !== 'mairie') {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        return response()->json([
            'user' => $user,
            'type' => $user->type
        ]);
    }

    public function generate_num_commerce(Request $request)
    {
        $agent = $request->user();
        $mairie = Mairie::findOrFail($agent->mairie_ref);

        // Générer le numéro de commerce 
        $prefix = strtoupper(substr(preg_replace('/\s+/', '', $mairie->name), 0, 4));

        $lastCommerce = Commercant::where('mairie_ref', $agent->mairie_ref)
                                    ->orderByDesc('id')
                                    ->first();

        $lastNumber = 0;
        if ($lastCommerce && preg_match('/\d+$/', $lastCommerce->num_commerce, $matches)) {
            $lastNumber = (int) $matches[0];
        }

        $newNumber = $lastNumber + 1;
        $numeroFormate = str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        $num_commerce = $prefix . $numeroFormate;

        return response()->json(['num_commerce' => $num_commerce]);
    }


    /**
     * Enregistre un nouveau commerçant.
     */
    public function store_commercant(Request $request)
    {
        $agent = $request->user();

        if (!$agent || $agent->type !== 'mairie') {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:commercants,email',
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
            'num_commerce' => 'required|string|unique:commercants,num_commerce',
            'mot_de_passe' => 'nullable|string|min:6',
            'taxe_ids' => 'required|array',
            'secteur_id' => 'required|integer|exists:secteurs,id',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        $commercant = Commercant::create([
            'nom' => $data['nom'],
            'email' => $data['email'],
            'telephone' => $data['telephone'],
            'adresse' => $data['adresse'],
            'num_commerce' => $data['num_commerce'],
            'mot_de_passe' => isset($data['mot_de_passe']) ? bcrypt($data['mot_de_passe']) : null,
            'agent_id' => $agent->id,
            'mairie_ref' => $agent->mairie_ref,
            'taxe_id' => $data['taxe_ids'],
            'secteur_id' => [$data['secteur_id']],
        ]);

        return response()->json([
            'message' => 'Commerçant ajouté avec succès',
            'commercant' => $commercant,
        ], 201);
    }

    /**
     * NOUVELLE FONCTION : Affiche les détails d'un commerçant spécifique.
     */
    public function show_commercant(Request $request, $id)
    {
        $agent = $request->user();
        $commercant = Commercant::find($id);

        if (!$commercant) {
            return response()->json(['message' => 'Commerçant non trouvé.'], 404);
        }

        // Vérification de sécurité : l'agent ne peut voir que les commerçants de sa mairie
        if ($commercant->mairie_ref !== $agent->mairie_ref) {
            return response()->json(['message' => 'Accès non autorisé à ce commerçant.'], 403);
        }

        // Vous pouvez charger les relations si nécessaire
        // $commercant->load('taxes', 'secteur');

        return response()->json(['commercant' => $commercant]);
    }

    /**
     * NOUVELLE FONCTION : Met à jour les informations d'un commerçant.
     */
    public function update_commercant(Request $request, $id)
    {
        $agent = $request->user();
        $commercant = Commercant::find($id);

        if (!$commercant) {
            return response()->json(['message' => 'Commerçant non trouvé.'], 404);
        }

        // Vérification de sécurité
        if ($commercant->mairie_ref !== $agent->mairie_ref) {
            return response()->json(['message' => 'Accès non autorisé à ce commerçant.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:commercants,email,' . $id,
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
            'taxe_ids' => 'sometimes|required|array',
            'secteur_id' => 'sometimes|required|integer|exists:secteurs,id',
            'mot_de_passe' => 'nullable|string|min:6', // Pour la mise à jour du mot de passe
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $data = $validator->validated();
        
        // Le numéro de commerce ne doit généralement pas être modifié
        $commercant->update($data);

        // Gérer la mise à jour du mot de passe séparément
        if (!empty($data['mot_de_passe'])) {
            $commercant->mot_de_passe = bcrypt($data['mot_de_passe']);
        }
        
        // Assurer que secteur_id est un tableau
        if(isset($data['secteur_id'])) {
             $commercant->secteur_id = [$data['secteur_id']];
        }

        $commercant->save();

        return response()->json([
            'message' => 'Commerçant mis à jour avec succès.',
            'commercant' => $commercant
        ]);
    }


    /**
     * NOUVELLE FONCTION : Supprime un commerçant.
     */
    public function destroy_commercant(Request $request, $id)
    {
        $agent = $request->user();
        $commercant = Commercant::find($id);

        if (!$commercant) {
            return response()->json(['message' => 'Commerçant non trouvé.'], 404);
        }

        // Vérification de sécurité
        if ($commercant->mairie_ref !== $agent->mairie_ref) {
            return response()->json(['message' => 'Accès non autorisé à ce commerçant.'], 403);
        }

        $commercant->delete();

        return response()->json(['message' => 'Commerçant supprimé avec succès.'], 200);
    }
    
    /**
     * NOUVELLE FONCTION : Lister tous les commerçants de la mairie de l'agent
     */
    public function list_commercants(Request $request)
    {
        $agent = $request->user();
        
        $commercants = Commercant::where('mairie_ref', $agent->mairie_ref)
                                 ->orderBy('nom', 'asc')
                                 ->paginate(15); // Utiliser la pagination est une bonne pratique pour les API

        return response()->json($commercants);
    }


    /**
     * Déconnecte l'agent.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Déconnexion réussie']);
    }
}