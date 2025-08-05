<?php

namespace App\Models;

// IMPORTANT : Étendre Authenticatable pour que le modèle puisse être utilisé pour la connexion
use Illuminate\Foundation\Auth\User as Authenticatable; 
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

// La classe doit étendre Authenticatable
class Financier extends Authenticatable
{
    use HasFactory, Notifiable;
   
    protected $guard = 'financier';

    protected $fillable = [
        'name',
        'email',
        'region',
        'commune',
        'status',
        'otp_code',
        'otp_expires_at',
        'password',
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