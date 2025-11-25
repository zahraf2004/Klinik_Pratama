<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TenagaKesehatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\TenagaKesehatan::create([
            'nama' => 'Dr. Ahmad Fauzi',
            'email' => 'ahmad.fauzi@klinik.com',
            'hp' => '081234567890',
            'str' => '123456789012345',
            'sip' => '503/SIP/2020/001',
            'tahun_mulai' => 2014,
            'role' => 'dokter_umum',
            'jadwal_shift' => [
                ['hari' => 'Senin', 'jam_mulai' => '08:00', 'jam_selesai' => '14:00'],
                ['hari' => 'Rabu', 'jam_mulai' => '08:00', 'jam_selesai' => '14:00'],
                ['hari' => 'Jumat', 'jam_mulai' => '13:00', 'jam_selesai' => '17:00'],
            ]
        ]);

        \App\Models\TenagaKesehatan::create([
            'nama' => 'Dr. Siti Nurhaliza',
            'email' => 'siti.nurhaliza@klinik.com',
            'hp' => '081234567891',
            'str' => '123456789012346',
            'sip' => '503/SIP/2021/002',
            'tahun_mulai' => 2017,
            'role' => 'admin',
            'jadwal_shift' => [
                ['hari' => 'Selasa', 'jam_mulai' => '08:00', 'jam_selesai' => '16:00'],
                ['hari' => 'Kamis', 'jam_mulai' => '08:00', 'jam_selesai' => '16:00'],
                ['hari' => 'Sabtu', 'jam_mulai' => '09:00', 'jam_selesai' => '13:00'],
            ]
        ]);

        \App\Models\TenagaKesehatan::create([
            'nama' => 'Bidan Rina Wati',
            'email' => 'rina.wati@klinik.com',
            'hp' => '081234567892',
            'str' => '223456789012347',
            'sip' => '503/SIP/2022/003',
            'tahun_mulai' => 2019,
            'role' => 'dokter_umum',
            'jadwal_shift' => [
                ['hari' => 'Senin', 'jam_mulai' => '13:00', 'jam_selesai' => '17:00'],
                ['hari' => 'Rabu', 'jam_mulai' => '13:00', 'jam_selesai' => '17:00'],
                ['hari' => 'Jumat', 'jam_mulai' => '08:00', 'jam_selesai' => '12:00'],
            ]
        ]);
    }
}
