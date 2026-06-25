<?php
// app/Providers/SubscriptionServiceProvider.php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;

class SubscriptionServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Configure cache for subscription notifications
        Cache::extend('subscription', function ($app) {
            return Cache::store('file');
        });
    }
}