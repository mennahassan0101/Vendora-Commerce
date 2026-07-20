<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class OrderService
{
    /**
     * @param  array  $customer  validated checkout form data
     * @param  Collection  $lines  cart lines from CartService::items()
     *
     * @throws RuntimeException if any item no longer has enough stock —
     *         the whole transaction rolls back, so nothing is half-created
     *         and no stock is touched.
     */
    public function placeOrder(array $customer, Collection $lines): Order
    {
        return DB::transaction(function () use ($customer, $lines) {
            $subtotal = round((float) $lines->sum('subtotal'), 2);
            $shipping = Order::calculateShipping($subtotal);
            $total = round($subtotal + $shipping, 2);

            $order = Order::create([
                'tracking_reference' => $this->generateTrackingReference(),
                'customer_name' => $customer['name'],
                'customer_email' => $customer['email'],
                'customer_phone' => $customer['phone'],
                'shipping_address' => $customer['address'],
                'shipping_city' => $customer['city'],
                'shipping_postal_code' => $customer['postal_code'] ?? null,
                'shipping_country' => $customer['country'],
                'payment_method' => $customer['payment_method'],
                'status' => 'pending',
                'subtotal' => $subtotal,
                'shipping_cost' => $shipping,
                'total' => $total,
                'notes' => $customer['notes'] ?? null,
            ]);

            foreach ($lines as $line) {
                // Lock the row so two simultaneous checkouts can't both
                // succeed against the same last unit of stock.
                $product = Product::where('id', $line->product->id)->lockForUpdate()->first();

                if (! $product || $product->stock < $line->quantity) {
                    throw new RuntimeException("Sorry, \"{$line->product->name}\" no longer has enough stock for that quantity.");
                }

                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_price' => $product->price,
                    'quantity' => $line->quantity,
                    'subtotal' => $line->subtotal,
                ]);

                $product->decrement('stock', $line->quantity);
            }

            $order->statusHistory()->create([
                'status' => 'pending',
                'note' => 'Order placed by customer.',
            ]);

            return $order;
        });
    }

    protected function generateTrackingReference(): string
    {
        do {
            $reference = 'VEN-' . strtoupper(Str::random(8));
        } while (Order::where('tracking_reference', $reference)->exists());

        return $reference;
    }
}