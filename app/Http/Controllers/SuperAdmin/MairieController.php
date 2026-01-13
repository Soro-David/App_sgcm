<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Commune;
use App\Models\Mairie;
use App\Notifications\MairieInvitationNotification;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class MairieController extends Controller
{
    public function index()
    {
        $regions = Commune::select('region')->distinct()->orderBy('region', 'asc')->get();

        return view('superAdmin.mairie.index', compact('regions'));
    }

    public function create()
    {
        // Normalement, cette méthode renvoie la vue du formulaire de création.
        // return view('superAdmin.mairie.create');
    }

    private function generateMairieRef()
    {
        return 'M'.str_pad(random_int(1, 999), 3, '0', STR_PAD_LEFT);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:mairies,email',
            'region' => 'required|string',
            'commune' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Génération d'un code OTP (One-Time Password)
        $otp = random_int(100000, 999999);

        // Création de la mairie avec un statut en attente
        $mairie = Mairie::create([
            'name' => $request->name,
            'email' => $request->email,
            'region' => $request->region,
            'commune' => $request->commune,
            'mairie_ref' => $this->generateMairieRef(),
            'status' => 'pending', // Statut initial
            'otp_code' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(30),
        ]);

        // dd($mairie);
        // Envoi de la notification par e-mail avec le code d'invitation
        try {
            $mairie->notify(new MairieInvitationNotification($otp));
            \Log::info('Invitation envoyée avec succès à '.$mairie->email);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'envoi de l\'invitation : '.$e->getMessage());
            // En cas d'échec de l'envoi, on supprime la mairie pour éviter les données orphelines
            $mairie->delete();

            // Redirection avec un message d'erreur explicite
            return redirect()->back()->withInput()->with('error', 'Échec de l\'envoi de l’e-mail : '.$e->getMessage());
        }

        return redirect()->route('superadmin.mairies.index')
            ->with('success', 'La mairie a été ajoutée. Un e-mail d\'invitation a été envoyé.');
    }

    /**
     * Affiche les détails d'une mairie spécifique.
     * (Actuellement vide)
     */
    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $mairie = Mairie::with('commune')->findOrFail($id);

        $regions = Commune::select('region')->distinct()->orderBy('region', 'asc')->get();

        $regionActuelle = $mairie->commune;

        $communesDeLaRegion = Commune::where('id', $regionActuelle)->orderBy('nom', 'asc')->get();
        // dd($communesDeLaRegion);

        return view('superAdmin.mairie.edit_mairie', compact(
            'mairie',
            'regions',
            'communesDeLaRegion'
        ));
    }

    /**
     * Met à jour une mairie spécifique dans la base de données.
     * (Actuellement vide)
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            // 'name' => 'required|string|max:255',
            // 'email' => 'required|email',
            // 'region' => 'required|string',
            // 'commune' => 'required|integer|exists:communes,id'
        ]);

        $mairie = Mairie::findOrFail($id);
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

            $query = Mairie::select(['id', 'name', 'email', 'created_at']);

            // dd($query);
            return DataTables::of($query)
                ->editColumn('created_at', function ($mairie) {
                    return $mairie->created_at ? $mairie->created_at->format('d/m/Y H:i') : 'N/A';
                })
                ->addColumn('action', function ($mairie) {
                    $editUrl = route('superadmin.mairies.edit', $mairie->id);
                    $deleteUrl = route('superadmin.mairies.destroy', $mairie->id);

                    return '<a href="'.$editUrl.'" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
                            <button class="btn btn-danger btn-sm btn-delete" data-url="'.$deleteUrl.'"><i class="fa fa-trash"></i></button>';
                })
                ->rawColumns(['action'])
                ->make(true);

        } catch (\Exception $e) {
            \Log::error('Erreur DataTable mairie : '.$e->getMessage());

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
