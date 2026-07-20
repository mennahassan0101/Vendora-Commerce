<?php

namespace App\Http\Controllers;

use App\Models\StockNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StockNotificationController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        StockNotification::create($data);

        return back()->with('notify_success', 'You\'re on the list — we\'ll email you the moment it\'s back.');
    }
}