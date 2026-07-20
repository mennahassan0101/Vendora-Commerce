<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Vendora Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-rose-50/30 text-ink font-sans antialiased">
        @include('admin.partials.header')


    <header class="bg-white border-b border-rose-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 h-20 flex items-center justify-between">
            <p class="font-display text-2xl">Vendora Admin</p>
            <div class="flex items-center gap-6">
                <span class="text-sm text-mauve">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button class="text-sm text-rose-600 hover:text-rose-700 font-semibold">Log out</button>
                </form>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 lg:px-8 py-10">

        <h1 class="font-display text-3xl mb-8">Dashboard</h1>

        {{-- STAT CARDS --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="bg-white rounded-2xl border border-rose-100 p-6">
                <p class="text-xs font-semibold uppercase tracking-wider text-mauve">Total orders</p>
                <p class="mt-2 font-display text-3xl">{{ $stats['orders_total'] }}</p>
                <p class="mt-1 text-xs text-rose-600">{{ $stats['orders_pending'] }} pending</p>
            </div>

            <div class="bg-white rounded-2xl border border-rose-100 p-6">
                <p class="text-xs font-semibold uppercase tracking-wider text-mauve">Revenue</p>
                <p class="mt-2 font-display text-3xl">${{ number_format($stats['revenue'], 2) }}</p>
                <p class="mt-1 text-xs text-mauve">Excludes cancelled orders</p>
            </div>

            <div class="bg-white rounded-2xl border border-rose-100 p-6">
                <p class="text-xs font-semibold uppercase tracking-wider text-mauve">Products</p>
                <p class="mt-2 font-display text-3xl">{{ $stats['products_total'] }}</p>
                <p class="mt-1 text-xs {{ $stats['products_low_stock'] + $stats['products_out_of_stock'] > 0 ? 'text-rose-600' : 'text-mauve' }}">
                    {{ $stats['products_low_stock'] }} low · {{ $stats['products_out_of_stock'] }} out of stock
                </p>
            </div>

            <div class="bg-white rounded-2xl border border-rose-100 p-6">
                <p class="text-xs font-semibold uppercase tracking-wider text-mauve">Categories</p>
                <p class="mt-2 font-display text-3xl">{{ $stats['categories_total'] }}</p>
            </div>
        </div>

        {{-- RECENT ORDERS --}}
        <div class="mt-10 bg-white rounded-2xl border border-rose-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-rose-100">
                <h2 class="font-display text-xl">Recent activity</h2>
            </div>

            @if ($recentOrders->isEmpty())
                <p class="px-6 py-10 text-sm text-mauve text-center">No orders yet.</p>
            @else
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs uppercase tracking-wider text-mauve border-b border-rose-100">
                            <th class="px-6 py-3 font-medium">Reference</th>
                            <th class="px-6 py-3 font-medium">Customer</th>
                            <th class="px-6 py-3 font-medium">Status</th>
                            <th class="px-6 py-3 font-medium text-right">Total</th>
                            <th class="px-6 py-3 font-medium text-right">Placed</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentOrders as $order)
                            <tr class="border-b border-rose-50 last:border-0">
                                <td class="px-6 py-3 font-medium">{{ $order->tracking_reference }}</td>
                                <td class="px-6 py-3">{{ $order->customer_name }}</td>
                                <td class="px-6 py-3">
                                    <span class="inline-flex rounded-full bg-rose-50 text-rose-700 text-xs font-medium px-2.5 py-1">
                                        {{ $order->statusLabel() }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-right">${{ number_format($order->total, 2) }}</td>
                                <td class="px-6 py-3 text-right text-mauve">{{ $order->created_at->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

    </main>

</body>
</html>