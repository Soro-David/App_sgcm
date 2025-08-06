<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Notifications\MairieInvitationNotification;
use App\Notifications\AgentInvitationNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use App\Services\QrCodeService;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreCommercantRequest;
use App\Models\Commercant;
use App\Models\Commune;
use App\Models\Mairie;
use App\Models\Agent;
use App\Models\Taxe;
use App\Models\Secteur;
use App\Models\TypeContribuable;



class AgentController extends Controller
{
   
    public function index(Request $request)
    { 
        // dd($request); 
        $regions = Commune::select('region')->distinct()->orderBy('region', 'asc')->get();
        $mairieId = Auth::guard('mairie')->id();

        // dd($mairieId);
         return view('agent.contribuable.index', compact('regions','mairieId'));
    }

   
    public function programer_agent()
    {
        $mairie_id = Auth::guard('mairie')->id();

        $agents = Agent::where('mairie_id', $mairie_id)->get();
        $taxes = Taxe::where('mairie_id', $mairie_id)->get();
        $secteurs = Secteur::where('mairie_id', $mairie_id)->get();

        return view('agent.contribuable.index', compact('agents', 'taxes','secteurs'));
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

    public function get_list_commercants(Request $request)
    {
        $mairie = Auth::guard('agent')->user();

        if (!$mairie) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        // Ajuste selon ta structure : souvent c’est $mairie->id
        $mairieId = $mairie->mairie_id ?? $mairie->id;

        $commercants = Commercant::where('mairie_id', $mairieId)
                                ->select(['id', 'nom','num_commerce', 'email', 'telephone', 'created_at'])
                                ->orderBy('created_at', 'desc');


                                // dd($commercants);
        return datatables()->of($commercants)
            ->addColumn('action', function ($row) {
                // $detailUrl = route('mairie.commerce.show', $row->id);
                $editUrl = route('agent.commerce.edit', $row->id);
                $deleteUrl = route('agent.commerce.destroy', $row->id);
                return '
                    <a href="' . $editUrl . '" class="btn btn-sm btn-warning me-1" title="Modifier">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button class="btn btn-sm btn-danger" onclick="deleteCommercant(' . $row->id . ')" title="Supprimer">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                ';
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('d/m/Y H:i');
            })
            ->rawColumns(['action'])
            ->make(true);
    }


    public function get_list_programmes(Request $request)
    {
        if ($request->ajax()) {

            // Récupérer la mairie connectée
            $mairie_id = Auth::guard('mairie')->id();

            // Filtrer les agents par mairie_id
            $data = Agent::where('mairie_id', $mairie_id)
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
                    if (!empty($row->taxe_id)) {
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

    public function create(Request $request)
    {
        $agent = Auth::guard('agent')->user();

        if (!$agent) {
            return redirect()->route('login.agent');
        }

        $mairie_id = $agent->mairie_id;
        
        $mairie = Mairie::findOrFail($mairie_id);

        // Générer le numéro de commerce
        $prefix = strtoupper(substr(preg_replace('/\s+/', '', $mairie->name), 0, 4));

        $lastCommerce = Commercant::where('mairie_id', $mairie_id)
                                    ->orderByDesc('id')
                                    ->first();

        $lastNumber = 0;
        if ($lastCommerce && preg_match('/\d+$/', $lastCommerce->num_commerce, $matches)) {
            $lastNumber = (int) $matches[0];
        }

        $newNumber = $lastNumber + 1;
        $numeroFormate = str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        $num_commerce = $prefix . $numeroFormate;

        // Récupération et décodage des IDs
        $taxeIds = is_array($agent->taxe_id) ? $agent->taxe_id : (!is_null($agent->taxe_id) ? json_decode($agent->taxe_id, true) : []);
        $secteurIds = is_array($agent->secteur_id) ? $agent->secteur_id : (!is_null($agent->secteur_id) ? json_decode($agent->secteur_id, true) : []);

        // Message d’avertissement si vide
        $warningMessage = null;
        if (empty($taxeIds) || empty($secteurIds)) {
            $warningMessage = "⚠️ Vous n'êtes pas encore lié à une taxe ou un secteur. Veuillez contacter l'administrateur.";
        }

        // Récupération des données associées
        $taxes = Taxe::whereIn('id', $taxeIds)->get();
        $secteurs = Secteur::whereIn('id', $secteurIds)->get();

        $type_contribuables = TypeContribuable::where('mairie_id', $mairie_id)->get();


        return view('agent.contribuable.create', compact('secteurs', 'taxes', 'num_commerce', 'agent', 'warningMessage','type_contribuables'));
    }
        
public function store(StoreCommercantRequest $request, QrCodeService $qrCodeService)
{
    $validatedData = $request->validated();
    $agent = $request->user('agent');

    $commercant = null;
    
    $profilPath = null;
    $rectoPath = null;
    $versoPath = null;

    try {
        if ($request->hasFile('photo_profil')) {
            $profilPath = $request->file('photo_profil')->store('commercants/profils', 'public');
        }
        if ($request->hasFile('photo_recto')) {
            $rectoPath = $request->file('photo_recto')->store('commercants/pieces', 'public');
        }
        if ($request->hasFile('photo_verso')) {
            $versoPath = $request->file('photo_verso')->store('commercants/pieces', 'public');
        }

        $commercant = DB::transaction(function () use ($validatedData, $agent, $profilPath, $rectoPath, $versoPath) {
            
            $mairie = $agent->mairie;
            if (!$mairie) {
                throw new \Exception("L'agent connecté n'est rattaché à aucune mairie.");
            }

            $commercantData = collect($validatedData)->except('taxe_ids')->all();

            $commercantData['photo_profil'] = $profilPath;
            $commercantData['photo_recto'] = $rectoPath;
            $commercantData['photo_verso'] = $versoPath;
            $commercantData['mairie_id'] = $mairie->id;
            $commercantData['agent_id'] = $agent->id;

            $newCommercant = Commercant::create($commercantData);

            if (!empty($validatedData['taxe_ids'])) {
                $newCommercant->taxes()->sync($validatedData['taxe_ids']);
            }

            return $newCommercant;
        });
        
        if ($commercant) {
            $qrCodePath = $qrCodeService->generateForCommercant($commercant);
            $commercant->update(['qr_code_path' => $qrCodePath]);
        }

        return redirect()
            ->route('agent.commerce.virtual_card', ['commercant' => $commercant->id])
            ->with('success', 'Contribuable ajouté avec succès ! Voici sa carte virtuelle.');

    } catch (\Exception $e) {
        if ($profilPath) Storage::disk('public')->delete($profilPath);
        if ($rectoPath) Storage::disk('public')->delete($rectoPath);
        if ($versoPath) Storage::disk('public')->delete($versoPath);
        
        Log::error("Échec de la création du commerçant : " . $e->getMessage() . "\n" . $e->getTraceAsString());
        report($e);

        return redirect()->back()
            ->withInput()
            ->with('error', 'Une erreur interne est survenue lors de la création du contribuable. L\'administrateur a été notifié.');
    }
}

    public function show_virtual_card(Commercant $commercant)
    {
        $commercant->load('mairie', 'secteur', 'taxes');
        return view('agent.contribuable.virtual_carte', compact('commercant'));
    }



    public function show(string $id)
    {
        //
    }


    public function edit(string $id)
    {
        $mairie = Agent::with('commune')->findOrFail($id);

        $regions = Commune::select('region')->distinct()->orderBy('region', 'asc')->get();

        $regionActuelle = $mairie->commune;

        $communesDeLaRegion = Commune::where('id', $regionActuelle)->orderBy('nom', 'asc')->get();
        // dd($communesDeLaRegion);
        
        return view('agent.edit_mairie', compact(
            'mairie', 
            'regions', 
            'communesDeLaRegion'
        ));
    }



    public function ajouter_contribuable(Request $request)
    {
        $agent = Auth::guard('agent')->user();
        $mairie_id = $agent->mairie_id;
        $agent_id = $agent->id;

        $request->validate([
            'libelle' => 'required|string|max:255',
        ]);

        // dd($request);
        TypeContribuable::create([
            'libelle' => $request->libelle,
            'mairie_id'=>$mairie_id,
            'agent_id'=>$agent_id
        ]);

        return redirect()->back()->with('success', 'Type de contribuable ajouté avec succès.');
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
            if (!$request->ajax()) {
                return response()->json(['error' => 'Requête non autorisée.'], 403);
            }

            // Récupérer la mairie connectée
            $mairieId = Auth::guard('mairie')->id();

            if (!$mairieId) {
                return response()->json(['error' => 'Mairie non authentifiée.'], 401);
            }

            // Requête filtrée par mairie_id
            $query = Agent::where('mairie_id', $mairieId)
                        ->select(['id', 'name', 'email', 'created_at']);

            return DataTables::of($query)
                ->editColumn('created_at', function ($agent) {
                    return $agent->created_at ? $agent->created_at->format('d/m/Y H:i') : 'N/A';
                })
                ->addColumn('action', function ($agent) {
                    $editUrl = route('mairie.agents.edit', $agent->id);
                    $deleteUrl = route('mairie.agents.destroy', $agent->id);

                    return '<a href="' . $editUrl . '" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
                            <button class="btn btn-danger btn-sm btn-delete" data-url="' . $deleteUrl . '"><i class="fa fa-trash"></i></button>';
                })
                ->rawColumns(['action'])
                ->make(true);

        } catch (\Exception $e) {
            \Log::error("Erreur DataTable agents mairie : " . $e->getMessage());
            return response()->json([
                'error' => 'Erreur serveur',
                'message' => $e->getMessage()
            ], 500);
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