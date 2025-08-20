<?php

namespace App\Http\Controllers\Mairie;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Mairie;
use App\Models\Agent;
use App\Models\Taxe;
use App\Models\Secteur;
use App\Models\Encaissement;

class EncaissementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        return view('mairie.encaissements.index');
    }



    /**
     * Fournit les données des encaissements pour DataTables via AJAX.
     */
    public function get_list_encaissement(Request $request)
    {
        if (!$request->ajax()) {
            abort(403, 'Accès non autorisé');
        }

        try {
            $mairieId = Auth::guard('mairie')->id();

            $query = Encaissement::where('mairie_id', $mairieId)
                ->with([
                    'agent:id,name',
                    'taxe:id,nom', 
                    'commercant:id,nom,num_commerce'
                ])
                ->select('encaissements.*') 
                ->orderBy('created_at', 'desc');

            return DataTables::of($query)
                ->editColumn('created_at', function ($encaissement) {
                    return $encaissement->created_at ? $encaissement->created_at->format('d/m/Y à H:i') : 'N/A';
                })
                ->addColumn('agent_nom', function ($encaissement) {
                    return $encaissement->agent ? e($encaissement->agent->name) : '<span class="text-muted">Agent non trouvé</span>';
                })
                ->addColumn('taxe_nom', function ($encaissement) {
                    return $encaissement->taxe ? e($encaissement->taxe->nom) : '<span class="text-muted">Taxe non définie</span>';
                })
                 ->addColumn('commercant_info', function ($encaissement) {
                    $commercantNom = $encaissement->commercant ? e($encaissement->commercant->nom) : 'Commerçant inconnu';
                    return $commercantNom . ' <br><small class="text-muted">' . e($encaissement->num_commerce) . '</small>';
                })
                ->editColumn('montant_percu', function ($encaissement) {
                    return '<b>' . number_format($encaissement->montant_percu, 0, ',', ' ') . ' FCFA</b>';
                })
                ->editColumn('statut', function ($encaissement) {
                    $badgeClass = $encaissement->statut === 'encaisse' ? 'bg-success' : 'bg-warning';
                    return '<span class="badge ' . $badgeClass . '">' . e(ucfirst($encaissement->statut)) . '</span>';
                })
                ->rawColumns(['agent_nom', 'taxe_nom', 'commercant_info', 'montant_percu', 'statut'])
                ->make(true);

        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement des encaissements : ' . $e->getMessage() . ' à la ligne ' . $e->getLine());
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
}
