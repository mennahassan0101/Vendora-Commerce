<?php

namespace App\Notifications\Admin;

use App\Models\Order;
use Illuminate\Notifications\Notification;

class NewOrderPlaced extends Notification
{
    public function __construct(public Order $order)
    {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'new_order',
            'order_id' => $this->order->id,
            'message' => "New order {$this->order->tracking_reference} from {$this->order->customer_name} — \${$this->order->total}",
            'url' => route('admin.dashboard'),
        ];
    }
}