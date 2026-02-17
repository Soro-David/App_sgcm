<?php

namespace App\Http\Controllers\Mairie;

use App\Http\Controllers\Controller;
use App\Models\Finance;
use App\Models\Financier;
use App\Models\Mairie;
use App\Notifications\MairieAgentInvitationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class AgentFinanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('mairie.finance.list_agent');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function list_agent(Request $request)
    {
        return view('mairie.finance.list_agent');
    }

    public function create(Request $request)
    {
        $mairie = Auth::guard('mairie')->user();

        if (! $mairie) {
            return redirect()->route('login.mairie')
                ->with('error', 'Vous devez être connecté pour ajouter un agent.');
        }

        return view('mairie.finance.add_agent', compact('mairie'));
    }

    public function store(Request $request)
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
            'email' => [
                'required',
                'email',
                'max:255',
                function ($attribute, $value, $fail) {
                    $existsInFinance = Finance::where('email', $value)->exists();
                    $existsInFinancier = Financier::where('email', $value)->exists();
                    if ($existsInFinance || $existsInFinancier) {
                        $fail('Cette adresse e-mail est déjà utilisée.');
                    }
                },
            ],
            'mairie_ref' => 'required|exists:mairies,mairie_ref',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $mairie = Mairie::where('mairie_ref', $request->mairie_ref)->first();
        if (! $mairie) {
            return redirect()->back()->withInput()->with('error', 'Mairie introuvable.');
        }

        $otp = random_int(100000, 999999);

        $currentUser = Auth::guard('mairie')->user() ?? Auth::guard('finance')->user();
        $added_by = $currentUser->name.' ('.($currentUser->role ?? 'admin').')';

        try {
            if ($request->type_agent === 'responsable_financier') {
                $agent = Financier::create([
                    'name' => $request->name,
                    'genre' => $request->genre,
                    'date_naissance' => $request->date_naissance,
                    'type_piece' => $request->type_piece,
                    'numero_piece' => $request->numero_piece,
                    'role' => 'financiers',
                    'adresse' => $request->adresse,
                    'telephone1' => $request->telephone1,
                    'telephone2' => $request->telephone2,
                    'email' => $request->email,
                    'otp_code' => $otp,
                    'otp_expires_at' => now()->addMinutes(30),
                    'mairie_ref' => $request->mairie_ref,
                    'region' => $mairie->region,
                    'commune' => $mairie->commune,
                    'status' => 'pending',
                    'added_by' => $added_by,
                ]);
            } else {
                $agent = Finance::create([
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
                    'otp_code' => $otp,
                    'otp_expires_at' => now()->addMinutes(30),
                    'mairie_ref' => $request->mairie_ref,
                    'region' => $mairie->region,
                    'commune' => $mairie->commune,
                    'status' => 'pending',
                    'added_by' => $added_by,
                ]);
            }

            // Utiliser la notification appropriée qui pointe vers le formulaire de finalisation finance
            $agent->notify(new MairieAgentInvitationNotification($otp));

            return redirect()->route('mairie.finance.index')
                ->with('success', "L'agent de finance a été ajouté. Un e-mail d'invitation a été envoyé.");
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Erreur lors de l’enregistrement : '.$e->getMessage());
        }
    }

    public function get_list(Request $request)
    {
        try {
            if (! $request->ajax()) {
                return response()->json(['error' => 'Requête non autorisée.'], 403);
            }

            $current_user = Auth::guard('mairie')->user() ?? Auth::guard('finance')->user();
            $mairie_ref = $current_user->mairie_ref;

            if (! $mairie_ref) {
                return response()->json(['error' => 'Mairie non authentifiée.'], 401);
            }

            $financeQuery = Finance::where('mairie_ref', $mairie_ref)
                ->select(['id', 'name', 'email', 'role', 'added_by', 'created_at', \DB::raw("'finance' as source_table")]);

            $financierQuery = Financier::where('mairie_ref', $mairie_ref)
                ->select(['id', 'name', 'email', 'role', 'added_by', 'created_at', \DB::raw("'financier' as source_table")]);

            $query = $financeQuery->union($financierQuery);

            return DataTables::of($query)
                ->addColumn('type', function ($agent) {
                    if ($agent->source_table === 'financier') {
                        return 'Responsable Financier';
                    }

                    return $agent->role === 'caissier' ? 'Caissier' : 'Agent Financier';
                })
                ->editColumn('created_at', function ($agent) {
                    return $agent->created_at ? \Carbon\Carbon::parse($agent->created_at)->format('d/m/Y H:i') : 'N/A';
                })
                ->addColumn('action', function ($agent) {
                    // Les routes edit/destroy à adapter si nécessaire
                    return '<button class="btn btn-warning btn-sm" title="Modifier"><i class="fa fa-edit"></i></button> '.
                           '<button class="btn btn-danger btn-sm btn-delete" title="Supprimer"><i class="fa fa-trash"></i></button>';
                })
                ->rawColumns(['action'])
                ->make(true);

        } catch (\Exception $e) {
            \Log::error('Erreur lors de la récupération des agents finance pour DataTables : '.$e->getMessage());

            return response()->json(['error' => 'Une erreur interne est survenue : '.$e->getMessage()], 500);
        }
    }
}
