<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Chatify\Facades\ChatifyMessenger as Chatify;
use Illuminate\Support\Facades\DB;

class CustomChatifyController extends Controller
{
    /**
     * Get contacts untuk pasien dan dokter dengan logika berbeda
     * - Pasien: tampilkan semua dokter, yang terakhir chat di atas
     * - Dokter: hanya tampilkan pasien yang sudah pernah chat
     */
    public function getContacts(Request $request)
    {
        $user = Auth::user();
        $contacts = [];
        
        if ($user->role === 'pasien') {
            // Untuk pasien: tampilkan semua dokter
            $contacts = $this->getContactsForPatient($user);
        } else {
            // Untuk dokter/nakes: hanya tampilkan pasien yang sudah chat
            $contacts = $this->getContactsForDoctor($user);
        }
        
        // Render contact items
        $contactsHtml = '';
        foreach ($contacts as $contact) {
            $contactsHtml .= $this->renderContactItem($contact, $user);
        }
        
        return response()->json([
            'contacts' => $contactsHtml,
            'total' => count($contacts),
            'last_page' => 1
        ]);
    }
    
    /**
     * Render single contact item
     */
    private function renderContactItem($contact, $currentUser)
    {
        // Ambil pesan terakhir
        $lastMessage = DB::table('ch_messages')
            ->where(function($query) use ($currentUser, $contact) {
                $query->where(function($q) use ($currentUser, $contact) {
                    $q->where('from_id', $currentUser->id)
                      ->where('to_id', $contact->id);
                })->orWhere(function($q) use ($currentUser, $contact) {
                    $q->where('from_id', $contact->id)
                      ->where('to_id', $currentUser->id);
                });
            })
            ->orderBy('created_at', 'desc')
            ->first();
        
        // Hitung unread messages
        $unseenCounter = DB::table('ch_messages')
            ->where('from_id', $contact->id)
            ->where('to_id', $currentUser->id)
            ->where('seen', 0)
            ->count();
        
        // Jika tidak ada pesan, tampilkan item tanpa pesan terakhir
        if (!$lastMessage) {
            return view('Chatify::layouts.listItem', [
                'get' => 'search_item',
                'user' => $contact
            ])->render();
        }
        
        // Format waktu pesan terakhir
        $lastMessage->timeAgo = $this->timeAgo($lastMessage->created_at);
        
        return view('Chatify::layouts.listItem', [
            'get' => 'users',
            'user' => $contact,
            'lastMessage' => $lastMessage,
            'unseenCounter' => $unseenCounter
        ])->render();
    }
    
    /**
     * Format time ago
     */
    private function timeAgo($datetime)
    {
        $timestamp = strtotime($datetime);
        $difference = time() - $timestamp;
        
        if ($difference < 60) {
            return 'Baru saja';
        } elseif ($difference < 3600) {
            $minutes = floor($difference / 60);
            return $minutes . ' menit lalu';
        } elseif ($difference < 86400) {
            $hours = floor($difference / 3600);
            return $hours . ' jam lalu';
        } elseif ($difference < 604800) {
            $days = floor($difference / 86400);
            return $days . ' hari lalu';
        } else {
            return date('d/m/Y', $timestamp);
        }
    }
    
    /**
     * Get contacts untuk pasien
     * Tampilkan semua dokter, yang terakhir chat di atas
     */
    private function getContactsForPatient($user)
    {
        // Ambil semua dokter (hanya role dokter)
        $allDoctors = User::where('role', 'dokter')
            ->where('id', '!=', $user->id)
            ->get();
        
        // Ambil ID semua dokter
        $doctorIds = $allDoctors->pluck('id')->toArray();
        
        // Ambil dokter yang sudah pernah chat dengan pasien ini
        $doctorsWithMessages = DB::table('ch_messages')
            ->where(function($query) use ($user, $doctorIds) {
                $query->where(function($q) use ($user, $doctorIds) {
                    // Pesan dari pasien ke dokter
                    $q->where('from_id', $user->id)
                      ->whereIn('to_id', $doctorIds);
                })->orWhere(function($q) use ($user, $doctorIds) {
                    // Pesan dari dokter ke pasien
                    $q->whereIn('from_id', $doctorIds)
                      ->where('to_id', $user->id);
                });
            })
            ->select(
                DB::raw('CASE 
                    WHEN from_id = ' . $user->id . ' THEN to_id 
                    ELSE from_id 
                END as contact_id'),
                DB::raw('MAX(created_at) as last_message_time')
            )
            ->groupBy('contact_id')
            ->orderBy('last_message_time', 'desc')
            ->pluck('last_message_time', 'contact_id');
        
        // Pisahkan dokter yang sudah chat dan belum
        $doctorsWithChat = [];
        $doctorsWithoutChat = [];
        
        foreach ($allDoctors as $doctor) {
            if (isset($doctorsWithMessages[$doctor->id])) {
                $doctor->last_message_time = $doctorsWithMessages[$doctor->id];
                $doctorsWithChat[] = $doctor;
            } else {
                $doctorsWithoutChat[] = $doctor;
            }
        }
        
        // Sort dokter yang sudah chat berdasarkan waktu pesan terakhir
        usort($doctorsWithChat, function($a, $b) {
            return strtotime($b->last_message_time) - strtotime($a->last_message_time);
        });
        
        // Sort dokter yang belum chat berdasarkan nama
        usort($doctorsWithoutChat, function($a, $b) {
            return strcmp($a->name, $b->name);
        });
        
        // Gabungkan: dokter yang sudah chat di atas, sisanya di bawah
        return array_merge($doctorsWithChat, $doctorsWithoutChat);
    }
    
    /**
     * Get contacts untuk dokter
     * Hanya tampilkan pasien yang sudah pernah mengirim pesan
     */
    private function getContactsForDoctor($user)
    {
        // Ambil pasien yang sudah pernah chat dengan dokter ini
        $patientIds = DB::table('ch_messages')
            ->where(function($query) use ($user) {
                $query->where('from_id', $user->id)
                      ->orWhere('to_id', $user->id);
            })
            ->select(
                DB::raw('CASE 
                    WHEN from_id = ' . $user->id . ' THEN to_id 
                    ELSE from_id 
                END as contact_id'),
                DB::raw('MAX(created_at) as last_message_time')
            )
            ->groupBy('contact_id')
            ->orderBy('last_message_time', 'desc')
            ->pluck('last_message_time', 'contact_id');
        
        // Ambil data user pasien
        $patients = User::whereIn('id', array_keys($patientIds->toArray()))
            ->where('role', 'pasien')
            ->get();
        
        // Tambahkan last_message_time ke setiap pasien
        foreach ($patients as $patient) {
            $patient->last_message_time = $patientIds[$patient->id];
        }
        
        // Sort berdasarkan waktu pesan terakhir
        $patients = $patients->sortByDesc(function($patient) {
            return strtotime($patient->last_message_time);
        })->values();
        
        return $patients;
    }
    
    /**
     * Update contact item - dipanggil saat ada pesan baru
     */
    public function updateContacts(Request $request)
    {
        $userId = $request->user_id;
        $user = Auth::user();
        
        // Ambil data contact
        $contact = User::find($userId);
        
        if (!$contact) {
            return response()->json(['error' => 'User not found'], 404);
        }
        
        // Ambil pesan terakhir
        $lastMessage = DB::table('ch_messages')
            ->where(function($query) use ($user, $userId) {
                $query->where(function($q) use ($user, $userId) {
                    $q->where('from_id', $user->id)
                      ->where('to_id', $userId);
                })->orWhere(function($q) use ($user, $userId) {
                    $q->where('from_id', $userId)
                      ->where('to_id', $user->id);
                });
            })
            ->orderBy('created_at', 'desc')
            ->first();
        
        // Hitung unread messages
        $unseenCount = DB::table('ch_messages')
            ->where('from_id', $userId)
            ->where('to_id', $user->id)
            ->where('seen', 0)
            ->count();
        
        // Format waktu pesan terakhir
        if ($lastMessage) {
            $lastMessage->timeAgo = $this->timeAgo($lastMessage->created_at);
        }
        
        return response()->json([
            'contactItem' => $this->renderContactItem($contact, $user)
        ]);
    }
}
