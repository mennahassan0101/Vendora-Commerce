<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Notifications\Admin\LowStockAlert;
use App\Services\AdminNotifier;
use Illuminate\Console\Command;

class NotifyLowStockProducts extends Command
{
    /**
     * Execute the console command.
     */
    protected $signature = 'products:notify-low-stock';

    protected $description = 'Notify admins of all products currently at or below their low-stock threshold';

    public function handle()
    {
        $lowStockProducts = Product::query()
        ->whereColumn('stock', '<=', 'low_stock_threshold')
        ->where('stock', '>', 0)
        ->get();
                if ($lowStockProducts->isEmpty()) {
            $this->info('No low-stock products today.');
            return;
        }

        foreach ($lowStockProducts as $product) {
            AdminNotifier::send(new LowStockAlert($product));
        }

        $this->info("Sent low-stock alerts for {$lowStockProducts->count()} product(s).");
    }
}
