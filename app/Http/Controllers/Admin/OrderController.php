<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query();

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('tracking_reference', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        $orders = $query->latest()->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['items', 'statusHistory']);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,processing,shipped,delivered,cancelled'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        if ($order->status === $data['status']) {
            return back()->with('admin_error', 'Order is already in that status.');
        }

        $order->update(['status' => $data['status']]);

        $order->statusHistory()->create([
            'status' => $data['status'],
            'note' => $data['note'] ?? null,
        ]);

        return back()->with('admin_success', 'Order status updated — customer has been notified.');
    }
}