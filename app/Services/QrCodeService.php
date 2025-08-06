<?php

namespace App\Services;

use App\Models\Commercant;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

class QrCodeService
{
    

 public function generateForCommercant(Commercant $commercant): string
{
    $virtualCardUrl = route('agent.commerce.virtual_card', $commercant->id);

    $result = Builder::create()
        ->writer(new PngWriter())
        ->data($virtualCardUrl)
        ->size(250)
        ->margin(10)
        ->build();

    $fileName = 'commercants/qrcodes/commercant_' . $commercant->id . '_' . time() . '.png';
    Storage::disk('public')->put($fileName, $result->getString());

    return $fileName;
}


}