<?php

namespace App\Http\Controllers\Mairie;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

use App\Models\Mairie;
use App\Models\Taxe;


class TaxeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return view('mairie.taxe.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mairieId = Auth::guard('mairie')->id();

        return view('mairie.taxe.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'montant' => 'nullable|numeric|min:0',
            'frequence' => 'required|in:jour,mois,an',
            'mairie_id' => 'required|exists:mairies,id',
        ]);

        Taxe::create([
            'nom' => $request->nom,
            'description' => $request->description,
            'montant' => $request->montant,
            'frequence' => $request->frequence,
            'mairie_id' => $request->mairie_id,
        ]);

        return redirect()->back()->with('success', 'La taxe a été ajoutée avec succès.');
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
        $taxes = Taxe::with('mairie')->whereNotNull('mairie_id')->get();

        return view('superAdmin.taxe.edit_taxe', compact('taxe', 'mairies', 'taxes'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'nom' => 'required',
            'mairie_id' => 'required|exists:mairies,id',
            'montant' => 'nullable|numeric',
            'description' => 'nullable|string',
        ]);

        $taxe = new Taxe();
        $taxe->nom = $request->nom;
        $taxe->description = $request->description;
        $taxe->montant = $request->montant;
        $taxe->mairie_id = $request->mairie_id;
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
            if (!$request->ajax()) {
                return response()->json(['error' => 'Requête non autorisée.'], 403);
            }

            // Récupère les noms uniques avec le plus ancien ID et la date de création correspondante
            $query = Taxe::selectRaw('MIN(id) as id, nom, MIN(created_at) as created_at')
                ->groupBy('nom')
                ->orderBy('nom');

            return DataTables::of($query)
                ->editColumn('created_at', function ($taxe) {
                    return $taxe->created_at ? date('d/m/Y H:i', strtotime($taxe->created_at)) : 'N/A';
                })
                ->addColumn('action', function ($taxe) {
                    $editUrl = route('superadmin.taxes.edit', $taxe->id);
                    $deleteUrl = route('superadmin.taxes.destroy', $taxe->id);

                    return '<a href="' . $editUrl . '" class="btn btn-warning btn-sm" title="Assigner cette taxe à une mairie"><i class="fa fa-eye"></i></a>
                            <button class="btn btn-danger btn-sm btn-delete" data-url="' . $deleteUrl . '" title="Supprimer cette taxe"><i class="fa fa-trash"></i></button>';
                })
                ->rawColumns(['action'])
                ->make(true);

        } catch (\Exception $e) {
            \Log::error("Erreur DataTable taxe : " . $e->getMessage());
            return response()->json([
                'error' => 'Erreur serveur',
                'message' => $e->getMessage()
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
                    // Adaptez les actions selon vos besoins
                    $detailsButton = '<a href="'.route('superadmin.mairies.show', $mairie->id).'" class="btn btn-sm btn-info"><i class="fas fa-eye"></i> Voir</a>';
                    return $detailsButton;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        // Si la requête n'est pas AJAX, vous pouvez retourner une erreur ou rediriger
        return abort(403, 'Accès non autorisé');
    }



}
