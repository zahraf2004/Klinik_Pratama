<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            static::logActivity('create', $model, 'Menambahkan ' . static::getModelName() . ' <b>' . static::getModelIdentifier($model) . '</b>');
            
            // Buat notifikasi untuk appointment baru
            if ($model instanceof \App\Models\Appointment) {
                \App\Http\Controllers\NotificationController::createAppointmentNotification($model, 'new');
            }
        });

        static::updated(function ($model) {
            static::logActivity('update', $model, 'Mengupdate ' . static::getModelName() . ' <b>' . static::getModelIdentifier($model) . '</b>');
            
            // Buat notifikasi untuk perubahan appointment
            if ($model instanceof \App\Models\Appointment) {
                if ($model->isDirty('status')) {
                    // Notifikasi untuk admin tentang perubahan status
                    $status = $model->status;
                    $type = match($status) {
                        'Disetujui' => 'approved',
                        'Dibatalkan' => 'cancelled',
                        'Selesai' => 'completed',
                        default => null
                    };
                    
                    if ($type) {
                        \App\Http\Controllers\NotificationController::createAppointmentNotification($model, $type);
                    }
                    
                    // Notifikasi untuk pasien tentang status appointment
                    \App\Http\Controllers\NotificationController::createAppointmentStatusNotification($model, $status);
                } else {
                    // Notifikasi untuk edit data appointment oleh pasien
                    \App\Http\Controllers\NotificationController::createAppointmentNotification($model, 'updated');
                }
            }
        });

        static::deleted(function ($model) {
            static::logActivity('delete', $model, 'Menghapus ' . static::getModelName() . ' <b>' . static::getModelIdentifier($model) . '</b>');
        });
    }

    protected static function logActivity($action, $model, $description)
    {
        ActivityLog::log(
            $action,
            class_basename($model),
            $model->id,
            $description,
            auth()->id()
        );
    }

    protected static function getModelName()
    {
        $modelName = class_basename(static::class);
        
        switch ($modelName) {
            case 'TenagaKesehatan':
                return 'tenaga kesehatan';
            case 'Appointment':
                return 'janji berobat';
            case 'Obat':
                return 'obat';
            default:
                return strtolower($modelName);
        }
    }

    protected static function getModelIdentifier($model)
    {
        if (isset($model->nama)) {
            return $model->nama;
        } elseif (isset($model->name)) {
            return $model->name;
        } elseif (isset($model->nama_obat)) {
            return $model->nama_obat;
        } else {
            return 'ID: ' . $model->id;
        }
    }

    // Method untuk log custom activity
    public static function logCustomActivity($action, $modelId, $description)
    {
        ActivityLog::log(
            $action,
            class_basename(static::class),
            $modelId,
            $description,
            auth()->id()
        );
    }
}