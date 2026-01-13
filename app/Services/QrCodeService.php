<?php

namespace App\Services;

use App\Models\Commercant;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Storage;

class QrCodeService
{
    public function generateForCommercant(Commercant $commercant): string
    {
        $secteurNom = $commercant->secteur->nom ?? 'Non défini';
        $mairieNom = $commercant->mairie->nom ?? 'Non défini';

        $qrData = sprintf(
            "ID: %s\nNuméro commerce: %s\nTéléphone: %s\nSecteur: %s\nMairie: %s\nURL: %s",
            $commercant->id,
            $commercant->num_commerce,
            $commercant->telephone ?? 'Non fourni',
            $secteurNom,
            $mairieNom,
            route('agent.contribuable.virtual_card', $commercant->id)
        );

        $result = Builder::create()
            ->writer(new PngWriter)
            ->data($qrData)
            ->size(250)
            ->margin(10)
            ->build();

        $fileName = 'commercants/qrcodes/commercant_'.$commercant->id.'_'.time().'.png';
        Storage::disk('public')->put($fileName, $result->getString());

        return $fileName;
    }
}
