<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // Mendapatkan notifikasi untuk admin yang login
    public function getNotifications()
    {
        $notifications = Notification::forAdmin(Auth::id())
            ->latest()
            ->take(10)
            ->get();

        $unreadCount = Notification::forAdmin(Auth::id())
            ->unread()
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }

    // Menandai notifikasi sebagai dibaca
    public function markAsRead($id)
    {
        $notification = Notification::forAdmin(Auth::id())->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    // Menandai semua notifikasi sebagai dibaca
    public function markAllAsRead()
    {
        Notification::forAdmin(Auth::id())
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);

        return response()->json(['success' => true]);
    }

    // Mendapatkan jumlah notifikasi yang belum dibaca
    public function getUnreadCount()
    {
        $count = Notification::forAdmin(Auth::id())
            ->unread()
            ->count();

        return response()->json(['count' => $count]);
    }

    // Method untuk membuat notifikasi baru (akan dipanggil dari model lain)
    public static function createAppointmentNotification($appointment, $type)
    {
        // Ambil nama dari relasi user atau dari field nama langsung
        $patientName = $appointment->user ? $appointment->user->name : $appointment->nama;
        
        $messages = [
            'new' => "Ajuan janji berobat baru dari <b>{$patientName}</b>",
            'updated' => "Janji berobat <b>{$patientName}</b> telah diubah",
            'approved' => "Janji berobat <b>{$patientName}</b> telah disetujui",
            'cancelled' => "Janji berobat <b>{$patientName}</b> telah dibatalkan",
            'completed' => "Janji berobat <b>{$patientName}</b> telah selesai"
        ];

        $colors = [
            'new' => 'bg-primary',
            'updated' => 'bg-warning',
            'approved' => 'bg-success',
            'cancelled' => 'bg-danger',
            'completed' => 'bg-info'
        ];

        $icons = [
            'new' => 'fas fa-calendar-plus',
            'updated' => 'fas fa-edit',
            'approved' => 'fas fa-check',
            'cancelled' => 'fas fa-times',
            'completed' => 'fas fa-check-circle'
        ];

        Notification::create_notification(
            'appointment',
            'Janji Berobat',
            $messages[$type],
            [
                'icon' => $icons[$type],
                'color' => $colors[$type],
                'related_id' => $appointment->id,
                'related_type' => 'App\Models\Appointment',
                'action_url' => route('appointment.admin')
            ]
        );
    }

    // Method untuk notifikasi pasien baru registrasi
    public static function createUserRegistrationNotification($user)
    {
        Notification::create_notification(
            'user_registration',
            'Pasien Baru',
            "Pasien baru <b>{$user->name}</b> telah mendaftar",
            [
                'icon' => 'fas fa-user-plus',
                'color' => 'bg-success',
                'related_id' => $user->id,
                'related_type' => 'App\Models\User',
                'action_url' => route('data.pasien')
            ]
        );
    }

    // Method untuk notifikasi berlangganan baru
    public static function createSubscriptionNotification($user, $subscription)
    {
        Notification::create_notification(
            'subscription',
            'Berlangganan Baru',
            "Pasien <b>{$user->name}</b> telah berlangganan paket {$subscription->plan_name}",
            [
                'icon' => 'fas fa-crown',
                'color' => 'bg-warning',
                'related_id' => $user->id,
                'related_type' => 'App\Models\User',
                'action_url' => route('data.pasien')
            ]
        );
    }

    // Method untuk membuat notifikasi sistem
    public static function createSystemNotification($title, $message, $options = [])
    {
        Notification::create_notification(
            'system',
            $title,
            $message,
            array_merge([
                'icon' => 'fas fa-cog',
                'color' => 'bg-warning'
            ], $options)
        );
    }
}
