<?php

namespace App\Listeners;

use App\Events\AutoCatalogSaving;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Validation\ValidationException;

class ListenerAutoCatalog
{
    /**
     * Handle the event.
     *
     * @param AutoCatalogSaving $event
     * @return void
     * @throws ValidationException
     */
    public function handle(AutoCatalogSaving $event)
    {
        $event->autoCatalog->validate();
    }
}
