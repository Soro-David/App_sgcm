<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; 
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

// La classe doit étendre Authenticatable
class Mairie extends Authenticatable
{
    use HasFactory, Notifiable;
   
    protected $guard = 'mairie';

    protected $fillable = [
        'name', 'genre', 'date_naissance', 'type_piece', 'numero_piece',
        'adresse', 'telephone1', 'telephone2', 'type', 'region', 'commune',
        'role', 'email', 'password', 'otp_code', 'otp_expires_at', 'status',
        "created_at"
    ];

   
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime', 
        'updated_at' => 'datetime',
    ];

    public function commune()
    {
        // Laravel va chercher une colonne 'commune_id' dans la table 'mairies'
        return $this->belongsTo(Commune::class);
    }

    public function agents()
    {
        return $this->hasMany(Agent::class);
    }

    public function secteurs()
    {
        return $this->hasMany(Secteur::class);
    }
    public function taxes()
    {
        return $this->hasMany(Taxe::class);
    }

     public function versement()
    {
        return $this->belongsToMany(Versement::class);
    }

    public function encaissement()
    {
        return $this->belongsToMany(Encaissement::class);
    }


}