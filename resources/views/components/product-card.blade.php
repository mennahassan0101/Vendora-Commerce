@props(['product'])

@php
    $secondImage = $product->images->count() > 1 ? $product->images->skip(1)->first() : null;
@endphp

<div class="group">
    <div class="relative">
        <a href="{{ route('products.show', $product) }}" class="block">
            <x-blob-frame class="aspect-[4/5] rounded-2xl p-6 overflow-hidden">
                @if ($product->primaryImage)
                    <img
                        src="{{ asset('storage/' . $product->primaryImage->path) }}"
                        alt="{{ $product->name }}"
                        class="w-full h-full object-contain drop-shadow-sm transition-opacity duration-300 {{ $secondImage ? 'group-hover:opacity-0' : '' }}"
                    >
                    @if ($secondImage)
                        <img
                            src="{{ asset('storage/' . $secondImage->path) }}"
                            alt="{{ $product->name }}"
                            class="absolute inset-0 m-auto w-full h-full object-contain p-6 opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                        >
                    @endif
                @else
                    <div class="w-2/3 h-2/3 rounded-2xl bg-white/70 border border-rose-200/70 flex items-center justify-center text-mauve text-xs">
                        No image yet
                    </div>
                @endif

                @if ($product->compare_price && $product->compare_price > $product->price)
                    <span class="absolute top-3 left-3 rounded-full bg-rose-600 text-white text-[11px] font-semibold px-2.5 py-1">
                        Sale
                    </span>
                @endif

                @if (! $product->isPurchasable())
                    <span class="absolute top-3 right-3 rounded-full bg-ink/80 text-white text-[11px] font-semibold px-2.5 py-1">
                        Out of stock
                    </span>
                @endif
            </x-blob-frame>
        </a>

        @if ($product->isPurchasable())
            <form action="{{ route('cart.store') }}" method="POST"
                  class="absolute left-4 right-4 bottom-4 opacity-0 translate-y-2 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" value="1">
                <button type="submit"
                        class="w-full rounded-full bg-white/95 text-ink text-sm font-semibold py-2.5 shadow-md hover:bg-rose-600 hover:text-white transition-colors">
                    Add to cart
                </button>
            </form>
        @endif
    </div>

    <a href="{{ route('products.show', $product) }}" class="block mt-4">
        @if ($product->categories->isNotEmpty())
            <p class="text-[11px] font-semibold uppercase tracking-wider text-rose-600">
                {{ $product->categories->first()->name }}
            </p>
        @endif

        <p class="mt-1 font-display text-lg text-ink leading-snug">
            {{ $product->name }}
        </p>

        <div class="mt-1 flex items-baseline gap-2">
            <span class="text-ink font-semibold">${{ number_format($product->price, 2) }}</span>
            @if ($product->compare_price && $product->compare_price > $product->price)
                <span class="text-mauve text-sm line-through">${{ number_format($product->compare_price, 2) }}</span>
            @endif
        </div>
    </a>
</div>