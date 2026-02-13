<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Taxe extends Model
{
    //
    use HasFactory, Notifiable;

   
    protected $fillable = ['nom', 'description', 'montant','mairie_ref','frequence'];

    public function mairie()
    {
        return $this->belongsTo(Mairie::class, 'mairie_ref', 'mairie_ref');
    }

    public function agents()
    {
        return $this->belongsToMany(Agent::class, 'agent_taxe');
    }


    public function commercants()
    {
        return $this->belongsToMany(Commercant::class, 'commercant_taxe');
    }


    public function versement() {
        return $this->belongsTo(Versement::class);
    }
    public function payement_taxe()
    {
        return $this->belongsToMany(PaiementTaxe::class);
    }

}
