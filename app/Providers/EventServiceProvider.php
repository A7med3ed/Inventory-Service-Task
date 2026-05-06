<?php

namespace App\Providers;

use App\Modules\Product\Events\StockBelowThreshold;
use App\Modules\Product\Listeners\SendStockAlertNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        StockBelowThreshold::class => [
            SendStockAlertNotification::class,
        ],
    ];
}
