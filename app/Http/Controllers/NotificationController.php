<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function markasReadNotification($id)
    {
        try {
            $notification = auth()->user()->notifications->find($id);
            $notification->markAsRead();
            return redirect()->back();
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function markasReadAllNotification()
    {
        try {
            $user = auth()->user();
            $user->unreadNotifications->markAsRead();
            return redirect()->back();
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
