<?php

namespace App\Http\Controllers\Mairie;

use App\Http\Controllers\Controller;
use App\Models\Commercant;
use App\Models\Mairie;
use App\Models\Secteur;
use App\Models\Taxe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommerceController extends Controller
{
    public function index(Request $request)
    {

        return view('mairie.commercant.index');
    }

    public function show($id)
    {
        $commercant = Commercant::findOrFail($id);

        return view('mairie.commercant.show', compact('commercant'));
    }

    public function edit($id)
    {
        $commercant = Commercant::findOrFail($id);

        $taxes = Taxe::where('mairie_ref', $commercant->mairie_ref)->get();
        $secteurs = Secteur::where('mairie_ref', $commercant->mairie_ref)->get();

        $selectedTaxes = $commercant->taxes->pluck('id')->toArray();

        return view('mairie.commercant.edit', compact('commercant', 'taxes', 'secteurs', 'selectedTaxes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
            'secteur_id' => 'required|integer|exists:secteurs,id',
            'taxe_ids' => 'nullable|array',
            'taxe_ids.*' => 'exists:taxes,id',
        ]);

        $commercant = Commercant::findOrFail($id);

        $commercant->nom = $data['nom'];
        $commercant->email = $data['email'] ?? null;
        $commercant->telephone = $data['telephone'] ?? null;
        $commercant->adresse = $data['adresse'] ?? null;
        $commercant->secteur_id = $data['secteur_id'];

        // Met à jour taxe_id uniquement si présent dans la requête
        if (isset($data['taxe_ids'])) {
            $commercant->taxes()->sync($data['taxe_ids']);
        }
        $commercant->save();

        return redirect()->route('mairie.commerce.index')->with('success', 'Commerçant mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $commercant = Commercant::findOrFail($id);
        $commercant->delete();

        return response()->json(['success' => true]);
    }

    public function get_list_commercants(Request $request)
    {
        $mairieConnectee = Auth::guard('mairie')->user();

        if (! $mairieConnectee) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $mairie_ref = Mairie::where('region', $mairieConnectee->region)
            ->where('commune', $mairieConnectee->commune)
            ->pluck('mairie_ref');

        $commercants = Commercant::whereIn('mairie_ref', $mairie_ref)
            ->select([
                'id',
                'nom',
                'num_commerce',
                'email',
                'telephone',
                'created_at',
            ]);

        return datatables()->of($commercants)
            ->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" class="contribuable-checkbox form-check-input" value="'.$row->id.'">';
            })
            ->addColumn('action', function ($commercant) {
                $editUrl = route('mairie.commerce.edit', $commercant->id);
                $deleteUrl = route('mairie.commerce.destroy', $commercant->id);

                return '
                    <a href="'.$editUrl.'" class="btn btn-sm btn-warning me-1" title="Modifier">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button class="btn btn-sm btn-danger btn-delete" data-url="'.$deleteUrl.'" title="Supprimer">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                ';
            })
            ->editColumn('created_at', function ($commercant) {
                return \Carbon\Carbon::parse($commercant->created_at)->format('d/m/Y à H:i');
            })
            ->rawColumns(['action', 'checkbox'])
            ->make(true);
    }

    public function print_bulk_cards(Request $request)
    {
        $ids = $request->query('ids');
        $mairieConnectee = Auth::guard('mairie')->user();

        if (! $mairieConnectee) {
            abort(403);
        }

        $mairie_ref = Mairie::where('region', $mairieConnectee->region)
            ->where('commune', $mairieConnectee->commune)
            ->pluck('mairie_ref');

        $query = Commercant::whereIn('mairie_ref', $mairie_ref);

        if ($ids === 'all') {
            $commercants = $query->with('mairie', 'secteur', 'taxes')->get();
        } else {
            $idArray = explode(',', $ids);
            $commercants = $query->whereIn('id', $idArray)->with('mairie', 'secteur', 'taxes')->get();
        }

        if ($commercants->isEmpty()) {
            return redirect()->back()->with('error', 'Aucun contribuable sélectionné.');
        }

        return view('agent.contribuable.export_multiple_virtual_cartes', compact('commercants'));
    }
}
