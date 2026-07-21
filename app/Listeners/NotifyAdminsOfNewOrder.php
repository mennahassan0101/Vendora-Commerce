<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Notifications\Admin\NewOrderPlaced;
use App\Services\AdminNotifier;

class NotifyAdminsOfNewOrder
{
    public function handle(OrderPlaced $event): void
    {
        AdminNotifier::send(new NewOrderPlaced($event->order));
    }
}