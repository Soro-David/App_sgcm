<?php

namespace App\Exports;

use App\Services\RecetteService; // Importer le service
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RecettesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $request;
    protected $recetteService;

    public function __construct(Request $request)
    {
        $this->request = $request;
        // Instancier le service pour réutiliser la logique de requête
        $this->recetteService = new RecetteService(); 
    }

    /**
     * Récupère les données à exporter en utilisant la même logique que le contrôleur.
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // On réutilise la méthode du service pour construire la requête
        return $this->recetteService->buildRecetteQuery($this->request)->get();
    }

    public function headings(): array
    {
        return [
            'Date du Paiement',
            'Commerçant',
            'Numéro de Commerce',
            'Taxe Concernée',
            'Période',
            'Montant (FCFA)',
        ];
    }

    public function map($paiement): array
    {
        return [
            $paiement->created_at->format('d/m/Y H:i'),
            optional($paiement->commercant)->nom,
            optional($paiement->commercant)->num_commerce,
            $paiement->taxe->nom,
            $paiement->periode->format('M Y'),
            $paiement->montant,
        ];
    }
}