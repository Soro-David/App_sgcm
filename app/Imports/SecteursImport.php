<?php

namespace App\Imports;

use App\Models\Secteur;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SecteursImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $rows
    */
    public function collection(Collection $rows)
    {
        $user = Auth::guard('mairie')->user() ?: Auth::guard('finance')->user();
        if (!$user) {
            return;
        }

        foreach ($rows as $row) {
            // Slugified headings: 'nom' or 'nom_du_secteur' or 'secteur' or 'secteurs'
            $nom = $row['nom'] ?? $row['nom_du_secteur'] ?? $row['nom_des_secteurs'] ?? $row['secteur'] ?? $row['secteurs'] ?? null;

            if ($nom) {
                // Prevent duplication
                $exists = Secteur::where('mairie_ref', $user->mairie_ref)
                    ->where('nom', $nom)
                    ->exists();

                if ($exists) {
                    continue;
                }

                $prefixCommune = strtoupper(Str::substr($user->name, 0, 3));
                $prefixSecteur = strtoupper(Str::substr($nom, 0, 3));

                // Get last ID again to ensure unique codes within the import
                $lastSecteur = Secteur::where('mairie_ref', $user->mairie_ref)->orderBy('id', 'desc')->first();
                $lastId = $lastSecteur ? $lastSecteur->id : 0;
                $newId = str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);

                $code = $prefixCommune . '-' . $prefixSecteur . '-' . $newId;

                Secteur::create([
                    'mairie_ref' => $user->mairie_ref,
                    'nom' => $nom,
                    'code' => $code,
                ]);
            }
        }
    }
}
