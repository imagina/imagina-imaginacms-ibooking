<?php

namespace Modules\Ibooking\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

use Illuminate\Support\Facades\Event;
use Modules\Ibooking\Events\Handlers\ProcessReservationOrder;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
    ];

    public function register()
    {

        if (is_module_enabled('Icommerce')) {
          //Listen order processed
          Event::listen(
            "Modules\\Icommerce\\Events\\OrderWasProcessed",
            [ProcessReservationOrder::class, 'handle']
          );
        }

    }
}
