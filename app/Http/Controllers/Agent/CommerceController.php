<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Commercant;
use App\Models\Mairie;
use App\Models\Secteur;
use App\Models\Taxe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommerceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index(Request $request)
    // {
    //     $agentId = Auth::guard('agent')->id();
    //     $agent = Auth::guard('agent')->user();

    //     $mairie_ref = $agent->mairie_ref;

    //     $secteurs = Secteur::all();
    //     $agents = Agent::all();

    //     // dd($$agent->taxe_id);

    //     return view('agent.commerce.index', compact('secteurs','agents'));
    // }

    public function index(Request $request)
    {
        $agent = Auth::guard('agent')->user();

        if (! $agent) {
            return redirect()->route('login.agent');
        }

        $mairie_ref = $agent->mairie_ref;
        $mairie = Mairie::where('mairie_ref', $mairie_ref)->first();

        // Générer le numéro de commerce
        $prefix = strtoupper(substr(preg_replace('/\s+/', '', $mairie->name), 0, 4));

        $lastCommerce = Commercant::where('mairie_ref', $mairie_ref)
            ->orderByDesc('id')
            ->first();

        $lastNumber = 0;
        if ($lastCommerce && preg_match('/\d+$/', $lastCommerce->num_commerce, $matches)) {
            $lastNumber = (int) $matches[0];
        }

        $newNumber = $lastNumber + 1;
        $numeroFormate = str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        $num_commerce = $prefix.$numeroFormate;

        // Récupération et décodage des IDs
        $taxeIds = is_array($agent->taxe_id) ? $agent->taxe_id : (! is_null($agent->taxe_id) ? json_decode($agent->taxe_id, true) : []);
        $secteurIds = is_array($agent->secteur_id) ? $agent->secteur_id : (! is_null($agent->secteur_id) ? json_decode($agent->secteur_id, true) : []);

        // Message d’avertissement si vide
        $warningMessage = null;
        if (empty($taxeIds) || empty($secteurIds)) {
            $warningMessage = "⚠️ Vous n'êtes pas encore lié à une taxe ou un secteur. Veuillez contacter l'administrateur.";
        }

        // Récupération des données associées
        $taxes = Taxe::whereIn('id', $taxeIds)->get();
        $secteurs = Secteur::whereIn('id', $secteurIds)->get();

        return view('agent.contribuable.index', compact('secteurs', 'taxes', 'num_commerce', 'agent', 'warningMessage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $agent = Auth::guard('agent')->user();

        if (! $agent) {
            return redirect()->route('login.agent')->with('error', 'Connexion requise.');
        }

        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
            'secteur_id' => 'required|integer|exists:secteurs,id',
            'taxe_ids' => 'required|array',
            'taxe_ids.*' => 'exists:taxes,id',
            'agent_id' => 'nullable|string|max:255',
            'num_commerce' => 'required|string|max:255|unique:commercants,num_commerce',
        ]);
        // dd($data);

        $mairie = $agent->mairie;

        $commerce = Commercant::create([
            'nom' => $data['nom'],
            'email' => $data['email'],
            'telephone' => $data['telephone'],
            'adresse' => $data['adresse'],
            'secteur_id' => [$data['secteur_id']],
            'num_commerce' => $data['num_commerce'],
            'mairie_id' => $mairie->id,
            'mairie_ref' => $mairie->mairie_ref,
            'agent_id' => $agent->id,
            'taxe_id' => $data['taxe_ids'],
        ]);

        return redirect()->back()->with('success', 'Commerçant ajouté avec succès.');
    }

    /**
     * Display the specified resource.
     */

    /**
     * Show the form for editing the specified resource.
     */
    public function show($id)
    {
        $commercant = Commercant::findOrFail($id);

        // Ici taxe_id et secteur_id sont déjà des tableaux grâce aux casts
        return view('agent.contribuable.show', compact('commercant'));
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

        return view('agent.contribuable.edit', compact('commercant', 'taxes', 'secteurs', 'selectedTaxes'));
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

        return redirect()->route('agent.contribuable.index')->with('success', 'Commerçant mis à jour avec succès.');
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
        $agent = Auth::guard('agent')->user();
        $mairie_ref = $agent->mairie_ref;

        $commercants = Commercant::where('mairie_ref', $mairie_ref)
            ->select(['id', 'nom', 'email', 'telephone', 'created_at'])
            ->orderBy('created_at', 'desc');

        return datatables()->of($commercants)
            ->addColumn('action', function ($row) {
                $detailUrl = route('agent.contribuable.show', $row->id);
                $editUrl = route('agent.contribuable.edit', $row->id);
                // $deleteUrl = route('agent.commerce.destroy', $row->id);

                //  <button class="btn btn-sm btn-danger" onclick="deleteCommercant(' . $row->id . ')" title="Supprimer">
                //     <i class="fas fa-trash-alt"></i>
                // </button>
                return '
                    <a href="'.$detailUrl.'" class="btn btn-sm btn-info me-1" title="Détails">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="'.$editUrl.'" class="btn btn-sm btn-warning me-1" title="Modifier">
                        <i class="fas fa-edit"></i>
                    </a>
                ';
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('d/m/Y H:i');
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
