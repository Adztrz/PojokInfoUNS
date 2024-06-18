<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Log;

class LogUserLogin
{
    public function handle(Login $event)
    {
        Log::channel('user_actions')->info('User Logged In', [
            'user_id' => $event->user->id,
            'user_name' => $event->user->name,
            'user_role' => $event->user->role_id,
        ]);
    }
}
