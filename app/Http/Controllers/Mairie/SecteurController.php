<?php

namespace App\Http\Controllers\Mairie;

use App\Http\Controllers\Controller;
use App\Models\Secteur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class SecteurController extends Controller
{
    public function index()
    {
        return view('mairie.secteur.index');
    }

    public function create()
    {
        return view('mairie.secteur.create');
    }

    /**
     * Enregistre un nouveau secteur dans la base de données.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
        ]);

        try {
            $mairie = Auth::guard('mairie')->user() ?: Auth::guard('finance')->user();

            if (! $mairie) {
                return back()->with('error', 'Aucune mairie associée à cet utilisateur.')->withInput();
            }

            $nomSecteur = $request->nom;
            // Correction : Utilisation de 'name' au lieu de 'nom' pour le modèle Mairie
            $prefixCommune = strtoupper(Str::substr($mairie->name, 0, 3));
            $prefixSecteur = strtoupper(Str::substr($nomSecteur, 0, 3));

            // On calcule l'ID suivant par mairie pour garantir l'unicité du code
            $lastSecteur = Secteur::where('mairie_ref', $mairie->mairie_ref)->orderBy('id', 'desc')->first();
            $lastId = $lastSecteur ? $lastSecteur->id : 0;
            $newId = str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);

            $code = $prefixCommune.'-'.$prefixSecteur.'-'.$newId;

            // Enregistrement du secteur
            Secteur::create([
                'mairie_ref' => $mairie->mairie_ref,
                'nom' => $nomSecteur,
                'code' => $code,
            ]);

            return redirect()->route('mairie.secteurs.index')->with('success', 'Secteur ajouté avec succès.');

        } catch (\Exception $e) {
            // Affichage de l'erreur réelle pour le débogage
            return back()->with('error', 'Une erreur est survenue : '.$e->getMessage())->withInput();
        }
    }

    /**
     * Génère un code secteur unique pour une requête AJAX.
     */
    public function genererCodeSecteurAjax(Request $request)
    {
        $nomSecteur = $request->input('nom');
        $mairie = Auth::guard('mairie')->user() ?: Auth::guard('finance')->user();

        if (! $mairie || ! $nomSecteur) {
            return response()->json(['error' => 'Données manquantes'], 422);
        }

        $prefixCommune = strtoupper(Str::substr($mairie->name, 0, 3));
        $prefixSecteur = strtoupper(Str::substr($nomSecteur, 0, 3));

        // S’assurer que le code est unique par mairie
        $lastSecteur = Secteur::where('mairie_ref', $mairie->mairie_ref)->orderBy('id', 'desc')->first();
        $lastId = $lastSecteur ? $lastSecteur->id : 0;
        $newId = str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);

        $code = $prefixCommune.'-'.$prefixSecteur.'-'.$newId;

        return response()->json(['code' => $code]);
    }

    public function get_list_secteurs(Request $request)
    {
        if ($request->ajax()) {
            $user = Auth::guard('mairie')->user() ?: Auth::guard('finance')->user();
            if (! $user) {
                return response()->json(['error' => 'Non authentifié'], 401);
            }
            $mairie_ref = $user->mairie_ref;
            $data = Secteur::where('mairie_ref', $mairie_ref)->latest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d/m/Y à H:i');
                })
                ->addColumn('action', function ($row) {
                    return '
                        <div class="d-flex justify-content-center gap-2">
                            <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="'.$row->id.'">
                                <i class="fas fa-edit"></i>
                            </button>

                            <button type="button" class="btn btn-sm btn-danger btn-delete" data-id="'.$row->id.'">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    ';
                })

                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function edit($id)
    {
        $secteur = Secteur::findOrFail($id);

        return response()->json([
            'success' => true,
            'secteur' => $secteur,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:secteurs,code,'.$id,
        ]);

        try {
            $secteur = Secteur::findOrFail($id);
            $secteur->update([
                'nom' => $request->nom,
                'code' => $request->code,
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Secteur mis à jour avec succès.',
                ]);
            }

            return redirect()->route('mairie.secteurs.index')->with('success', 'Secteur mis à jour avec succès.');

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Une erreur est survenue : '.$e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Une erreur est survenue : '.$e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $secteur = Secteur::findOrFail($id);
            $secteur->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Secteur supprimé avec succès.',
                ]);
            }

            return redirect()->route('mairie.secteurs.index')->with('success', 'Secteur supprimé avec succès.');

        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Une erreur est survenue : '.$e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Une erreur est survenue : '.$e->getMessage());
        }
    }
}
