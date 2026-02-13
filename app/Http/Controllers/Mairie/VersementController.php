<?php

namespace App\Http\Controllers\Mairie;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Encaissement;
use App\Models\Mairie;
use App\Models\Versement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class VersementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return view('mairie.versement.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::guard('mairie')->user() ?: Auth::guard('finance')->user();
        $mairieRef = $user ? $user->mairie_ref : null;

        // Récupère les IDs des agents de la mairie
        $agentIds = Agent::where('mairie_ref', $mairieRef)->pluck('id');

        // Récupère les IDs uniques des agents ayant au moins un encaissement
        $encaissementAgentIds = Encaissement::whereIn('agent_id', $agentIds)
            ->pluck('agent_id')
            ->unique();

        // Récupère les agents correspondants avec leurs noms
        $agents = Agent::whereIn('id', $encaissementAgentIds)->get();

        return view('mairie.versement.create', compact('agents'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|exists:agents,id',
            'montant_verse' => 'required|numeric|min:0',
        ]);

        $user = Auth::guard('mairie')->user() ?: Auth::guard('finance')->user();
        $mairieRef = $user ? $user->mairie_ref : null;
        $recordedBy = $user ? $user->name : 'Système';

        // Récupérer la dette précédente (reste du dernier versement)
        $dernierVersement = Versement::where('agent_id', $request->agent_id)
            ->where('mairie_ref', $mairieRef)
            ->orderByDesc('created_at')
            ->first();
        $previousDebt = $dernierVersement ? $dernierVersement->reste : 0;

        // Récupérer les encaissements non encore versés
        $encaissementsQuery = Encaissement::where('agent_id', $request->agent_id)
            ->where('mairie_ref', $mairieRef)
            ->where('statut', 'non versé');

        $montantPercu = $encaissementsQuery->sum('montant_percu');
        $totalDue = $montantPercu + $previousDebt;
        $reste = max(0, $totalDue - $request->montant_verse);

        // Calcul de l'appréciation
        $appreciation = 'Faible';
        if ($totalDue > 0) {
            $pourcentage = ($request->montant_verse / $totalDue) * 100;
            if ($pourcentage >= 100) {
                $appreciation = 'Excellent';
            } elseif ($pourcentage >= 75) {
                $appreciation = 'Bon';
            } elseif ($pourcentage >= 50) {
                $appreciation = 'Moyen';
            }
        } else {
            $appreciation = 'Excellent';
        }

        $agent = Agent::find($request->agent_id);
        $nomVersement = 'Versement de '.($agent ? $agent->name : 'Agent inconnu');

        // Création du versement
        $versement = Versement::create([
            'agent_id' => $request->agent_id,
            'nom_versement' => $nomVersement,
            'mairie_ref' => $mairieRef,
            'montant_percu' => $montantPercu,
            'total_due' => $totalDue,
            'previous_debt' => $previousDebt,
            'montant_verse' => $request->montant_verse,
            'reste' => $reste,
            'recorded_by' => $recordedBy,
            'appreciation' => $appreciation,
        ]);

        // Mise à jour de tous les encaissements qui ont été pris en compte
        $encaissementsQuery->update(['statut' => 'versé']);

        return redirect()->route('mairie.versements.index')->with('success', 'Versement enregistré avec succès. Appréciation : '.$appreciation);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('mairie.versement.show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('mairie.versement.edit');
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
     * Fournit la liste des versements pour DataTables avec un traitement serveur optimisé.
     */
    public function get_liste_versement(Request $request)
    {
        if (! $request->ajax()) {
            abort(403);
        }

        $user = Auth::guard('mairie')->user() ?: Auth::guard('finance')->user();
        $mairieRef = $user ? $user->mairie_ref : null;

        $query = Versement::where('mairie_ref', $mairieRef)
            ->with('agent:id,name')
            ->select('versements.*');

        return DataTables::of($query)
            ->addColumn('nom_agent', function ($versement) {
                return $versement->agent ? '<b>'.e($versement->agent->name).'</b>' : '<span class="text-danger">Agent supprimé</span>';
            })
            ->editColumn('created_at', function ($versement) {
                return $versement->created_at->format('d/m/Y à H:i');
            })
            ->editColumn('total_due', function ($versement) {
                return '<b>'.number_format($versement->total_due, 0, ',', ' ').' FCFA</b>';
            })
            ->editColumn('montant_verse', function ($versement) {
                return number_format($versement->montant_verse, 0, ',', ' ').' FCFA';
            })
            ->editColumn('reste', function ($versement) {
                return '<b class="text-danger">'.number_format($versement->reste, 0, ',', ' ').' FCFA</b>';
            })
            ->addColumn('recorded_by_name', function ($versement) {
                return e($versement->recorded_by ?? 'N/A');
            })

            ->rawColumns(['reste', 'nom_agent', 'total_due'])
            ->make(true);
    }

    public function get_montant_non_verse($agent_id)
    {
        $user = Auth::guard('mairie')->user() ?: Auth::guard('finance')->user();
        $mairieRef = $user ? $user->mairie_ref : null;

        $dernierVersement = Versement::where('agent_id', $agent_id)
            ->where('mairie_ref', $mairieRef)
            ->orderByDesc('created_at')
            ->first();

        $dette = $dernierVersement ? $dernierVersement->reste : 0;

        // Récupérer les encaissements non encore versés pour cet agent dans cette mairie
        $encaissements = Encaissement::where('agent_id', $agent_id)
            ->where('mairie_ref', $mairieRef)
            ->where('statut', 'non versé')
            ->with(['taxe', 'commercant'])
            ->get();

        $montantPercu = $encaissements->sum('montant_percu');

        return response()->json([
            'montant' => $montantPercu,
            'dette' => $dette,
            'encaissements' => $encaissements,
        ]);
    }
}
