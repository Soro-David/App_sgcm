<?php

namespace App\Http\Controllers\Commercant;

use App\Http\Controllers\Controller;
use App\Models\PaiementTaxe;
use App\Models\Solde;
use App\Models\HistoriqueRecharge;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RechargeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $commercant = auth()->guard('commercant')->user();

        // On récupère le solde. Si inexistant on le crée à 0
        $solde = Solde::firstOrCreate(
            ['commercant_id' => $commercant->id],
            ['montant' => 0]
        );

        // Récupérer les 10 dernières recharges
        $recharges = HistoriqueRecharge::where('commercant_id', $commercant->id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('commercant.recharge.index', compact('solde', 'recharges'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'montant' => 'required|numeric|min:100',
        ]);

        $commercant = auth()->guard('commercant')->user();

        // On récupère ou crée le solde
        $solde = Solde::firstOrCreate(
            ['commercant_id' => $commercant->id],
            ['montant' => 0]
        );

        // Simulation de recharge (en attendant CinetPay)
        $solde->increment('montant', $request->montant);

        // Enregistrer dans l'historique
        HistoriqueRecharge::create([
            'commercant_id' => $commercant->id,
            'montant' => $request->montant,
            'reference' => 'RECH-' . Str::upper(Str::random(10)),
            'mode_paiement' => 'Simulation',
            'statut' => 'réussi'
        ]);

        return redirect()->back()->with('success', 'Votre compte a été rechargé de '.number_format($request->montant, 0, ',', ' ').' FCFA avec succès.');
    }
}
