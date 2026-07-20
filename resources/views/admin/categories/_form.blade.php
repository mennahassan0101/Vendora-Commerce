@php $category = $category ?? null; @endphp

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
        <input id="name" name="name" type="text" value="{{ old('name', $category?->name) }}" required
               class="w-full rounded-lg border border-rose-200 px-4 py-2.5 focus:outline-none focus:border-rose-400">
    </div>

    <div>
        <label for="description" class="block text-sm font-medium mb-1">Description</label>
        <textarea id="description" name="description" rows="3"
                  class="w-full rounded-lg border border-rose-200 px-4 py-2.5 focus:outline-none focus:border-rose-400">{{ old('description', $category?->description) }}</textarea>
    </div>

    <label class="flex items-center gap-2 text-sm">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category?->is_active ?? true) ? 'checked' : '' }}
               class="rounded border-rose-300">
        Active
    </label>

    <div>
        @if ($category?->image)
            <div class="flex items-center gap-4 mb-3">
                <div class="w-20 h-20 rounded-xl overflow-hidden border border-rose-100">
                    <img src="{{ asset('storage/' . $category->image) }}" class="w-full h-full object-cover">
                </div>
                <button type="submit" form="remove-category-image"
                        class="text-sm text-mauve hover:text-rose-600 font-medium">
                    Remove image
                </button>
            </div>
        @endif
        <label for="image" class="block text-sm font-medium mb-1">
            {{ $category?->image ? 'Replace image' : 'Image' }}
        </label>
        <input id="image" name="image" type="file" accept="image/*"
            class="w-full text-sm file:mr-4 file:rounded-full file:border-0 file:bg-rose-100 file:px-4 file:py-2 file:text-rose-700 file:font-medium">
    </div>

</div>