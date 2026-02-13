<?php

namespace App\Http\Controllers\Mairie;

use App\Http\Controllers\Controller;
use App\Models\Depense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class DepenseController extends Controller
{
    public function index()
    {
        return view('mairie.comptabilite.depense.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'motif' => 'required|string|max:255',
            'montant' => 'required|numeric|min:0',
            'date_depense' => 'required|date',
            'description' => 'required|string',
            'mode_paiement' => 'required|string',
            'reference' => 'nullable|string|max:255',
            'piece_jointe' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $data = $request->except('piece_jointe');
            $data['agent_id'] = Auth::id();
            $data['mairie_ref'] = Auth::user()->mairie_ref; 

            if ($request->hasFile('piece_jointe')) {
                $filePath = $request->file('piece_jointe')->store('pieces_jointes/depenses', 'public');
                $data['piece_jointe'] = $filePath;
            }

            Depense::create($data);

            return response()->json(['success' => 'Dépense enregistrée avec succès !']);
        } catch (\Exception $e) {
            Log::error('Erreur enregistrement dépense: ' . $e->getMessage());
            return response()->json(['error' => 'Une erreur interne est survenue.'], 500);
        }
    }

        public function show(Depense $depense)
    {
        if ($depense->mairie_ref !== Auth::user()->mairie_ref) {
            abort(403, 'Accès non autorisé.');
        }
        return view('mairie.comptabilite.depense.show', compact('depense'));
    }


    public function edit(Depense $depense)
    {
        if ($depense->mairie_ref !== Auth::user()->mairie_ref) {
            abort(403, 'Accès non autorisé.');
        }
        return view('mairie.comptabilite.depense.edit', compact('depense'));
    }

    public function update(Request $request, Depense $depense)
    {
        if ($depense->mairie_ref !== Auth::user()->mairie_ref) {
            abort(403, 'Accès non autorisé.');
        }

        $validator = Validator::make($request->all(), [
            'motif' => 'required|string|max:255',
            'montant' => 'required|numeric|min:0',
            'date_depense' => 'required|date',
            'description' => 'required|string',
            'mode_paiement' => 'required|string',
            'reference' => 'nullable|string|max:255',
            'piece_jointe' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $data = $request->except(['piece_jointe', '_token', '_method']);

            if ($request->hasFile('piece_jointe')) {
                if ($depense->piece_jointe) {
                    Storage::disk('public')->delete($depense->piece_jointe);
                }
                $data['piece_jointe'] = $request->file('piece_jointe')->store('pieces_jointes/depenses', 'public');
            }

            $depense->update($data);

            return redirect()->route('mairie.depense.index')->with('success', 'Dépense mise à jour avec succès !');
        } catch (\Exception $e) {
            Log::error('Erreur mise à jour dépense: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur interne est survenue.')->withInput();
        }
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = Depense::where('mairie_ref', Auth::user()->mairie_ref)->latest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('montant', fn($row) => number_format($row->montant, 0, ',', ' ') . ' FCFA')
                ->editColumn('date_depense', fn($row) => $row->date_depense->format('d/m/Y'))
                ->addColumn('action', function($row){
                    $showUrl = route('mairie.depense.show', $row->id);
                    $editUrl = route('mairie.depense.edit', $row->id);

                    $actionBtn  = '<a href="'.$showUrl.'" class="btn btn-info btn-sm me-1" title="Voir les détails">';
                    $actionBtn .= '<i class="fas fa-eye"></i> Voir</a>';

                    $actionBtn .= '<a href="'.$editUrl.'" class="btn btn-success btn-sm" title="Modifier">';
                    $actionBtn .= '<i class="fas fa-edit"></i> Modifier</a>';

                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
}