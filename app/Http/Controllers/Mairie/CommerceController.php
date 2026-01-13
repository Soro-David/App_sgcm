<?php

namespace App\Http\Controllers\Mairie;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Secteur;
use App\Models\Commercant;
use App\Models\Mairie;
use App\Models\Taxe;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

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

        // Décoder taxe_id JSON en tableau pour pré-sélection dans le formulaire
        $selectedTaxes = [];
        if ($commercant->taxe_id) {
            if (is_string($commercant->taxe_id)) {
                $selectedTaxes = json_decode($commercant->taxe_id, true);
            } elseif (is_array($commercant->taxe_id)) {
                $selectedTaxes = $commercant->taxe_id;
            }
        }

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
        $commercant->secteur_id = [$data['secteur_id']];
         
        // Met à jour taxe_id uniquement si présent dans la requête
        if (isset($data['taxe_ids'])) {
            $commercant->taxe_id = $data['taxe_ids'];
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

        if (!$mairieConnectee) {
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
                'created_at'
            ]);

        return datatables()->of($commercants)
            ->addColumn('action', function ($commercant) {
                $editUrl = route('mairie.commerce.edit', $commercant->id);
                $deleteUrl = route('mairie.commerce.destroy', $commercant->id); 

                return '
                    <a href="' . $editUrl . '" class="btn btn-sm btn-warning me-1" title="Modifier">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button class="btn btn-sm btn-danger btn-delete" data-url="' . $deleteUrl . '" title="Supprimer">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                ';
            })
            ->editColumn('created_at', function ($commercant) {
                return \Carbon\Carbon::parse($commercant->created_at)->format('d/m/Y à H:i');
            })
            ->rawColumns(['action'])
            ->make(true);
    }

}
