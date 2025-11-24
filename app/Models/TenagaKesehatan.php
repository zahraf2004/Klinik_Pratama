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

    // Helper untuk mendapatkan jadwal shift berdasarkan tanggal
    public function getJadwalByTanggal($tanggal)
    {
        if (!$this->jadwal_shift) {
            return null;
        }
        
        $date = \Carbon\Carbon::parse($tanggal);
        
        return collect($this->jadwal_shift)->first(function ($jadwal) use ($date) {
            $mulai = \Carbon\Carbon::parse($jadwal['tanggal_mulai']);
            $selesai = \Carbon\Carbon::parse($jadwal['tanggal_selesai']);
            
            return $date->between($mulai, $selesai);
        });
    }
    
    // Helper untuk cek apakah tersedia di tanggal tertentu
    public function isAvailableOnDate($tanggal)
    {
        return $this->getJadwalByTanggal($tanggal) !== null;
    }
    
    // Helper untuk mendapatkan jadwal shift aktif saat ini
    public function getCurrentShift()
    {
        return $this->getJadwalByTanggal(now());
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
