@extends('layouts.app')

@section('title', 'Shop')

@section('content')

    <section class="max-w-7xl mx-auto px-6 lg:px-8 pt-14 pb-4">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-rose-600">Shop</p>
        <h1 class="mt-2 font-display text-4xl text-ink">
            @if (request('category'))
                {{ $categories->firstWhere('slug', request('category'))?->name ?? 'Products' }}
            @else
                All products
            @endif
        </h1>
    </section>

    <section class="max-w-7xl mx-auto px-6 lg:px-8 pb-24 grid lg:grid-cols-[220px_1fr] gap-10">

        {{-- FILTERS --}}
        <aside class="space-y-8">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-mauve mb-3">Category</p>
                <ul class="space-y-2 text-sm">
                    <li>
                        <a href="{{ route('products.index', array_filter(request()->except(['category', 'page']))) }}"
                           class="{{ request('category') ? 'text-ink hover:text-rose-600' : 'text-rose-600 font-semibold' }} transition-colors">
                            All
                        </a>
                    </li>
                     @foreach ($categories as $category)
                        <li>
                            <a href="{{ route('products.index', array_filter(request()->except(['page', 'category']) + ['category' => $category->slug])) }}"
                               class="{{ request('category') === $category->slug ? 'text-rose-600 font-semibold' : 'text-ink hover:text-rose-600' }} transition-colors">
                                {{ $category->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </aside>

        {{-- RESULTS --}}
        <div>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                <form action="{{ route('products.index') }}" method="GET" class="sm:hidden">
                    @if (request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <input type="search" name="search" value="{{ request('search') }}" placeholder="Search products…"
                           class="w-full rounded-full border border-rose-200 px-4 py-2.5 text-sm focus:outline-none focus:border-rose-400">
                </form>

                <p class="text-sm text-mauve">{{ $products->total() }} product{{ $products->total() === 1 ? '' : 's' }}</p>

                <form action="{{ route('products.index') }}" method="GET" class="flex items-center gap-2">
                    @if (request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    @if (request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    <label for="sort" class="text-sm text-mauve">Sort</label>
                    <select id="sort" name="sort" onchange="this.form.submit()"
                            class="rounded-lg border border-rose-200 text-sm px-3 py-2 focus:outline-none focus:border-rose-400">
                        <option value="" @selected(!request('sort'))>Name (A–Z)</option>
                        <option value="price_asc" @selected(request('sort') === 'price_asc')>Price: Low to High</option>
                        <option value="price_desc" @selected(request('sort') === 'price_desc')>Price: High to Low</option>
                        <option value="newest" @selected(request('sort') === 'newest')>Newest</option>
                    </select>
                </form>
            </div>

            @if ($products->isEmpty())
                <div class="rounded-2xl border border-dashed border-rose-200 py-24 text-center">
                    <p class="font-display text-2xl text-ink">No products found</p>
                    <p class="mt-2 text-mauve text-sm">Try a different search term or clear the category filter.</p>
                    <a href="{{ route('products.index') }}" class="mt-6 inline-block text-sm font-semibold text-rose-600 hover:text-rose-700">
                        Clear filters
                    </a>
                </div>
            @else
                <div class="grid grid-cols-2 md:grid-cols-3 gap-x-6 gap-y-12">
                    @foreach ($products as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>

                <div class="mt-14">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </section>

@endsection