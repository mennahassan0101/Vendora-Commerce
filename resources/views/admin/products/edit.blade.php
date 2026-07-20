<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product — Vendora Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-rose-50/30 text-ink font-sans antialiased">

    @include('admin.partials.header')

    <main class="max-w-3xl mx-auto px-6 lg:px-8 py-10">
        <h1 class="font-display text-3xl mb-8">Edit product</h1>

        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            @include('admin.products._form')

            <button type="submit" class="rounded-full bg-rose-600 text-white px-8 py-3 text-sm font-semibold hover:bg-rose-700 transition-colors">
                Save changes
            </button>
        </form>

        @if ($product->images->isNotEmpty())
        <div class="mt-10 bg-white rounded-2xl border border-rose-100 p-6">
            <h2 class="font-display text-xl mb-4">Current images</h2>
            <div class="grid grid-cols-4 gap-4">
                @foreach ($product->images as $image)
                    <div class="relative">
                        <div class="aspect-square rounded-xl overflow-hidden border {{ $image->is_primary ? 'border-rose-500 ring-2 ring-rose-200' : 'border-rose-100' }}">
                            <img src="{{ asset('storage/' . $image->path) }}" class="w-full h-full object-cover">
                        </div>
                        @if ($image->is_primary)
                            <span class="absolute top-2 left-2 rounded-full bg-rose-600 text-white text-[10px] font-semibold px-2 py-0.5">Primary</span>
                        @endif
                        <div class="mt-2 flex justify-between text-xs">
                            @unless ($image->is_primary)
                                <form action="{{ route('admin.products.images.primary', [$product, $image]) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-rose-600 hover:text-rose-700 font-medium">Make primary</button>
                                </form>
                            @else
                                <span></span>
                            @endunless
                            <form action="{{ route('admin.products.images.destroy', [$product, $image]) }}" method="POST"
                                  onsubmit="return confirm('Remove this image?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-mauve hover:text-rose-600 font-medium">Remove</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </main>

</body>
</html>