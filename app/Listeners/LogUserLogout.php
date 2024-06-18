<?php

namespace App\Listeners;

use App\Events\UserLoggedOut;
use Illuminate\Support\Facades\Log;

class LogUserLogout
{
    public function handle(UserLoggedOut $event)
    {
        Log::channel('user_actions')->info('User Logged Out', [
            'user_id' => $event->user->id,
            'user_name' => $event->user->name,
        ]);
    }
}
