<?php

namespace App\Http\Controllers\Mairie;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use App\Models\Mairie;
use App\Models\Agent;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $onlineAgents = collect();
        $onlineMairies = collect();
        return view('mairie.dashboard', compact('onlineAgents', 'onlineMairies'));
    }

    /**
     * Récupère les statuts des utilisateurs pour l'affichage en temps réel.
     */
    public function getUsersStatus(): JsonResponse
    {
        $currentMairie = Auth::guard('mairie')->user();
        $onlineThreshold = Carbon::now()->subMinutes(5);

        $agents = Agent::with(['logs' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->whereHas('mairie', function ($query) use ($currentMairie) {
                $query->where('commune', $currentMairie->commune)
                    ->where('region', $currentMairie->region);
            })
            ->whereDate('last_activity', today())
            ->get();
        
        $mairies = Mairie::with(['logs' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->where('commune', $currentMairie->commune)
            ->where('region', $currentMairie->region)
            ->where('id', '!=', $currentMairie->id)
            ->whereDate('last_activity', today())
            ->get();

        $users = $mairies->merge($agents);

        $data = $users->map(function ($user) use ($onlineThreshold) {
            $isOnline = $user->last_activity && $user->last_activity >= $onlineThreshold;
            
            $latestLogin = $user->logs->where('event', 'login')->first();
            $latestLogout = $user->logs->where('event', 'logout')->first();

            return [
                'name' => $user->name,
                'role' => $user instanceof Agent ? 'Agent' : 'Mairie',
                'status' => $isOnline ? 'En ligne' : 'Déconnecté',
                'login_time' => $latestLogin ? $latestLogin->created_at->format('d/m/Y H:i') : 'N/A',
                'logout_time' => !$isOnline ? ($latestLogout ? $latestLogout->created_at->format('d/m/Y H:i') : $user->last_activity->format('d/m/Y H:i')) : '-',
            ];
        });

        return response()->json($data);
    }

    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
