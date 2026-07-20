<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product — Vendora Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-rose-50/30 text-ink font-sans antialiased">
    
    @include('admin.partials.header')

    <main class="max-w-3xl mx-auto px-6 lg:px-8 py-10">
        <h1 class="font-display text-3xl mb-8">Add product</h1>

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @include('admin.products._form')

            <button type="submit" class="rounded-full bg-rose-600 text-white px-8 py-3 text-sm font-semibold hover:bg-rose-700 transition-colors">
                Create product
            </button>
        </form>
    </main>

</body>
</html>