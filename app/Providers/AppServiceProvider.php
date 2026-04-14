<?php

namespace App\Providers;
use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Event;
use App\Events\OrderPlaced;
use App\Listeners\SendOrderEmailNotification;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(
            OrderPlaced::class,
            [SendOrderEmailNotification::class, 'handle']
        );
    }
}
