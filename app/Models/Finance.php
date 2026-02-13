<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Finance extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guard = 'mairie';

    protected $table = 'finances';

    protected $fillable = [
        'mairie_ref',
        'name',
        'genre',
        'date_naissance',
        'type_piece',
        'numero_piece',
        'adresse',
        'telephone1',
        'telephone2',
        'region',
        'commune',
        'role',
        'email',
        'password',
        'otp_code',
        'otp_expires_at',
        'status',
        'added_by',
        'remember_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'otp_expires_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $attributes = [
        'status' => 'pending',
    ];
    
}
