<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    protected $fillable = [
        'user_id', 'user_type', 'event', 'ip_address', 'user_agent'
    ];

    public function user()
    {
        return $this->morphTo();
    }
}