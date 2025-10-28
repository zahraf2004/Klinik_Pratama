<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $table = 'appointment'; // karena tabel kamu singular

    protected $fillable = [
        'user_id',
        'nama',
        'no_hp',
        'tanggal_lahir',
        'alamat',
        'keluhan',
        'tanggal',
        'jam',
        'status',
        'admin_notes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
