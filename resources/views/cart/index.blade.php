@extends('layouts.app')

@section('title', 'Your Cart')

@section('content')

    <section class="max-w-5xl mx-auto px-6 lg:px-8 pt-14 pb-24">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-rose-600">Your cart</p>
        <h1 class="mt-2 font-display text-4xl text-ink">
            {{ $items->isEmpty() ? 'Your cart is empty' : 'Review your order' }}
        </h1>

        @if ($items->isEmpty())
            <div class="mt-10 rounded-2xl border border-dashed border-rose-200 py-24 text-center">
                <p class="text-mauve">Nothing here yet — go find something you like.</p>
                <a href="{{ route('products.index') }}"
                   class="mt-6 inline-flex items-center rounded-full bg-rose-600 text-white px-7 py-3 text-sm font-semibold hover:bg-rose-700 transition-colors">
                    Browse products
                </a>
            </div>
        @else
            <div class="mt-10 grid lg:grid-cols-[1fr_320px] gap-10 items-start">

                {{-- LINE ITEMS --}}
                <div class="space-y-6">
                    @foreach ($items as $line)
                        <div class="flex gap-5 pb-6 border-b border-rose-100">
                            <a href="{{ route('products.show', $line->product) }}" class="shrink-0">
                                <x-blob-frame class="w-24 h-24 p-3">
                                    @if ($line->product->primaryImage)
                                        <img src="{{ asset('storage/' . $line->product->primaryImage->path) }}"
                                             alt="{{ $line->product->name }}"
                                             class="w-full h-full object-contain">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-mauve text-[10px] text-center">
                                            No image
                                        </div>
                                    @endif
                                </x-blob-frame>
                            </a>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <a href="{{ route('products.show', $line->product) }}"
                                           class="font-display text-lg text-ink hover:text-rose-700 transition-colors">
                                            {{ $line->product->name }}
                                        </a>
                                        <p class="text-sm text-mauve mt-0.5">${{ number_format($line->product->price, 2) }} each</p>
                                    </div>
                                    <p class="font-semibold text-ink whitespace-nowrap">${{ number_format($line->subtotal, 2) }}</p>
                                </div>

                                <div class="mt-4 flex items-center gap-4">
                                    <form action="{{ route('cart.update', $line->product->id) }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <label for="qty-{{ $line->product->id }}" class="sr-only">Quantity</label>
                                        <input
                                            id="qty-{{ $line->product->id }}"
                                            type="number"
                                            name="quantity"
                                            min="1"
                                            max="{{ $line->product->stock }}"
                                            value="{{ $line->quantity }}"
                                            onchange="this.form.submit()"
                                            class="w-16 rounded-lg border border-rose-200 px-2 py-1.5 text-center text-sm focus:outline-none focus:border-rose-400"
                                        >
                                    </form>

                                    <form action="{{ route('cart.destroy', $line->product->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm text-mauve hover:text-rose-600 transition-colors">
                                            Remove
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <form action="{{ route('cart.clear') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm text-mauve hover:text-rose-600 transition-colors">
                            Clear cart
                        </button>
                    </form>
                </div>

                {{-- SUMMARY --}}
                <div class="bg-rose-50/60 rounded-2xl p-6 sticky top-28">
                    <h2 class="font-display text-xl text-ink mb-4">Order summary</h2>

                    <div class="flex justify-between text-sm text-mauve">
                        <span>Subtotal</span>
                        <span>${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-mauve mt-2">
                        <span>Shipping</span>
                        <span>Calculated at checkout</span>
                    </div>

                    <div class="border-t border-rose-200 mt-4 pt-4 flex justify-between font-semibold text-ink">
                        <span>Total</span>
                        <span>${{ number_format($subtotal, 2) }}</span>
                    </div>

                    <a
                        href="{{ route('checkout.index') }}"
                        class="mt-6 block w-full text-center rounded-full bg-rose-600 text-white py-3.5 text-sm font-semibold hover:bg-rose-700 transition-colors"
                    >
                        Proceed to checkout
                    </a>
                </div>
            </div>
        @endif
    </section>

@endsection