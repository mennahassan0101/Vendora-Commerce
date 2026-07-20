<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Orders — Vendora Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-rose-50/30 text-ink font-sans antialiased">

    @include('admin.partials.header')

    <main class="max-w-7xl mx-auto px-6 lg:px-8 py-10">

        <h1 class="font-display text-3xl mb-8">Orders</h1>

        @if (session('admin_success'))
            <div class="mb-6 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3">
                {{ session('admin_success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-rose-100 p-4 mb-6 flex flex-wrap gap-4">
            <form action="{{ route('admin.orders.index') }}" method="GET" class="flex-1 min-w-[220px]">
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Search reference, name, or email…"
                       class="w-full rounded-lg border border-rose-200 px-4 py-2 text-sm focus:outline-none focus:border-rose-400">
            </form>
            <form action="{{ route('admin.orders.index') }}" method="GET">
                @if (request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                <select name="status" onchange="this.form.submit()"
                        class="rounded-lg border border-rose-200 text-sm px-3 py-2 focus:outline-none focus:border-rose-400">
                    <option value="">All statuses</option>
                    <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                    <option value="processing" @selected(request('status') === 'processing')>Processing</option>
                    <option value="shipped" @selected(request('status') === 'shipped')>Shipped</option>
                    <option value="delivered" @selected(request('status') === 'delivered')>Delivered</option>
                    <option value="cancelled" @selected(request('status') === 'cancelled')>Cancelled</option>
                </select>
            </form>
        </div>

        <div class="bg-white rounded-2xl border border-rose-100 overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs uppercase tracking-wider text-mauve border-b border-rose-100">
                        <th class="px-6 py-3 font-medium">Reference</th>
                        <th class="px-6 py-3 font-medium">Customer</th>
                        <th class="px-6 py-3 font-medium">Status</th>
                        <th class="px-6 py-3 font-medium text-right">Total</th>
                        <th class="px-6 py-3 font-medium text-right">Placed</th>
                        <th class="px-6 py-3 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr class="border-b border-rose-50 last:border-0">
                            <td class="px-6 py-3 font-medium">{{ $order->tracking_reference }}</td>
                            <td class="px-6 py-3">
                                <p>{{ $order->customer_name }}</p>
                                <p class="text-xs text-mauve">{{ $order->customer_email }}</p>
                            </td>
                            <td class="px-6 py-3">
                                <span class="inline-flex rounded-full bg-rose-50 text-rose-700 text-xs font-medium px-2.5 py-1">
                                    {{ $order->statusLabel() }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-right">${{ number_format($order->total, 2) }}</td>
                            <td class="px-6 py-3 text-right text-mauve">{{ $order->created_at->diffForHumans() }}</td>
                            <td class="px-6 py-3 text-right">
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-rose-600 hover:text-rose-700 font-medium">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center text-mauve">No orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">{{ $orders->links() }}</div>

    </main>

</body>
</html>