<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\ChatSession;
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
    
    /**
     * Get user details untuk profile modal (dokter atau pasien)
     */
    public function getUserDetails(Request $request)
    {
        $userId = $request->user_id;
        $user = User::with(['tenagaKesehatan', 'profil_pasien'])->find($userId);
        
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        
        $userData = [
            'name' => $user->name,
            'avatar' => $user->avatar,
            'role' => $user->role,
            'email' => $user->email,
            'user_type' => $user->role === 'pasien' ? 'patient' : 'doctor'
        ];
        
        // Jika user adalah dokter, ambil data dari tenaga_kesehatan
        if ($user->role === 'dokter' && $user->tenagaKesehatan) {
            $nakes = $user->tenagaKesehatan;
            $userData = array_merge($userData, [
                'str' => $nakes->str,
                'sip' => $nakes->sip,
                'tahun_mulai' => $nakes->tahun_mulai,
                'pengalaman' => $nakes->pengalaman,
                'jadwal_shift' => $nakes->jadwal_shift,
                'foto_url' => $nakes->foto_url,
                'spesialisasi' => $nakes->role === 'dokter_umum' ? 'Dokter Umum' : ucfirst($nakes->role),
            ]);
        }
        
        // Jika user adalah pasien, ambil data dari profil_pasien
        if ($user->role === 'pasien' && $user->profil_pasien) {
            $profil = $user->profil_pasien;
            $userData = array_merge($userData, [
                'tanggal_lahir' => $profil->tanggal_lahir,
                'jenis_kelamin' => $profil->jenis_kelamin,
                'alamat' => $profil->alamat,
                'no_hp' => $profil->no_hp,
                'pekerjaan' => $profil->pekerjaan,
                'status_pernikahan' => $profil->status_pernikahan,
                'golongan_darah' => $profil->golongan_darah,
                'tinggi_badan' => $profil->tinggi_badan,
                'berat_badan' => $profil->berat_badan,
                'riwayat_penyakit' => $profil->riwayat_penyakit,
                'alergi' => $profil->alergi,
                'foto' => $profil->foto,
                'umur' => $profil->tanggal_lahir ? \Carbon\Carbon::parse($profil->tanggal_lahir)->age : null,
            ]);
        }
        
        return response()->json([
            'user' => $userData
        ]);
    }
    
    /**
     * Get atau create chat session
     */
    public function getOrCreateSession(Request $request)
    {
        $currentUser = Auth::user();
        $targetUserId = $request->target_user_id;
        
        // Tentukan siapa pasien dan siapa dokter
        $patientId = $currentUser->role === 'pasien' ? $currentUser->id : $targetUserId;
        $doctorId = $currentUser->role === 'dokter' ? $currentUser->id : $targetUserId;
        
        // Cek apakah pasien punya subscription aktif
        $patient = User::find($patientId);
        $hasActiveSubscription = $patient->hasActiveSubscription();
        $activeSubscription = $patient->activeSubscription();
        
        // Cari session yang aktif
        $session = ChatSession::where('patient_id', $patientId)
            ->where('doctor_id', $doctorId)
            ->where('is_active', true)
            ->first();
        
        // Jika tidak ada session aktif, cek apakah bisa buat baru
        if (!$session) {
            // Cek apakah user bisa mulai session baru
            if (!$patient->canStartNewSession()) {
                return response()->json([
                    'error' => 'Session token habis',
                    'message' => 'Anda sudah menggunakan 3 session gratis. Upgrade ke premium untuk unlimited session.',
                    'remaining_tokens' => 0,
                    'can_chat' => false
                ]);
            }
            
            $session = ChatSession::create([
                'patient_id' => $patientId,
                'doctor_id' => $doctorId,
                'message_count' => 0,
                'is_premium' => $hasActiveSubscription,
                'is_active' => true,
                'started_at' => now(),
            ]);
        } else {
            // Update status premium jika subscription berubah
            if ($session->is_premium !== $hasActiveSubscription) {
                $session->update(['is_premium' => $hasActiveSubscription]);
            }
        }
        
        // Prepare subscription info
        $subscriptionInfo = null;
        if ($hasActiveSubscription && $activeSubscription) {
            $subscriptionInfo = [
                'plan_name' => $activeSubscription->plan_name,
                'expires_at' => $activeSubscription->expires_at->format('d/m/Y'),
                'days_remaining' => $activeSubscription->daysRemaining(),
                'is_expiring_soon' => $activeSubscription->daysRemaining() <= 7
            ];
        }
        
        return response()->json([
            'session' => [
                'id' => $session->id,
                'message_count' => $session->message_count,
                'is_premium' => $session->is_premium,
                'is_active' => $session->is_active,
                'can_chat' => true, // Jika sampai sini berarti bisa chat
            ],
            'subscription' => $subscriptionInfo,
            'user_premium_status' => [
                'has_active_subscription' => $hasActiveSubscription,
                'remaining_session_tokens' => $patient->getRemainingSessionTokens(),
                'can_start_new_session' => $patient->canStartNewSession()
            ]
        ]);
    }
    
    /**
     * Increment message count saat pasien kirim pesan
     */
    public function incrementMessageCount(Request $request)
    {
        $currentUser = Auth::user();
        $targetUserId = $request->target_user_id;
        
        // Hanya pasien yang di-track message count-nya
        if ($currentUser->role !== 'pasien') {
            return response()->json(['success' => true, 'message' => 'Doctor messages are not counted']);
        }
        
        $patientId = $currentUser->id;
        $doctorId = $targetUserId;
        
        $session = ChatSession::where('patient_id', $patientId)
            ->where('doctor_id', $doctorId)
            ->where('is_active', true)
            ->first();
        
        if ($session) {
            $session->incrementMessageCount();
            
            return response()->json([
                'success' => true,
                'session' => [
                    'message_count' => $session->message_count,
                    'has_reached_limit' => $session->hasReachedLimit(),
                    'remaining_messages' => max(0, 3 - $session->message_count),
                ]
            ]);
        }
        
        return response()->json(['success' => false, 'message' => 'Session not found'], 404);
    }
    
    /**
     * End session (hanya dokter yang bisa)
     */
    public function endSession(Request $request)
    {
        try {
            \Log::info('EndSession called', ['request' => $request->all()]);
            
            $currentUser = Auth::user();
            \Log::info('Current user', ['user_id' => $currentUser->id, 'role' => $currentUser->role]);
            
            // Hanya dokter yang bisa end session
            if ($currentUser->role !== 'dokter') {
                \Log::warning('Non-doctor tried to end session', ['user_id' => $currentUser->id, 'role' => $currentUser->role]);
                return response()->json(['success' => false, 'message' => 'Only doctors can end sessions'], 403);
            }
        
            $patientId = $request->patient_id;
            $doctorId = $currentUser->id;
            
            \Log::info('Looking for session', ['patient_id' => $patientId, 'doctor_id' => $doctorId]);
            
            $session = ChatSession::where('patient_id', $patientId)
                ->where('doctor_id', $doctorId)
                ->where('is_active', true)
                ->first();
            
            if ($session) {
                \Log::info('Session found, ending session', ['session_id' => $session->id]);
                $session->endSession();
                
                // Get updated token info for patient
                $patient = User::find($patientId);
                $remainingTokens = $patient->getRemainingSessionTokens();
                
                \Log::info('Session ended successfully', ['remaining_tokens' => $remainingTokens]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Session ended successfully',
                    'patient_remaining_tokens' => $remainingTokens,
                    'session_completed' => true
                ]);
            }
            
            // Jika tidak ada session aktif, cek apakah ada chat messages antara dokter dan pasien
            $hasMessages = \DB::table('ch_messages')
                ->where(function($query) use ($patientId, $doctorId) {
                    $query->where('from_id', $patientId)->where('to_id', $doctorId);
                })
                ->orWhere(function($query) use ($patientId, $doctorId) {
                    $query->where('from_id', $doctorId)->where('to_id', $patientId);
                })
                ->exists();
            
            if ($hasMessages) {
                // Ada chat tapi tidak ada session aktif, buat session baru lalu langsung end
                $patient = User::find($patientId);
                
                if (!$patient->canStartNewSession()) {
                    \Log::warning('Patient cannot start new session - tokens exhausted', ['patient_id' => $patientId]);
                    
                    $remainingTokens = $patient->getRemainingSessionTokens();
                    
                    return response()->json([
                        'success' => false, 
                        'message' => 'Patient has no remaining session tokens. All 3 free sessions have been used.',
                        'patient_remaining_tokens' => $remainingTokens,
                        'tokens_exhausted' => true
                    ]);
                }
                
                $hasActiveSubscription = $patient->hasActiveSubscription();
                
                $session = ChatSession::create([
                    'patient_id' => $patientId,
                    'doctor_id' => $doctorId,
                    'message_count' => 0,
                    'is_premium' => $hasActiveSubscription,
                    'is_active' => true,
                    'started_at' => now(),
                ]);
                
                \Log::info('Created new session and ending it', ['session_id' => $session->id]);
                $session->endSession();
                
                $remainingTokens = $patient->getRemainingSessionTokens();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Session created and ended successfully',
                    'patient_remaining_tokens' => $remainingTokens,
                    'session_completed' => true
                ]);
            }
            
            \Log::warning('No active session and no messages found', ['patient_id' => $patientId, 'doctor_id' => $doctorId]);
            return response()->json(['success' => false, 'message' => 'No active conversation found'], 404);
            
        } catch (\Exception $e) {
            \Log::error('EndSession error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['success' => false, 'message' => 'Server error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Check if user can send chat messages
     */
    public function checkChatPermission(Request $request)
    {
        $currentUser = Auth::user();

        // If a doctor is checking permission for a patient (via AJAX from doctor UI),
        // use the target_user_id to return the patient's token/subscription status.
        $targetUserId = $request->input('target_user_id');

        if ($currentUser->role === 'dokter' && $targetUserId) {
            $patient = User::find($targetUserId);
            if (!$patient) {
                return response()->json(['can_chat' => false, 'remaining_tokens' => 0, 'has_active_subscription' => false]);
            }

            $hasActiveSubscription = $patient->hasActiveSubscription();
            $remainingTokens = $patient->getRemainingSessionTokens();

            // If premium patient
            if ($hasActiveSubscription) {
                return response()->json([
                    'can_chat' => true,
                    'remaining_tokens' => -1,
                    'has_active_subscription' => true,
                    'message' => 'Patient is premium - unlimited chat'
                ]);
            }

            $canChat = $remainingTokens > 0;

            return response()->json([
                'can_chat' => $canChat,
                'remaining_tokens' => $remainingTokens,
                'has_active_subscription' => false,
                'message' => $canChat ? 'Patient can chat' : 'Patient has no remaining tokens',
                'tokens_exhausted' => $remainingTokens <= 0
            ]);
        }

        // Default: treat the authenticated user (typically patient) as the target
        // For patients, check their own tokens/subscription
        $hasActiveSubscription = $currentUser->hasActiveSubscription();
        $remainingTokens = $currentUser->getRemainingSessionTokens();

        if ($hasActiveSubscription) {
            return response()->json([
                'can_chat' => true,
                'remaining_tokens' => -1,
                'has_active_subscription' => true,
                'message' => 'Premium user - unlimited chat'
            ]);
        }

        $canChat = $remainingTokens > 0;

        return response()->json([
            'can_chat' => $canChat,
            'remaining_tokens' => $remainingTokens,
            'has_active_subscription' => false,
            'message' => $canChat ? 'Can send messages' : 'No remaining tokens, upgrade required',
            'tokens_exhausted' => $remainingTokens <= 0
        ]);
    }
}
