<?php

namespace App\Http\Controllers\Mairie;

use App\Http\Controllers\Controller;
use App\Models\PaiementTaxe;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class PaiementController extends Controller
{
    /**
     * Affiche la vue de la liste des paiements.
     */
    public function index()
    {
        return view('mairie.paiement.index');
    }

    /**
     * Fournit les données des PAIEMENTS pour DataTables via AJAX.
     */
    public function get_list_paiement(Request $request)
    {
        if (! $request->ajax()) {
            abort(403, 'Accès non autorisé');
        }

        try {
            $user = Auth::guard('mairie')->user() ?: Auth::guard('finance')->user();
            if (! $user) {
                return response()->json(['error' => 'Non authentifié'], 401);
            }
            $mairie_ref = $user->mairie_ref;

            // Grouper par commerçant (via num_commerce)
            $query = PaiementTaxe::where('mairie_ref', $mairie_ref)
                ->with(['commercant:num_commerce,nom'])
                ->selectRaw('num_commerce, SUM(montant) as total_montant, COUNT(*) as nombre_paiements, MAX(created_at) as dernier_paiement')
                ->groupBy('num_commerce');

            return DataTables::of($query)
                ->addColumn('commercant_info', function ($paiement) {
                    $commercantNom = $paiement->commercant ? e($paiement->commercant->nom) : 'Commerçant inconnu';
                    $numCommerce = e($paiement->num_commerce);

                    return $commercantNom.' <br><small class="text-muted">'.$numCommerce.'</small>';
                })
                ->editColumn('total_montant', function ($paiement) {
                    return '<b>'.number_format($paiement->total_montant, 0, ',', ' ').' FCFA</b>';
                })
                ->addColumn('nombre_paiements', function ($paiement) {
                    return '<span class="badge bg-info">'.$paiement->nombre_paiements.'</span>';
                })
                ->editColumn('dernier_paiement', function ($paiement) {
                    return $paiement->dernier_paiement ? Carbon::parse($paiement->dernier_paiement)->format('d/m/Y à H:i') : '-';
                })
                ->addColumn('actions', function ($paiement) {
                    return '
                        <div class="d-flex justify-content-center">
                            <button class="btn btn-sm btn-primary view-details" data-id="'.$paiement->num_commerce.'">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>';
                })
                ->rawColumns(['commercant_info', 'total_montant', 'nombre_paiements', 'actions'])
                ->make(true);

        } catch (\Exception $e) {
            Log::error('Erreur DataTables get_list_payement: '.$e->getMessage().' à la ligne '.$e->getLine());

            return response()->json(['error' => 'Une erreur interne est survenue. Détails : '.$e->getMessage()], 500);
        }
    }

    /**
     * Fournit les détails des paiements pour un commerçant spécifique.
     */
    public function get_details_paiement(Request $request, $num_commerce)
    {
        if (! $request->ajax()) {
            abort(403, 'Accès non autorisé');
        }

        try {
            $user = Auth::guard('mairie')->user() ?: Auth::guard('finance')->user();
            if (! $user) {
                return response()->json(['error' => 'Non authentifié'], 401);
            }

            $paiements = PaiementTaxe::where('num_commerce', $num_commerce)
                ->where('mairie_ref', $user->mairie_ref)
                ->with(['taxe:id,nom'])
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $paiements->map(function ($p) {
                    return [
                        'date' => $p->created_at ? $p->created_at->format('d/m/Y H:i') : 'Date non définie',
                        'taxe' => $p->taxe ? $p->taxe->nom : 'Taxe non définie',
                        'periode' => $p->periode ? Carbon::parse($p->periode)->isoFormat('MMMM YYYY') : 'Période non définie',
                        'montant' => number_format($p->montant, 0, ',', ' ').' FCFA',
                        'statut' => ucfirst($p->statut),
                    ];
                }),
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur get_details_paiement: '.$e->getMessage());

            return response()->json(['error' => 'Erreur lors de la récupération des détails'], 500);
        }
    }
}
