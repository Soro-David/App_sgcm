<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class Agent extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guard = 'agent';

    protected $fillable = [
        'name',
        'email',
        'region',
        'commune',
        'status',
        'otp_code',
        'otp_expires_at',
        'password',
        'created_at',
        'mairie_id',
        'remember_token',
        'taxe_id',
        'secteur_id'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'taxe_id' => 'array',  
        'secteur_id' => 'array',
    ];

    public function mairie()
    {
        return $this->belongsTo(Mairie::class);
    }

    public function taxes()
    {
        return $this->belongsToMany(Taxe::class); 
    }

    public function secteurs()
    {
        return $this->belongsToMany(Secteur::class);
    }

    public function versement()
    {
        return $this->belongsToMany(Versement::class);
    }

    public function encaissements()
    {
        return $this->hasMany(Encaissement::class, 'agent_id');
    }
}
