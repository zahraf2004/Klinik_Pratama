<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $table = 'transactions';

    protected $fillable = [
        'order_id',
        'user_id',
        'transaction_id',
        'gross_amount',
        'payment_type',
        'transaction_status',
        'fraud_status',
        'midtrans_response',
        'description'
    ];

    protected $casts = [
        'midtrans_response' => 'array',
        'gross_amount' => 'decimal:2'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isPending(): bool
    {
        return $this->transaction_status === 'pending';
    }

    public function isSuccess(): bool
    {
        return $this->transaction_status === 'settlement';
    }

    public function isFailed(): bool
    {
        return in_array($this->transaction_status, ['deny', 'cancel', 'expire', 'failure']);
    }
}
