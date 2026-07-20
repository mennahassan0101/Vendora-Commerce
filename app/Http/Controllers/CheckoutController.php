<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use RuntimeException;

class CheckoutController extends Controller
{
    public function index(CartService $cart)
    {
        $items = $cart->items();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('cart_error', 'Your cart is empty.');
        }

        $subtotal = $cart->subtotal();

        return view('checkout.index', [
            'items' => $items,
            'subtotal' => $subtotal,
            'shipping' => Order::calculateShipping($subtotal),
        ]);
    }

    public function store(Request $request, CartService $cart, OrderService $orders): RedirectResponse
    {
        $items = $cart->items();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('cart_error', 'Your cart is empty.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'country' => ['required', 'string', 'max:100'],
            'payment_method' => ['required', 'in:cash_on_delivery,card'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $order = $orders->placeOrder($data, $items);
        } catch (RuntimeException $e) {
            return back()->withInput()->with('cart_error', $e->getMessage());
        }

        $cart->clear();

        return redirect()
            ->route('orders.track', ['reference' => $order->tracking_reference])
            ->with('cart_success', "Order placed! Your tracking reference is {$order->tracking_reference} — save it to check your order status.");
    }
}