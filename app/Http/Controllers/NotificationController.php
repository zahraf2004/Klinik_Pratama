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

        // Ambil semua admin dan buat notifikasi untuk masing-masing
        $admins = \App\Models\User::where('role', 'admin')->get();
        
        foreach ($admins as $admin) {
            Notification::create_notification(
                'appointment',
                'Janji Berobat',
                $messages[$type],
                [
                    'icon' => $icons[$type],
                    'color' => $colors[$type],
                    'user_id' => $admin->id, // Set user_id ke admin spesifik
                    'related_id' => $appointment->id,
                    'related_type' => 'App\Models\Appointment',
                    'action_url' => route('appointment.admin')
                ]
            );
        }
    }

    // Method untuk notifikasi pasien baru registrasi
    public static function createUserRegistrationNotification($user)
    {
        // Ambil semua admin dan buat notifikasi untuk masing-masing
        $admins = \App\Models\User::where('role', 'admin')->get();
        
        foreach ($admins as $admin) {
            Notification::create_notification(
                'user_registration',
                'Pasien Baru',
                "Pasien baru <b>{$user->name}</b> telah mendaftar",
                [
                    'icon' => 'fas fa-user-plus',
                    'color' => 'bg-success',
                    'user_id' => $admin->id, // Set user_id ke admin spesifik
                    'related_id' => $user->id,
                    'related_type' => 'App\Models\User',
                    'action_url' => route('data.pasien')
                ]
            );
        }
    }

    // Method untuk notifikasi berlangganan baru
    public static function createSubscriptionNotification($user, $subscription)
    {
        // Ambil semua admin dan buat notifikasi untuk masing-masing
        $admins = \App\Models\User::where('role', 'admin')->get();
        
        foreach ($admins as $admin) {
            Notification::create_notification(
                'subscription',
                'Berlangganan Baru',
                "Pasien <b>{$user->name}</b> telah berlangganan paket {$subscription->plan_name}",
                [
                    'icon' => 'fas fa-crown',
                    'color' => 'bg-warning',
                    'user_id' => $admin->id, // Set user_id ke admin spesifik
                    'related_id' => $user->id,
                    'related_type' => 'App\Models\User',
                    'action_url' => route('data.pasien')
                ]
            );
        }
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

    // Method untuk notifikasi pesan baru (untuk dokter)
    public static function createNewMessageNotification($fromUser, $toUser, $message)
    {
        Notification::create_notification(
            'new_message',
            'Pesan Baru',
            "Pesan baru dari <b>{$fromUser->name}</b>",
            [
                'icon' => 'fas fa-comment',
                'color' => 'bg-info',
                'user_id' => $toUser->id,
                'related_id' => $fromUser->id,
                'related_type' => 'App\Models\User',
                'action_url' => url("/chatify/{$fromUser->id}")
            ]
        );
    }

    // Method untuk notifikasi status appointment (untuk pasien)
    public static function createAppointmentStatusNotification($appointment, $status)
    {
        if (!$appointment->user_id) return; // Skip jika tidak ada user_id
        
        $messages = [
            'Disetujui' => "Janji berobat Anda telah <b>disetujui</b>",
            'Dibatalkan' => "Janji berobat Anda telah <b>dibatalkan</b>",
            'Selesai' => "Janji berobat Anda telah <b>selesai</b>"
        ];

        $colors = [
            'Disetujui' => 'bg-success',
            'Dibatalkan' => 'bg-danger',
            'Selesai' => 'bg-info'
        ];

        $icons = [
            'Disetujui' => 'fas fa-check',
            'Dibatalkan' => 'fas fa-times',
            'Selesai' => 'fas fa-check-circle'
        ];

        if (isset($messages[$status])) {
            Notification::create_notification(
                'appointment_status',
                'Status Janji Berobat',
                $messages[$status],
                [
                    'icon' => $icons[$status],
                    'color' => $colors[$status],
                    'user_id' => $appointment->user_id,
                    'related_id' => $appointment->id,
                    'related_type' => 'App\Models\Appointment',
                    'action_url' => route('appointment.index')
                ]
            );
        }
    }

    // Method untuk notifikasi balasan dokter (untuk pasien)
    public static function createDoctorReplyNotification($fromDoctor, $toPatient)
    {
        Notification::create_notification(
            'doctor_reply',
            'Balasan Dokter',
            "Dokter <b>{$fromDoctor->name}</b> membalas pesan Anda",
            [
                'icon' => 'fas fa-user-md',
                'color' => 'bg-success',
                'user_id' => $toPatient->id,
                'related_id' => $fromDoctor->id,
                'related_type' => 'App\Models\User',
                'action_url' => url("/chatify/{$fromDoctor->id}")
            ]
        );
    }

    // Method untuk mendapatkan notifikasi berdasarkan role user
    public function getNotificationsByRole()
    {
        $user = Auth::user();
        $notifications = Notification::where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        $unreadCount = Notification::where('user_id', $user->id)
            ->unread()
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }

    // Method untuk mark as read berdasarkan user
    public function markAsReadByUser($id)
    {
        $notification = Notification::where('user_id', Auth::id())->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    // Method untuk mark all as read berdasarkan user
    public function markAllAsReadByUser()
    {
        Notification::where('user_id', Auth::id())
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);

        return response()->json(['success' => true]);
    }

    // Method untuk mendapatkan unread count berdasarkan user
    public function getUnreadCountByUser()
    {
        $count = Notification::where('user_id', Auth::id())
            ->unread()
            ->count();

        return response()->json(['count' => $count]);
    }
}
