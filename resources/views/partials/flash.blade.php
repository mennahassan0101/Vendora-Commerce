@if (session('cart_success') || session('cart_error'))
    <div class="max-w-7xl mx-auto px-6 lg:px-8 pt-4">
        <div class="rounded-xl px-4 py-3 text-sm font-medium
            {{ session('cart_error') ? 'bg-rose-100 text-rose-700' : 'bg-green-50 text-green-700' }}">
            {{ session('cart_error') ?? session('cart_success') }}
        </div>
    </div>
@endif