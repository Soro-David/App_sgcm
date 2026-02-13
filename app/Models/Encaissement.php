<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Encaissement extends Model
{
    use HasFactory;

    protected $fillable = [
        'montant_percu',
        'montant_verse',
        'reste',
        'agent_id',
        'mairie_ref',
        'taxe_id',
        'num_commerce',
        'recorded_by',
        'statut',
    ];

    /**
     * L'encaissement appartient à une Mairie.
     */
    public function mairie()
    {
        return $this->belongsTo(Mairie::class, 'mairie_ref', 'mairie_ref');
    }

    /**
     * L'encaissement a été réalisé par un Agent (collecteur terrain).
     */
    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    /**
     * L'encaissement a été enregistré par un utilisateur Mairie (caisse).
     */
    public function recorder()
    {
        return $this->belongsTo(Mairie::class, 'recorded_by');
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
