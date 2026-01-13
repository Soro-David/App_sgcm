<?php

namespace App\Http\Controllers\Mairie;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use App\Models\PaiementTaxe; 

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
        if (!$request->ajax()) {
            abort(403, 'Accès non autorisé');
        }

        try {
            $mairie_ref = Auth::guard('mairie')->user()->mairie_ref;

            // Cette ligne fonctionnera maintenant car le modèle est importé
            $query = PaiementTaxe::where('mairie_ref', $mairie_ref)
                ->with([
                    'taxe:id,nom', 
                    'commercant:id,nom,num_commerce'
                ])
                ->select('paiement_taxes.*') 
                ->orderBy('created_at', 'desc');

            return DataTables::of($query)
                ->editColumn('created_at', function ($paiement) {
                    return $paiement->created_at ? $paiement->created_at->format('d/m/Y à H:i') : 'N/A';
                })
                ->addColumn('commercant_info', function ($paiement) {
                    $commercantNom = $paiement->commercant ? e($paiement->commercant->nom) : 'Commerçant inconnu';
                    return $commercantNom . ' <br><small class="text-muted">' . e($paiement->num_commerce) . '</small>';
                })
                ->addColumn('taxe_nom', function ($paiement) {
                    return $paiement->taxe ? e($paiement->taxe->nom) : '<span class="text-muted">Taxe non définie</span>';
                })
                ->editColumn('montant', function ($paiement) {
                    return '<b>' . number_format($paiement->montant, 0, ',', ' ') . ' FCFA</b>';
                })
                ->editColumn('periode', function ($paiement) {
                    return $paiement->periode ? Carbon::parse($paiement->periode)->isoFormat('MMMM YYYY') : 'N/A';
                })
                ->editColumn('statut', function ($paiement) {
                    $badgeClass = $paiement->statut === 'payé' ? 'bg-success' : 'bg-secondary';
                    return '<span class="badge ' . $badgeClass . '">' . e(ucfirst($paiement->statut)) . '</span>';
                })
                ->rawColumns(['commercant_info', 'taxe_nom', 'montant', 'statut'])
                ->make(true);

        } catch (\Exception $e) {
            Log::error('Erreur DataTables get_list_payement: ' . $e->getMessage() . ' à la ligne ' . $e->getLine());
            return response()->json(['error' => "Une erreur interne est survenue. Détails : " . $e->getMessage()], 500);
        }
    }
}