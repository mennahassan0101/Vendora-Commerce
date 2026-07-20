@extends('layouts.app')

@section('title', 'Track Your Order')

@section('content')

    <section class="max-w-3xl mx-auto px-6 lg:px-8 pt-14 pb-24">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-rose-600">Order tracking</p>
        <h1 class="mt-2 font-display text-4xl text-ink">Where's my order?</h1>
        <p class="mt-2 text-mauve">Enter the tracking reference from your confirmation email.</p>

        <form action="{{ route('orders.track') }}" method="GET" class="mt-8 flex gap-3">
            <label for="reference" class="sr-only">Tracking reference</label>
            <input
                id="reference"
                type="text"
                name="reference"
                value="{{ request('reference') }}"
                placeholder="e.g. VEN-AB12CD34"
                class="flex-1 rounded-full border border-rose-200 px-5 py-3 text-sm focus:outline-none focus:border-rose-400"
            >
            <button type="submit"
                    class="rounded-full bg-rose-600 text-white px-7 py-3 text-sm font-semibold hover:bg-rose-700 transition-colors">
                Track
            </button>
        </form>

        @if ($searched)
            @if (! $order)
                <div class="mt-10 rounded-2xl border border-dashed border-rose-200 py-16 text-center">
                    <p class="text-ink font-medium">No order found</p>
                    <p class="mt-1 text-mauve text-sm">Double-check the reference and try again.</p>
                </div>
            @else
                <div class="mt-10 bg-white rounded-2xl border border-rose-100 p-8">
                    <div class="flex items-start justify-between flex-wrap gap-4">
                        <div>
                            <p class="text-xs text-mauve">Tracking reference</p>
                            <p class="font-display text-2xl text-ink">{{ $order->tracking_reference }}</p>
                        </div>
                        <span class="inline-flex items-center rounded-full bg-rose-50 text-rose-700 px-4 py-1.5 text-sm font-semibold">
                            {{ $order->statusLabel() }}
                        </span>
                    </div>

                    {{-- STATUS TIMELINE --}}
                    <div class="mt-8 pt-8 border-t border-rose-100">
                        <h2 class="font-display text-lg text-ink mb-4">Status history</h2>
                        <ol class="space-y-4">
                            @foreach ($order->statusHistory as $event)
                                <li class="flex gap-3">
                                    <span class="mt-1.5 w-2 h-2 rounded-full bg-rose-500 shrink-0"></span>
                                    <div>
                                        <p class="text-sm font-medium text-ink">{{ ucfirst($event->status) }}</p>
                                        @if ($event->note)
                                            <p class="text-sm text-mauve">{{ $event->note }}</p>
                                        @endif
                                        <p class="text-xs text-mauve mt-0.5">{{ $event->created_at->format('M j, Y \a\t g:ia') }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ol>
                    </div>

                    {{-- ITEMS --}}
                    <div class="mt-8 pt-8 border-t border-rose-100">
                        <h2 class="font-display text-lg text-ink mb-4">Items</h2>
                        <div class="space-y-3">
                            @foreach ($order->items as $item)
                                <div class="flex justify-between text-sm">
                                    <span class="text-ink">{{ $item->product_name }} <span class="text-mauve">× {{ $item->quantity }}</span></span>
                                    <span class="text-ink">${{ number_format($item->subtotal, 2) }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4 pt-4 border-t border-rose-100 space-y-1.5">
                            <div class="flex justify-between text-sm text-mauve">
                                <span>Subtotal</span>
                                <span>${{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm text-mauve">
                                <span>Shipping</span>
                                <span>{{ $order->shipping_cost == 0 ? 'Free' : '$' . number_format($order->shipping_cost, 2) }}</span>
                            </div>
                            <div class="flex justify-between font-semibold text-ink pt-1.5">
                                <span>Total</span>
                                <span>${{ number_format($order->total, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- SHIPPING INFO --}}
                    <div class="mt-8 pt-8 border-t border-rose-100 text-sm text-mauve">
                        <h2 class="font-display text-lg text-ink mb-2">Shipping to</h2>
                        <p>{{ $order->customer_name }}</p>
                        <p>{{ $order->shipping_address }}, {{ $order->shipping_city }}</p>
                        <p>{{ $order->shipping_postal_code ? $order->shipping_postal_code . ', ' : '' }}{{ $order->shipping_country }}</p>
                    </div>
                </div>
            @endif
        @endif
    </section>

@endsection