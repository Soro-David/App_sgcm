<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Secteur extends Model
{
    //
    use HasFactory, Notifiable;
   
    protected $guard = 'mairie';

    protected $fillable = [
        'nom',
        'code',
        'mairie_ref',
    ];

    public function mairie()
    {
        return $this->belongsTo(Mairie::class, 'mairie_ref', 'mairie_ref');
    }
 
    public function commercant(): BelongsToMany
    {
        return $this->belongsToMany(Commercant::class);
    }
}
