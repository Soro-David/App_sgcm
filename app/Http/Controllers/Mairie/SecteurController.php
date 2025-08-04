<?php

namespace App\Http\Controllers\Mairie;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Secteur;
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
            $mairie = Auth::guard('mairie')->user(); // ✅ on récupère l'objet mairie complet

            if (!$mairie) {
                return back()->with('error', 'Aucune mairie associée à cet utilisateur.')->withInput();
            }

            $nomSecteur = $request->nom;
            $prefixCommune = strtoupper(Str::substr($mairie->nom, 0, 3));
            $prefixSecteur = strtoupper(Str::substr($nomSecteur, 0, 3));

            // On calcule l'ID suivant manuellement (car ID ne sera dispo qu'après insert)
            $lastId = Secteur::max('id') ?? 0;
            $newId = str_pad($lastId + 1, 3, '0', STR_PAD_LEFT); // exemple : 001, 002

            $code = $prefixCommune . '-' . $prefixSecteur . '-' . $newId;

            // Enregistrement du secteur
            Secteur::create([
                'mairie_id' => $mairie->id,
                'nom' => $nomSecteur,
                'code' => $code,
            ]);

            return redirect()->route('mairie.secteurs.index')->with('success', 'Secteur ajouté avec succès.');

        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de l\'ajout du secteur.')->withInput();
        }
    }


    /**
     * Génère un code secteur unique pour une requête AJAX.
     */
public function genererCodeSecteurAjax(Request $request)
{
    $nomSecteur = $request->input('nom');
    $mairie = Auth::guard('mairie')->user();

    if (!$mairie || !$nomSecteur) {
        return response()->json(['error' => 'Données manquantes'], 422);
    }

    $prefixSecteur = strtoupper(Str::substr($nomSecteur, 0, 3));

    // S’assurer que le code est unique par mairie si nécessaire
    $lastId = Secteur::where('mairie_id', $mairie->id)->max('id') ?? 0;
    $newId = str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);

    $code = $prefixSecteur . '-' . $newId;

    return response()->json(['code' => $code]);
}

public function get_list_secteurs(Request $request)
{
    if ($request->ajax()) {
        $mairie_id = Auth::guard('mairie')->id();
        $data = Secteur::where('mairie_id', $mairie_id)->latest();

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('d/m/Y à H:i');
            })
            ->addColumn('action', function ($row) {
                return '<a href="'.route('mairie.secteurs.edit', $row->id).'" class="btn btn-sm btn-primary">Modifier</a>
                        <form action="'.route('mairie.secteurs.destroy', $row->id).'" method="POST" style="display:inline;">
                            '.csrf_field().method_field('DELETE').'
                            <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                        </form>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}

}
