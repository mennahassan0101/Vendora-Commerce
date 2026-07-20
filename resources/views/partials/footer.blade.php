<footer class="bg-ink text-white/80 mt-24">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-14 grid grid-cols-1 md:grid-cols-4 gap-10">

        <div class="md:col-span-2">
            <p class="font-display text-2xl text-white">Vendora</p>
            <p class="mt-3 text-sm text-white/60 max-w-sm">
                Thoughtfully chosen products, delivered without the fuss.
                No account required to shop.
            </p>
        </div>

        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-rose-300">Shop</p>
            <ul class="mt-4 space-y-2 text-sm">
                <li><a href="{{ route('products.index') }}" class="hover:text-white transition-colors">All products</a></li>
                <li><a href="{{ route('home') }}#categories" class="hover:text-white transition-colors">Categories</a></li>
                <li><a href="{{ route('orders.track') }}" class="hover:text-white transition-colors">Track your order</a></li>
            </ul>
        </div>

        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-rose-300">Stay in touch</p>
            <form class="mt-4 flex" onsubmit="return false;">
                <label for="newsletter" class="sr-only">Email address</label>
                <input id="newsletter" type="email" placeholder="you@email.com"
                    class="w-full rounded-l-full bg-white/10 border border-white/10 px-4 py-2 text-sm placeholder:text-white/40 focus:outline-none focus:border-rose-400">
                <button type="submit" class="rounded-r-full bg-rose-600 px-4 text-sm font-medium hover:bg-rose-700 transition-colors">
                    Join
                </button>
            </form>
        </div>
    </div>

    <div class="border-t border-white/10">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-5 text-xs text-white/40 flex justify-between">
            <span>&copy; {{ date('Y') }} Vendora. All rights reserved.</span>
            <span>Made with care.</span>
        </div>
    </div>
</footer>