<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'foto',
        'no_hp',
        'tanggal_lahir',
        'alamat',
        'golongan_darah',
        'jenis_kelamin',
        'berat_badan',
        'tinggi_badan'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'berat_badan' => 'decimal:2',
        'tinggi_badan' => 'decimal:2'
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessor untuk BMI
    public function getBmiAttribute()
    {
        if ($this->tinggi_badan && $this->berat_badan) {
            $tinggi_meter = $this->tinggi_badan / 100;
            return number_format($this->berat_badan / ($tinggi_meter * $tinggi_meter), 1);
        }
        return null;
    }
}