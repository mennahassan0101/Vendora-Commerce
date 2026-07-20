@php $product = $product ?? null; @endphp

@if ($errors->any())
    <div class="rounded-lg bg-rose-50 border border-rose-200 text-rose-700 text-sm px-4 py-3">
        <ul class="list-disc list-inside space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="bg-white rounded-2xl border border-rose-100 p-6 space-y-5">

    <div>
        <label for="name" class="block text-sm font-medium mb-1">Name</label>
        <input id="name" name="name" type="text" value="{{ old('name', $product?->name) }}" required
               class="w-full rounded-lg border border-rose-200 px-4 py-2.5 focus:outline-none focus:border-rose-400">
    </div>

    <div>
        <label for="short_description" class="block text-sm font-medium mb-1">Short description</label>
        <input id="short_description" name="short_description" type="text" maxlength="500"
               value="{{ old('short_description', $product?->short_description) }}"
               class="w-full rounded-lg border border-rose-200 px-4 py-2.5 focus:outline-none focus:border-rose-400">
    </div>

    <div>
        <label for="description" class="block text-sm font-medium mb-1">Description</label>
        <textarea id="description" name="description" rows="4"
                  class="w-full rounded-lg border border-rose-200 px-4 py-2.5 focus:outline-none focus:border-rose-400">{{ old('description', $product?->description) }}</textarea>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label for="sku" class="block text-sm font-medium mb-1">SKU</label>
            <input id="sku" name="sku" type="text" value="{{ old('sku', $product?->sku) }}"
                   class="w-full rounded-lg border border-rose-200 px-4 py-2.5 focus:outline-none focus:border-rose-400">
        </div>
        <div class="flex items-end gap-6 pb-2.5">
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product?->is_active ?? true) ? 'checked' : '' }}
                       class="rounded border-rose-300">
                Active
            </label>
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product?->is_featured) ? 'checked' : '' }}
                       class="rounded border-rose-300">
                Featured
            </label>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label for="price" class="block text-sm font-medium mb-1">Price</label>
            <input id="price" name="price" type="number" step="0.01" min="0" value="{{ old('price', $product?->price) }}" required
                   class="w-full rounded-lg border border-rose-200 px-4 py-2.5 focus:outline-none focus:border-rose-400">
        </div>
        <div>
            <label for="compare_price" class="block text-sm font-medium mb-1">Compare price</label>
            <input id="compare_price" name="compare_price" type="number" step="0.01" min="0" value="{{ old('compare_price', $product?->compare_price) }}"
                   class="w-full rounded-lg border border-rose-200 px-4 py-2.5 focus:outline-none focus:border-rose-400">
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label for="stock" class="block text-sm font-medium mb-1">Stock</label>
            <input id="stock" name="stock" type="number" min="0" value="{{ old('stock', $product?->stock ?? 0) }}" required
                   class="w-full rounded-lg border border-rose-200 px-4 py-2.5 focus:outline-none focus:border-rose-400">
        </div>
        <div>
            <label for="low_stock_threshold" class="block text-sm font-medium mb-1">Low stock threshold</label>
            <input id="low_stock_threshold" name="low_stock_threshold" type="number" min="0"
                   value="{{ old('low_stock_threshold', $product?->low_stock_threshold ?? 5) }}" required
                   class="w-full rounded-lg border border-rose-200 px-4 py-2.5 focus:outline-none focus:border-rose-400">
        </div>
    </div>

    <div>
        <p class="block text-sm font-medium mb-2">Categories</p>
        <div class="flex flex-wrap gap-3">
            @php $selected = old('categories', $product?->categories->pluck('id')->toArray() ?? []); @endphp
            @foreach ($categories as $category)
                <label class="flex items-center gap-2 text-sm bg-rose-50/60 rounded-full px-3 py-1.5">
                    <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                           {{ in_array($category->id, $selected) ? 'checked' : '' }}
                           class="rounded border-rose-300">
                    {{ $category->name }}
                </label>
            @endforeach
        </div>
    </div>

    <div>
        <label for="images" class="block text-sm font-medium mb-1">
            {{ $product ? 'Add more images' : 'Images' }}
        </label>
        <input id="images" name="images[]" type="file" multiple accept="image/*"
               class="w-full text-sm file:mr-4 file:rounded-full file:border-0 file:bg-rose-100 file:px-4 file:py-2 file:text-rose-700 file:font-medium">
        <p class="text-xs text-mauve mt-1">First image uploaded becomes the primary image if none exists yet.</p>
    </div>

</div>