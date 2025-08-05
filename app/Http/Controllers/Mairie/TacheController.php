<?php

namespace App\Http\Controllers\Mairie;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mairie;
use App\Models\Agent;
use App\Models\Taxe;
use App\Models\Secteur;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;


class TacheController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mairieId = Auth::guard('mairie')->id();

        $agents = Agent::where('mairie_id', $mairieId)->get();
        $secteurs = Secteur::where('mairie_id', $mairieId)->get();
        $taxes = Taxe::where('mairie_id', $mairieId)->get();

        return view('mairie.tache.index',compact('agents','secteurs','taxes'));
    }

    public function list_tache()
    {
        return view('mairie.tache.list');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mairieId = Auth::guard('mairie')->id();

        return view('mairie.tache.create');
    }

    public function store(Request $request)
    {

    }
    
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


    public function get_list_taches(Request $request)
    {
        if (!$request->ajax()) {
            return abort(403, 'Accès non autorisé');
        }

        try {
            $mairieId = Auth::guard('mairie')->id();

            // Récupère uniquement les taxes liées à la mairie authentifiée
            $taxes = Taxe::where('mairie_id', $mairieId)
                ->orderBy('created_at', 'desc');

            return DataTables::of($taxes)
                ->addColumn('action', function ($taxe) {
                    $detailsButton = '<a href="' . route('mairie.taches.show', $taxe->id) . '" class="btn btn-sm btn-info"><i class="fas fa-eye"></i> Voir</a>';
                    return $detailsButton;
                })
                ->editColumn('created_at', function ($taxe) {
                    return $taxe->created_at ? $taxe->created_at->format('d/m/Y H:i') : 'N/A';
                })
                ->editColumn('nom', function ($taxe) {
                    return e($taxe->nom);
                })
                ->rawColumns(['action'])
                ->make(true);

        } catch (\Exception $e) {
            \Log::error('Erreur lors du chargement des taxes : ' . $e->getMessage());
            return response()->json([
                'error' => 'Erreur lors du chargement des taxes.'
            ], 500);
        }
    }
}
