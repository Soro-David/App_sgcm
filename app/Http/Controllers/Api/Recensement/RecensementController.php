<?php

namespace App\Http\Controllers\Api\Recensement;

use App\Http\Controllers\Controller;
use App\Models\Commercant;
use App\Models\Mairie;
use App\Services\QrCodeService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RecensementController extends Controller
{
    public function index(Request $request)
    {
        $agent = $request->user();
        if ($agent->type !== 'recensement') {
            return response()->json(['success' => false, 'message' => 'Accès refusé. Seuls les agents de recensement peuvent accéder à cette ressource.'], 403);
        }

        $commercants = Commercant::where('agent_id', $agent->id)
            ->with(['secteur', 'taxes'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $commercants,
        ]);
    }

    public function generateNumCommerce(Request $request)
    {
        $agent = $request->user();
        $mairie = Mairie::where('mairie_ref', $agent->mairie_ref)->first();

        if (! $mairie) {
            return response()->json(['success' => false, 'message' => 'Mairie non trouvée pour cet agent.'], 404);
        }

        $prefix = 'CONT';
        if ($mairie->name) {
            $prefix = strtoupper(substr(preg_replace('/\s+/', '', $mairie->name), 0, 4));
        } elseif ($mairie->mairie_ref) {
            $prefix = strtoupper(substr($mairie->mairie_ref, 0, 4));
        }

        $lastCommerce = Commercant::where('mairie_ref', $agent->mairie_ref)
            ->orderByDesc('id')
            ->first();

        $lastNumber = 0;
        if ($lastCommerce && preg_match('/\d+$/', $lastCommerce->num_commerce, $matches)) {
            $lastNumber = (int) $matches[0];
        }

        $num_commerce = $prefix.str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        return response()->json([
            'success' => true,
            'num_commerce' => $num_commerce,
        ]);
    }

    public function store(Request $request, QrCodeService $qrCodeService)
    {
        $agent = $request->user();
        if ($agent->type !== 'recensement') {
            return response()->json(['success' => false, 'message' => 'Accès refusé.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:commercants,email',
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
            'secteur_id' => 'nullable|exists:secteurs,id',
            'type_contribuable_id' => 'required|exists:type_contribuables,id',
            'taxe_ids' => 'required|array',
            'taxe_ids.*' => 'exists:taxes,id',
            'type_piece' => 'required|string|in:cni,attestation,passeport,consulaire,autre',
            'numero_piece' => 'nullable|string|max:255',
            'autre_type_piece' => 'nullable|string|max:255|required_if:type_piece,autre',
            'photo_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'photo_recto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'photo_verso' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();

        // 1. Récupérer le secteur de l'agent si non fourni
        if (empty($validatedData['secteur_id'])) {
            // On vérifie d'abord secteur_id (colonne simple ou array castée dans le modèle Agent)
            if (! empty($agent->secteur_id)) {
                $validatedData['secteur_id'] = is_array($agent->secteur_id) ? $agent->secteur_id[0] : $agent->secteur_id;
            }
            // Sinon via la relation
            elseif ($agent->secteurs()->exists()) {
                $validatedData['secteur_id'] = $agent->secteurs()->first()->id;
            }

            if (empty($validatedData['secteur_id'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'L\'agent n\'a pas de secteur assigné. Veuillez spécifier un secteur_id ou assigner un secteur à l\'agent.',
                ], 422);
            }
        }

        // 2. Générer automatiquement le numéro de commerce
        $mairie = Mairie::where('mairie_ref', $agent->mairie_ref)->first();
        if (! $mairie) {
            return response()->json(['success' => false, 'message' => 'Impossible de générer le numéro de commerce : Mairie non trouvée.'], 404);
        }

        $prefix = 'CONT';
        if ($mairie->name) {
            $prefix = strtoupper(substr(preg_replace('/\s+/', '', $mairie->name), 0, 4));
        } elseif ($mairie->mairie_ref) {
            $prefix = strtoupper(substr($mairie->mairie_ref, 0, 4));
        }

        $lastCommerce = Commercant::where('mairie_ref', $agent->mairie_ref)
            ->orderByDesc('id')
            ->first();

        $lastNumber = 0;
        if ($lastCommerce && preg_match('/\d+$/', $lastCommerce->num_commerce, $matches)) {
            $lastNumber = (int) $matches[0];
        }

        $num_commerce = $prefix.str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        $validatedData['num_commerce'] = $num_commerce;
        $validatedData['agent_id'] = $agent->id;
        $validatedData['mairie_ref'] = $agent->mairie_ref;

        $profilPath = $rectoPath = $versoPath = null;

        try {
            if ($request->hasFile('photo_profil')) {
                $profilPath = $request->file('photo_profil')->store('commercants/profils', 'public');
                $validatedData['photo_profil'] = $profilPath;
            }

            if ($request->hasFile('photo_recto')) {
                $rectoPath = $request->file('photo_recto')->store('commercants/recto', 'public');
                $validatedData['photo_recto'] = $rectoPath;
            }

            if ($request->hasFile('photo_verso')) {
                $versoPath = $request->file('photo_verso')->store('commercants/verso', 'public');
                $validatedData['photo_verso'] = $versoPath;
            }

            $commercant = Commercant::create($validatedData);

            if ($request->has('taxe_ids')) {
                $commercant->taxes()->sync($request->input('taxe_ids'));
            }

            $qrCodePath = $qrCodeService->generateForCommercant($commercant);
            $commercant->update(['qr_code_path' => $qrCodePath]);

            if ($commercant->email) {
                try {
                    Log::info('Tentative d\'envoi OTP à : '.$commercant->email);
                    $otp = rand(100000, 999999);
                    $commercant->otp_code = $otp;
                    $commercant->otp_expires_at = Carbon::now()->addHours(48);
                    $commercant->save();

                    Notification::sendNow($commercant, new \App\Notifications\CommercantWelcomeNotification($commercant, (string) $otp));
                    Log::info('Email OTP envoyé via API pour '.$commercant->email);
                } catch (\Exception $e) {
                    Log::error('Erreur envoi mail commerçant API: '.$e->getMessage());
                    Log::error($e->getTraceAsString());
                }
            } else {
                Log::warning('Pas d\'email pour le commerçant '.$commercant->id.', envoi OTP annulé.');
            }

            return response()->json([
                'success' => true,
                'message' => 'Contribuable ajouté avec succès !',
                'data' => [
                    'id' => $commercant->id,
                    'num_commerce' => $commercant->num_commerce,
                    'nom' => $commercant->nom,
                    'qr_code_path' => $commercant->qr_code_path,
                ],
            ], 201);

        } catch (\Exception $e) {
            if ($profilPath) {
                Storage::disk('public')->delete($profilPath);
            }
            if ($rectoPath) {
                Storage::disk('public')->delete($rectoPath);
            }
            if ($versoPath) {
                Storage::disk('public')->delete($versoPath);
            }

            Log::error('Erreur ajout commerçant API: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Une erreur interne est survenue lors de l\'enregistrement.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function show(Request $request, $id)
    {
        $agent = $request->user();
        $commercant = Commercant::where('id', $id)->where('mairie_ref', $agent->mairie_ref)->first();

        if (! $commercant) {
            return response()->json(['success' => false, 'message' => 'Contribuable non trouvé ou accès non autorisé.'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $commercant->load(['secteur', 'taxes', 'typeContribuable']),
        ]);
    }

    public function update(Request $request, $id)
    {
        $agent = $request->user();
        if ($agent->type !== 'recensement') {
            return response()->json(['success' => false, 'message' => 'Accès refusé.'], 403);
        }

        $commercant = Commercant::where('id', $id)->where('mairie_ref', $agent->mairie_ref)->first();
        if (! $commercant) {
            return response()->json(['success' => false, 'message' => 'Contribuable non trouvé.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nom' => 'sometimes|required|string|max:255',
            'email' => 'nullable|email|max:255|unique:commercants,email,'.$id,
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
            'secteur_id' => 'nullable|exists:secteurs,id',
            'type_contribuable_id' => 'sometimes|required|exists:type_contribuables,id',
            'taxe_ids' => 'sometimes|required|array',
            'taxe_ids.*' => 'exists:taxes,id',
            'type_piece' => 'sometimes|required|string|in:cni,attestation,passeport,consulaire,autre',
            'numero_piece' => 'nullable|string|max:255',
            'autre_type_piece' => 'nullable|string|max:255|required_if:type_piece,autre',
            'photo_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'photo_recto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'photo_verso' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();

        try {
            if ($request->hasFile('photo_profil')) {
                if ($commercant->photo_profil) {
                    Storage::disk('public')->delete($commercant->photo_profil);
                }
                $validatedData['photo_profil'] = $request->file('photo_profil')->store('commercants/profils', 'public');
            }

            if ($request->hasFile('photo_recto')) {
                if ($commercant->photo_recto) {
                    Storage::disk('public')->delete($commercant->photo_recto);
                }
                $validatedData['photo_recto'] = $request->file('photo_recto')->store('commercants/recto', 'public');
            }

            if ($request->hasFile('photo_verso')) {
                if ($commercant->photo_verso) {
                    Storage::disk('public')->delete($commercant->photo_verso);
                }
                $validatedData['photo_verso'] = $request->file('photo_verso')->store('commercants/verso', 'public');
            }

            $commercant->update($validatedData);

            if ($request->has('taxe_ids')) {
                $commercant->taxes()->sync($request->input('taxe_ids'));
            }

            return response()->json([
                'success' => true,
                'message' => 'Contribuable mis à jour avec succès !',
                'data' => $commercant->load(['secteur', 'taxes', 'typeContribuable']),
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur modification commerçant API: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Une erreur interne est survenue lors de la modification.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Liste tous les contribuables de la mairie de l'agent.
     */
    public function listContribuables(Request $request)
    {
        $agent = $request->user();
        if ($agent->type !== 'recensement') {
            return response()->json(['success' => false, 'message' => 'Accès refusé. Seuls les agents de recensement peuvent accéder à cette ressource.'], 403);
        }

        $commercants = Commercant::where('mairie_ref', $agent->mairie_ref)
            ->with(['secteur', 'taxes', 'typeContribuable'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $commercants,
        ]);
    }
}
