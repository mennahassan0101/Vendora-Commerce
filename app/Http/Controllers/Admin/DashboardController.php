<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'orders_total' => Order::count(),
            'orders_pending' => Order::where('status', 'pending')->count(),
            'revenue' => Order::whereNotIn('status', ['cancelled'])->sum('total'),
            'products_total' => Product::count(),
            'products_low_stock' => Product::whereColumn('stock', '<=', 'low_stock_threshold')->where('stock', '>', 0)->count(),
            'products_out_of_stock' => Product::where('stock', 0)->count(),
            'categories_total' => Category::count(),
        ];

        $recentOrders = Order::query()
            ->latest()
            ->take(6)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }
}