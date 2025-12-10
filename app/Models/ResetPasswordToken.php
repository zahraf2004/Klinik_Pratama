<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResetPasswordToken extends Model
{
    protected $table = 'reset_password';

    protected $fillable = [
        'reset_email',
        'reset_otp',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime'
    ];

}
