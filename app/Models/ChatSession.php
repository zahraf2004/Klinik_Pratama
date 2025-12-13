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

    // Check apakah user sudah mencapai limit session (bukan per pesan)
    public function userHasReachedSessionLimit()
    {
        if ($this->is_premium) {
            return false; // Premium unlimited
        }
        
        // Hitung berapa session yang sudah di-end untuk user ini
        $completedSessions = ChatSession::where('patient_id', $this->patient_id)
            ->where('is_active', false) // Session yang sudah di-end
            ->where('is_premium', false) // Hanya hitung free sessions
            ->count();
            
        return $completedSessions >= 3; // Limit 3 session
    }

    // Increment message count (tetap ada untuk tracking)
    public function incrementMessageCount()
    {
        $this->increment('message_count');
    }

    // End session - ini yang mengurangi token
    public function endSession()
    {
        $this->update([
            'is_active' => false,
            'ended_at' => now(),
        ]);
    }
    
    // Get remaining session tokens untuk user
    public static function getRemainingSessionTokens($userId)
    {
        $completedSessions = self::where('patient_id', $userId)
            ->where('is_active', false)
            ->where('is_premium', false)
            ->count();
            
        return max(0, 3 - $completedSessions);
    }
}
