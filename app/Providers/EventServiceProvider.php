<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

//События, которые будут сгенерированы с помощью event:generate
use App\Events\Order\OrderPlaced;
use App\Events\Order\OrderPaid;

//События, которые будут сгенерированы с помощью event:generate
use App\Listeners\Jupiter\Order\MakeOrderXml;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        // Registered::class => [
        //     SendEmailVerificationNotification::class,
        // ],

        OrderPlaced::class => [
            MakeOrderXml::class,
        ],

        OrderPaid::class => [
            MakeOrderXml::class,
        ],
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

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
