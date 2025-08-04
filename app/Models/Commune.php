<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Commune extends Model
{
     use HasFactory, Notifiable;

   
    protected $fillable = [
        'nom',
        'region',
    
    ];

     public function mairies()
    {
        return $this->hasMany(Mairie::class);
    }
}
