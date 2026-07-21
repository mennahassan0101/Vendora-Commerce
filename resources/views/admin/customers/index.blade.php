<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customers — Vendora Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-rose-50/30 text-ink font-sans antialiased">

    @include('admin.partials.header')

    <main class="max-w-7xl mx-auto px-6 lg:px-8 py-10">

        <h1 class="font-display text-3xl mb-8">Customers</h1>

        <form action="{{ route('admin.customers.index') }}" method="GET" class="mb-6">
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Search name or email…"
                   class="w-full max-w-sm rounded-lg border border-rose-200 px-4 py-2 text-sm focus:outline-none focus:border-rose-400">
        </form>

        <div class="bg-white rounded-2xl border border-rose-100 overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs uppercase tracking-wider text-mauve border-b border-rose-100">
                        <th class="px-6 py-3 font-medium">Customer</th>
                        <th class="px-6 py-3 font-medium text-right">Orders</th>
                        <th class="px-6 py-3 font-medium text-right">Total spent</th>
                        <th class="px-6 py-3 font-medium text-right">Last order</th>
                        <th class="px-6 py-3 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customers as $customer)
                        <tr class="border-b border-rose-50 last:border-0">
                            <td class="px-6 py-3">
                                <p class="font-medium">{{ $customer->customer_name }}</p>
                                <p class="text-xs text-mauve">{{ $customer->customer_email }}</p>
                            </td>
                            <td class="px-6 py-3 text-right">{{ $customer->orders_count }}</td>
                            <td class="px-6 py-3 text-right">${{ number_format($customer->total_spent, 2) }}</td>
                            <td class="px-6 py-3 text-right text-mauve">{{ \Carbon\Carbon::parse($customer->last_order_at)->diffForHumans() }}</td>
                            <td class="px-6 py-3 text-right">
                                <a href="{{ route('admin.customers.show', $customer->customer_email) }}" class="text-rose-600 hover:text-rose-700 font-medium">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center text-mauve">No customers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">{{ $customers->links() }}</div>

    </main>

</body>
</html>