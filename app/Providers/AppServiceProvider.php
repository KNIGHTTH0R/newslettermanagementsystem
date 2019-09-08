<?php

namespace App\Providers;

use App\Delivery;
use App\DeliveryStatus;
use App\Observers\DeliveryObserver;
use App\Observers\DeliveryStatusObserver;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Delivery::observe(DeliveryObserver::class);
        DeliveryStatus::observe(DeliveryStatusObserver::class);
    }
}
