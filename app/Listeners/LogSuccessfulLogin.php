<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\UserLog;

class LogSuccessfulLogin
{
    public function handle(Login $event)
    {
        if (!$event->user) {
            return;
        }

        UserLog::create([
            'user_id' => $event->user->id,
            'user_type' => get_class($event->user),
            'event' => 'login',
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
        ]);
    }
}
