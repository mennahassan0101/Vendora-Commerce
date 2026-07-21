<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Models\ActivityLog;

class LogOrderActivity
{
    public function handle(OrderPlaced $event): void
    {
        ActivityLog::create([
            'user_id' => null, // system-generated, no admin involved — order came from a guest checkout
            'action' => 'created',
            'subject_type' => $event->order::class,
            'subject_id' => $event->order->id,
            'description' => "Order {$event->order->tracking_reference} placed by {$event->order->customer_name} — \${$event->order->total}",
        ]);
    }
}