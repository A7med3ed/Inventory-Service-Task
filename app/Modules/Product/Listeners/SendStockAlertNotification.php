<?php

namespace App\Modules\Product\Listeners;

use App\Modules\Product\Events\StockBelowThreshold;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendStockAlertNotification implements ShouldQueue
{
    public function handle(StockBelowThreshold $event): void
    {
        Log::warning('Low stock alert', [
            'product_id'     => $event->product->id,
            'sku'            => $event->product->sku,
            'name'           => $event->product->name,
            'stock_quantity' => $event->product->stock_quantity,
            'threshold'      => $event->product->low_stock_threshold,
        ]);
    }
}
