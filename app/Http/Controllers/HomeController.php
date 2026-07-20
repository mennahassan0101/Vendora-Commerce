<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $featured = Product::query()
            ->active()
            ->inStock()
            ->featured()
            ->with(['primaryImage', 'images'])
            ->latest()
            ->take(8)
            ->get();

        $latest = Product::query()
            ->active()
            ->inStock()
            ->with(['primaryImage', 'images'])
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::query()
            ->active()
            ->orderBy('name')
            ->take(6)
            ->get();

        return view('home', compact('featured', 'latest', 'categories'));
    }
}