<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class PayementTaxe extends Model
{
    protected $table = 'payement_taxes';

    protected $fillable = [
        'secteur_id',
        'taxe_id',
        'mairie_id',
        'num_commerce',
        'montant',
        'statut',
        'periode',
    ];

    protected $casts = [
        'secteur_id' => 'array',
    ];

    public function taxe()
    {
        return $this->belongsTo(Taxe::class);
    }

    public function mairie()
    {
        return $this->belongsTo(Mairie::class);
    }
}
