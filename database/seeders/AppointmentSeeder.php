<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $appointments = [
            // Data bulan ini (Desember 2025)
            [
                'user_id' => 5,
                'nama' => 'Ahmad Rizki',
                'no_hp' => '081234567890',
                'tanggal_lahir' => '1990-05-15',
                'alamat' => 'Jl. Merdeka No. 123',
                'keluhan' => 'Kontrol rutin diabetes',
                'tanggal' => '2025-12-15',
                'jam' => '09:00:00',
                'status' => 'Selesai',
                'created_at' => now()->subDays(12),
                'updated_at' => now()->subDays(10),
            ],
            [
                'user_id' => 6,
                'nama' => 'Siti Nurhaliza',
                'no_hp' => '082345678901',
                'tanggal_lahir' => '1985-08-20',
                'alamat' => 'Jl. Sudirman No. 456',
                'keluhan' => 'Pemeriksaan kesehatan umum',
                'tanggal' => '2025-12-20',
                'jam' => '10:30:00',
                'status' => 'Selesai',
                'created_at' => now()->subDays(7),
                'updated_at' => now()->subDays(5),
            ],
            
            // Data bulan lalu (November 2025)
            [
                'user_id' => 5,
                'nama' => 'Budi Santoso',
                'no_hp' => '083456789012',
                'tanggal_lahir' => '1992-03-10',
                'alamat' => 'Jl. Gatot Subroto No. 789',
                'keluhan' => 'Sakit kepala berkepanjangan',
                'tanggal' => '2025-11-25',
                'jam' => '14:00:00',
                'status' => 'Selesai',
                'created_at' => now()->subMonth()->subDays(5),
                'updated_at' => now()->subMonth()->subDays(3),
            ],
            [
                'user_id' => 6,
                'nama' => 'Dewi Sartika',
                'no_hp' => '084567890123',
                'tanggal_lahir' => '1988-12-05',
                'alamat' => 'Jl. Diponegoro No. 321',
                'keluhan' => 'Konsultasi gizi',
                'tanggal' => '2025-11-18',
                'jam' => '11:00:00',
                'status' => 'Selesai',
                'created_at' => now()->subMonth()->subDays(12),
                'updated_at' => now()->subMonth()->subDays(10),
            ],
            [
                'user_id' => 5,
                'nama' => 'Andi Wijaya',
                'no_hp' => '085678901234',
                'tanggal_lahir' => '1995-07-22',
                'alamat' => 'Jl. Ahmad Yani No. 654',
                'keluhan' => 'Pemeriksaan mata',
                'tanggal' => '2025-11-10',
                'jam' => '15:30:00',
                'status' => 'Selesai',
                'created_at' => now()->subMonth()->subDays(20),
                'updated_at' => now()->subMonth()->subDays(18),
            ],
            
            // Data 2 bulan lalu (Oktober 2025)
            [
                'user_id' => 6,
                'nama' => 'Maya Sari',
                'no_hp' => '086789012345',
                'tanggal_lahir' => '1991-11-30',
                'alamat' => 'Jl. Pahlawan No. 987',
                'keluhan' => 'Vaksinasi influenza',
                'tanggal' => '2025-10-28',
                'jam' => '08:30:00',
                'status' => 'Selesai',
                'created_at' => now()->subMonths(2)->subDays(3),
                'updated_at' => now()->subMonths(2)->subDays(1),
            ],
            [
                'user_id' => 5,
                'nama' => 'Rudi Hartono',
                'no_hp' => '087890123456',
                'tanggal_lahir' => '1987-04-18',
                'alamat' => 'Jl. Veteran No. 147',
                'keluhan' => 'Terapi fisik',
                'tanggal' => '2025-10-15',
                'jam' => '13:00:00',
                'status' => 'Selesai',
                'created_at' => now()->subMonths(2)->subDays(15),
                'updated_at' => now()->subMonths(2)->subDays(13),
            ]
        ];

        foreach ($appointments as $appointment) {
            \App\Models\Appointment::create($appointment);
        }
    }
}
