<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Secteur extends Model
{
    //
    use HasFactory, Notifiable;
   
    protected $guard = 'mairie';

    protected $fillable = [
        'nom',
        'code',
        'mairie_id',
       
    ];

    public function mairie()
    {
        return $this->belongsTo(Mairie::class);
    }
}
