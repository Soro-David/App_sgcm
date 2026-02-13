<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriqueRecharge extends Model
{
    use HasFactory;

    protected $fillable = [
        'commercant_id',
        'montant',
        'reference',
        'mode_paiement',
        'statut',
    ];

    public function commercant()
    {
        return $this->belongsTo(Commercant::class);
    }
}
