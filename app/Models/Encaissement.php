<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Encaissement extends Model
{
    use HasFactory;
   
    protected $fillable = [
        'montant_percu',
        'montant_verse',
        'reste',
        'agent_id',
        'mairie_id',
        'taxe_id',
        'num_commerce',
        'statut'
    ];

    /**
     * L'encaissement appartient à une Mairie.
     */
    public function mairie()
    {
        return $this->belongsTo(Mairie::class);
    }

    /**
     * L'encaissement a été réalisé par un Agent.
     */
    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    /**
     * L'encaissement concerne une Taxe.
     */
    public function taxe()
    {
        return $this->belongsTo(Taxe::class);
    }

    /**
     * L'encaissement est lié à un Commerçant via le numéro de commerce.
     * On spécifie les clés locales et étrangères car elles ne suivent pas la convention standard.
     */
    public function commercant()
    {
        return $this->belongsTo(Commercant::class, 'num_commerce', 'num_commerce');
    }
}