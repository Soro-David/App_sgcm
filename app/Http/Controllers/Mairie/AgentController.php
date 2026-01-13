<?php

namespace App\Http\Controllers\Mairie;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Commune;
use App\Models\Mairie;
use App\Models\Secteur;
use App\Models\Taxe;
use App\Notifications\AgentInvitationNotification;
use App\Notifications\MairieAgentInvitationNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class AgentController extends Controller
{
    public function index(Request $request)
    {
        // dd($request);
        $regions = Commune::select('region')->distinct()->orderBy('region', 'asc')->get();
        $agent = Auth::guard('mairie')->user();
        $mairie_ref = $agent->mairie_ref;

        return view('mairie.agents.index', compact('regions', 'mairie_ref'));
    }

    public function programer_agent()
    {
        $admin = Auth::guard('mairie')->user();

        $agents = Agent::join('mairies', 'agents.mairie_ref', '=', 'mairies.mairie_ref')
            ->where('mairies.commune', $admin->commune)
            ->where('mairies.region', $admin->region)
            ->select('agents.*')
            ->get();

        $taxes = Taxe::join('mairies', 'taxes.mairie_ref', '=', 'mairies.mairie_ref')
            ->where('mairies.commune', $admin->commune)
            ->where('mairies.region', $admin->region)
            ->select('taxes.*')
            ->get();

        $secteurs = Secteur::join('mairies', 'secteurs.mairie_ref', '=', 'mairies.mairie_ref')
            ->where('mairies.commune', $admin->commune)
            ->where('mairies.region', $admin->region)
            ->select('secteurs.*')
            ->get();

        return view('mairie.agents.programme_agent', compact('agents', 'taxes', 'secteurs'));
    }

    public function storeProgramme(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|exists:agents,id',
            'taxe_ids' => 'required|array',
            'taxe_ids.*' => 'exists:taxes,id',
            'secteur_id' => 'required|exists:secteurs,id',
        ]);

        $agent = Agent::findOrFail($request->agent_id);

        // On stocke taxe_ids comme tableau JSON (champ taxe_id)
        $agent->taxe_id = $request->taxe_ids;

        // secteur_id est une valeur unique, donc on peut stocker en tableau avec 1 seul élément ou en string
        // je te conseille d'être cohérent, donc en tableau
        $agent->secteur_id = [$request->secteur_id];

        $agent->save();

        return redirect()->back()->with('success', 'Taxes assignées avec succès à l\'agent.');
    }

    public function get_list_programmes(Request $request)
    {
        if ($request->ajax()) {

            // Récupérer la mairie connectée
            $mairie_ref = Auth::guard('mairie')->user()->mairie_ref;

            // Filtrer les agents par mairie_ref
            $data = Agent::where('mairie_ref', $mairie_ref)
                ->whereNotNull('taxe_id')
                ->latest()
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('secteur', function ($row) {
                    $secteur_id = is_array($row->secteur_id) ? ($row->secteur_id[0] ?? null) : $row->secteur_id;
                    $secteur = $secteur_id ? Secteur::find($secteur_id) : null;

                    return $secteur ? $secteur->nom : 'Non défini';
                })
                ->addColumn('taxes', function ($row) {
                    if (! empty($row->taxe_id)) {
                        $taxeNames = Taxe::whereIn('id', $row->taxe_id)->pluck('nom')->implode(', ');

                        return $taxeNames;
                    }

                    return 'Aucune';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">Modifier</a>';
                    $btn .= ' <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Supprimer</a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        // dd($request);
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'genre' => 'required|in:masculin,féminin',
            'date_naissance' => 'required|date',
            'type_piece' => 'required|string|max:50',
            'numero_piece' => 'required|string|max:100',
            'type_agent' => 'required|string|max:50',
            'adresse' => 'required|string|max:255',
            'telephone1' => 'required|string|max:20',
            'telephone2' => 'nullable|string|max:20',
            'email' => 'required|email|max:255|unique:agents,email',
            'mairie_ref' => 'required|exists:mairies,mairie_ref',
            'region' => 'required|string',
            'commune' => 'required|',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $otp = random_int(100000, 999999);

        try {
            $agent = Mairie::create([
                'name' => $request->name,
                'genre' => $request->genre,
                'date_naissance' => $request->date_naissance,
                'type_piece' => $request->type_piece,
                'numero_piece' => $request->numero_piece,
                'role' => $request->type_agent,
                'adresse' => $request->adresse,
                'telephone1' => $request->telephone1,
                'telephone2' => $request->telephone2,
                'email' => $request->email,
                'remember_token' => $request->_token,
                'otp_code' => $otp,
                'otp_expires_at' => now()->addMinutes(30),
                'mairie_ref' => $request->mairie_ref,
                'region' => $request->region,
                'commune' => $request->commune,
            ]);

            $agent->notify(new MairieAgentInvitationNotification($otp));

            return redirect()->route('mairie.agents.index')
                ->with('success', "L'agent a été ajouté. Un e-mail d'invitation a été envoyé.");
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Erreur lors de l’enregistrement : '.$e->getMessage());
        }
    }

    public function show(string $id)
    {
        //
    }

    /**
     * Affiche les détails d'une mairie spécifique.
     * (Actuellement vide)
     */
    public function list_agent(Request $request)
    {
        return view('mairie.agents.list_agent');
    }

    public function add_agent(Request $request)
    {
        $mairie = Auth::guard('mairie')->user();

        if (! $mairie) {
            return redirect()->route('login.mairie')
                ->with('error', 'Vous devez être connecté pour ajouter un agent.');
        }

        return view('mairie.agents.add_agent', compact('mairie'));
    }

    public function store_agent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'genre' => 'required|in:masculin,féminin',
            'date_naissance' => 'required|date',
            'type_piece' => 'required|string|max:50',
            'numero_piece' => 'required|string|max:100',
            'type_agent' => 'required|string|max:50',
            'adresse' => 'required|string|max:255',
            'telephone1' => 'required|string|max:20',
            'telephone2' => 'nullable|string|max:20',
            'email' => 'required|email|max:255|unique:agents,email',
            'mairie_ref' => 'required|exists:mairies,mairie_ref',

        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $otp = random_int(100000, 999999);

        try {
            $agent = Agent::create([
                'name' => $request->name,
                'genre' => $request->genre,
                'date_naissance' => $request->date_naissance,
                'type_piece' => $request->type_piece,
                'numero_piece' => $request->numero_piece,
                'type' => $request->type_agent,
                'adresse' => $request->adresse,
                'telephone1' => $request->telephone1,
                'telephone2' => $request->telephone2,
                'email' => $request->email,
                'remember_token' => $request->_token,
                'otp_code' => $otp,
                'otp_expires_at' => now()->addMinutes(30),
                'mairie_ref' => $request->mairie_ref,

            ]);

            $agent->notify(new AgentInvitationNotification($otp));

            return redirect()->route('mairie.agents.list_agent')
                ->with('success', "L'agent a été ajouté. Un e-mail d'invitation a été envoyé.");
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Erreur lors de l’enregistrement : '.$e->getMessage());
        }
    }

    public function edit(string $id)
    {
        $agent = Agent::with('commune')->findOrFail($id);

        $regions = Commune::select('region')->distinct()->orderBy('region', 'asc')->get();

        $regionActuelle = $agent->commune;

        // $communesDeLaRegion = [];
        // if ($regionActuelle) {
        //     $communesDeLaRegion = Commune::where('region', $regionActuelle->region)->orderBy('nom', 'asc')->get();
        // }
        // On récupère toutes les communes pour l'instant ou on gère dynamiquement si besoin
        // Pour modifier un agent de mairie, souvent la région/commune ne change pas, ou alors on garde la logique existante.
        // Ici on simplifie pour correspondre à add_agent qui n'a pas de sélecteur de région/commune visible (c'est caché ou auto).

        return view('mairie.agents.edit_agent', compact(
            'agent',
            'regions'
        ));
    }

    public function create(Request $request)
    {
        $mairie = Auth::guard('mairie')->user();
        $regions = Commune::select('region')->distinct()->orderBy('region', 'asc')->get();

        if (! $mairie) {
            return redirect()->route('login.mairie')
                ->with('error', 'Vous devez être connecté pour ajouter un agent.');
        }

        $mairie->load('commune');

        if (! $mairie->commune) {
            return redirect()->back()
                ->with('error', 'Aucune commune n\'est associée à votre mairie. Veuillez contacter l\'administrateur.');
        }

        return view('mairie.agents.create', compact('regions', 'mairie'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            // 'name' => 'required|string|max:255',
            // 'email' => 'required|email',
            // 'region' => 'required|string',
            // 'commune' => 'required|integer|exists:communes,id'
        ]);

        $mairie = Agent::findOrFail($id);
        $mairie->update([
            'name' => $request->name,
            'email' => $request->email,
            'region' => $request->region,
            'commune_id' => $request->commune,
        ]);

        return redirect()->route('superadmin.mairies.index')->with('success', 'Mairie mise à jour avec succès.');
    }

    public function destroy(string $id)
    {
        //
    }

    public function get_list_mairie(Request $request)
    {
        try {
            if (! $request->ajax()) {
                return response()->json(['error' => 'Requête non autorisée.'], 403);
            }

            // Récupérer la mairie connectée
            $mairie_ref = Auth::guard('mairie')->user()->mairie_ref;

            if (! $mairie_ref) {
                return response()->json(['error' => 'Mairie non authentifiée.'], 401);
            }

            // dd($mairie_ref);
            // Requête filtrée par mairie_ref
            $query = Mairie::where('mairie_ref', $mairie_ref)
                ->select(['id', 'name', 'email', 'role',  'created_at']);

            return DataTables::of($query)
                ->editColumn('created_at', function ($agent) {
                    return $agent->created_at ? $agent->created_at->format('d/m/Y H:i') : 'N/A';
                })
                ->addColumn('action', function ($agent) {
                    $editUrl = route('mairie.agents.edit', $agent->id);
                    $deleteUrl = route('mairie.agents.destroy', $agent->id);

                    return '<a href="'.$editUrl.'" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
                            <button class="btn btn-danger btn-sm btn-delete" data-url="'.$deleteUrl.'"><i class="fa fa-trash"></i></button>';
                })
                ->rawColumns(['action'])
                ->make(true);

        } catch (\Exception $e) {
            \Log::error('Erreur DataTable agents mairie : '.$e->getMessage());

            return response()->json([
                'error' => 'Erreur serveur',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function get_list_agent(Request $request)
    {
        try {
            if (! $request->ajax()) {
                return response()->json(['error' => 'Requête non autorisée.'], 403);
            }

            $mairie_ref = Auth::guard('mairie')->user()->mairie_ref;
            if (! $mairie_ref) {
                return response()->json(['error' => 'Mairie non authentifiée.'], 401);
            }

            $query = Agent::where('mairie_ref', $mairie_ref)
                ->select(['id', 'name', 'email', 'type', 'created_at']);

            return DataTables::of($query)
                ->editColumn('created_at', function ($agent) {
                    return $agent->created_at->format('d/m/Y H:i');
                })
                ->addColumn('action', function ($agent) {
                    $editUrl = route('mairie.agents.edit', $agent->id);
                    $deleteUrl = route('mairie.agents.destroy', $agent->id);

                    return '<a href="'.$editUrl.'" class="btn btn-warning btn-sm" title="Modifier"><i class="fa fa-edit"></i></a> '.
                           '<button class="btn btn-danger btn-sm btn-delete" data-url="'.$deleteUrl.'" title="Supprimer"><i class="fa fa-trash"></i></button>';
                })
                ->rawColumns(['action'])
                ->make(true);

        } catch (\Exception $e) {
            \Log::error('Erreur lors de la récupération des agents pour DataTables : '.$e->getMessage());

            return response()->json(['error' => 'Une erreur interne est survenue.'], 500);
        }
    }

    public function get_communes(string $regionName): JsonResponse
    {
        $communes = Commune::where('region', $regionName)
            ->orderBy('nom', 'asc')
            ->get(['id', 'nom']);

        return response()->json($communes);
    }
}
