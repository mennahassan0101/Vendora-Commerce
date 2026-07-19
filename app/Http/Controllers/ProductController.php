<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()
            ->active()
            ->with(['primaryImage', 'categories']);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->string('search') . '%');
        }

        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('slug', $request->string('category'));
            });
        }

        match ($request->get('sort')) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'newest' => $query->latest(),
            default => $query->orderBy('name'),
        };

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::active()->orderBy('name')->get();

        return view('products.index', compact('products', 'categories'));
    }
}