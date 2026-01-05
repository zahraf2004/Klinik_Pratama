<?php

namespace App\Http\Controllers;

use Chatify\Http\Controllers\MessagesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\NotificationController;

class CustomMessagesController extends MessagesController
{
    /**
     * Override method send untuk menambahkan notifikasi
     */
    public function send(Request $request)
    {
        // Panggil method send dari parent class
        $response = parent::send($request);
        
        // Jika pengiriman berhasil, buat notifikasi
        if ($response->getStatusCode() == 200) {
            $fromUser = Auth::user();
            $toUserId = $request->id; // ID penerima
            $toUser = User::find($toUserId);
            
            if ($fromUser && $toUser) {
                // Jika pengirim adalah pasien dan penerima adalah dokter
                if ($fromUser->role === 'pasien' && $toUser->role === 'dokter') {
                    NotificationController::createNewMessageNotification($fromUser, $toUser, $request->message);
                }
                
                // Jika pengirim adalah dokter dan penerima adalah pasien
                if ($fromUser->role === 'dokter' && $toUser->role === 'pasien') {
                    NotificationController::createDoctorReplyNotification($fromUser, $toUser);
                }
            }
        }
        
        return $response;
    }
}