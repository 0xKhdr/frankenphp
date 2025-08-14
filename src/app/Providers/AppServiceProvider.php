<?php

namespace App\Providers;

use App\Actions\Units\ClearUnitsCacheAction;
use App\Actions\Units\ListUnitsAction;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
