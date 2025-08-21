<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class RecettesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithColumnFormatting
{
    /**
     * @var \Illuminate\Support\Collection
     */
    protected $paiements;

    /**
     * Le constructeur accepte maintenant une collection de données,
     * au lieu de l'objet Request.
     */
    public function __construct(Collection $paiements)
    {
        $this->paiements = $paiements;
    }

    /**
     * Retourne la collection de données qui a été passée au constructeur.
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->paiements;
    }

    /**
     * Définit les en-têtes des colonnes du fichier Excel.
     */
    public function headings(): array
    {
        return [
            'Date du Paiement',
            'Commerçant',
            'Numéro de Commerce',
            'Taxe Concernée',
            'Période',
            'Montant (FCFA)',
            'Statut Encaissement',
            'Agent Encaisseur',
        ];
    }

    /**
     * Mappe les données de chaque paiement sur les colonnes correspondantes.
     */
    public function map($paiement): array
    {
        return [
            Date::dateTimeToExcel($paiement->created_at), // Formatage correct pour les dates Excel
            optional($paiement->commercant)->nom,
            optional($paiement->commercant)->num_commerce,
            $paiement->taxe->nom,
            $paiement->periode, // Laisser Excel formater cette date via columnFormats
            $paiement->montant,
            $paiement->statut_encaissement, // Colonne ajoutée
            $paiement->agent_encaisseur,   // Colonne ajoutée
        ];
    }

    /**
     * Définit le format des colonnes dans Excel (dates, nombres, etc.).
     */
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'E' => 'mmm yyyy',
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }
}