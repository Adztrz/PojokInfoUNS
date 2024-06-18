<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Log;

class LogUserRegistration
{
    public function handle(Registered $event)
    {
        Log::channel('user_actions')->info('User Registered', ['user_id' => $event->user->id]);
    }
}
