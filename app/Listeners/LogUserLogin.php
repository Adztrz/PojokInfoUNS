<?php

namespace App\Listeners;

use IlluminateAuthEventsLogin;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Login;


class LogUserLogin
{

    
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
    /**
     * Handle the event.
     *
     * @param  Login  $event
     *     * @return void
     */
    public function handle(Login $event)
    {
        Log::channel('user_actions')->info('User Logged In', ['user_id' => $event->user->id]);
    }
}
