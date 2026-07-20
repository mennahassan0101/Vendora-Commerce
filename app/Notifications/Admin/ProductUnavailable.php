<?php

namespace App\Notifications\Admin;

use App\Models\Product;
use Illuminate\Notifications\Notification;

class ProductUnavailable extends Notification
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
        $reason = $this->product->stock === 0 ? 'out of stock' : 'deactivated';

        return [
            'type' => 'product_unavailable',
            'product_id' => $this->product->id,
            'message' => "\"{$this->product->name}\" is now {$reason} and can't be purchased.",
            'url' => route('admin.products.edit', $this->product),
        ];
    }
}