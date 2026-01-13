<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommercantRequest;
use App\Models\Agent;
use App\Models\Commercant;
use App\Models\Commune;
use App\Models\Mairie;
use App\Models\Secteur;
use App\Models\Taxe;
use App\Models\TypeContribuable;
use App\Services\QrCodeService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class AgentController extends Controller
{
    public function index(Request $request)
    {
        // dd($request);
        $regions = Commune::select('region')->distinct()->orderBy('region', 'asc')->get();
        $agent = Auth::guard('agent')->user();

        if (! $agent || ! $agent->mairie_ref) {
            abort(403, 'Agent ou mairie non trouvée');
        }

        $mairie_ref = $agent->mairie_ref;

        return view('agent.contribuable.index', compact('regions', 'mairie_ref'));
    }

    public function programer_agent()
    {
        $mairie_ref = Auth::guard('mairie')->user()->mairie_ref;

        $agents = Agent::where('mairie_ref', $mairie_ref)->get();
        $taxes = Taxe::where('mairie_ref', $mairie_ref)->get();
        $secteurs = Secteur::where('mairie_ref', $mairie_ref)->get();

        return view('agent.contribuable.index', compact('agents', 'taxes', 'secteurs'));
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

        if (! $mairie) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $mairie_ref = $mairie->mairie_ref;

        $commercants = Commercant::where('mairie_ref', $mairie_ref)
            ->select(['id', 'nom', 'num_commerce', 'email', 'telephone', 'created_at'])
            ->orderBy('created_at', 'desc');

        return datatables()->of($commercants)

            ->addColumn('action', function ($row) {
                $editCardUrl = route('agent.contribuable.edit', $row->id);
                $deleteUrl = route('agent.contribuable.destroy', $row->id);

                return '
                    <a href="'.$editCardUrl.'" class="btn btn-sm btn-primary me-1" title="Voir carte virtuelle">
                        <i class="fas fa-id-card"></i>
                    </a>
                    <button class="btn btn-sm btn-danger" onclick="deleteCommercant('.$row->id.')" title="Supprimer">
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

    public function create(Request $request)
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

        $nomsSecteurs = $secteurs->map(function ($secteur) {
            return [
                'id' => $secteur->id,
                'nom' => $secteur->nom,
            ];
        });

        // dd($nomsSecteurs);

        $type_contribuables = TypeContribuable::where('mairie_ref', $mairie_ref)->get();

        return view('agent.contribuable.create', compact('secteurs', 'nomsSecteurs', 'taxes', 'num_commerce', 'agent', 'warningMessage', 'type_contribuables'));
    }

    public function store(StoreCommercantRequest $request, QrCodeService $qrCodeService)
    {
        // dd($request->all());
        $validatedData = $request->validated();
        $agent = $request->user('agent');
        $profilPath = null;
        $rectoPath = null;
        $versoPath = null;

        try {
            // Upload fichiers
            if ($request->hasFile('photo_profil')) {
                $profilPath = $request->file('photo_profil')->store('commercants/profils', 'public');
                $validatedData['photo_profil'] = $profilPath;
            }

            if ($request->hasFile('photo_recto')) {
                $rectoPath = $request->file('photo_recto')->store('commercants/recto', 'public');
                $validatedData['photo_recto'] = $rectoPath;
            }

            if ($request->hasFile('photo_verso')) {
                $versoPath = $request->file('photo_verso')->store('commercants/verso', 'public');
                $validatedData['photo_verso'] = $versoPath;
            }

            $validatedData['agent_id'] = $agent->id;
            $validatedData['mairie_ref'] = $agent->mairie_ref;

            // dd($validatedData);
            $commercant = Commercant::create($validatedData);

            if ($request->has('taxe_ids')) {
                $commercant->taxes()->sync($request->input('taxe_ids'));
            }

            $qrCodePath = $qrCodeService->generateForCommercant($commercant);
            $commercant->update(['qr_code_path' => $qrCodePath]);

            $redirectUrl = route('agent.contribuable.virtual_card', ['commercant' => $commercant->id]);

            // Envoi de l'email de bienvenue avec OTP
            if ($commercant->email) {
                try {
                    // Génération OTP
                    $otp = rand(100000, 999999);
                    $commercant->otp_code = $otp;
                    $commercant->otp_expires_at = Carbon::now()->addMinutes(30);
                    $commercant->save();

                    // Utilisation de sendNow pour garantir l'envoi immédiat (synchrone)
                    Notification::sendNow($commercant, new \App\Notifications\CommercantWelcomeNotification($commercant, (string) $otp));
                } catch (\Exception $e) {
                    Log::error('Erreur envoi mail commerçant : '.$e->getMessage());
                }
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Contribuable ajouté avec succès ! Vous allez être redirigé.',
                    'redirect_url' => $redirectUrl,
                ]);
            }

            return redirect($redirectUrl)->with('success', 'Contribuable ajouté avec succès ! Voici sa carte virtuelle. Un email a été envoyé pour définir le mot de passe.');

        } catch (\Exception $e) {
            if ($profilPath) {
                Storage::disk('public')->delete($profilPath);
            }
            if ($rectoPath) {
                Storage::disk('public')->delete($rectoPath);
            }
            if ($versoPath) {
                Storage::disk('public')->delete($versoPath);
            }

            Log::error('Erreur ajout commerçant : '.$e->getMessage());
            dd($e);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => "Une erreur interne est survenue. L'administrateur a été notifié.",
                ], 500);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', "Une erreur interne est survenue. L'administrateur a été notifié.");
        }
    }

    public function show_virtual_card(Commercant $commercant)
    {
        $commercant->load('mairie', 'secteur', 'taxes');

        return view('agent.contribuable.virtual_carte', compact('commercant'));
    }

    public function edit_virtual_card(Commercant $commercant)
    {

        $commercant->load('mairie', 'secteur', 'taxes');

        return view('agent.contribuable.virtual_carte', compact('commercant'));
    }

    public function show(string $id)
    {
        //
    }

    public function edit_commercant(Commercant $commercant)
    {
        $agent = Auth::guard('agent')->user();
        $mairie_ref = $agent->mairie_ref ?? $agent->id;

        if ($commercant->mairie_ref !== $mairie_ref) {
            abort(403, 'Accès non autorisé à ce commerçant.');
        }

        $commercant->load('mairie', 'secteur', 'taxes');

        $secteurs = Secteur::where('mairie_ref', $mairie_ref)->get();
        $taxes = Taxe::where('mairie_ref', $mairie_ref)->get();

        $selectedTaxes = $commercant->taxes->pluck('id')->toArray();

        return view('agent.contribuable.edit', compact('commercant', 'secteurs', 'taxes', 'selectedTaxes'));
    }

    public function update_commercant(Request $request, Commercant $commercant)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'nullable|email',
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
            'secteur_id' => 'required|exists:secteurs,id',
            'taxe_ids' => 'required|array',
            'taxe_ids.*' => 'exists:taxes,id',
        ]);

        $commercant->update($validated);
        $commercant->taxes()->sync($validated['taxe_ids']);
        if ($request->hasFile('photo_profil')) {
            $commercant->photo_profil = $request->file('photo_profil')->store('commercants/photos_profil', 'public');
        }
        if ($request->hasFile('photo_recto')) {
            $commercant->photo_recto = $request->file('photo_recto')->store('commercants/photos_recto', 'public');
        }
        if ($request->hasFile('photo_verso')) {
            $commercant->photo_verso = $request->file('photo_verso')->store('commercants/photos_verso', 'public');
        }

        return redirect()->route('agent.contribuable.index')->with('success', 'Commerçant mis à jour avec succès.');
    }

    public function ajouter_contribuable(Request $request)
    {
        $agent = Auth::guard('agent')->user();
        $mairie_ref = $agent->mairie_ref;
        $agent_id = $agent->id;

        $request->validate([
            'libelle' => 'required|string|max:255',
        ]);

        // dd($request);
        TypeContribuable::create([
            'libelle' => $request->libelle,
            'mairie_ref' => $mairie_ref,
            'agent_id' => $agent_id,
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
            if (! $request->ajax()) {
                return response()->json(['error' => 'Requête non autorisée.'], 403);
            }

            // Récupérer la mairie connectée
            $mairie_ref = Auth::guard('mairie')->user()->mairie_ref;

            if (! $mairie_ref) {
                return response()->json(['error' => 'Mairie non authentifiée.'], 401);
            }

            // Requête filtrée par mairie_ref
            $query = Agent::where('mairie_ref', $mairie_ref)
                ->select(['id', 'name', 'email', 'created_at']);

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

    public function get_communes(string $regionName): JsonResponse
    {
        $communes = Commune::where('region', $regionName)
            ->orderBy('nom', 'asc')
            ->get(['id', 'nom']);

        return response()->json($communes);
    }
}
