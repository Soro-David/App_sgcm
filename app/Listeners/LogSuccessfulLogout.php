<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use App\Models\UserLog;


class LogSuccessfulLogout
{
    public function handle(Logout $event)
    {
        if (!$event->user) {
            return;
        }

        UserLog::create([
            'user_id' => $event->user->id,
            'user_type' => get_class($event->user),
            'event' => 'logout',
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
        ]);
    }
}

