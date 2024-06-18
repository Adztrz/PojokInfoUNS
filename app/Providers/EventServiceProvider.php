<?php

namespace App\Providers;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Listeners\LogUserLogin;
use App\Listeners\LogUserRegistration;
use App\Listeners\LogPostCreated;
use App\Listeners\LogUserLogout;
// use App\Listeners\LogPostUpdated;
// use App\Listeners\LogPostDeleted;
// use App\Events\PostUpdated;
// use App\Events\PostDeleted;
use App\Events\PostCreated;
use App\Events\UserLoggedOut;

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
        Logout::class => [
            LogUserLogout::class,
        ],
        PostCreated::class => [
            LogPostCreated::class,
        ],
        UserLoggedOut::class => [
            LogUserLogout::class,
        ],
    ];


    public function boot()
    {
        parent::boot();
    }
}

