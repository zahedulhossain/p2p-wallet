<?php

namespace App\Providers;

use App\Services\CurrencyConverter\CurrencyConverter;
use App\Services\CurrencyConverter\OpenExchangeRates;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CurrencyConverter::class, OpenExchangeRates::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
