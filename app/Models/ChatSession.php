<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'message_count',
        'is_premium',
        'is_active',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'is_premium' => 'boolean',
        'is_active' => 'boolean',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    // Relasi ke User (Pasien)
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    // Relasi ke User (Dokter)
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    // Check apakah sudah mencapai limit
    public function hasReachedLimit()
    {
        return !$this->is_premium && $this->message_count >= 3;
    }

    // Increment message count
    public function incrementMessageCount()
    {
        $this->increment('message_count');
    }

    // End session
    public function endSession()
    {
        $this->update([
            'is_active' => false,
            'ended_at' => now(),
        ]);
    }
}
