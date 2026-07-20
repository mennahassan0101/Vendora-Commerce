@extends('layouts.app')

@section('title', 'Home')

@section('content')

    {{-- HERO --}}
    <section class="max-w-7xl mx-auto px-6 lg:px-8 pt-16 pb-20 grid lg:grid-cols-2 gap-12 items-center">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-rose-600">New season arrivals</p>
            <h1 class="mt-4 font-display text-5xl sm:text-6xl leading-[1.05] text-ink">
                Curated finds,<br>
                <span class="italic text-rose-600">delivered with care.</span>
            </h1>
            <p class="mt-6 text-mauve text-lg max-w-md">
                Browse and order in minutes — no account needed. Every piece
                is picked for quality first, trend second.
            </p>
            <div class="mt-8 flex flex-wrap gap-4">
                <a href="{{ route('products.index') }}"
                   class="inline-flex items-center rounded-full bg-rose-600 text-white px-7 py-3.5 text-sm font-semibold hover:bg-rose-700 transition-colors">
                    Shop new arrivals
                </a>
                <a href="#categories"
                   class="inline-flex items-center rounded-full border border-rose-200 text-ink px-7 py-3.5 text-sm font-semibold hover:border-rose-400 transition-colors">
                    Browse categories
                </a>
            </div>
        </div>

        <x-blob-frame class="aspect-square p-10">
            @if ($featured->first()?->primaryImage)
                <img src="{{ asset('storage/' . $featured->first()->primaryImage->path) }}"
                     alt="{{ $featured->first()->name }}"
                     class="w-full h-full object-contain drop-shadow-md">
            @else
                <div class="w-2/3 h-2/3 rounded-3xl bg-white/70 border border-rose-200/70 flex items-center justify-center text-mauve text-sm text-center px-6">
                    Add a featured product to showcase it here
                </div>
            @endif
        </x-blob-frame>
    </section>

    {{-- CATEGORIES --}}
    @if ($categories->isNotEmpty())
    <section id="categories" class="max-w-7xl mx-auto px-6 lg:px-8 py-16">
        <div class="flex items-end justify-between mb-8">
            <h2 class="font-display text-3xl text-ink">Shop by category</h2>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-5">
            @foreach ($categories as $category)
                <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="group text-center">
                    <x-blob-frame class="aspect-square p-5">
                        @if ($category->image)
                            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}"
                                 class="w-full h-full object-contain transition-transform duration-300 group-hover:scale-105">
                        @else
                            <span class="font-display text-2xl text-rose-700">{{ mb_substr($category->name, 0, 1) }}</span>
                        @endif
                    </x-blob-frame>
                    <p class="mt-3 text-sm font-medium text-ink group-hover:text-rose-600 transition-colors">
                        {{ $category->name }}
                    </p>
                </a>
            @endforeach
        </div>
    </section>
    @endif

    {{-- FEATURED PRODUCTS --}}
    @if ($featured->isNotEmpty())
    <section class="max-w-7xl mx-auto px-6 lg:px-8 py-16">
        <div class="flex items-end justify-between mb-8">
            <h2 class="font-display text-3xl text-ink">Featured picks</h2>
            <a href="{{ route('products.index') }}" class="text-sm font-semibold text-rose-600 hover:text-rose-700">
                View all &rarr;
            </a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-x-6 gap-y-12">
            @foreach ($featured as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </section>
    @endif

    {{-- PROMO BANNER --}}
    <section class="my-4">
        <div class="bg-rose-600 text-white">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 py-10 flex flex-col sm:flex-row items-center justify-between gap-4 text-center sm:text-left">
                <div>
                    <p class="font-display text-2xl">Free shipping over $50</p>
                    <p class="text-rose-100 text-sm mt-1">No code needed — applied automatically at checkout.</p>
                </div>
                <a href="{{ route('products.index') }}"
                   class="inline-flex items-center rounded-full bg-white text-rose-700 px-6 py-3 text-sm font-semibold hover:bg-rose-50 transition-colors">
                    Start shopping
                </a>
            </div>
        </div>
    </section>

    {{-- LATEST PRODUCTS --}}
    @if ($latest->isNotEmpty())
    <section class="max-w-7xl mx-auto px-6 lg:px-8 py-16">
        <div class="flex items-end justify-between mb-8">
            <h2 class="font-display text-3xl text-ink">Just landed</h2>
            <a href="{{ route('products.index', ['sort' => 'newest']) }}" class="text-sm font-semibold text-rose-600 hover:text-rose-700">
                View all &rarr;
            </a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-x-6 gap-y-12">
            @foreach ($latest as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </section>
    @endif

    {{-- CONTACT --}}
    <section id="contact" class="bg-rose-50/60 mt-8">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-20 grid lg:grid-cols-2 gap-12">
            <div>
                <h2 class="font-display text-3xl text-ink">Questions before you order?</h2>
                <p class="mt-4 text-mauve max-w-sm">
                    We usually reply within a business day. For order tracking,
                    use the tracking reference from your confirmation email instead.
                </p>
                <div class="mt-6 space-y-1 text-sm text-ink">
                    <p>hello@vendora.test</p>
                    <p>Mon–Fri, 9am–6pm</p>
                </div>
            </div>

            <form class="bg-white rounded-2xl border border-rose-100 p-8 space-y-4" onsubmit="return false;">
                <div>
                    <label for="contact-name" class="block text-sm font-medium text-ink mb-1">Name</label>
                    <input id="contact-name" type="text" class="w-full rounded-lg border border-rose-200 px-4 py-2.5 focus:outline-none focus:border-rose-400">
                </div>
                <div>
                    <label for="contact-email" class="block text-sm font-medium text-ink mb-1">Email</label>
                    <input id="contact-email" type="email" class="w-full rounded-lg border border-rose-200 px-4 py-2.5 focus:outline-none focus:border-rose-400">
                </div>
                <div>
                    <label for="contact-message" class="block text-sm font-medium text-ink mb-1">Message</label>
                    <textarea id="contact-message" rows="4" class="w-full rounded-lg border border-rose-200 px-4 py-2.5 focus:outline-none focus:border-rose-400"></textarea>
                </div>
                <button type="submit" class="w-full rounded-full bg-rose-600 text-white py-3 text-sm font-semibold hover:bg-rose-700 transition-colors">
                    Send message
                </button>
                <p class="text-xs text-mauve text-center">This form isn't wired up yet — that's part of the Contact Messages bonus feature.</p>
            </form>
        </div>
    </section>

@endsection