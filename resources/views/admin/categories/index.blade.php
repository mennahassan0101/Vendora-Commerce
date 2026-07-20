<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Categories — Vendora Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-rose-50/30 text-ink font-sans antialiased">

    @include('admin.partials.header')

    <main class="max-w-7xl mx-auto px-6 lg:px-8 py-10">

        <div class="flex items-center justify-between mb-8">
            <h1 class="font-display text-3xl">Categories</h1>
            <a href="{{ route('admin.categories.create') }}"
               class="rounded-full bg-rose-600 text-white px-6 py-2.5 text-sm font-semibold hover:bg-rose-700 transition-colors">
                Add category
            </a>
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

        <form action="{{ route('admin.categories.index') }}" method="GET" class="mb-6">
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Search categories…"
                   class="w-full max-w-sm rounded-lg border border-rose-200 px-4 py-2 text-sm focus:outline-none focus:border-rose-400">
        </form>

        <div class="bg-white rounded-2xl border border-rose-100 overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs uppercase tracking-wider text-mauve border-b border-rose-100">
                        <th class="px-6 py-3 font-medium">Category</th>
                        <th class="px-6 py-3 font-medium">Products</th>
                        <th class="px-6 py-3 font-medium">Status</th>
                        <th class="px-6 py-3 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr class="border-b border-rose-50 last:border-0">
                            <td class="px-6 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-rose-50 overflow-hidden flex items-center justify-center shrink-0">
                                        @if ($category->image)
                                            <img src="{{ asset('storage/' . $category->image) }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="font-display text-rose-600">{{ mb_substr($category->name, 0, 1) }}</span>
                                        @endif
                                    </div>
                                    <span class="font-medium">{{ $category->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-3 text-mauve">{{ $category->products_count }}</td>
                            <td class="px-6 py-3">
                                @if ($category->is_active)
                                    <span class="inline-flex rounded-full bg-emerald-50 text-emerald-700 text-xs font-medium px-2.5 py-1">Active</span>
                                @else
                                    <span class="inline-flex rounded-full bg-ink/5 text-ink/60 text-xs font-medium px-2.5 py-1">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-3 text-right space-x-3">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="text-rose-600 hover:text-rose-700 font-medium">Edit</a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Delete this category?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-mauve hover:text-rose-600 font-medium">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center text-mauve">No categories found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">{{ $categories->links() }}</div>

    </main>

</body>
</html>