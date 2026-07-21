<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query()
            ->select('customer_email')
            ->selectRaw('MAX(customer_name) as customer_name')
            ->selectRaw('MAX(customer_phone) as customer_phone')
            ->selectRaw('COUNT(*) as orders_count')
            ->selectRaw('SUM(CASE WHEN status != "cancelled" THEN total ELSE 0 END) as total_spent')
            ->selectRaw('MAX(created_at) as last_order_at')
            ->groupBy('customer_email');

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->having('customer_name', 'like', "%{$search}%")
                ->orHaving('customer_email', 'like', "%{$search}%");
        }

        $customers = $query->orderByDesc('last_order_at')->paginate(15)->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    public function show(Request $request, string $email)
    {
        $orders = Order::query()
            ->where('customer_email', $email)
            ->latest()
            ->paginate(10);

        abort_if($orders->isEmpty() && ! $request->boolean('allow_empty'), 404);

        $customer = [
            'email' => $email,
            'name' => $orders->first()?->customer_name,
            'phone' => $orders->first()?->customer_phone,
            'orders_count' => Order::where('customer_email', $email)->count(),
            'total_spent' => Order::where('customer_email', $email)->where('status', '!=', 'cancelled')->sum('total'),
        ];

        return view('admin.customers.show', compact('customer', 'orders'));
    }
}