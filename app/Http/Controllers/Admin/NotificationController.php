<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
class NotificationController extends Controller
{
    public function index()
    {
        /** @var User|null $user */
        $user = Auth::user();     
        $notifications = $user->notifications()->paginate(20);

        return view('admin.notifications.index', compact('notifications'));
    }

    public function markRead(string $notification): RedirectResponse
    {
        /** @var User|null $user */
        $user = Auth::user();     
        $user->notifications()->findOrFail($notification)->markAsRead();

        return back();
    }

    public function markAllRead(): RedirectResponse
    {
        /** @var User|null $user */
        $user = Auth::user();     
        $user->unreadNotifications->markAsRead();

        return back();
    }
}