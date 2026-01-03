<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'message',
        'icon',
        'color',
        'user_id',
        'related_id',
        'related_type',
        'action_url',
        'is_read',
        'read_at'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime'
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi polymorphic ke model terkait
    public function related()
    {
        return $this->morphTo();
    }

    // Scope untuk notifikasi yang belum dibaca
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    // Scope untuk notifikasi admin (user_id null atau admin)
    public function scopeForAdmin($query, $adminId = null)
    {
        return $query->where(function($q) use ($adminId) {
            $q->whereNull('user_id') // Notifikasi untuk semua admin
              ->orWhere('user_id', $adminId); // Notifikasi khusus admin tertentu
        });
    }

    // Method untuk menandai sebagai dibaca
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now()
        ]);
    }

    // Helper method untuk membuat notifikasi
    public static function create_notification($type, $title, $message, $options = [])
    {
        return self::create([
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'icon' => $options['icon'] ?? 'fas fa-bell',
            'color' => $options['color'] ?? 'bg-info',
            'user_id' => $options['user_id'] ?? null,
            'related_id' => $options['related_id'] ?? null,
            'related_type' => $options['related_type'] ?? null,
            'action_url' => $options['action_url'] ?? null,
        ]);
    }
}
