<?php

namespace App\Notifications\Customer;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusUpdated extends Notification
{
    use Queueable;

    public function __construct(public Order $order)
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Your order {$this->order->tracking_reference} has been updated")
            ->greeting("Hi {$this->order->customer_name},")
            ->line("Your order status is now: {$this->order->statusLabel()}.")
            ->line("Order reference: {$this->order->tracking_reference}")
            ->action('Track your order', route('orders.track', ['reference' => $this->order->tracking_reference]))
            ->line('Thank you for shopping with Vendora!');
    }
}