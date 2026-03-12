<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Commercant;
use App\Notifications\PasswordResetNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Authentifie un utilisateur (Commerçant ou Agent).
     */
    public function login(Request $request)
    {
        // Support pour plusieurs noms de paramètres pour être plus flexible
        $identifiant = $request->input('identifiant') ?? $request->input('email') ?? $request->input('num_commerce') ?? $request->input('agentID');
        $password = $request->input('password') ?? $request->input('mot_de_passe');

        if (! $identifiant || ! $password) {
            return response()->json([
                'success' => false,
                'message' => 'L\'identifiant et le mot de passe sont requis.',
            ], 400);
        }

        // ==========================
        // 1. Vérifier Commercant
        // ==========================

        // Recherche par num_commerce ou email
        $commercant = Commercant::where('num_commerce', $identifiant)
            ->orWhere('email', $identifiant)
            ->first();

        // Mots de passe : vérifie les deux colonnes possibles 'password' ou 'mot_de_passe'
        if ($commercant) {
            // Dans votre DB, la colonne officielle est 'password' mais vous aviez peut-être aussi utilisé 'mot_de_passe' dans le code précédent
            $hashedPassword = $commercant->password ?? $commercant->mot_de_passe;

            if ($hashedPassword && Hash::check($password, $hashedPassword)) {

                $expiresAt = $request->input('remember_me') ? now()->addYear() : now()->addHours(8);

                $token = $commercant
                    ->createToken('commercant-token', ['commercant'], $expiresAt)
                    ->plainTextToken;

                $commercant->update(['last_activity' => now()]);

                return response()->json([
                    'success' => true,
                    'message' => 'Connexion réussie',
                    'data' => [
                        'token' => $token,
                        'role' => 'commercant',
                        'user' => [
                            'id' => $commercant->id,
                            'num_commerce' => $commercant->num_commerce,
                            'nom' => $commercant->nom ?? null,
                            'email' => $commercant->email,
                        ],
                    ],
                ], 200);
            }
        }

        // ==========================
        // 2. Vérifier Agent
        // ==========================

        // Recherche par matricule ou email
        $agent = Agent::where('matricule', $identifiant)
            ->orWhere('email', $identifiant)
            ->first();

        // On vérifie que le mot de passe correspond
        if ($agent && $agent->password && Hash::check($password, $agent->password)) {

            $abilities = [];
            $tokenName = 'agent-token';

            if ($agent->type === 'mairie') {
                $abilities = ['agent-mairie'];
                $tokenName = 'agent-mairie-token';
            } elseif ($agent->type === 'recouvrement') {
                $abilities = ['agent-recouvrement'];
                $tokenName = 'agent-recouvrement-token';
            } elseif ($agent->type === 'recensement') {
                $abilities = ['agent-recensement'];
                $tokenName = 'agent-recensement-token';
            }

            $expiresAt = $request->input('remember_me') ? now()->addYear() : now()->addHours(8);
            $token = $agent->createToken($tokenName, $abilities, $expiresAt)->plainTextToken;

            $agent->update(['last_activity' => now()]);

            return response()->json([
                'success' => true,
                'message' => 'Connexion réussie',
                'data' => [
                    'token' => $token,
                    'role' => 'agent_'.$agent->type,
                    'user' => [
                        'id' => $agent->id,
                        'matricule' => $agent->matricule,
                        'nom' => $agent->name ?? null,
                        'email' => $agent->email,
                        'type' => $agent->type,
                    ],
                ],
            ], 200);
        }

        // ==========================
        // Identifiants incorrects
        // ==========================
        Log::warning('Login failed for identifier: '.$identifiant);

        return response()->json([
            'success' => false,
            'message' => 'Identifiant ou mot de passe incorrect',
        ], 401);
    }

    /**
     * Envoie un lien de réinitialisation de mot de passe.
     */
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $email = $request->email;
        $user = Commercant::where('email', $email)->first() ?? Agent::where('email', $email)->first();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Aucun utilisateur trouvé avec cet email.',
            ], 404);
        }

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            ['token' => $token, 'created_at' => now()]
        );

        // L'URL peut être configurée via le frontend ou ici par défaut
        // Pour une API, on renvoie souvent le token ou on envoie un mail avec un lien vers le frontend
        $resetUrl = env('FRONTEND_URL', 'http://localhost:8082').'/reset-password?token='.$token.'&email='.urlencode($email);

        try {
            $user->notify(new PasswordResetNotification($resetUrl));
        } catch (\Exception $e) {
            Log::error('Erreur d\'envoi d\'email : '.$e->getMessage());
            // Pour le debug local, on peut retourner le token si l'envoi d'email échoue
            if (config('app.debug')) {
                return response()->json([
                    'success' => true,
                    'message' => 'Lien de réinitialisation généré (Email non envoyé en raison d\'une erreur de config)',
                    'token' => $token,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Lien de réinitialisation envoyé par email.',
        ]);
    }

    /**
     * Réinitialise le mot de passe.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $reset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (! $reset || Carbon::parse($reset->created_at)->addMinutes(60)->isPast()) {
            return response()->json([
                'success' => false,
                'message' => 'Token invalide ou expiré.',
            ], 400);
        }

        $user = Commercant::where('email', $request->email)->first() ?? Agent::where('email', $request->email)->first();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé.',
            ], 404);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Mot de passe réinitialisé avec succès.',
        ]);
    }

    /**
     * Récupère le profil de l'utilisateur connecté (Commerçant ou Agent).
     */
    public function profile(Request $request)
    {
        try {
            $user = auth()->user();

            if (! $user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Utilisateur non authentifié.',
                ], 401);
            }

            $role = 'unknown';
            $userData = [];

            if ($user instanceof Agent) {
                $role = 'agent_'.$user->type;

                // Charger les relations utiles pour un agent
                $user->load(['mairie', 'commune']);

                $userData = [
                    'id' => $user->id,
                    'matricule' => $user->matricule,
                    'nom' => $user->name,
                    'email' => $user->email,
                    'telephone' => $user->telephone1,
                    'type' => $user->type,
                    'status' => $user->status,
                    'genre' => $user->genre,
                    'adresse' => $user->adresse,
                    'photo_profil' => $user->photo_profil ? asset('storage/'.$user->photo_profil) : null,
                    'mairie' => $user->mairie ? [
                        'nom' => $user->mairie->nom_mairie,
                        'code' => $user->mairie->mairie_ref,
                    ] : null,
                    'commune' => $user->commune ? $user->commune->nom : null,
                ];

                // Ajouter les taxes et secteurs si c'est un agent de recouvrement ou recensement
                if (in_array($user->type, ['recouvrement', 'recensement'])) {
                    // Les taxes et secteurs sont stockés dans des colonnes JSON (taxe_id, secteur_id)
                    // On récupère les IDs et on fait une requête manuelle car la relation belongsToMany n'a pas de table pivot

                    $taxeIds = is_array($user->taxe_id) ? $user->taxe_id : json_decode($user->taxe_id ?? '[]', true);
                    if (! empty($taxeIds)) {
                        $userData['taxes'] = \App\Models\Taxe::whereIn('id', $taxeIds)->get(['id', 'nom']);
                    } else {
                        $userData['taxes'] = [];
                    }

                    $secteurIds = is_array($user->secteur_id) ? $user->secteur_id : json_decode($user->secteur_id ?? '[]', true);
                    if (! empty($secteurIds)) {
                        $userData['secteurs'] = \App\Models\Secteur::whereIn('id', $secteurIds)->get(['id', 'nom']);
                    } else {
                        $userData['secteurs'] = [];
                    }
                }

            } elseif ($user instanceof Commercant) {
                $role = 'commercant';

                // Charger les relations utiles pour un commerçant
                $user->load(['mairie', 'secteur', 'typeContribuable']);

                $userData = [
                    'id' => $user->id,
                    'num_commerce' => $user->num_commerce,
                    'nom' => $user->nom,
                    'email' => $user->email,
                    'telephone' => $user->telephone,
                    'adresse' => $user->adresse,
                    'photo_profil' => $user->photo_profil ? asset('storage/'.$user->photo_profil) : null,
                    'type_contribuable' => $user->typeContribuable ? $user->typeContribuable->libelle : null,
                    'secteur' => $user->secteur ? $user->secteur->nom : null,
                    'mairie' => $user->mairie ? [
                        'nom' => $user->mairie->nom_mairie,
                        'code' => $user->mairie->mairie_ref,
                    ] : null,
                ];
            }

            return response()->json([
                'success' => true,
                'message' => 'Profil récupéré avec succès',
                'data' => [
                    'role' => $role,
                    'user' => $userData,
                ],
            ], 200);

        } catch (\Exception $e) {
            Log::error('Profile error: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération du profil.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Déconnecte l'utilisateur en révoquant son token actuel.
     */
    public function logout(Request $request)
    {
        try {
            $user = $request->user();

            if ($user) {
                // Révoquer le token actuel
                $user->currentAccessToken()->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Déconnexion réussie.',
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé ou non connecté.',
            ], 401);

        } catch (\Exception $e) {
            Log::error('Logout error: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la déconnexion.',
            ], 500);
        }
    }
}
