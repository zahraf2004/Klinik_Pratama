<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class TenagaKesehatan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tenaga_kesehatan';

    protected $fillable = [
        'user_id',
        'foto_path',
        'nama',
        'email',
        'hp',
        'str',
        'sip',
        'tahun_mulai',
        'role',
        'jadwal_shift',
    ];

    protected $casts = [
        'jadwal_shift' => 'array',
        'tahun_mulai' => 'integer',
    ];

    // Relasi opsional ke users
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Akses URL foto siap pakai di Blade: $tk->foto_url
    public function getFotoUrlAttribute()
    {
        return $this->foto_path
            ? Storage::disk('public')->url($this->foto_path)
            : null;
    }

    // Helper untuk mendapatkan jadwal shift berdasarkan hari
    public function getJadwalByHari($hari)
    {
        if (!$this->jadwal_shift) {
            return null;
        }
        
        return collect($this->jadwal_shift)->firstWhere('hari', $hari);
    }
    
    // Helper untuk cek apakah tersedia di hari tertentu
    public function isAvailableOnDay($hari)
    {
        return $this->getJadwalByHari($hari) !== null;
    }
    
    // Helper untuk mendapatkan jadwal shift hari ini
    public function getTodayShift()
    {
        $hariIni = \Carbon\Carbon::now()->locale('id')->dayName;
        
        // Map nama hari dari Carbon ke format Indonesia
        $hariMap = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu'
        ];
        
        $hariIndonesia = $hariMap[\Carbon\Carbon::now()->format('l')] ?? null;
        
        return $this->getJadwalByHari($hariIndonesia);
    }
    
    // Helper untuk menghitung pengalaman otomatis dari tahun mulai
    public function getPengalamanAttribute()
    {
        if (!$this->tahun_mulai) {
            return null;
        }
        
        $tahunSekarang = date('Y');
        $lamaKerja = $tahunSekarang - $this->tahun_mulai;
        
        if ($lamaKerja < 0) {
            return 'Belum mulai';
        } elseif ($lamaKerja == 0) {
            return 'Kurang dari 1 tahun';
        } elseif ($lamaKerja == 1) {
            return '1 tahun';
        } else {
            return $lamaKerja . ' tahun';
        }
    }
}
