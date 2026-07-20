@extends('layouts.app')

@section('title', $product->name)

@section('content')

    {{-- BREADCRUMB --}}
    <div class="max-w-7xl mx-auto px-6 lg:px-8 pt-8 text-sm text-mauve">
        <a href="{{ route('home') }}" class="hover:text-rose-600 transition-colors">Home</a>
        <span class="mx-2">/</span>
        <a href="{{ route('products.index') }}" class="hover:text-rose-600 transition-colors">Shop</a>
        @if ($product->categories->isNotEmpty())
            <span class="mx-2">/</span>
            <a href="{{ route('products.index', ['category' => $product->categories->first()->slug]) }}" class="hover:text-rose-600 transition-colors">
                {{ $product->categories->first()->name }}
            </a>
        @endif
        <span class="mx-2">/</span>
        <span class="text-ink">{{ $product->name }}</span>
    </div>

    {{-- MAIN --}}
    <section class="max-w-7xl mx-auto px-6 lg:px-8 pt-8 pb-20 grid lg:grid-cols-2 gap-12">

        {{-- GALLERY --}}
        <div>
            <x-blob-frame class="aspect-square p-10">
                @if ($product->images->isNotEmpty())
                    <img
                        id="main-image"
                        src="{{ asset('storage/' . ($product->images->firstWhere('is_primary', true)?->path ?? $product->images->first()->path)) }}"
                        alt="{{ $product->name }}"
                        class="w-full h-full object-contain drop-shadow-md"
                    >
                @else
                    <div class="w-2/3 h-2/3 rounded-3xl bg-white/70 border border-rose-200/70 flex items-center justify-center text-mauve text-sm text-center px-6">
                        No image yet
                    </div>
                @endif
            </x-blob-frame>

            @if ($product->images->count() > 1)
                <div class="mt-4 flex gap-3">
                    @foreach ($product->images as $image)
                        <button
                            type="button"
                            onclick="document.getElementById('main-image').src = this.querySelector('img').src"
                            class="w-20 h-20 rounded-xl border border-rose-100 bg-rose-50/60 p-2 hover:border-rose-400 transition-colors"
                        >
                            <img src="{{ asset('storage/' . $image->path) }}" alt="" class="w-full h-full object-contain">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- INFO --}}
        <div>
            @if ($product->categories->isNotEmpty())
                <p class="text-xs font-semibold uppercase tracking-wider text-rose-600">
                    {{ $product->categories->first()->name }}
                </p>
            @endif

            <h1 class="mt-2 font-display text-4xl text-ink leading-tight">{{ $product->name }}</h1>

            <div class="mt-4 flex items-baseline gap-3">
                <span class="text-2xl font-semibold text-ink">${{ number_format($product->price, 2) }}</span>
                @if ($product->compare_price && $product->compare_price > $product->price)
                    <span class="text-mauve line-through">${{ number_format($product->compare_price, 2) }}</span>
                @endif
            </div>

            <div class="mt-4 flex items-center gap-2 text-sm">
                @if ($product->isPurchasable())
                    <span class="w-2 h-2 rounded-full bg-green-500"></span>
                    <span class="text-ink">In stock</span>
                    @if ($product->isLowStock())
                        <span class="text-rose-600">— only {{ $product->stock }} left</span>
                    @endif
                @else
                    <span class="w-2 h-2 rounded-full bg-mauve"></span>
                    <span class="text-mauve">Out of stock</span>
                @endif
            </div>

            @if ($product->short_description)
                <p class="mt-6 text-mauve leading-relaxed">{{ $product->short_description }}</p>
            @endif

            {{-- Quantity + add to cart (cart module not wired up yet) --}}
            <div class="mt-8 flex items-center gap-4">
                <label for="quantity" class="sr-only">Quantity</label>
                <input
                    id="quantity"
                    type="number"
                    min="1"
                    max="{{ max($product->stock, 1) }}"
                    value="1"
                    {{ $product->isPurchasable() ? '' : 'disabled' }}
                    class="w-20 rounded-lg border border-rose-200 px-3 py-2.5 text-center focus:outline-none focus:border-rose-400 disabled:opacity-50"
                >
                <button
                    type="button"
                    disabled
                    title="Cart isn't wired up yet — coming in the Shopping Cart module"
                    class="flex-1 rounded-full bg-rose-600 text-white py-3 text-sm font-semibold opacity-50 cursor-not-allowed"
                >
                    Add to cart
                </button>
            </div>
            <p class="mt-2 text-xs text-mauve">Cart functionality is the next module — this button is a placeholder for now.</p>

            @if ($product->description)
                <div class="mt-10 pt-10 border-t border-rose-100">
                    <h2 class="font-display text-xl text-ink mb-3">Details</h2>
                    <div class="text-mauve leading-relaxed whitespace-pre-line">{{ $product->description }}</div>
                </div>
            @endif
        </div>
    </section>

    {{-- RELATED PRODUCTS --}}
    @if ($related->isNotEmpty())
        <section class="max-w-7xl mx-auto px-6 lg:px-8 pb-20">
            <h2 class="font-display text-3xl text-ink mb-8">You might also like</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-x-6 gap-y-12">
                @foreach ($related as $item)
                    <x-product-card :product="$item" />
                @endforeach
            </div>
        </section>
    @endif

    {{-- REVIEWS (placeholder — Reviews module builds the real thing) --}}
    <section class="bg-rose-50/60">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-16">
            <h2 class="font-display text-3xl text-ink mb-2">Customer reviews</h2>
            <p class="text-mauve text-sm mb-8">Reviews aren't collected yet — this section will come alive with the Reviews module.</p>

            <div class="bg-white rounded-2xl border border-rose-100 p-10 text-center">
                <div class="flex justify-center gap-1 text-rose-300 mb-3" aria-hidden="true">
                    @for ($i = 0; $i < 5; $i++)
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 1.5l2.6 5.6 6.1.6-4.6 4.1 1.3 6-5.4-3.1-5.4 3.1 1.3-6-4.6-4.1 6.1-.6L10 1.5z"/>
                        </svg>
                    @endfor
                </div>
                <p class="text-ink font-medium">No reviews yet</p>
                <p class="text-mauve text-sm mt-1">Be the first to share what you think once reviews are enabled.</p>
            </div>
        </div>
    </section>

@endsection