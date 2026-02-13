<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solde extends Model
{
    use HasFactory;

    protected $fillable = [
        'commercant_id',
        'montant',
    ];

    public function commercant()
    {
        return $this->belongsTo(Commercant::class);
    }
}
