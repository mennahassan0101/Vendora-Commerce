<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $customer['name'] }} — Vendora Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-rose-50/30 text-ink font-sans antialiased">

    @include('admin.partials.header')

    <main class="max-w-5xl mx-auto px-6 lg:px-8 py-10">

        <a href="{{ route('admin.customers.index') }}" class="text-sm text-mauve hover:text-rose-600">&larr; Back to customers</a>

        <div class="mt-4 mb-8">
            <h1 class="font-display text-3xl">{{ $customer['name'] }}</h1>
            <p class="text-mauve text-sm mt-1">{{ $customer['email'] }} @if($customer['phone']) · {{ $customer['phone'] }} @endif</p>
        </div>

        <div class="grid grid-cols-2 gap-5 mb-8">
            <div class="bg-white rounded-2xl border border-rose-100 p-6">
                <p class="text-xs font-semibold uppercase tracking-wider text-mauve">Total orders</p>
                <p class="mt-2 font-display text-3xl">{{ $customer['orders_count'] }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-rose-100 p-6">
                <p class="text-xs font-semibold uppercase tracking-wider text-mauve">Total spent</p>
                <p class="mt-2 font-display text-3xl">${{ number_format($customer['total_spent'], 2) }}</p>
                <p class="mt-1 text-xs text-mauve">Excludes cancelled orders</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-rose-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-rose-100">
                <h2 class="font-display text-lg">Order history</h2>
            </div>
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs uppercase tracking-wider text-mauve border-b border-rose-100">
                        <th class="px-6 py-3 font-medium">Reference</th>
                        <th class="px-6 py-3 font-medium">Status</th>
                        <th class="px-6 py-3 font-medium text-right">Total</th>
                        <th class="px-6 py-3 font-medium text-right">Placed</th>
                        <th class="px-6 py-3 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr class="border-b border-rose-50 last:border-0">
                            <td class="px-6 py-3 font-medium">{{ $order->tracking_reference }}</td>
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
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">{{ $orders->links() }}</div>

    </main>

</body>
</html>