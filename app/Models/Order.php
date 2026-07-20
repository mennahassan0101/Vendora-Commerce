<?php

namespace App\Models;

use App\Notifications\Admin\NewOrderPlaced;
use App\Notifications\Customer\OrderStatusUpdated;
use App\Services\AdminNotifier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class Order extends Model
{
    use Notifiable;

    protected $fillable = [
        'tracking_reference',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'shipping_city',
        'shipping_postal_code',
        'shipping_country',
        'payment_method',
        'status',
        'subtotal',
        'shipping_cost',
        'total',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'shipping_cost' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::created(function (Order $order) {
            AdminNotifier::send(new NewOrderPlaced($order));
        });

        static::updated(function (Order $order) {
            if ($order->wasChanged('status')) {
                $order->notify(new OrderStatusUpdated($order));
            }
        });
    }

    public function routeNotificationForMail(): string
    {
        return $this->customer_email;
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class)->orderBy('created_at');
    }

    public static function calculateShipping(float $subtotal): float
    {
        return $subtotal >= 50 ? 0.0 : 5.99;
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending' => 'Order received',
            'processing' => 'Preparing your order',
            'shipped' => 'On its way',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
            default => ucfirst($this->status),
        };
    }
}