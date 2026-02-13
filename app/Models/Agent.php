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
        'type',
        'status',
        'otp_code',
        'otp_expires_at',
        'password',
        'created_at',
        'mairie_ref',
        'remember_token',
        'taxe_id',
        'secteur_id',
        'genre',
        'date_naissance',
        'type_piece',
        'numero_piece',
        'adresse',
        'telephone1',
        'telephone2',
        'last_activity',
        'added_by',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'last_activity' => 'datetime',
        'taxe_id' => 'array',  
        'secteur_id' => 'array',
        // 'secteur_id' => 'integer',
    ];

    public function mairie()
    {
        return $this->belongsTo(Mairie::class, 'mairie_ref', 'mairie_ref');
    }


    public function commune()
    {
        return $this->belongsTo(Commune::class);
    }

    public function taxes()
    {
        return $this->belongsToMany(Taxe::class); 
    }

    public function secteurs()
    {
        return $this->belongsToMany(Secteur::class);
    }

    public function versements()
    {
        return $this->hasMany(Versement::class);
    }

    public function encaissements()
    {
        return $this->hasMany(Encaissement::class, 'agent_id');
    }

    public function logs()
    {
        return $this->morphMany(UserLog::class, 'user');
    }

    public function isOnline()
{
    return $this->last_activity && $this->last_activity->gt(now()->subMinutes(5));
}


}
