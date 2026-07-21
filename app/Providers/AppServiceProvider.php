<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Events\OrderPlaced;
use App\Listeners\NotifyAdminsOfNewOrder;
use App\Listeners\LogOrderActivity;
use Illuminate\Support\Facades\Event;
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
        View::composer('partials.header', function ($view) {
            $view->with('cartCount', app(\App\Services\CartService::class)->count());
        });
        Event::listen(OrderPlaced::class, NotifyAdminsOfNewOrder::class);
        Event::listen(OrderPlaced::class, LogOrderActivity::class);
    }
}
