<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    /**
     * Buat notifikasi baru untuk user
     */
    public function create(User $user, array $data)
    {
        return Notification::create([
            'user_id' => $user->id,
            'type' => $data['type'] ?? 'system',
            'title' => $data['title'],
            'message' => $data['message'],
            'icon' => $data['icon'] ?? $this->getDefaultIcon($data['type'] ?? 'system'),
            'color' => $data['color'] ?? $this->getDefaultColor($data['type'] ?? 'system'),
            'link' => $data['link'] ?? null,
            'data' => $data['data'] ?? null,
        ]);
    }

    /**
     * Notifikasi untuk pembayaran berhasil
     */
    public function paymentSuccess($transaction)
    {
        $user = $transaction->user;
        $item = $transaction->items->first();
        $product = $item->product;

        return $this->create($user, [
            'type' => 'transaction',
            'title' => 'Pembayaran Berhasil',
            'message' => "Pembayaran untuk {$product->name} berhasil! Invoice: {$transaction->invoice}",
            'icon' => 'bx-check-circle',
            'color' => 'bg-green-100 text-green-600',
            'link' => route('orders.show', $transaction->id),
            'data' => [
                'transaction_id' => $transaction->id,
                'invoice' => $transaction->invoice,
                'amount' => $transaction->amount,
            ],
        ]);
    }

    /**
     * Notifikasi untuk promo
     */
    public function promo(User $user, string $title, string $message, $link = null)
    {
        return $this->create($user, [
            'type' => 'promo',
            'title' => $title,
            'message' => $message,
            'icon' => 'bx-gift',
            'color' => 'bg-red-100 text-red-600',
            'link' => $link,
        ]);
    }

    /**
     * Notifikasi sistem
     */
    public function system(User $user, string $title, string $message)
    {
        return $this->create($user, [
            'type' => 'system',
            'title' => $title,
            'message' => $message,
            'icon' => 'bx-cog',
            'color' => 'bg-blue-100 text-blue-600',
        ]);
    }

    /**
     * Get unread count untuk user
     */
    public function getUnreadCount(User $user)
    {
        return Notification::where('user_id', $user->id)
            ->unread()
            ->count();
    }

    /**
     * Mark all as read
     */
    public function markAllAsRead(User $user)
    {
        return Notification::where('user_id', $user->id)
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }

    /**
     * Get default icon berdasarkan type
     */
    private function getDefaultIcon($type)
    {
        return match($type) {
            'transaction' => 'bx-check-circle',
            'promo' => 'bx-gift',
            'system' => 'bx-cog',
            default => 'bx-bell',
        };
    }

    /**
     * Get default color berdasarkan type
     */
    private function getDefaultColor($type)
    {
        return match($type) {
            'transaction' => 'bg-green-100 text-green-600',
            'promo' => 'bg-red-100 text-red-600',
            'system' => 'bg-blue-100 text-blue-600',
            default => 'bg-blue-100 text-blue-600',
        };
    }
}