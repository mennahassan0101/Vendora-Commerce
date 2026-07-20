<header class="sticky top-0 z-40 bg-white/90 backdrop-blur border-b border-rose-100">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 h-20 flex items-center justify-between">

        <a href="{{ route('home') }}" class="font-display text-2xl tracking-tight text-ink">
            Vendora
        </a>

        <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-ink/80">
            <a href="{{ route('home') }}" class="hover:text-rose-600 transition-colors">Home</a>
            <a href="{{ route('products.index') }}" class="hover:text-rose-600 transition-colors">Shop</a>
            <a href="{{ route('home') }}#categories" class="hover:text-rose-600 transition-colors">Categories</a>
            <a href="{{ route('home') }}#contact" class="hover:text-rose-600 transition-colors">Contact</a>
        </nav>

        <div class="flex items-center gap-4">
            <form action="{{ route('products.index') }}" method="GET" class="hidden lg:block">
                <label for="nav-search" class="sr-only">Search products</label>
                <input
                    id="nav-search"
                    type="search"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search products…"
                    class="w-56 rounded-full border border-rose-200 bg-rose-50/60 px-4 py-2 text-sm placeholder:text-mauve focus:bg-white focus:border-rose-400 focus:outline-none transition-colors"
                >
            </form>

            <a href="{{ route('cart.index') }}" aria-label="Cart" class="relative p-2 rounded-full hover:bg-rose-50 transition-colors">
                <svg class="w-5 h-5 text-ink" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h1.5l2.4 12.4a2 2 0 0 0 2 1.6h8.2a2 2 0 0 0 2-1.6L21 8H6" />
                    <circle cx="9" cy="20" r="1.4" fill="currentColor" stroke="none"/>
                    <circle cx="17" cy="20" r="1.4" fill="currentColor" stroke="none"/>
                </svg>
                @if (($cartCount ?? 0) > 0)
                    <span class="absolute -top-1 -right-1 flex items-center justify-center w-4.5 h-4.5 rounded-full bg-rose-600 text-white text-[10px] font-semibold">
                        {{ $cartCount }}
                    </span>
                @endif
            </a>
        </div>
    </div>
</header>