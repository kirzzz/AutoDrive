<?php

namespace App\Providers;

use App\Actions\AutoCatalogParseAction;
use App\Contracts\AutoCatalogActionContract;
use Illuminate\Support\ServiceProvider;

class ActionServiceProvider extends ServiceProvider
{
    public array $bindings = [
        AutoCatalogActionContract::class => AutoCatalogParseAction::class
    ];
}
