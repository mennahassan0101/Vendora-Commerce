<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected const SESSION_KEY = 'cart';

    /**
     * Add a product to the cart (or increase quantity if already present).
     * Silently ignores products that aren't purchasable.
     */
    public function add(Product $product, int $quantity = 1): void
    {
        if (! $product->isPurchasable()) {
            return;
        }

        $cart = $this->raw();
        $current = $cart[$product->id] ?? 0;
        $cart[$product->id] = $this->clamp($product, $current + $quantity);

        Session::put(self::SESSION_KEY, $cart);
    }

    /**
     * Set a line to an exact quantity. Quantity 0 removes the line.
     */
    public function update(int $productId, int $quantity): void
    {
        $product = Product::find($productId);

        if (! $product) {
            return;
        }

        $cart = $this->raw();

        if ($quantity < 1) {
            unset($cart[$productId]);
        } else {
            $cart[$productId] = $this->clamp($product, $quantity);
        }

        Session::put(self::SESSION_KEY, $cart);
    }

    public function remove(int $productId): void
    {
        $cart = $this->raw();
        unset($cart[$productId]);
        Session::put(self::SESSION_KEY, $cart);
    }

    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    /**
     * Resolved cart lines. Always loads fresh product data so price/stock
     * changes since the item was added are reflected immediately, and
     * anything that's gone out of stock or inactive is dropped silently.
     */
    public function items(): Collection
    {
        $cart = $this->raw();

        if (empty($cart)) {
            return collect();
        }

        return Product::query()
            ->whereIn('id', array_keys($cart))
            ->with('primaryImage')
            ->get()
            ->filter(fn (Product $product) => $product->isPurchasable())
            ->map(function (Product $product) use ($cart) {
                $quantity = $this->clamp($product, $cart[$product->id]);

                return (object) [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => round($product->price * $quantity, 2),
                ];
            })
            ->values();
    }

    public function count(): int
    {
        return (int) $this->items()->sum('quantity');
    }

    public function subtotal(): float
    {
        return round((float) $this->items()->sum('subtotal'), 2);
    }

    protected function clamp(Product $product, int $quantity): int
    {
        return max(1, min($quantity, max($product->stock, 1)));
    }

    protected function raw(): array
    {
        return Session::get(self::SESSION_KEY, []);
    }
}