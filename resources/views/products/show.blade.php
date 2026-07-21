@extends('layouts.app')

@section('title', $product->name)

@section('content')

    <section class="max-w-7xl mx-auto px-6 lg:px-8 pt-10 pb-4 text-sm text-mauve">
        <a href="{{ route('products.index') }}" class="hover:text-rose-600">Shop</a>
        <span class="mx-1">/</span>
        @if ($product->categories->isNotEmpty())
            <a href="{{ route('products.index', ['category' => $product->categories->first()->slug]) }}" class="hover:text-rose-600">
                {{ $product->categories->first()->name }}
            </a>
            <span class="mx-1">/</span>
        @endif
        <span class="text-ink">{{ $product->name }}</span>
    </section>

    <section class="max-w-7xl mx-auto px-6 lg:px-8 pb-20 grid lg:grid-cols-2 gap-12">

        {{-- IMAGES --}}
        <div>
            <x-blob-frame class="aspect-[4/5] rounded-3xl p-10">
                @if ($product->images->isNotEmpty())
                    <img id="main-image"
                         src="{{ asset('storage/' . ($product->primaryImage->path ?? $product->images->first()->path)) }}"
                         alt="{{ $product->name }}"
                         class="w-full h-full object-contain drop-shadow-md">
                @else
                    <div class="w-2/3 h-2/3 rounded-3xl bg-white/70 border border-rose-200/70 flex items-center justify-center text-mauve text-sm">
                        No image yet
                    </div>
                @endif
            </x-blob-frame>

            @if ($product->images->count() > 1)
                <div class="mt-4 flex gap-3">
                    @foreach ($product->images as $image)
                        <button type="button"
                                class="thumb-btn w-16 h-16 rounded-xl border border-rose-200 overflow-hidden hover:border-rose-400 transition-colors"
                                data-image="{{ asset('storage/' . $image->path) }}">
                            <img src="{{ asset('storage/' . $image->path) }}" alt="" class="w-full h-full object-contain">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- DETAILS --}}
        <div>
            @if ($product->categories->isNotEmpty())
                <p class="text-xs font-semibold uppercase tracking-wider text-rose-600">
                    {{ $product->categories->first()->name }}
                </p>
            @endif

            <h1 class="mt-2 font-display text-4xl text-ink">{{ $product->name }}</h1>

            <div class="mt-4 flex items-baseline gap-3">
                <span class="text-2xl font-semibold text-ink">${{ number_format($product->price, 2) }}</span>
                @if ($product->compare_price && $product->compare_price > $product->price)
                    <span class="text-mauve line-through">${{ number_format($product->compare_price, 2) }}</span>
                @endif
            </div>

            <p class="mt-6 text-mauve leading-relaxed">{{ $product->description }}</p>

            <div class="mt-6">
                @if ($product->isPurchasable())
                    @if ($product->isLowStock())
                        <p class="text-sm font-medium text-rose-600">Only {{ $product->stock }} left in stock</p>
                    @else
                        <p class="text-sm font-medium text-emerald-600">In stock</p>
                    @endif
                @else
                    <p class="text-sm font-medium text-ink/60">Out of stock</p>
                @endif
            </div>

            @if ($product->isPurchasable())
                <form action="{{ route('cart.store') }}" method="POST" class="mt-8 flex items-center gap-4">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <label for="qty" class="sr-only">Quantity</label>
                    <input id="qty" type="number" name="quantity" min="1" max="{{ $product->stock }}" value="1"
                           class="w-20 rounded-full border border-rose-200 px-4 py-3 text-sm text-center focus:outline-none focus:border-rose-400">
                    <button type="submit"
                            class="flex-1 rounded-full bg-rose-600 text-white py-3.5 text-sm font-semibold hover:bg-rose-700 transition-colors">
                        Add to cart
                    </button>
                </form>
            @else
                <div class="mt-8">
                    <button type="button" onclick="document.getElementById('notify-modal').showModal()"
                            class="w-full rounded-full border-2 border-rose-600 text-rose-600 py-3.5 text-sm font-semibold hover:bg-rose-50 transition-colors">
                        Notify me when available
                    </button>
                </div>

                    <dialog id="notify-modal" class="m-auto rounded-2xl p-0 backdrop:bg-ink/40 w-[calc(100%-2rem)] max-w-sm">                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <h2 class="font-display text-xl text-ink">Get notified</h2>
                            <button type="button" onclick="document.getElementById('notify-modal').close()"
                                    class="text-mauve hover:text-ink text-xl leading-none" aria-label="Close">
                                &times;
                            </button>
                        </div>
                        <p class="mt-1 text-sm text-mauve">We'll email you as soon as {{ $product->name }} is back in stock.</p>

                        @if (session('notify_success'))
                            <div class="mt-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3">
                                {{ session('notify_success') }}
                            </div>
                        @else
                            @if ($errors->any())
                                <div class="mt-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 text-sm px-4 py-3">
                                    {{ $errors->first() }}
                                </div>
                            @endif
                            <form action="{{ route('stock-notifications.store') }}" method="POST" class="mt-4 space-y-3">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <label for="notify-name" class="sr-only">Name</label>
                                <input id="notify-name" name="name" type="text" value="{{ old('name') }}" placeholder="Your name" required
                                       class="w-full rounded-lg border border-rose-200 px-4 py-2.5 text-sm focus:outline-none focus:border-rose-400">

                                <label for="notify-email" class="sr-only">Email</label>
                                <input id="notify-email" name="email" type="email" value="{{ old('email') }}" placeholder="you@email.com" required
                                       class="w-full rounded-lg border border-rose-200 px-4 py-2.5 text-sm focus:outline-none focus:border-rose-400">

                                <label for="notify-phone" class="sr-only">Phone (optional)</label>
                                <input id="notify-phone" name="phone" type="tel" value="{{ old('phone') }}" placeholder="Phone (optional)"
                                       class="w-full rounded-lg border border-rose-200 px-4 py-2.5 text-sm focus:outline-none focus:border-rose-400">

                                <button type="submit" class="w-full rounded-full bg-rose-600 text-white py-3 text-sm font-semibold hover:bg-rose-700 transition-colors">
                                    Notify me
                                </button>
                            </form>
                        @endif
                    </div>
                </dialog>

                @if ($errors->any() || session('notify_success'))
                    <script>
                        document.addEventListener('DOMContentLoaded', () => {
                            document.getElementById('notify-modal')?.showModal();
                        });
                    </script>
                @endif
            @endif
        </div>
    </section>

    {{-- REVIEWS (placeholder until Reviews module) --}}
    <section class="max-w-7xl mx-auto px-6 lg:px-8 pb-20 border-t border-rose-100 pt-14">
        <h2 class="font-display text-2xl text-ink mb-6">Customer reviews</h2>
        <p class="text-mauve text-sm">No reviews yet — be the first to review this product.</p>
    </section>

    {{-- RELATED PRODUCTS --}}
    @if ($related->isNotEmpty())
    <section class="max-w-7xl mx-auto px-6 lg:px-8 pb-24">
        <h2 class="font-display text-2xl text-ink mb-8">You might also like</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-x-6 gap-y-12">
            @foreach ($related as $item)
                <x-product-card :product="$item" />
            @endforeach
        </div>
    </section>
    @endif
    @push('scripts')
    <script>
        document.querySelectorAll('.thumb-btn').forEach((btn) => {
            btn.addEventListener('click', () => {
                document.getElementById('main-image').src = btn.dataset.image;
            });
        });
    </script>
@endpush

@endsection