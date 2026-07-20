<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order {{ $order->tracking_reference }} — Vendora Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-rose-50/30 text-ink font-sans antialiased">

    @include('admin.partials.header')

    <main class="max-w-5xl mx-auto px-6 lg:px-8 py-10">

        <a href="{{ route('admin.orders.index') }}" class="text-sm text-mauve hover:text-rose-600">&larr; Back to orders</a>

        <div class="flex items-start justify-between mt-4 mb-8">
            <div>
                <h1 class="font-display text-3xl">{{ $order->tracking_reference }}</h1>
                <p class="text-mauve text-sm mt-1">Placed {{ $order->created_at->format('M j, Y \a\t g:i A') }}</p>
            </div>
            <span class="inline-flex rounded-full bg-rose-50 text-rose-700 text-sm font-medium px-3 py-1.5">
                {{ $order->statusLabel() }}
            </span>
        </div>

        @if (session('admin_success'))
            <div class="mb-6 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3">
                {{ session('admin_success') }}
            </div>
        @endif
        @if (session('admin_error'))
            <div class="mb-6 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 text-sm px-4 py-3">
                {{ session('admin_error') }}
            </div>
        @endif

        <div class="grid lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2 space-y-8">

                {{-- ITEMS --}}
                <div class="bg-white rounded-2xl border border-rose-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-rose-100">
                        <h2 class="font-display text-lg">Items</h2>
                    </div>
                    <table class="w-full text-sm">
                        <tbody>
                            @foreach ($order->items as $item)
                                <tr class="border-b border-rose-50 last:border-0">
                                    <td class="px-6 py-3">
                                        <p class="font-medium">{{ $item->product_name }}</p>
                                        <p class="text-xs text-mauve">${{ number_format($item->product_price, 2) }} × {{ $item->quantity }}</p>
                                    </td>
                                    <td class="px-6 py-3 text-right font-medium">${{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="px-6 py-4 border-t border-rose-100 space-y-1 text-sm">
                        <div class="flex justify-between text-mauve">
                            <span>Subtotal</span>
                            <span>${{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-mauve">
                            <span>Shipping</span>
                            <span>{{ $order->shipping_cost > 0 ? '$' . number_format($order->shipping_cost, 2) : 'Free' }}</span>
                        </div>
                        <div class="flex justify-between font-semibold text-ink pt-1">
                            <span>Total</span>
                            <span>${{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>
                </div>

                {{-- STATUS HISTORY --}}
                <div class="bg-white rounded-2xl border border-rose-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-rose-100">
                        <h2 class="font-display text-lg">Status history</h2>
                    </div>
                    @if ($order->statusHistory->isEmpty())
                        <p class="px-6 py-6 text-sm text-mauve">No status changes recorded yet.</p>
                    @else
                        <div class="divide-y divide-rose-50">
                            @foreach ($order->statusHistory as $entry)
                                <div class="px-6 py-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium capitalize">{{ $entry->status }}</span>
                                        <span class="text-xs text-mauve">{{ $entry->created_at->format('M j, Y g:i A') }}</span>
                                    </div>
                                    @if ($entry->note)
                                        <p class="text-xs text-mauve mt-1">{{ $entry->note }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>

            <div class="space-y-8">

                {{-- UPDATE STATUS --}}
                <div class="bg-white rounded-2xl border border-rose-100 p-6">
                    <h2 class="font-display text-lg mb-4">Update status</h2>
                    <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="space-y-3">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="w-full rounded-lg border border-rose-200 px-3 py-2 text-sm focus:outline-none focus:border-rose-400">
                            <option value="pending" @selected($order->status === 'pending')>Pending</option>
                            <option value="processing" @selected($order->status === 'processing')>Processing</option>
                            <option value="shipped" @selected($order->status === 'shipped')>Shipped</option>
                            <option value="delivered" @selected($order->status === 'delivered')>Delivered</option>
                            <option value="cancelled" @selected($order->status === 'cancelled')>Cancelled</option>
                        </select>
                        <textarea name="note" rows="2" placeholder="Internal note (optional)"
                                  class="w-full rounded-lg border border-rose-200 px-3 py-2 text-sm focus:outline-none focus:border-rose-400"></textarea>
                        <button type="submit" class="w-full rounded-full bg-rose-600 text-white py-2.5 text-sm font-semibold hover:bg-rose-700 transition-colors">
                            Update status
                        </button>
                    </form>
                </div>

                {{-- CUSTOMER --}}
                <div class="bg-white rounded-2xl border border-rose-100 p-6">
                    <h2 class="font-display text-lg mb-4">Customer</h2>
                    <div class="space-y-2 text-sm">
                        <p class="font-medium">{{ $order->customer_name }}</p>
                        <p class="text-mauve">{{ $order->customer_email }}</p>
                        <p class="text-mauve">{{ $order->customer_phone }}</p>
                    </div>
                </div>

                {{-- SHIPPING --}}
                <div class="bg-white rounded-2xl border border-rose-100 p-6">
                    <h2 class="font-display text-lg mb-4">Shipping</h2>
                    <div class="text-sm text-mauve space-y-1">
                        <p>{{ $order->shipping_address }}</p>
                        <p>{{ $order->shipping_city }}{{ $order->shipping_postal_code ? ', ' . $order->shipping_postal_code : '' }}</p>
                        <p>{{ $order->shipping_country }}</p>
                    </div>
                    <p class="text-xs text-mauve mt-3 uppercase tracking-wider">Payment</p>
                    <p class="text-sm mt-1">{{ $order->payment_method === 'cash_on_delivery' ? 'Cash on delivery' : 'Card' }}</p>
                    @if ($order->notes)
                        <p class="text-xs text-mauve mt-3 uppercase tracking-wider">Notes</p>
                        <p class="text-sm mt-1">{{ $order->notes }}</p>
                    @endif
                </div>

            </div>

        </div>

    </main>

</body>
</html>