<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function track(Request $request)
    {
        $order = null;
        $searched = $request->filled('reference');

        if ($searched) {
            $order = Order::query()
                ->where('tracking_reference', strtoupper(trim($request->string('reference'))))
                ->with(['items', 'statusHistory'])
                ->first();
        }

        return view('orders.track', compact('order', 'searched'));
    }
}