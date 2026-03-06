<?php

namespace App\Imports;

use App\Models\Taxe;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;

class TaxesImport implements ToCollection, WithHeadingRow
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
            $nom = $row['nom'] ?? $row['nom_de_la_taxe'] ?? $row['taxe'] ?? null;
            $frequence = $row['frequence'] ?? $row['periodicite'] ?? null;
            $montantRaw = $row['montant'] ?? $row['montant_fcfa'] ?? $row['prix'] ?? 0;


            // Sanitize amount: remove spaces, replace comma with dot 
            $montant = str_replace(' ', '', (string)$montantRaw);
            $montant = str_replace(',', '.', $montant);
            $montant = (float) $montant;

            if ($nom && $frequence) {
                // Ensure frequency is lowercase and valid
                $frequence = strtolower($frequence);
                if (!in_array($frequence, ['jour', 'mois', 'an'])) {
                    $frequence = 'mois'; // Default or skip
                }

                Taxe::updateOrCreate(
                    [
                        'mairie_ref' => $user->mairie_ref,
                        'nom' => $nom,
                        'frequence' => $frequence,
                    ],
                    [
                        'montant' => $montant,
                        'description' => 'Importé via fichier',
                    ]
                );
            }
        }
    }
}
