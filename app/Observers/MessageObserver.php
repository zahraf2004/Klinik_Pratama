<?php

namespace App\Observers;

use App\Models\User;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\DB;

class MessageObserver
{
    /**
     * Handle the message "created" event.
     */
    public function created($message)
    {
        // Ambil data pengirim dan penerima
        $fromUser = User::find($message->from_id);
        $toUser = User::find($message->to_id);
        
        if (!$fromUser || !$toUser) {
            return;
        }
        
        // Jika pengirim adalah pasien dan penerima adalah dokter
        if ($fromUser->role === 'pasien' && $toUser->role === 'dokter') {
            NotificationController::createNewMessageNotification($fromUser, $toUser, $message->body);
        }
        
        // Jika pengirim adalah dokter dan penerima adalah pasien
        if ($fromUser->role === 'dokter' && $toUser->role === 'pasien') {
            NotificationController::createDoctorReplyNotification($fromUser, $toUser);
        }
    }
}