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
        $notifications = [
            [
                'type' => 'appointment',
                'title' => 'Janji Berobat',
                'message' => 'Ajuan janji berobat baru dari <b>Riko Alfian</b>',
                'icon' => 'fas fa-calendar-plus',
                'color' => 'bg-primary',
                'user_id' => null, // Untuk semua admin
                'related_id' => 9,
                'related_type' => 'App\Models\Appointment',
                'action_url' => '/data-janji-berobat',
                'is_read' => false,
                'created_at' => now()->subMinutes(5),
                'updated_at' => now()->subMinutes(5)
            ],
            [
                'type' => 'appointment',
                'title' => 'Janji Berobat',
                'message' => 'Janji berobat <b>Ahmad Rizki</b> telah diubah',
                'icon' => 'fas fa-edit',
                'color' => 'bg-warning',
                'user_id' => null,
                'related_id' => 10,
                'related_type' => 'App\Models\Appointment',
                'action_url' => '/data-janji-berobat',
                'is_read' => false,
                'created_at' => now()->subMinutes(15),
                'updated_at' => now()->subMinutes(15)
            ],
            [
                'type' => 'user_registration',
                'title' => 'Pasien Baru',
                'message' => 'Pasien baru <b>Siti Nurhaliza</b> telah mendaftar',
                'icon' => 'fas fa-user-plus',
                'color' => 'bg-success',
                'user_id' => null,
                'related_id' => 6,
                'related_type' => 'App\Models\User',
                'action_url' => '/data-pasien',
                'is_read' => false,
                'created_at' => now()->subMinutes(30),
                'updated_at' => now()->subMinutes(30)
            ],
            [
                'type' => 'subscription',
                'title' => 'Berlangganan Baru',
                'message' => 'Pasien <b>Budi Santoso</b> telah berlangganan paket monthly',
                'icon' => 'fas fa-crown',
                'color' => 'bg-warning',
                'user_id' => null,
                'related_id' => 5,
                'related_type' => 'App\Models\User',
                'action_url' => '/data-pasien',
                'is_read' => true,
                'read_at' => now()->subHour(1),
                'created_at' => now()->subHour(2),
                'updated_at' => now()->subHour(1)
            ],
            [
                'type' => 'user_registration',
                'title' => 'Pasien Baru',
                'message' => 'Pasien baru <b>Dewi Sartika</b> telah mendaftar',
                'icon' => 'fas fa-user-plus',
                'color' => 'bg-success',
                'user_id' => null,
                'related_id' => 21,
                'related_type' => 'App\Models\User',
                'action_url' => '/data-pasien',
                'is_read' => true,
                'read_at' => now()->subHours(3),
                'created_at' => now()->subHours(4),
                'updated_at' => now()->subHours(3)
            ]
        ];

        foreach ($notifications as $notification) {
            \App\Models\Notification::create($notification);
        }
    }
}
