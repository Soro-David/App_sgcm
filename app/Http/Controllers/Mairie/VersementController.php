<?php

namespace App\Http\Controllers\Mairie;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mairie;
use App\Models\Agent;
use App\Models\Taxe;
use App\Models\Secteur;
use App\Models\Encaissement;
use App\Models\Versement;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

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
        $mairieId = Auth::guard('mairie')->id();

        // dd($mairieId);
        // Récupère les IDs des agents de la mairie
        $agentIds = Agent::where('mairie_id', $mairieId)->pluck('id');

        // Récupère les IDs uniques des agents ayant au moins un encaissement
        $encaissementAgentIds = Encaissement::whereIn('agent_id', $agentIds)
            ->pluck('agent_id')
            ->unique();

        // dd($encaissementAgentIds);

        // Récupère les agents correspondants avec leurs noms
        $agents = Agent::whereIn('id', $encaissementAgentIds)->get();
        // dd($agents);

        return view('mairie.versement.create', compact('agents'));
    }


    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    $request->validate([
        'agent_id' => 'required|exists:agents,id',
        'montant_percu' => 'required|numeric|min:0',
        'montant_verse' => 'required|numeric|min:0',
        'reste' => 'nullable|numeric|min:0',
    ]);

    $mairieId = Auth::guard('mairie')->id();

    $reste = $request->dette + ($request->montant_percu - $request->montant_verse);

    // Création du versement
    $versement = Versement::create([
        'agent_id' => $request->agent_id,
        'mairie_id' => $mairieId,
        'montant_percu' => $request->montant_percu,
        'montant_verse' => $request->montant_verse,
        'reste' => $reste,
    ]);

    // Mise à jour de tous les encaissements "non verser" de cet agent
    Encaissement::where('agent_id', $request->agent_id)
        ->where('statut', 'non versé')
        ->update(['statut' => 'versé']);

    return redirect()->route('mairie.versements.index')->with('success', 'Versement enregistré et encaissements mis à jour.');
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

    public function get_liste_versement(Request $request)
    {
        $mairieId = auth()->guard('mairie')->id();

        $versements = Versement::with('agent')
            ->whereHas('agent', function ($query) use ($mairieId) {
                $query->where('mairie_id', $mairieId);
            })
            ->latest()
            ->get();

        return datatables()->of($versements)
            ->addColumn('nom_agent', function ($versement) {
                return $versement->agent ? $versement->agent->nom . ' ' . $versement->agent->prenom : '';
            })
            ->addColumn('date_creation', function ($versement) {
                return $versement->created_at->format('d/m/Y H:i');
            })
            ->addColumn('montant_percu', function ($versement) {
                return number_format($versement->montant_percu, 0, ',', ' ') . ' F';
            })
            ->addColumn('montant_verse', function ($versement) {
                return number_format($versement->montant_verse, 0, ',', ' ') . ' F';
            })
            ->addColumn('reste', function ($versement) {
                $reste = $versement->montant_percu - $versement->montant_verse;
                return number_format($reste, 0, ',', ' ') . ' F';
            })
            ->addColumn('actions', function ($versement) {
                return '<a href="#" class="btn btn-sm btn-info">Voir</a>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }


    public function get_montant_non_verse($agent_id)
    {
        // Récupère le dernier versement effectué par cet agent (le plus récent)
        $dernierVersement = Versement::where('agent_id', $agent_id)
            ->orderByDesc('created_at')
            ->first();

        // On récupère 'reste' mais on le renomme en 'dette' dans la réponse
        $dette = $dernierVersement ? $dernierVersement->reste : 0;

        // Somme des encaissements non encore versés
        $montantPercu = Encaissement::where('agent_id', $agent_id)
            ->where('statut', 'non verser')
            ->sum('montant_verse');

        return response()->json([
            'montant' => $montantPercu,
            'dette' => $dette  // <- même si ça vient de 'reste', on continue d'appeler ça 'dette' côté JS/vue
        ]);
    }




}
