<?php

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\EmailVerified;
use App\Listeners\EmailVerification;

class EventServiceProvider extends ServiceProvider {

    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        EmailVerified::class => [
            EmailVerification::class
        ]
    ];


    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

}

