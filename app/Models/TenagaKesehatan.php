<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class TenagaKesehatan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tenaga_kesehatan';

    protected $fillable = [
        'user_id',
        'foto_path',
        'nama',
        'tanggal_lahir',
        'email',
        'hp',
        'alumnus',
        'profesi',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
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
}
