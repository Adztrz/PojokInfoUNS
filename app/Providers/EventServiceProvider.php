<?php

namespace App\Providers;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Listeners\LogUserLogin;
use App\Listeners\LogUserRegistration;
use App\Listeners\LogPostCreated;
use App\Events\PostCreated;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Login::class => [
            LogUserLogin::class,
        ],
        Registered::class => [
            LogUserRegistration::class,
            SendEmailVerificationNotification::class,
        ],
        PostCreated::class => [
            LogPostCreated::class,
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}

