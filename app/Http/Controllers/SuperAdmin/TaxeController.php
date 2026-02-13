<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Mairie;
use App\Models\Taxe;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TaxeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return view('superAdmin.taxe.index');
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
        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'montant' => 'nullable|numeric',
        ]);

        $mairie_ref = $request->mairie_ref;
        // dd($request->all(), $mairie_ref);
        try {
            Taxe::create($request->only(['nom', 'description', 'montant', 'mairie_ref']));

            return redirect()->route('superadmin.taxes.index')
                ->with('success', 'Taxe ajoutée avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de l\'ajout de la taxe : '.$e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function edit_taxe($id)
    {
        $taxe = Taxe::with('mairie')->findOrFail($id);
        $mairies = Mairie::all();
        $taxes = Taxe::with('mairie')->whereNotNull('mairie_ref')->get();

        return view('superAdmin.taxe.edit_taxe', compact('taxe', 'mairies', 'taxes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'nom' => 'required',
            'mairie_ref' => 'required|exists:mairies,mairie_ref',
            'montant' => 'nullable|numeric',
            'description' => 'nullable|string',
        ]);

        $taxe = new Taxe;
        $taxe->nom = $request->nom;
        $taxe->description = $request->description;
        $taxe->montant = $request->montant;
        $taxe->mairie_ref = $request->mairie_ref;
        $taxe->save();

        return redirect()->route('superadmin.taxes.index')->with('success', 'Taxe ajoutée avec succès.');
    }

    public function destroy(string $id)
    {
        //
    }

    public function get_list_taxes(Request $request)
    {
        try {
            if (! $request->ajax()) {
                return response()->json(['error' => 'Requête non autorisée.'], 403);
            }

            // Récupère les noms uniques avec le plus ancien ID et la date de création correspondante
            $query = Taxe::selectRaw('MIN(id) as id, nom, MIN(montant) as montant, MIN(created_at) as created_at')
                ->groupBy('nom')
                ->orderBy('nom');

            return DataTables::of($query)
                ->editColumn('created_at', function ($taxe) {
                    return $taxe->created_at ? date('d/m/Y H:i', strtotime($taxe->created_at)) : 'N/A';
                })
                ->addColumn('action', function ($taxe) {
                    $editUrl = route('superadmin.taxes.edit', $taxe->id);
                    $deleteUrl = route('superadmin.taxes.destroy', $taxe->id);

                    return '<div class="action-buttons">
                                <a href="'.$editUrl.'" class="btn-table-action view" title="Voir les détails">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="'.$editUrl.'" class="btn-table-action edit" title="Modifier la taxe">
                                    <i class="fa fa-pencil-alt"></i>
                                </a>
                                <button class="btn-table-action delete btn-delete" data-url="'.$deleteUrl.'" title="Supprimer la taxe">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>';
                })
                ->rawColumns(['action'])
                ->make(true);

        } catch (\Exception $e) {
            \Log::error('Erreur DataTable taxe : '.$e->getMessage());

            return response()->json([
                'error' => 'Erreur serveur',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function get_infos_mairie($id)
    {
        $mairie = Mairie::findOrFail($id);

        return response()->json([
            'region' => $mairie->region,
            'commune' => $mairie->name,
        ]);
    }

    // ...

    public function get_mairie_taxe_list(Request $request)
    {
        if ($request->ajax()) {
            $mairies = Mairie::whereHas('taxes')->with('commune.region'); // Eager loading pour la performance

            return DataTables::of($mairies)
                ->addColumn('action', function ($mairie) {
                    return '<div class="action-buttons">
                                <a href="'.route('superadmin.mairies.show', $mairie->id).'" class="btn-table-action view" title="Voir les détails">
                                    <i class="fa fa-eye"></i>
                                </a>
                            </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        // Si la requête n'est pas AJAX, vous pouvez retourner une erreur ou rediriger
        return abort(403, 'Accès non autorisé');
    }
}
