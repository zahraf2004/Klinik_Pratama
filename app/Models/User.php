<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait; 

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function profil_pasien()
    {
        return $this->hasOne(\App\Models\ProfilPasien::class, 'user_id');
    }

    public function isPasien()
    {
        return $this->role === 'pasien';
    }

    // Relasi ke tenaga kesehatan
    public function tenagaKesehatan()
    {
        return $this->hasOne(\App\Models\TenagaKesehatan::class, 'user_id');
    }

    // Method untuk Chatify - ambil avatar dari database
    public function getAvatarAttribute()
    {
        // Jika user adalah dokter/nakes, ambil foto dari tenaga_kesehatan
        if (in_array($this->role, ['dokter', 'bidan', 'perawat', 'admin'])) {
            // Load relasi jika belum di-load
            if (!$this->relationLoaded('tenagaKesehatan')) {
                $this->load('tenagaKesehatan');
            }
            
            $nakes = $this->tenagaKesehatan;
            if ($nakes && $nakes->foto_url) {
                return $nakes->foto_url;
            }
        }
        
        // Jika user adalah pasien, ambil foto dari profil_pasien
        if ($this->role === 'pasien') {
            // Load relasi jika belum di-load
            if (!$this->relationLoaded('profil_pasien')) {
                $this->load('profil_pasien');
            }
            
            $profil = $this->profil_pasien;
            if ($profil && $profil->foto) {
                // Foto pasien disimpan di storage/app/public/patient-photos
                return \Storage::disk('public')->url($profil->foto);
            }
        }
        
        // Default avatar jika tidak ada foto
        return asset('assets/img/avatar/avatar-1.png');
    }

    // Helper untuk cek apakah user punya foto custom
    public function hasCustomAvatar()
    {
        if (in_array($this->role, ['dokter', 'bidan', 'perawat', 'admin'])) {
            return $this->tenagaKesehatan && $this->tenagaKesehatan->foto_url;
        }
        
        if ($this->role === 'pasien') {
            return $this->profil_pasien && $this->profil_pasien->foto;
        }
        
        return false;
    }

}
