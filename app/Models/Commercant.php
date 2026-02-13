<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Commercant extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'email',
        'telephone',
        'adresse',
        'secteur_id',
        'type_contribuable_id',
        'num_commerce',
        'password',
        'type_piece',
        'numero_piece',
        'autre_type_piece',
        'photo_profil',
        'photo_recto',
        'photo_verso',
        'mairie_ref',
        'agent_id',
        'qr_code_path',
        'autre_images',
        'otp_code',
        'otp_expires_at',
        'last_activity',
    ];

    protected $casts = [
        // 'taxes_ids' => 'array',  // C'est correct de l'avoir retiré
        // 'secteur_id' => 'array', // C'est correct de l'avoir retiré
        'last_activity' => 'datetime',
    ];

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function secteur(): BelongsTo
    {
        return $this->belongsTo(Secteur::class);
    }

    public function taxes(): BelongsToMany
    {
        return $this->belongsToMany(Taxe::class);
    }

    public function mairie()
    {
        return $this->belongsTo(Mairie::class, 'mairie_ref', 'mairie_ref');
    }

    public function encaissements()
    {
        return $this->hasMany(Encaissement::class);
    }

    public function typeContribuable()
    {
        return $this->belongsTo(TypeContribuable::class, 'type_contribuable_id');
    }

    /**
     * NOUVELLE MÉTHODE À AJOUTER
     * Un commerçant peut avoir effectué plusieurs paiements de taxes.
     * La liaison se fait via le champ 'num_commerce'.
     */
    public function paiementTaxes()
    {
        return $this->hasMany(PaiementTaxe::class, 'num_commerce', 'num_commerce');
    }

    public function solde()
    {
        return $this->hasOne(Solde::class);
    }
}
