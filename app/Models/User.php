<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi ke Profil_Pasien (jika user adalah pasien)
    public function profil_pasien()
    {
        return $this->hasOne(Profil_Pasien::class);
    }

    // Method untuk cek apakah user adalah pasien
    public function isPasien()
    {
        return $this->role === 'pasien';
    }

    // Method untuk mendapatkan atau membuat patient profile
    public function getProfilPasien()
    {
        return $this->profil_pasien ?? $this->profil_pasien()->create([]);
    }
}