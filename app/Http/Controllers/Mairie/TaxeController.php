<?php

namespace App\Http\Controllers\Mairie;

use App\Http\Controllers\Controller;
use App\Models\Mairie;
use App\Models\Taxe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

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
        $user = Auth::guard('mairie')->user() ?: Auth::guard('finance')->user();
        $mairie_ref = $user ? $user->mairie_ref : null;

        return view('mairie.taxe.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'montant' => 'nullable|numeric|min:0',
            'frequence' => 'required|in:jour,mois,an',
            'mairie_ref' => 'required|exists:mairies,mairie_ref',
        ]);

        Taxe::create([
            'nom' => $request->nom,
            'description' => $request->description,
            'montant' => $request->montant,
            'frequence' => $request->frequence,
            'mairie_ref' => $request->mairie_ref,
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $user = Auth::guard('mairie')->user() ?: Auth::guard('finance')->user();
            $mairie_ref = $user ? $user->mairie_ref : null;

            $taxe = Taxe::where('id', $id)
                ->where('mairie_ref', $mairie_ref)
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'taxe' => $taxe,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Taxe non trouvée.',
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $user = Auth::guard('mairie')->user() ?: Auth::guard('finance')->user();
            $mairie_ref = $user ? $user->mairie_ref : null;

            $request->validate([
                'nom' => 'required|string|max:255',
                'description' => 'nullable|string',
                'montant' => 'nullable|numeric|min:0',
                'frequence' => 'required|in:jour,mois,an',
            ]);

            $taxe = Taxe::where('id', $id)
                ->where('mairie_ref', $mairie_ref)
                ->firstOrFail();

            $taxe->update([
                'nom' => $request->nom,
                'description' => $request->description,
                'montant' => $request->montant,
                'frequence' => $request->frequence,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'La taxe a été modifiée avec succès.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la modification: '.$e->getMessage(),
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $user = Auth::guard('mairie')->user() ?: Auth::guard('finance')->user();
            $mairie_ref = $user ? $user->mairie_ref : null;

            $taxe = Taxe::where('id', $id)
                ->where('mairie_ref', $mairie_ref)
                ->firstOrFail();

            $taxe->delete();

            return response()->json([
                'success' => true,
                'message' => 'La taxe a été supprimée avec succès.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: '.$e->getMessage(),
            ], 500);
        }
    }

    public function get_list_taxes(Request $request)
    {
        try {
            if (! $request->ajax()) {
                return response()->json(['error' => 'Requête non autorisée.'], 403);
            }

            // Récupérer la mairie connectée
            $user = Auth::guard('mairie')->user() ?: Auth::guard('finance')->user();
            $mairie_ref = $user ? $user->mairie_ref : null;

            if (! $mairie_ref) {
                return response()->json(['error' => 'Mairie non trouvée.'], 404);
            }

            // Récupère les taxes de la mairie connectée
            $query = Taxe::where('mairie_ref', $mairie_ref)
                ->select('id', 'nom', 'created_at', 'frequence', 'montant')
                ->orderBy('created_at', 'desc');

            return DataTables::of($query)
                ->editColumn('created_at', function ($taxe) {
                    return $taxe->created_at ? date('d/m/Y H:i', strtotime($taxe->created_at)) : 'N/A';
                })
                ->editColumn('montant', function ($taxe) {
                    return $taxe->montant ? number_format($taxe->montant, 0, ',', ' ').' FCFA' : 'N/A';
                })
                ->editColumn('frequence', function ($taxe) {
                    return ucfirst($taxe->frequence ?: 'N/A');
                })
                ->addColumn('action', function ($taxe) {
                    return '
                        <button class="btn btn-sm btn-warning btn-edit" data-id="'.$taxe->id.'" title="Modifier">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger btn-delete" data-id="'.$taxe->id.'" title="Supprimer">
                            <i class="fa fa-trash"></i>
                        </button>
                    ';
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
                    // Adaptez les actions selon vos besoins
                    $detailsButton = '<a href="'.route('superadmin.mairies.show', $mairie->mairie_ref).'" class="btn btn-sm btn-info"><i class="fas fa-eye"></i> Voir</a>';

                    return $detailsButton;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        // Si la requête n'est pas AJAX, vous pouvez retourner une erreur ou rediriger
        return abort(403, 'Accès non autorisé');
    }
}
