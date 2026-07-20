<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products — Vendora Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-rose-50/30 text-ink font-sans antialiased">

    @include('admin.partials.header')

    <main class="max-w-7xl mx-auto px-6 lg:px-8 py-10">

        <div class="flex items-center justify-between mb-8">
            <h1 class="font-display text-3xl">Products</h1>
            <a href="{{ route('admin.products.create') }}"
               class="rounded-full bg-rose-600 text-white px-6 py-2.5 text-sm font-semibold hover:bg-rose-700 transition-colors">
                Add product
            </a>
        </div>

        @if (session('admin_success'))
            <div class="mb-6 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3">
                {{ session('admin_success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-rose-100 p-4 mb-6 flex flex-wrap gap-4">
            <form action="{{ route('admin.products.index') }}" method="GET" class="flex-1 min-w-[200px]">
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Search products…"
                       class="w-full rounded-lg border border-rose-200 px-4 py-2 text-sm focus:outline-none focus:border-rose-400">
            </form>
            <form action="{{ route('admin.products.index') }}" method="GET">
                @if (request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                <select name="status" onchange="this.form.submit()"
                        class="rounded-lg border border-rose-200 text-sm px-3 py-2 focus:outline-none focus:border-rose-400">
                    <option value="">All statuses</option>
                    <option value="active" @selected(request('status') === 'active')>Active</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                    <option value="low_stock" @selected(request('status') === 'low_stock')>Low stock</option>
                    <option value="out_of_stock" @selected(request('status') === 'out_of_stock')>Out of stock</option>
                </select>
            </form>
        </div>

        <div class="bg-white rounded-2xl border border-rose-100 overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs uppercase tracking-wider text-mauve border-b border-rose-100">
                        <th class="px-6 py-3 font-medium">Product</th>
                        <th class="px-6 py-3 font-medium">Categories</th>
                        <th class="px-6 py-3 font-medium">Price</th>
                        <th class="px-6 py-3 font-medium">Stock</th>
                        <th class="px-6 py-3 font-medium">Status</th>
                        <th class="px-6 py-3 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr class="border-b border-rose-50 last:border-0">
                            <td class="px-6 py-3">
                                <p class="font-medium">{{ $product->name }}</p>
                                <p class="text-xs text-mauve">{{ $product->images_count }} image{{ $product->images_count === 1 ? '' : 's' }}</p>
                            </td>
                            <td class="px-6 py-3 text-mauve">
                                {{ $product->categories->pluck('name')->join(', ') ?: '—' }}
                            </td>
                            <td class="px-6 py-3">${{ number_format($product->price, 2) }}</td>
                            <td class="px-6 py-3">
                                <span class="{{ $product->stock === 0 ? 'text-rose-600' : ($product->isLowStock() ? 'text-amber-600' : 'text-ink') }}">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td class="px-6 py-3">
                                @if ($product->is_active)
                                    <span class="inline-flex rounded-full bg-emerald-50 text-emerald-700 text-xs font-medium px-2.5 py-1">Active</span>
                                @else
                                    <span class="inline-flex rounded-full bg-ink/5 text-ink/60 text-xs font-medium px-2.5 py-1">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-3 text-right space-x-3">
                                <a href="{{ route('admin.products.edit', $product) }}" class="text-rose-600 hover:text-rose-700 font-medium">Edit</a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Delete this product? This can be reversed later from trashed items.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-mauve hover:text-rose-600 font-medium">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center text-mauve">No products found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">{{ $products->links() }}</div>

    </main>

</body>
</html>