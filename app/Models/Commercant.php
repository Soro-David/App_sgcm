<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class Commercant extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'nom',
        'email',
        'telephone',
        'adresse',
        'num_commerce',
        'agent_id',
        'mairie_id',
        'secteur_id',
        'taxe_id',
        'password',
    ];

    
    protected $casts = [
        'taxe_id' => 'array',
        'secteur_id' => 'array',
    ];

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function mairie()
    {
        return $this->belongsTo(Mairie::class);
    }

    // Relation personnalisée si tu veux  charger les secteurs depuis le champ JSON
    public function secteurs()
    {
        return $this->belongsToMany(Secteur::class);
    }

    public function taxes()
    {
        return $this->belongsToMany(Taxe::class);
    }

    public function versement()
    {
        return $this->belongsToMany(Versement::class);
    }

    public function encaissement()
    {
        return $this->belongsToMany(Encaissement::class);
    }
    public function encaissements()
{
    return $this->hasMany(Encaissement::class);
}

    
}
