<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return view('customer.pages.notifications', [
                'notifications' => collect([]),
                'unreadCount' => 0,
            ]);
        }

        // Ambil notifikasi dengan pagination
        $query = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc');

        // Filter by type jika ada
        if ($request->has('type') && $request->type !== 'all') {
            $query->ofType($request->type);
        }

        // Filter unread only
        if ($request->has('unread') && $request->unread == '1') {
            $query->unread();
        }

        $notifications = $query->paginate(20);
        $unreadCount = $this->notificationService->getUnreadCount($user);

        return view('customer.pages.notifications', compact('notifications', 'unreadCount'));
    }

    public function show($id)
    {
        $notification = Notification::where('user_id', auth()->id())
            ->findOrFail($id);

        // Mark as read
        if (!$notification->is_read) {
            $notification->markAsRead();
        }

        // Redirect ke link jika ada
        if ($notification->link) {
            return redirect($notification->link);
        }

        return redirect()->route('customer.pages.notification-show', $notification->id);
    }

    public function markAllAsRead()
    {
        $this->notificationService->markAllAsRead(auth()->user());

        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi ditandai sudah dibaca'
        ]);
    }

    public function clear()
    {
        Notification::where('user_id', auth()->id())->delete();

        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi dihapus'
        ]);
    }

    public function getUnreadCount()
    {
        $count = $this->notificationService->getUnreadCount(auth()->user());

        return response()->json([
            'count' => $count
        ]);
    }
}
