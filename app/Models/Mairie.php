<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// La classe doit étendre Authenticatable
class Mairie extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guard = 'mairie';

    /**
     * Les attributs qui peuvent être assignés en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'genre',
        'date_naissance',
        'type_piece',
        'numero_piece',
        'adresse',
        'telephone1',
        'telephone2',
        // 'type_agent',
        'region',
        'commune', // Utilisation de la clé étrangère
        'role',
        'email',
        'password',
        'otp_code',
        'otp_expires_at',
        'status',
        'last_activity',
        'mairie_ref',
        'added_by',
    ];

    /**
     * Les attributs qui doivent être cachés pour la sérialisation.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'otp_code',
    ];

    /**
     * Les attributs qui doivent être convertis en types natifs.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_naissance' => 'date',
        'otp_expires_at' => 'datetime',
        'last_activity' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Obtenir la commune associée à la mairie.
     */
    public function commune()
    {
        // Laravel va chercher une colonne 'commune_id' dans la table 'mairies'
        return $this->belongsTo(Commune::class);
    }

    /**
     * Obtenir les agents liés à cette mairie.
     */
    public function agents()
    {
        return $this->hasMany(Agent::class, 'mairie_ref', 'mairie_ref');
    }

    /**
     * Obtenir les secteurs gérés par cette mairie.
     */
    public function secteurs()
    {
        return $this->hasMany(Secteur::class, 'mairie_ref', 'mairie_ref');
    }

    /**
     * Obtenir les taxes définies par cette mairie.
     */
    public function taxes()
    {
        return $this->hasMany(Taxe::class, 'mairie_ref', 'mairie_ref');
    }

    /**
     * Obtenir les versements liés à cette mairie.
     */
    public function versement()
    {
        // La relation devrait probablement être hasMany si une mairie a plusieurs versements
        return $this->hasMany(Versement::class, 'mairie_ref', 'mairie_ref');
    }

    /**
     * Obtenir les encaissements liés à cette mairie.
     */
    public function encaissement()
    {
        // De même, hasMany est plus probable
        return $this->hasMany(Encaissement::class, 'mairie_ref', 'mairie_ref');
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
