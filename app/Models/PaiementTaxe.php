<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaiementTaxe extends Model
{
    use HasFactory; 

    protected $table = 'paiement_taxes';

    protected $fillable = [
        'secteur_id',
        'taxe_id',
        'mairie_ref',
        'num_commerce',
        'montant',
        'statut',
        'periode',
        'recette_effectuee',
    ];

    protected $casts = [
        'periode' => 'date',
        'recette_effectuee' => 'boolean',
    ];

    public function taxe()
    {
        return $this->belongsTo(Taxe::class);
    }

    public function mairie()
    {
        return $this->belongsTo(Mairie::class);
    }

 
    public function commercant()
    {
        return $this->belongsTo(Commercant::class, 'num_commerce', 'num_commerce');
    }
}