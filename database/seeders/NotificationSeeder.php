<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil admin pertama untuk contoh notifikasi
        $admin = \App\Models\User::where('role', 'admin')->first();
        $adminId = $admin ? $admin->id : 2; // Default ke ID 2 jika tidak ada admin
        
        // Ambil dokter dan pasien untuk sample notifikasi
        $dokter = \App\Models\User::where('role', 'dokter')->first();
        $pasien = \App\Models\User::where('role', 'pasien')->first();
        
        $notifications = [
            // Notifikasi untuk Admin
            [
                'type' => 'appointment',
                'title' => 'Janji Berobat',
                'message' => 'Ajuan janji berobat baru dari <b>Riko Alfian</b>',
                'icon' => 'fas fa-calendar-plus',
                'color' => 'bg-primary',
                'user_id' => $adminId,
                'related_id' => 9,
                'related_type' => 'App\Models\Appointment',
                'action_url' => '/data-janji-berobat',
                'is_read' => false,
                'created_at' => now()->subMinutes(5),
                'updated_at' => now()->subMinutes(5)
            ],
            [
                'type' => 'user_registration',
                'title' => 'Pasien Baru',
                'message' => 'Pasien baru <b>Siti Nurhaliza</b> telah mendaftar',
                'icon' => 'fas fa-user-plus',
                'color' => 'bg-success',
                'user_id' => $adminId,
                'related_id' => 6,
                'related_type' => 'App\Models\User',
                'action_url' => '/data-pasien',
                'is_read' => false,
                'created_at' => now()->subMinutes(30),
                'updated_at' => now()->subMinutes(30)
            ],
            
            // Notifikasi untuk Dokter (jika ada)
            ...$dokter ? [
                [
                    'type' => 'new_message',
                    'title' => 'Pesan Baru',
                    'message' => 'Pesan baru dari <b>' . ($pasien ? $pasien->name : 'Pasien') . '</b>',
                    'icon' => 'fas fa-comment',
                    'color' => 'bg-info',
                    'user_id' => $dokter->id,
                    'related_id' => $pasien ? $pasien->id : 5,
                    'related_type' => 'App\Models\User',
                    'action_url' => '/chatify/' . ($pasien ? $pasien->id : 5),
                    'is_read' => false,
                    'created_at' => now()->subMinutes(10),
                    'updated_at' => now()->subMinutes(10)
                ]
            ] : [],
            
            // Notifikasi untuk Pasien (jika ada)
            ...$pasien ? [
                [
                    'type' => 'appointment_status',
                    'title' => 'Status Janji Berobat',
                    'message' => 'Janji berobat Anda telah <b>disetujui</b>',
                    'icon' => 'fas fa-check',
                    'color' => 'bg-success',
                    'user_id' => $pasien->id,
                    'related_id' => 9,
                    'related_type' => 'App\Models\Appointment',
                    'action_url' => '/janji-berobat',
                    'is_read' => false,
                    'created_at' => now()->subMinutes(20),
                    'updated_at' => now()->subMinutes(20)
                ],
                [
                    'type' => 'doctor_reply',
                    'title' => 'Balasan Dokter',
                    'message' => 'Dokter <b>' . ($dokter ? $dokter->name : 'dr. Dokter') . '</b> membalas pesan Anda',
                    'icon' => 'fas fa-user-md',
                    'color' => 'bg-success',
                    'user_id' => $pasien->id,
                    'related_id' => $dokter ? $dokter->id : 7,
                    'related_type' => 'App\Models\User',
                    'action_url' => '/chatify/' . ($dokter ? $dokter->id : 7),
                    'is_read' => true,
                    'read_at' => now()->subHour(1),
                    'created_at' => now()->subHour(2),
                    'updated_at' => now()->subHour(1)
                ]
            ] : []
        ];

        foreach ($notifications as $notification) {
            \App\Models\Notification::create($notification);
        }
    }
}
