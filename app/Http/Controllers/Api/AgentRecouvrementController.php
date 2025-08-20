<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Commercant;
use App\Models\Taxe;
use App\Models\PaiementTaxe;
use App\Models\Encaissement;
use App\Models\Agent;



class AgentRecouvrementController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Assurez-vous de récupérer uniquement les agents de recouvrement
        $agent = Agent::where('email', $request->email)->where('type', 'recouvrement')->first();

        if (!$agent || !Hash::check($request->password, $agent->password)) {
            return response()->json(['message' => 'Identifiants incorrects'], 401);
        }

        // Création du token avec la bonne "ability"
        $token = $agent->createToken('agent-recouvrement-token', ['agent-recouvrement'])->plainTextToken;

        return response()->json([
            'token' => $token,
            'agent' => $agent
        ]);
    }


    public function me(Request $request)
    {
        return response()->json($request->user());
    }


    public function encaisserPaiement(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'num_commerce' => 'required|exists:commercants,num_commerce',
            'taxe_id'     => 'required|exists:taxes,id',
            'nombre'      => 'required|integer|min:1',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $agent = $request->user();
        $commercant = Commercant::where('num_commerce', $request->num_commerce)->first();
        $taxe = Taxe::findOrFail($request->taxe_id);

        // dd($commercant->taxe_id);
        $assignedTaxes = json_decode($commercant->taxe_id, true) ?? [];
        if (!in_array($taxe->id, $assignedTaxes)) {
            return response()->json(['message' => "Cette taxe n'est pas assignée à ce commerçant."], 403);
        }

        $dernierPaiement = PaiementTaxe::where('num_commerce', $commercant->num_commerce)
                            ->where('taxe_id', $taxe->id)
                            ->orderByDesc('periode')
                            ->first();
        if ($dernierPaiement) {
            try {
                $lastPeriod = Carbon::createFromFormat('Y-m', $dernierPaiement->periode)->startOfMonth();
            } catch (\Exception $e) {
                $lastPeriod = Carbon::createFromFormat('d/m/Y', $dernierPaiement->periode);
            }
        } else {
            $lastPeriod = $commercant->created_at ?? Carbon::today()->startOfMonth();
            $lastPeriod = Carbon::parse($lastPeriod)->startOfDay();
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
                    'periodes_restantes' => []
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
                    'mairie_id'    => $commercant->mairie_id,
                    'secteur_id'   => $commercant->secteur_id,
                    'taxe_id'      => $taxe->id,
                    'num_commerce' => $commercant->num_commerce,
                    'montant'      => $taxe->montant,
                    'statut'       => 'payé',
                    'periode'      => $periode,
                ]);

                Encaissement::create([
                    'mairie_id'    => $commercant->mairie_id,
                    'agent_id'     => $agent->id,
                    'taxe_id'      => $taxe->id,
                    'num_commerce' => $commercant->num_commerce,
                    'montant_verse'=> $taxe->montant,
                    'statut'       => 'versé',
                ]);

                $encaissementsEffectues[] = $periode;
            }
            DB::commit();

            return response()->json([
                'message'             => 'Encaissement effectué avec succès.',
                'periodes_enregistrees' => $encaissementsEffectues,
                'periodes_restantes'  => $periodesRestantes,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => "Une erreur est survenue lors de l'encaissement.",
                'error'   => $e->getMessage()
            ], 500);
        }
    }


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

        $commercant = $request->user();
        $encaissements = $commercant->encaissements;

        $match = $encaissements->firstWhere('num_commerce', $numCommerce);
        if (!$match) {
            return response()->json([
                'message' => 'Ce numéro de commerce ne vous appartient pas.',
            ], 403);
        }

        $dernierPaiement = PaiementTaxe::where('num_commerce', $numCommerce)
                                        ->where('taxe_id', $taxeId)
                                        ->orderByDesc('periode')
                                        ->first();

        if (!$dernierPaiement) {
            return response()->json([
                'message' => 'Aucun paiement trouvé pour ce commerçant pour cette taxe.',
                'dernier_paiement' => null,
                'periodes_dues' => []
            ], 200);
        }

        $lastPeriod = Carbon::createFromFormat('Y-m', $dernierPaiement->periode)->startOfMonth();
        $today = Carbon::today()->startOfMonth();
        $periodesDue = [];

        switch ($frequence) {
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
                    'dernier_paiement' => $lastPeriod->format('m/Y'),
                    'periodes_dues' => []
                ]);
        }

        return response()->json([
            'dernier_paiement' => $lastPeriod->format('m/Y'),
            'frequence' => $frequence,
            'periodes_dues' => $periodesDue,
        ]);
    }


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Déconnexion réussie']);
    }
}