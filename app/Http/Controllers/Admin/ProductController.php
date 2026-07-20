<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()->withCount('images')->with('categories');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->string('search') . '%');
        }

        if ($request->filled('status')) {
            match ($request->string('status')->toString()) {
                'active' => $query->where('is_active', true),
                'inactive' => $query->where('is_active', false),
                'low_stock' => $query->whereColumn('stock', '<=', 'low_stock_threshold')->where('stock', '>', 0),
                'out_of_stock' => $query->where('stock', 0),
                default => null,
            };
        }

        $products = $query->latest()->paginate(15)->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $this->uniqueSlug($data['name']);
        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');

        $product = Product::create($data);

        if (! empty($data['categories'])) {
            $product->categories()->sync($data['categories']);
        }

        $this->storeImages($product, $request);

        return redirect()->route('admin.products.index')->with('admin_success', "\"{$product->name}\" was created.");
    }

    public function edit(Product $product)
    {
        $product->load(['images', 'categories']);
        $categories = Category::orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $data = $request->validated();

        if ($data['name'] !== $product->name) {
            $data['slug'] = $this->uniqueSlug($data['name'], $product->id);
        }

        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');

        $product->update($data);
        $product->categories()->sync($data['categories'] ?? []);

        $this->storeImages($product, $request);

        return redirect()->route('admin.products.index')->with('admin_success', "\"{$product->name}\" was updated.");
    }

    public function destroy(Product $product): RedirectResponse
    {
        // Soft delete: keeps order history intact (order_items.product_id
        // is nullOnDelete, but soft-deleting preserves the product record itself).
        $product->delete();

        return redirect()->route('admin.products.index')->with('admin_success', "\"{$product->name}\" was deleted.");
    }

    public function destroyImage(Product $product, ProductImage $image): RedirectResponse
    {
        abort_unless($image->product_id === $product->id, 404);

        Storage::disk('public')->delete($image->path);
        $wasPrimary = $image->is_primary;
        $image->delete();

        if ($wasPrimary) {
            $product->images()->orderBy('sort_order')->first()?->update(['is_primary' => true]);
        }

        return back()->with('admin_success', 'Image removed.');
    }

    public function setPrimaryImage(Product $product, ProductImage $image): RedirectResponse
    {
        abort_unless($image->product_id === $product->id, 404);

        $product->images()->update(['is_primary' => false]);
        $image->update(['is_primary' => true]);

        return back()->with('admin_success', 'Primary image updated.');
    }

    private function storeImages(Product $product, Request $request): void
    {
        if (! $request->hasFile('images')) {
            return;
        }

        $hasPrimary = $product->images()->where('is_primary', true)->exists();
        $nextSort = $product->images()->max('sort_order') + 1;

        foreach ($request->file('images') as $file) {
            $path = $file->store('products', 'public');

            $product->images()->create([
                'path' => $path,
                'is_primary' => ! $hasPrimary,
                'sort_order' => $nextSort++,
            ]);

            $hasPrimary = true; // only the very first uploaded image (if none existed) becomes primary
        }
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;

        while (Product::withTrashed()->where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$base}-" . $i++;
        }

        return $slug;
    }
}