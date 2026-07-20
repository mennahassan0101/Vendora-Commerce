<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(CartService $cart)
    {
        return view('cart.index', [
            'items' => $cart->items(),
            'subtotal' => $cart->subtotal(),
        ]);
    }

    public function store(Request $request, CartService $cart): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $product = Product::findOrFail($data['product_id']);

        if (! $product->isPurchasable()) {
            return back()->with('cart_error', 'That product is currently unavailable.');
        }

        $cart->add($product, $data['quantity'] ?? 1);

        return back()->with('cart_success', "{$product->name} was added to your cart.");
    }

    public function update(Request $request, int $product, CartService $cart): RedirectResponse
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        $cart->update($product, $data['quantity']);

        return back()->with('cart_success', 'Cart updated.');
    }

    public function destroy(int $product, CartService $cart): RedirectResponse
    {
        $cart->remove($product);

        return back()->with('cart_success', 'Item removed from your cart.');
    }

    public function clear(CartService $cart): RedirectResponse
    {
        $cart->clear();

        return back()->with('cart_success', 'Your cart is now empty.');
    }
}