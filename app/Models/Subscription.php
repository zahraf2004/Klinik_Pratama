<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Subscription extends Model
{
    protected $table = 'subscriptions';

    protected $fillable = [
        'user_id',
        'plan_name',
        'price',
        'status',
        'starts_at',
        'expires_at',
        'transaction_id'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'order_id');
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && $this->expires_at > now();
    }

    public function isExpired(): bool
    {
        return $this->expires_at <= now();
    }

    public function daysRemaining(): int
    {
        if ($this->isExpired()) {
            return 0;
        }
        
        return now()->diffInDays($this->expires_at);
    }

    // Scope untuk subscription aktif
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                    ->where('expires_at', '>', now());
    }

    // Static method untuk cek user punya subscription aktif
    public static function userHasActiveSubscription($userId): bool
    {
        return self::where('user_id', $userId)
                  ->active()
                  ->exists();
    }
}