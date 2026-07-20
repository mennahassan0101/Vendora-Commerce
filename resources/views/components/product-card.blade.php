@props(['product'])

<a href="{{ route('products.show', $product) }}" class="group block">
    <x-blob-frame class="aspect-square p-6">
        @if ($product->primaryImage)
            <img
                src="{{ asset('storage/' . $product->primaryImage->path) }}"
                alt="{{ $product->name }}"
                class="w-full h-full object-contain drop-shadow-sm transition-transform duration-300 group-hover:scale-105"
            >
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

    <div class="mt-4">
        @if ($product->categories->isNotEmpty())
            <p class="text-[11px] font-semibold uppercase tracking-wider text-rose-600">
                {{ $product->categories->first()->name }}
            </p>
        @endif

        <p class="mt-1 font-display text-lg text-ink leading-snug group-hover:text-rose-700 transition-colors">
            {{ $product->name }}
        </p>

        <div class="mt-1 flex items-baseline gap-2">
            <span class="text-ink font-semibold">${{ number_format($product->price, 2) }}</span>
            @if ($product->compare_price && $product->compare_price > $product->price)
                <span class="text-mauve text-sm line-through">${{ number_format($product->compare_price, 2) }}</span>
            @endif
        </div>
    </div>
</a>