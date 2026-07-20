<?php

namespace App\Notifications\Admin;

use App\Models\Product;
use Illuminate\Notifications\Notification;

class LowStockAlert extends Notification
{
    public function __construct(public Product $product)
    {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'low_stock',
            'product_id' => $this->product->id,
            'message' => "\"{$this->product->name}\" is low on stock ({$this->product->stock} left).",
            'url' => route('admin.products.edit', $this->product),
        ];
    }
}