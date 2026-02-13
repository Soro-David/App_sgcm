<?php

namespace App\Http\Controllers\Mairie;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Commercant;
use App\Models\Encaissement;
use App\Models\Finance;
use App\Models\Mairie;
use App\Models\PaiementTaxe;
use App\Models\Taxe;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class EncaissementController extends Controller
{
    public function index()
    {
        $user = Auth::guard('mairie')->user() ?: Auth::guard('finance')->user();
        if (! $user) {
            return redirect()->route('login.mairie');
        }
        $mairie_ref = $user->mairie_ref;

        $agents = Agent::where('mairie_ref', $mairie_ref)
            ->where('type', 'recouvrement')
            ->get();

        // Récupérer les utilisateurs mairie ayant un rôle financier ou caisse
        $mairieStaff = Mairie::where('mairie_ref', $mairie_ref)
            ->whereIn('role', ['caisse', 'caissier', 'caisié', 'Caissier'])
            ->get();

        // Récupérer les utilisateurs finance
        $financeStaff = Finance::where('mairie_ref', $mairie_ref)->get();

        // Fusionner les populations pour le filtre
        $cashiers = $mairieStaff->concat($financeStaff);

        // Statistiques globales pour la mairie
        $totalCount = Encaissement::where('mairie_ref', $mairie_ref)->count();
        $totalAmount = Encaissement::where('mairie_ref', $mairie_ref)->sum('montant_percu');

        return view('mairie.encaissements.index', compact('agents', 'cashiers', 'totalCount', 'totalAmount'));
    }

    /**
     * Fournit les données des encaissements pour DataTables via AJAX.
     */
    public function get_list_encaissement(Request $request)
    {
        if (! $request->ajax()) {
            abort(403, 'Accès non autorisé');
        }

        try {
            $user = Auth::guard('mairie')->user() ?: Auth::guard('finance')->user();
            if (! $user) {
                return response()->json(['error' => 'Non authentifié'], 401);
            }
            $mairie_ref = $user->mairie_ref;

            $query = Encaissement::where('mairie_ref', $mairie_ref)
                ->with([
                    'agent:id,name',
                    'taxe:id,nom',
                    'commercant:id,nom,num_commerce',
                ])
                ->select('encaissements.*')
                ->orderBy('created_at', 'desc');

            return DataTables::of($query)
                ->editColumn('created_at', function ($encaissement) {
                    return $encaissement->created_at ? $encaissement->created_at->format('d/m/Y à H:i') : 'N/A';
                })
                ->addColumn('agent_nom', function ($encaissement) {
                    if ($encaissement->agent) {
                        return e($encaissement->agent->name);
                    } elseif ($encaissement->recorder) {
                        return e($encaissement->recorder->name).' <small class="badge bg-light text-dark">Caisse</small>';
                    }

                    return '<span class="text-muted">Inconnu</span>';
                })
                ->addColumn('taxe_nom', function ($encaissement) {
                    return $encaissement->taxe ? e($encaissement->taxe->nom) : '<span class="text-muted">Taxe non définie</span>';
                })
                ->addColumn('commercant_info', function ($encaissement) {
                    $commercantNom = $encaissement->commercant ? e($encaissement->commercant->nom) : 'Commerçant inconnu';

                    return $commercantNom.' <br><small class="text-muted">'.e($encaissement->num_commerce).'</small>';
                })
                ->editColumn('montant_percu', function ($encaissement) {
                    return '<b>'.number_format($encaissement->montant_percu, 0, ',', ' ').' FCFA</b>';
                })
                ->editColumn('statut', function ($encaissement) {
                    $badgeClass = $encaissement->statut === 'encaisse' ? 'bg-success' : 'bg-warning';

                    return '<span class="badge '.$badgeClass.'">'.e(ucfirst($encaissement->statut)).'</span>';
                })
                ->rawColumns(['agent_nom', 'taxe_nom', 'commercant_info', 'montant_percu', 'statut'])
                ->make(true);

        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement des encaissements : '.$e->getMessage().' à la ligne '.$e->getLine());

            return response()->json(['error' => 'Erreur lors du chargement des données. Veuillez contacter le support.'], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Fournit les encaissements groupés par agent et date.
     */
    public function get_grouped_encaissements(Request $request)
    {
        if (! $request->ajax()) {
            abort(403, 'Accès non autorisé');
        }

        try {
            $user = Auth::guard('mairie')->user() ?: Auth::guard('finance')->user();
            if (! $user) {
                return response()->json(['error' => 'Non authentifié'], 401);
            }
            $mairie_ref = $user->mairie_ref;

            $query = Encaissement::where('encaissements.mairie_ref', $mairie_ref)
                ->leftJoin('agents', 'encaissements.agent_id', '=', 'agents.id')
                ->leftJoin('mairies', 'encaissements.recorded_by', '=', 'mairies.id')
                ->select(
                    DB::raw('DATE(encaissements.created_at) as date_encaissement'),
                    'encaissements.agent_id',
                    'encaissements.recorded_by',
                    DB::raw('COALESCE(agents.name, mairies.name) as agent_nom'),
                    DB::raw('COUNT(encaissements.id) as nb_encaissements'),
                    DB::raw('SUM(encaissements.montant_percu) as total_percu')
                )
                ->groupBy('date_encaissement', 'encaissements.agent_id', 'encaissements.recorded_by', 'agent_nom')
                ->orderBy('date_encaissement', 'desc');

            // Filtres
            if ($request->agent_id) {
                $query->where('encaissements.agent_id', $request->agent_id);
            }
            if ($request->recorded_by) {
                $query->where('encaissements.recorded_by', $request->recorded_by);
            }

            return DataTables::of($query)
                ->editColumn('date_encaissement', function ($row) {
                    return Carbon::parse($row->date_encaissement)->format('d/m/Y');
                })
                ->editColumn('total_percu', function ($row) {
                    return '<b>'.number_format($row->total_percu, 0, ',', ' ').' FCFA</b>';
                })
                ->addColumn('action', function ($row) {
                    return '
                        <div class="d-flex justify-content-center">
                            <button class="btn btn-sm btn-info btn-detail"
                                data-agent-id="'.$row->agent_id.'" 
                                data-recorded-by="'.$row->recorded_by.'"
                                data-date="'.$row->date_encaissement.'"
                                data-agent-name="'.e($row->agent_nom).'">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['total_percu', 'action'])
                ->make(true);

        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement des encaissements groupés : '.$e->getMessage());

            return response()->json(['error' => 'Erreur serveur'], 500);
        }
    }

    /**
     * Récupère les détails des encaissements pour un agent et une date donnés.
     */
    public function get_details_encaissement(Request $request)
    {
        if (! $request->ajax()) {
            abort(403);
        }

        // On peut avoir soit agent_id soit recorded_by
        $validator = Validator::make($request->all(), [
            'agent_id' => 'nullable',
            'recorded_by' => 'nullable',
            'date' => 'required|date',
        ]);

        if ($validator->fails() || (! $request->agent_id && ! $request->recorded_by)) {
            return response()->json(['error' => 'Paramètres invalides'], 400);
        }

        try {
            $user = Auth::guard('mairie')->user() ?: Auth::guard('finance')->user();
            $mairie_ref = $user->mairie_ref;

            $encaissements = Encaissement::where('mairie_ref', $mairie_ref)
                ->whereDate('created_at', $request->date);

            if ($request->agent_id && $request->agent_id !== 'null') {
                $encaissements->where('agent_id', $request->agent_id);
            } elseif ($request->recorded_by && $request->recorded_by !== 'null') {
                $encaissements->where('recorded_by', $request->recorded_by);
            }

            $encaissements = $encaissements->with(['commercant', 'taxe'])->get();

            $data = $encaissements->map(function ($e) {
                return [
                    'date_heure' => $e->created_at->format('d/m/Y H:i'),
                    'commercant_nom' => $e->commercant ? $e->commercant->nom : 'Inconnu',
                    'num_commerce' => $e->commercant ? $e->commercant->num_commerce : ($e->num_commerce ?? 'Numéro de Commerce non trouvé'),
                    'taxe_nom' => $e->taxe ? $e->taxe->nom : 'Taxe non trouvée',
                    'montant' => $e->montant_percu,
                ];
            });

            return response()->json([
                'data' => $data,
                'total' => $encaissements->sum('montant_percu'),
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur détails encaissement : '.$e->getMessage());

            return response()->json(['error' => 'Erreur serveur'], 500);
        }
    }

    /**
     * Affiche la liste des encaissements propres au caissier connecté.
     */
    public function mes_encaissements()
    {
        return view('mairie.encaissements.mes_encaissements');
    }

    /**
     * Fournit les données des encaissements du caissier connecté pour DataTables.
     */
    public function get_mes_encaissements(Request $request)
    {
        if (! $request->ajax()) {
            abort(403);
        }

        try {
            $user = Auth::guard('mairie')->user() ?: Auth::guard('finance')->user();
            $mairie_ref = $user->mairie_ref;

            $query = Encaissement::where('mairie_ref', $mairie_ref)
                ->where('recorded_by', $user->id)
                ->with(['taxe:id,nom', 'commercant:id,nom,num_commerce'])
                ->select('encaissements.*')
                ->orderBy('created_at', 'desc');

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('created_at', function ($e) {
                    return $e->created_at->format('d/m/Y H:i');
                })
                ->addColumn('commercant_info', function ($e) {
                    return ($e->commercant ? $e->commercant->nom : 'Inconnu').' ('.$e->num_commerce.')';
                })
                ->editColumn('montant_percu', function ($e) {
                    return number_format($e->montant_percu, 0, ',', ' ').' FCFA';
                })
                ->addColumn('taxe_nom', function ($e) {
                    return $e->taxe ? $e->taxe->nom : 'N/A';
                })
                ->make(true);

        } catch (\Exception $e) {
            Log::error('Erreur mes encaissements : '.$e->getMessage());

            return response()->json(['error' => 'Erreur serveur'], 500);
        }
    }

    /**
     * Affiche la page de recherche pour le caissier.
     */
    public function caisse_index()
    {
        return view('mairie.encaissements.caisse_index');
    }

    /**
     * Recherche un contribuable par numcommerce, nom ou mail.
     */
    public function search_contribuable(Request $request)
    {
        $query = $request->input('query');
        $user = Auth::guard('mairie')->user() ?: Auth::guard('finance')->user();
        if (! $user) {
            return response()->json(['error' => 'Non authentifié'], 401);
        }
        $mairie_ref = $user->mairie_ref;

        $contribuable = Commercant::where('mairie_ref', $mairie_ref)
            ->where(function ($q) use ($query) {
                $q->where('num_commerce', 'like', "%{$query}%")
                    ->orWhere('nom', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%");
            })
            ->first();

        if ($contribuable) {
            return response()->json([
                'success' => true,
                'redirect' => route('mairie.caisse.faire_encaissement', $contribuable->id),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Aucun contribuable trouvé avec ces informations.',
        ]);
    }

    /**
     * Affiche le formulaire d'encaissement pour un contribuable spécifique.
     */
    public function faire_encaissement($id)
    {
        $user = Auth::guard('mairie')->user() ?: Auth::guard('finance')->user();
        $mairie_ref = $user->mairie_ref;

        $commercant = Commercant::where('mairie_ref', $mairie_ref)->findOrFail($id);

        // Récupérer toutes les taxes de cette mairie
        $taxesMairie = Taxe::where('mairie_ref', $mairie_ref)->get();

        $taxesCommercant = $commercant->taxes()->get();

        // Récupérer les agents de recouvrement
        $agents = Agent::where('mairie_ref', $mairie_ref)
            ->where('type', 'recouvrement')
            ->get();

        return view('mairie.encaissements.faire_encaissement', compact('commercant', 'taxesCommercant', 'taxesMairie', 'agents'));
    }

    /**
     * Fournit les détails d'une taxe pour un commerçant (via AJAX) - similaire à Agent.
     */
    public function getTaxeDetails(Request $request, $commercantId, $taxeId)
    {
        $user = Auth::guard('mairie')->user() ?: Auth::guard('finance')->user();
        $mairie_ref = $user->mairie_ref;

        $commercant = Commercant::where('mairie_ref', $mairie_ref)->findOrFail($commercantId);
        $taxe = Taxe::where('mairie_ref', $mairie_ref)->findOrFail($taxeId);

        $unpaidPeriods = $this->getUnpaidPeriodsAsDates($taxe, $commercant, null);

        return response()->json([
            'success' => true,
            'montant' => $taxe->montant,
            'frequence' => $taxe->frequence,
            'unpaid_count' => count($unpaidPeriods),
        ]);
    }

    /**
     * Source: App\Http\Controllers\Agent\EncaissementController
     */
    private function getUnpaidPeriodsAsDates(Taxe $taxe, Commercant $commercant, ?int $limit): array
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
            // Le paiement commence à la date de création du contribuable,
            // mais on s'assure qu'on ne remonte pas avant la date de création de la taxe elle-même.
            $dateDebut = $commercant->created_at->gt($taxe->created_at)
                ? $commercant->created_at
                : $taxe->created_at;

            $periodeCourante = Carbon::parse($dateDebut);
        }

        $now = Carbon::now()->startOfDay();
        $periodeCourante->startOfDay();
        $periodes = [];

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
     * Enregistre le paiement (soumission du formulaire d'encaissement).
     */
    public function store_encaissement(Request $request, $id)
    {
        $validatedData = $request->validate([
            'taxe_id' => 'required|integer|exists:taxes,id',
            'nombre_periodes' => 'required|integer|min:1',
            'num_commerce' => 'required|string',
            'agent_id' => 'nullable|integer|exists:agents,id',
        ]);

        $user = Auth::guard('mairie')->user() ?: Auth::guard('finance')->user();
        if (! $user) {
            return response()->json(['error' => 'Non authentifié'], 401);
        }
        $mairie_ref = $user->mairie_ref;

        $commercant = Commercant::where('mairie_ref', $mairie_ref)->findOrFail($id);

        if ($commercant->num_commerce !== $validatedData['num_commerce']) {
            return response()->json(['success' => false, 'message' => 'Erreur: Le numéro de commerce ne correspond pas.'], 400);
        }

        $taxe = Taxe::where('mairie_ref', $mairie_ref)->find($validatedData['taxe_id']);
        $nombrePeriodesAPayer = $validatedData['nombre_periodes'];

        DB::beginTransaction();
        try {
            $periodesAPayer = $this->getUnpaidPeriodsAsDates($taxe, $commercant, $nombrePeriodesAPayer);

            $montantTotalEncaisse = 0;

            foreach ($periodesAPayer as $periode) {
                PaiementTaxe::create([
                    'secteur_id' => $commercant->secteur_id,
                    'taxe_id' => $taxe->id,
                    'mairie_ref' => $mairie_ref,
                    'num_commerce' => $commercant->num_commerce,
                    'montant' => $taxe->montant,
                    'statut' => 'payé',
                    'periode' => $periode->toDateString(),
                ]);
                $montantTotalEncaisse += $taxe->montant;
            }

            if ($montantTotalEncaisse > 0) {
                Encaissement::create([
                    'montant_percu' => $montantTotalEncaisse,
                    'agent_id' => $validatedData['agent_id'] ?? null,
                    'recorded_by' => $user->id,
                    'mairie_ref' => $mairie_ref,
                    'taxe_id' => $taxe->id,
                    'num_commerce' => $commercant->num_commerce,
                    'statut' => 'encaisse',
                ]);
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Paiement de '.count($periodesAPayer).' période(s) enregistré avec succès.']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur d'encaissement mairie: ".$e->getMessage());

            return response()->json(['success' => false, 'message' => 'Une erreur interne est survenue.'], 500);
        }
    }
}
