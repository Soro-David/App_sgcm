<?php

namespace App\Http\Controllers\Api\Recouvrement;

use App\Http\Controllers\Controller;
use App\Models\Commercant;
use App\Models\Encaissement;
use App\Models\PaiementTaxe;
use App\Models\Taxe;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RecouvrementController extends Controller
{
    /**
     * Profil de l'agent de recouvrement connecté.
     */
    public function me(Request $request)
    {
        $agent = $request->user();

        return response()->json([
            'success' => true,
            'data'    => [
                'id'         => $agent->id,
                'name'       => $agent->name,
                'email'      => $agent->email,
                'telephone'  => $agent->telephone ?? null,
                'mairie_ref' => $agent->mairie_ref,
                'type'       => $agent->type,
            ],
        ]);
    }

    /**
     * Scan du QR code d'un contribuable par l'agent de recouvrement.
     */
    public function scanQrCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'num_commerce' => 'required|string',
            'qr_data'      => 'nullable|string', // Données brutes du QR si l'app envoie tout le contenu
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $agent = $request->user();

        // Résoudre le num_commerce : soit fourni directement, soit extrait du contenu brut du QR
        $numCommerce = $request->num_commerce;

        // Si le num_commerce n'est pas trouvé directement, essayer de l'extraire du qr_data brut
        if (empty($numCommerce) && $request->filled('qr_data')) {
            preg_match('/Numéro commerce:\s*(\S+)/i', $request->qr_data, $matches);
            $numCommerce = $matches[1] ?? null;
        }

        if (empty($numCommerce)) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de lire le numéro de commerce depuis le QR code.',
            ], 422);
        }

        // Rechercher le contribuable dans la mairie de l'agent uniquement
        $commercant = Commercant::where('num_commerce', $numCommerce)
            ->where('mairie_ref', $agent->mairie_ref)
            ->with(['secteur', 'taxes', 'typeContribuable'])
            ->first();

        if (! $commercant) {
            return response()->json([
                'success' => false,
                'message' => 'Contribuable non trouvé ou n\'appartient pas à votre mairie.',
            ], 404);
        }

        // Calculer les périodes dues pour chaque taxe assignée
        $taxesAvecPeriodes = [];
        $totalMontantDu    = 0;

        foreach ($commercant->taxes as $taxe) {
            $dernierPaiement = PaiementTaxe::where('num_commerce', $commercant->num_commerce)
                ->where('taxe_id', $taxe->id)
                ->orderByDesc('periode')
                ->first();

            if ($dernierPaiement) {
                try {
                    $lastPeriod = Carbon::createFromFormat('Y-m-d H:i:s', $dernierPaiement->periode)->startOfMonth();
                } catch (\Exception $e) {
                    try {
                        $lastPeriod = Carbon::createFromFormat('d/m/Y', $dernierPaiement->periode)->startOfDay();
                    } catch (\Exception $e2) {
                        $lastPeriod = Carbon::createFromFormat('Y', $dernierPaiement->periode)->startOfYear();
                    }
                }
            } else {
                $dateDebut = ($commercant->created_at && $taxe->created_at && $commercant->created_at->gt($taxe->created_at))
                    ? $commercant->created_at
                    : ($taxe->created_at ?? Carbon::today()->startOfMonth());

                $lastPeriod = Carbon::parse($dateDebut)->startOfDay();

                switch ($taxe->frequence) {
                    case 'jour': $lastPeriod->subDay(); break;
                    case 'mois': $lastPeriod->subMonth(); break;
                    case 'an':   $lastPeriod->subYear(); break;
                }
            }

            $today        = Carbon::today()->startOfDay();
            $periodesDues = [];

            switch ($taxe->frequence) {
                case 'jour':
                    $period = CarbonPeriod::create($lastPeriod->copy()->addDay(), '1 day', $today);
                    foreach ($period as $date) {
                        $periodesDues[] = $date->format('d/m/Y');
                    }
                    break;
                case 'mois':
                    $period = CarbonPeriod::create($lastPeriod->copy()->addMonth(), '1 month', $today);
                    foreach ($period as $date) {
                        $periodesDues[] = $date->format('m/Y');
                    }
                    break;
                case 'an':
                    $period = CarbonPeriod::create($lastPeriod->copy()->addYear(), '1 year', $today);
                    foreach ($period as $date) {
                        $periodesDues[] = $date->format('Y');
                    }
                    break;
            }

            $nombrePeriodesDues = count($periodesDues);
            $montantDu          = $nombrePeriodesDues * $taxe->montant;
            $totalMontantDu    += $montantDu;

            $taxesAvecPeriodes[] = [
                'taxe_id'            => $taxe->id,
                'nom'                => $taxe->nom,
                'montant_unitaire'   => $taxe->montant,
                'frequence'          => $taxe->frequence,
                'nombre_periodes_dues' => $nombrePeriodesDues,
                'montant_total_du'   => $montantDu,
                'periodes_dues'      => $periodesDues,
                'dernier_paiement'   => $dernierPaiement ? $dernierPaiement->periode : null,
            ];
        }

        return response()->json([
            'success' => true,
            'message' => 'Contribuable trouvé avec succès.',
            'data'    => [
                'contribuable' => [
                    'id'                  => $commercant->id,
                    'nom'                 => $commercant->nom,
                    'num_commerce'        => $commercant->num_commerce,
                    'telephone'           => $commercant->telephone,
                    'email'               => $commercant->email,
                    'adresse'             => $commercant->adresse,
                    'secteur'             => $commercant->secteur?->nom,
                    'type_contribuable'   => $commercant->typeContribuable?->libelle,
                    'photo_profil'        => $commercant->photo_profil
                        ? asset('storage/' . $commercant->photo_profil)
                        : null,
                    'qr_code_url'         => $commercant->qr_code_path
                        ? asset('storage/' . $commercant->qr_code_path)
                        : null,
                ],
                'taxes'             => $taxesAvecPeriodes,
                'total_montant_du'  => $totalMontantDu,
                'nombre_taxes_dues' => count(array_filter($taxesAvecPeriodes, fn($t) => $t['nombre_periodes_dues'] > 0)),
            ],
        ]);
    }

    /**
     * Encaisser le paiement d'une taxe.
     */
    public function encaisserPaiement(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'num_commerce' => 'required|exists:commercants,num_commerce',
            'taxe_id' => 'required|exists:taxes,id',
            'nombre' => 'required|integer|min:1',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $agent = $request->user();
        $commercant = Commercant::where('num_commerce', $request->num_commerce)->first();
        $taxe = Taxe::findOrFail($request->taxe_id);

        
        // Vérification des taxes assignées
        $assignedTaxes = $commercant->taxes->pluck('id')->toArray();
        
        if (! in_array($taxe->id, $assignedTaxes)) {
            return response()->json(['message' => "Cette taxe n'est pas assignée à ce commerçant."], 403);
        }

        $dernierPaiement = PaiementTaxe::where('num_commerce', $commercant->num_commerce)
            ->where('taxe_id', $taxe->id)
            ->orderByDesc('periode')
            ->first();
        // Parsing robuste du dernier paiement pour toutes les fréquences
        if ($dernierPaiement) {
            try {
                $lastPeriod = Carbon::createFromFormat('Y-m-d H:i:s', $dernierPaiement->periode);
            } catch (\Exception $e) {
                try {
                    $lastPeriod = Carbon::createFromFormat('d/m/Y', $dernierPaiement->periode);
                } catch (\Exception $e2) {
                    try {
                        $lastPeriod = Carbon::createFromFormat('m/Y', $dernierPaiement->periode)->startOfMonth();
                    } catch (\Exception $e3) {
                        $lastPeriod = Carbon::createFromFormat('Y', $dernierPaiement->periode)->startOfYear();
                    }
                }
            }
        } else {
            // Le paiement commence à la date de création du contribuable,
            // mais on s'assure qu'on ne remonte pas avant la date de création de la taxe elle-même.
            $dateDebut = ($commercant->created_at && $taxe->created_at && $commercant->created_at->gt($taxe->created_at))
                ? $commercant->created_at
                : ($taxe->created_at ?? Carbon::today()->startOfMonth());

            $lastPeriod = Carbon::parse($dateDebut)->startOfDay();

            // On soustrait une unité de fréquence car le CarbonPeriod::create utilisera ->addUnit() plus bas
            // Pour que le premier paiement soit bien à la date de début.
            switch ($taxe->frequence) {
                case 'jour': $lastPeriod->subDay();
                    break;
                case 'mois': $lastPeriod->subMonth();
                    break;
                case 'an': $lastPeriod->subYear();
                    break;
            }
        }

        $today = Carbon::today()->startOfDay();
        $periodesDue = [];
        switch ($taxe->frequence) {
            case 'jour':
                $period = CarbonPeriod::create($lastPeriod->copy()->addDay(), '1 day', $today);
                foreach ($period as $date) {
                    $periodesDue[] = $date->format('d/m/Y');
                }
                break;
            case 'mois':
                $period = CarbonPeriod::create($lastPeriod->copy()->addMonth(), '1 month', $today);
                foreach ($period as $date) {
                    $periodesDue[] = $date->format('m/Y');
                }
                break;
            case 'an':
                $period = CarbonPeriod::create($lastPeriod->copy()->addYear(), '1 year', $today);
                foreach ($period as $date) {
                    $periodesDue[] = $date->format('Y');
                }
                break;
            default:
                return response()->json([
                    'message' => 'Fréquence inconnue pour cette taxe.',
                    'periodes_enregistrees' => [],
                    'periodes_restantes' => [],
                ], 400);
        }

        $nombre = $request->nombre;
        $periodesPayees = array_slice($periodesDue, 0, $nombre);
        $periodesRestantes = array_slice($periodesDue, $nombre);

        $encaissementsEffectues = [];
        try {
            DB::beginTransaction();
            foreach ($periodesPayees as $periode) {
                $existe = PaiementTaxe::where('num_commerce', $commercant->num_commerce)
                    ->where('taxe_id', $taxe->id)
                    ->where('periode', $periode)
                    ->exists();
                if ($existe) {
                    continue;
                }

                PaiementTaxe::create([
                    'mairie_ref' => $commercant->mairie_ref,
                    'secteur_id' => $commercant->secteur_id,
                    'taxe_id' => $taxe->id,
                    'num_commerce' => $commercant->num_commerce,
                    'montant' => $taxe->montant,
                    'statut' => 'payé',
                    'periode' => $periode,
                ]);

                Encaissement::create([
                    'mairie_ref' => $agent->mairie_ref,
                    'agent_id' => $agent->id,
                    'taxe_id' => $taxe->id,
                    'num_commerce' => $commercant->num_commerce,
                    'montant_percu' => $taxe->montant,
                    'statut' => 'non versé',    
                ]);

                $encaissementsEffectues[] = $periode;
            }
            DB::commit();

            return response()->json([
                'message' => 'Encaissement effectué avec succès.',
                'periodes_enregistrees' => $encaissementsEffectues,
                'periodes_restantes' => $periodesRestantes,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => "Une erreur est survenue lors de l'encaissement.",
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Récupérer le dernier paiement et les périodes dues.
     */
    public function dernierPaiementEtDues(Request $request)
    {
        $request->validate([
            'taxe_id' => 'required|exists:taxes,id',
            'num_commerce' => 'required|string',
        ]);

        $taxeId = $request->taxe_id;
        $numCommerce = $request->num_commerce;

        $taxe = Taxe::findOrFail($taxeId);
        $frequence = $taxe->frequence;

        $agent = $request->user();
        
        // Rechercher le contribuable dans la mairie de l'agent
        $commercant = Commercant::where('num_commerce', $numCommerce)
            ->where('mairie_ref', $agent->mairie_ref)
            ->first();

        if (! $commercant) {
            return response()->json([
                'message' => 'Contribuable non trouvé ou n\'appartient pas à votre mairie.',
            ], 404);
        }

        $dernierPaiement = PaiementTaxe::where('num_commerce', $numCommerce)
            ->where('taxe_id', $taxeId)
            ->orderByDesc('periode')
            ->first();

        if (! $dernierPaiement) {
            // Si pas de paiement, on commence à la date de création
            $dateDebut = ($commercant->created_at && $taxe->created_at && $commercant->created_at->gt($taxe->created_at))
                ? $commercant->created_at
                : ($taxe->created_at ?? Carbon::today());
            
            $lastPeriod = Carbon::parse($dateDebut)->startOfDay();
            
            // Reculer d'une période pour commencer le calcul à la date de début
            switch ($frequence) {
                case 'jour': $lastPeriod->subDay(); break;
                case 'mois': $lastPeriod->subMonth(); break;
                case 'an':   $lastPeriod->subYear(); break;
            }
        } else {
            // Parsing robuste du dernier paiement
            try {
                $lastPeriod = Carbon::createFromFormat('Y-m-d H:i:s', $dernierPaiement->periode);
            } catch (\Exception $e) {
                try {
                    $lastPeriod = Carbon::createFromFormat('d/m/Y', $dernierPaiement->periode);
                } catch (\Exception $e2) {
                    try {
                        $lastPeriod = Carbon::createFromFormat('m/Y', $dernierPaiement->periode)->startOfMonth();
                    } catch (\Exception $e3) {
                        $lastPeriod = Carbon::createFromFormat('Y', $dernierPaiement->periode)->startOfYear();
                    }
                }
            }
        }

        $today = Carbon::today()->startOfDay();
        $periodesDue = [];

        switch ($frequence) {
            case 'jour':
                $period = CarbonPeriod::create($lastPeriod->copy()->addDay(), '1 day', $today);
                foreach ($period as $date) {
                    $periodesDue[] = $date->format('d/m/Y');
                }
                break;

            case 'mois':
                $period = CarbonPeriod::create($lastPeriod->copy()->startOfMonth()->addMonth(), '1 month', $today->copy()->startOfMonth());
                foreach ($period as $date) {
                    $periodesDue[] = $date->format('m/Y');
                }
                break;

            case 'an':
                $period = CarbonPeriod::create($lastPeriod->copy()->startOfYear()->addYear(), '1 year', $today->copy()->startOfYear());
                foreach ($period as $date) {
                    $periodesDue[] = $date->format('Y');
                }
                break;

            default:
                return response()->json([
                    'message' => 'Fréquence inconnue pour cette taxe.',
                    'dernier_paiement' => $dernierPaiement ? $dernierPaiement->periode : null,
                    'periodes_dues' => [],
                ], 400);
        }

        return response()->json([
            'dernier_paiement' => $dernierPaiement ? $dernierPaiement->periode : null,
            'frequence' => $frequence,
            'periodes_dues' => $periodesDue,
        ]);
    }

    /**
     * Déconnexion.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Déconnexion réussie']);
    }

    public function showContribuable(Request $request, $id)
    {
        $agent = $request->user();
        $commercant = Commercant::where('id', $id)->where('mairie_ref', $agent->mairie_ref)->first();

        if (! $commercant) {
            return response()->json(['success' => false, 'message' => 'Contribuable non trouvé.'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $commercant->load(['secteur', 'taxes', 'typeContribuable']),
        ]);
    }

    public function updateContribuable(Request $request, $id)
    {
        $agent = $request->user();
        if ($agent->type !== 'recouvrement') {
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
            'secteur_id' => 'sometimes|required|exists:secteurs,id',
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
            Log::error('Erreur modification commerçant API (Recouvrement): '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Une erreur interne est survenue lors de la modification.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Liste des encaissements non versés.
     */
    public function listEncaissementsNonVerses(Request $request)
    {
        $agent = $request->user();
        $encaissements = Encaissement::where('agent_id', $agent->id)
            ->where('statut', 'non versé')
            ->with(['taxe', 'commercant'])
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $encaissements,
        ]);
    }

    /**
     * Liste des encaissements versés.
     */
    public function listEncaissementsVerses(Request $request)
    {
        $agent = $request->user();
        $encaissements = Encaissement::where('agent_id', $agent->id)
            ->where('statut', 'versé')
            ->with(['taxe', 'commercant'])
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $encaissements,
        ]);
    }

    /**
     * Détails d'un encaissement spécifique par son ID.
     */
    public function showEncaissement(Request $request, $id)
    {
        $agent = $request->user();
        $encaissement = Encaissement::where('id', $id)
            ->where('agent_id', $agent->id)
            ->with(['taxe', 'commercant'])
            ->first();

        if (!$encaissement) {
            return response()->json([
                'success' => false,
                'message' => 'Encaissement non trouvé ou vous n\'en êtes pas l\'auteur.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $encaissement,
        ]);
    }
}
