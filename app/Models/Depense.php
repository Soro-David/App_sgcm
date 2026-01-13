<?php
// app/Models/Depense.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depense extends Model
{
    use HasFactory;

    // ... $fillable et $casts restent les mêmes ...
    protected $fillable = [
        'motif',
        'description',
        'montant',
        'date_depense',
        'mode_paiement',
        'reference',
        'agent_id',
        'mairie_ref',
        'piece_jointe',
    ];

    protected $casts = [
        'date_depense' => 'date',
    ];

    /**
     * Obtenir l'agent qui a enregistré la dépense.
     * La relation doit pointer vers le modèle Agent.
     */
    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id'); // <-- MODIFIÉ
    }

    /**
     * Obtenir la mairie à laquelle la dépense est liée.
     */
    public function mairie()
    {
        return $this->belongsTo(Mairie::class, 'mairie_ref');
    }
}