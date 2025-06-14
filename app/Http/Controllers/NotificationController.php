<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\NotificationUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // Ambil semua notifikasi untuk user yang sedang login
    public function index()
    {
        $notifications = NotificationUser::with('notification')
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();

        return response()->json($notifications);
    }

    public function sendToFollowers(User $sender, $judul, $deskripsi)
    {
        $notification = Notification::create([
            'judul' => $judul,
            'deskripsi' => $deskripsi,
        ]);

        foreach ($sender->followers as $follower) {
            NotificationUser::create([
                'notification_id' => $notification->id,
                'user_id' => $follower->id,
            ]);
        }

        return true; // tidak perlu response JSON karena dipanggil internal
    }

    // Tandai satu notifikasi sebagai dibaca
    public function markAsRead($notificationUserId)
    {
        $notifUser = NotificationUser::where('id', $notificationUserId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notifUser->update(['read_at' => now()]);

        return response()->json(['message' => 'Notifikasi ditandai sebagai dibaca.']);
    }
}
