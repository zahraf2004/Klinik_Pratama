<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    protected $table = 'ch_messages';
    
    protected $fillable = [
        'from_id',
        'to_id', 
        'body',
        'attachment',
        'seen'
    ];

    protected $casts = [
        'seen' => 'boolean'
    ];

    // Relasi ke user pengirim
    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_id');
    }

    // Relasi ke user penerima
    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_id');
    }
}