@extends('layouts.app')

@section('title', 'Checkout')

@section('content')

    <section class="max-w-5xl mx-auto px-6 lg:px-8 pt-14 pb-24">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-rose-600">Checkout</p>
        <h1 class="mt-2 font-display text-4xl text-ink">Almost there</h1>
        <p class="mt-2 text-mauve">No account needed — just fill in your details below.</p>

        <div class="mt-10 grid lg:grid-cols-[1fr_320px] gap-10 items-start">

            {{-- FORM --}}
            <form action="{{ route('checkout.store') }}" method="POST" class="space-y-8">
                @csrf

                <div class="bg-white rounded-2xl border border-rose-100 p-6">
                    <h2 class="font-display text-xl text-ink mb-4">Contact</h2>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-ink mb-1">Full name</label>
                            <input id="name" name="name" type="text" value="{{ old('name') }}" required
                                   class="w-full rounded-lg border border-rose-200 px-4 py-2.5 focus:outline-none focus:border-rose-400">
                            @error('name') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-ink mb-1">Email</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required
                                   class="w-full rounded-lg border border-rose-200 px-4 py-2.5 focus:outline-none focus:border-rose-400">
                            @error('email') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label for="phone" class="block text-sm font-medium text-ink mb-1">Phone</label>
                            <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" required
                                   class="w-full rounded-lg border border-rose-200 px-4 py-2.5 focus:outline-none focus:border-rose-400">
                            @error('phone') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-rose-100 p-6">
                    <h2 class="font-display text-xl text-ink mb-4">Shipping address</h2>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label for="address" class="block text-sm font-medium text-ink mb-1">Address</label>
                            <input id="address" name="address" type="text" value="{{ old('address') }}" required
                                   class="w-full rounded-lg border border-rose-200 px-4 py-2.5 focus:outline-none focus:border-rose-400">
                            @error('address') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="city" class="block text-sm font-medium text-ink mb-1">City</label>
                            <input id="city" name="city" type="text" value="{{ old('city') }}" required
                                   class="w-full rounded-lg border border-rose-200 px-4 py-2.5 focus:outline-none focus:border-rose-400">
                            @error('city') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="postal_code" class="block text-sm font-medium text-ink mb-1">Postal code <span class="text-mauve font-normal">(optional)</span></label>
                            <input id="postal_code" name="postal_code" type="text" value="{{ old('postal_code') }}"
                                   class="w-full rounded-lg border border-rose-200 px-4 py-2.5 focus:outline-none focus:border-rose-400">
                            @error('postal_code') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label for="country" class="block text-sm font-medium text-ink mb-1">Country</label>
                            <input id="country" name="country" type="text" value="{{ old('country') }}" required
                                   class="w-full rounded-lg border border-rose-200 px-4 py-2.5 focus:outline-none focus:border-rose-400">
                            @error('country') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-rose-100 p-6">
                    <h2 class="font-display text-xl text-ink mb-4">Payment method</h2>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 rounded-lg border border-rose-200 px-4 py-3 cursor-pointer has-[:checked]:border-rose-500 has-[:checked]:bg-rose-50/60">
                            <input type="radio" name="payment_method" value="cash_on_delivery"
                                   {{ old('payment_method', 'cash_on_delivery') === 'cash_on_delivery' ? 'checked' : '' }}
                                   class="accent-rose-600">
                            <span class="text-sm text-ink">Cash on delivery</span>
                        </label>
                        <label class="flex items-center gap-3 rounded-lg border border-rose-200 px-4 py-3 cursor-pointer has-[:checked]:border-rose-500 has-[:checked]:bg-rose-50/60">
                            <input type="radio" name="payment_method" value="card"
                                   {{ old('payment_method') === 'card' ? 'checked' : '' }}
                                   class="accent-rose-600">
                            <span class="text-sm text-ink">Credit / debit card</span>
                        </label>
                    </div>
                    @error('payment_method') <p class="text-xs text-rose-600 mt-2">{{ $message }}</p> @enderror
                    <p class="mt-3 text-xs text-mauve">No real card processing is wired up yet — this just records your preferred method for now.</p>
                </div>

                <div class="bg-white rounded-2xl border border-rose-100 p-6">
                    <label for="notes" class="block text-sm font-medium text-ink mb-1">Order notes <span class="text-mauve font-normal">(optional)</span></label>
                    <textarea id="notes" name="notes" rows="3"
                              class="w-full rounded-lg border border-rose-200 px-4 py-2.5 focus:outline-none focus:border-rose-400">{{ old('notes') }}</textarea>
                </div>

                <button type="submit"
                        class="w-full rounded-full bg-rose-600 text-white py-3.5 text-sm font-semibold hover:bg-rose-700 transition-colors">
                    Place order — ${{ number_format($subtotal + $shipping, 2) }}
                </button>
            </form>

            {{-- SUMMARY --}}
            <div class="bg-rose-50/60 rounded-2xl p-6 sticky top-28">
                <h2 class="font-display text-xl text-ink mb-4">Order summary</h2>

                <div class="space-y-3 max-h-64 overflow-y-auto pr-1">
                    @foreach ($items as $line)
                        <div class="flex justify-between text-sm">
                            <span class="text-ink">{{ $line->product->name }} <span class="text-mauve">× {{ $line->quantity }}</span></span>
                            <span class="text-ink whitespace-nowrap">${{ number_format($line->subtotal, 2) }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="border-t border-rose-200 mt-4 pt-4 space-y-2">
                    <div class="flex justify-between text-sm text-mauve">
                        <span>Subtotal</span>
                        <span>${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-mauve">
                        <span>Shipping</span>
                        <span>{{ $shipping == 0 ? 'Free' : '$' . number_format($shipping, 2) }}</span>
                    </div>
                    <div class="flex justify-between font-semibold text-ink pt-2 border-t border-rose-200">
                        <span>Total</span>
                        <span>${{ number_format($subtotal + $shipping, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection