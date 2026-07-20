<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Notifications\Notification;

class AdminNotifier
{
    public static function send(Notification $notification): void
    {
        User::admins()->where('is_active', true)->get()->each->notify($notification);
    }
}