<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Finance;
use App\Models\Financier;
use App\Models\Mairie;
use Illuminate\Http\Request;

class FiliationController extends Controller
{
    /**
     * Retourne toutes les filiations distinctes (non nulles et non vides)
     * depuis les tables agents, mairies, finances, financiers
     */
    public function index(Request $request)
    {
        $q = trim($request->get('q', ''));

        // Filtre optionnel par terme de recherche (LIKE)
        $filter = function ($query) use ($q) {
            if ($q !== '') {
                $query->where('filiation', 'LIKE', '%' . $q . '%');
            }
        };

        $filiations = Agent::whereNotNull('filiation')->where('filiation', '!=', '')->where($filter)->distinct()->pluck('filiation')
            ->merge(Mairie::whereNotNull('filiation')->where('filiation', '!=', '')->where($filter)->distinct()->pluck('filiation'))
            ->merge(Finance::whereNotNull('filiation')->where('filiation', '!=', '')->where($filter)->distinct()->pluck('filiation'))
            ->merge(Financier::whereNotNull('filiation')->where('filiation', '!=', '')->where($filter)->distinct()->pluck('filiation'))
            ->unique()
            ->sort()
            ->values();

        // Format Select2-compatible : [{id, text}, ...]
        $results = $filiations->map(fn($f) => ['id' => $f, 'text' => $f])->values();

        return response()->json([
            'results'    => $results,
            'pagination' => ['more' => false],
        ]);
    }
}
