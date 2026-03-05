<?php

namespace App\Exports;

use App\Models\Taxe;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TaxesExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $user = Auth::guard('mairie')->user() ?: Auth::guard('finance')->user();
        if (! $user) {
            return collect([]);
        }

        return Taxe::where('mairie_ref', $user->mairie_ref)->get();
    }

    public function headings(): array
    {
        return [
            'Nom de la taxe',
            'Fréquence',
            'Montant (FCFA)',
            'Date de création',
        ];
    }

    /**
     * @var Taxe
     */
    public function map($taxe): array
    {
        return [
            $taxe->nom,
            ucfirst($taxe->frequence),
            $taxe->montant,
            $taxe->created_at->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E74A3B'],
                ],
            ],
        ];
    }
}
