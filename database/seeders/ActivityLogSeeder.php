<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivityLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $activities = [
            [
                'action' => 'approve',
                'model' => 'Appointment',
                'model_id' => '9',
                'description' => 'Menyetujui janji berobat pasien <b>Riko</b>',
                'user_id' => 2, // Admin
                'created_at' => now()->subMinutes(5),
                'updated_at' => now()->subMinutes(5)
            ],
            [
                'action' => 'create',
                'model' => 'TenagaKesehatan',
                'model_id' => '11',
                'description' => 'Menambahkan data tenaga kesehatan <b>dr. Rifda Revonika</b>',
                'user_id' => 2, // Admin
                'created_at' => now()->subMinutes(15),
                'updated_at' => now()->subMinutes(15)
            ],
            [
                'action' => 'update',
                'model' => 'Obat',
                'model_id' => '7',
                'description' => 'Mengupdate informasi obat <b>BlackMores Vit D3 1000 IU</b>',
                'user_id' => 2, // Admin
                'created_at' => now()->subMinutes(30),
                'updated_at' => now()->subMinutes(30)
            ],
            [
                'action' => 'create',
                'model' => 'Obat',
                'model_id' => '9',
                'description' => 'Menambahkan informasi obat <b>Ventolin Inhaler 100 Mcg</b>',
                'user_id' => 2, // Admin
                'created_at' => now()->subHour(1),
                'updated_at' => now()->subHour(1)
            ],
            [
                'action' => 'cancel',
                'model' => 'Appointment',
                'model_id' => '8',
                'description' => 'Membatalkan janji berobat pasien <b>Riko</b>',
                'user_id' => 2, // Admin
                'created_at' => now()->subHours(2),
                'updated_at' => now()->subHours(2)
            ]
        ];

        foreach ($activities as $activity) {
            \App\Models\ActivityLog::create($activity);
        }
    }
}
