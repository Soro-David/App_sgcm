<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Foundation\Auth\User as Authenticatable; 
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class Encaissement extends Model
{
    use HasFactory, Notifiable;
   
    protected $guard = 'mairie';

    protected $fillable = [
        'id',
        'montant_percu',
        'montant_verse',
        'reste',
        'agent_id',
        'mairie_id',
        'taxe_id',
        'num_commerce',
        'statut'
       
    ];

    public function mairie()
    {
        return $this->belongsTo(Mairie::class);
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function encaissements()
    {
        return $this->hasMany(Encaissement::class, 'agent_id');
    }


    public function commerce()
    {
        return $this->belongsTo(Commerce::class);
    }
}
