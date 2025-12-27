<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'action',
        'model',
        'model_id',
        'description',
        'user_id',
        'old_values',
        'new_values'
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper method untuk membuat log aktivitas
    public static function log($action, $model, $modelId, $description, $userId = null, $oldValues = null, $newValues = null)
    {
        return self::create([
            'action' => $action,
            'model' => $model,
            'model_id' => $modelId,
            'description' => $description,
            'user_id' => $userId ?? auth()->id(),
            'old_values' => $oldValues,
            'new_values' => $newValues
        ]);
    }
}
