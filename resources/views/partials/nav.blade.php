<!-- Navbar -->
    <nav class="navbar">
        <div class="logo">
            <a href="/dashboard"><img src="/img/logo.png" alt="logo" height="40px" ></a>
            <div class="logo-text">
                <span class="top">Klinik Pratama</span>
                <span class="bottom">Dokter Yanti</span>
            </div>
        </div>
        <ul class="nav-links">
            <li><a href="/dashboard" class="">Beranda</a></li>
            <li class="dropdown">
                <a href="#">Layanan <i class="fas fa-caret-down"></i></a>
                <div class="dropdown-content">
                    <a href="/konsultasi"><i class="fas fa-stethoscope"></i> Konsultasi Dokter Online</a>
                    <a href="/Janji-Berobat"><i class="fa-regular fa-calendar"></i> Janji berobat</a>
                    <a href="/layanan-kami"><i class="fas fa-capsules"></i> Layanan lainnya</a>
                </div>
            </li>
            <li><a href="/tentang-kami">Tentang Kami</a></li>
            <li><a href="/kontak-kami">Kontak</a></li>            
        </ul>
        
        @guest
            <a href="{{ route('login') }}" class="btn">Masuk</a>
        @endguest
        @auth
            <!-- Notifikasi Bell Icon -->
            <div class="notification-bell">
                <div class="bell-icon" id="notificationBell">
                    <i class="far fa-bell"></i>
                    @if(isset($unreadCount) && $unreadCount > 0)
                        <span class="notification-badge">{{ $unreadCount }}</span>
                    @endif
                </div>
                
                <div class="notification-dropdown" id="notificationDropdown">
                    <div class="notification-header">
                        <span>Notifikasi</span>
                        <a href="#" onclick="markAllAsReadPasien()" class="mark-all-read">Tandai semua terbaca</a>
                    </div>
                    <div class="notification-list" id="notification-list-pasien">
                        @if(isset($notifications) && $notifications->count() > 0)
                            @foreach($notifications as $notification)
                            <a href="{{ $notification->action_url ?? '#' }}" 
                               class="notification-item {{ !$notification->is_read ? 'unread' : '' }}"
                               onclick="markAsReadPasien({{ $notification->id }})">
                                <div class="notification-icon {{ $notification->color }}">
                                    <i class="{{ $notification->icon }}"></i>
                                </div>
                                <div class="notification-content">
                                    <div class="notification-message">{!! $notification->message !!}</div>
                                    <div class="notification-time {{ !$notification->is_read ? 'text-primary' : '' }}">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </a>
                            @endforeach
                        @else
                            <div class="notification-item text-center">
                                <div class="notification-content">
                                    <div class="notification-message text-muted">Tidak ada notifikasi</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
                <!-- Dropdown Profil -->
                <div class="user-dropdown">
                    <div class="user-profile" id="profileDropdownBtn">
                        @if(Auth::user()->hasCustomAvatar())
                            <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #4a83d3;">
                        @else
                            <i class="fa-solid fa-circle-user fa-2xl"></i>
                        @endif
                        <div class="profile-name">{{ Auth::user()->name }}</div>
                        <i class="fas fa-caret-down"></i>
                    </div>
                    
                    <div class="dropdown-menu" id="profileDropdownMenu">
                        @if(Auth::user()->role == 'pasien')
                        <a href="/profil" class="dropdown-item">
                            <i class="fa-regular fa-user"></i> Profil
                        </a>
                        @endif  
                        <a href="#" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
        @endauth
        
        <div class="hamburger" id="hamburger">
            <i class="fas fa-bars"></i>
        </div>
    </nav>
<style>
/* Notification Bell Styles */
.notification-bell {
    position: relative;
    margin-right: -185px;
}

.bell-icon {
    cursor: pointer;
    font-size: 20px;
    color: #4a83d3;
    position: relative;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.bell-icon:hover {
    background-color: rgba(74, 131, 211, 0.1);
}

.notification-badge {
    position: absolute;
    top: -8px;
    right: -8px;
    background-color: #dc3545;
    color: white;
    border-radius: 50%;
    width: 18px;
    height: 18px;
    font-size: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    min-width: 18px;
}

.notification-dropdown {
    position: absolute;
    top: 100%;
    right: 0;
    width: 350px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    z-index: 1000;
    display: none;
    max-height: 400px;
    overflow-y: auto;
}

.notification-dropdown.show {
    display: block;
}

.notification-header {
    padding: 15px 20px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: bold;
    color: #333;
}

.mark-all-read {
    color: #4a83d3;
    text-decoration: none;
    font-size: 12px;
}

.mark-all-read:hover {
    text-decoration: underline;
}

.notification-list {
    max-height: 300px;
    overflow-y: auto;
}

.notification-item {
    display: flex;
    padding: 15px 20px;
    border-bottom: 1px solid #f0f0f0;
    text-decoration: none;
    color: #333;
    transition: background-color 0.2s ease;
}

.notification-item:hover {
    background-color: #f8f9fa;
    text-decoration: none;
    color: #333;
}

.notification-item.unread {
    background-color: #f0f8ff;
    border-left: 3px solid #4a83d3;
}

.notification-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    color: white;
    font-size: 16px;
}

.notification-icon.bg-primary { background-color: #007bff; }
.notification-icon.bg-success { background-color: #28a745; }
.notification-icon.bg-danger { background-color: #dc3545; }
.notification-icon.bg-warning { background-color: #ffc107; }
.notification-icon.bg-info { background-color: #17a2b8; }

.notification-content {
    flex: 1;
}

.notification-message {
    font-size: 14px;
    line-height: 1.4;
    margin-bottom: 5px;
}

.notification-time {
    font-size: 12px;
    color: #666;
}

.notification-time.text-primary {
    color: #4a83d3 !important;
    font-weight: bold;
}

/* Responsive */
@media (max-width: 768px) {
    .notification-dropdown {
        width: 300px;
        right: -50px;
    }
}
</style>

<script>
// Toggle notification dropdown
document.getElementById('notificationBell').addEventListener('click', function(e) {
    e.stopPropagation();
    const dropdown = document.getElementById('notificationDropdown');
    dropdown.classList.toggle('show');
});

// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
    const dropdown = document.getElementById('notificationDropdown');
    const bell = document.getElementById('notificationBell');
    
    if (!bell.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.classList.remove('show');
    }
});

function markAsReadPasien(notificationId) {
    fetch(`/user-notifications/mark-read/${notificationId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const notificationElement = event.target.closest('.notification-item');
            if (notificationElement) {
                notificationElement.classList.remove('unread');
                const timeElement = notificationElement.querySelector('.notification-time');
                if (timeElement) {
                    timeElement.classList.remove('text-primary');
                }
            }
            updateNotificationBadgePasien();
        }
    })
    .catch(error => console.error('Error:', error));
}

function markAllAsReadPasien() {
    fetch('/user-notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.querySelectorAll('.notification-item.unread').forEach(item => {
                item.classList.remove('unread');
            });
            document.querySelectorAll('.notification-time.text-primary').forEach(time => {
                time.classList.remove('text-primary');
            });
            updateNotificationBadgePasien();
        }
    })
    .catch(error => console.error('Error:', error));
}

function updateNotificationBadgePasien() {
    fetch('/user-notifications/unread-count')
    .then(response => response.json())
    .then(data => {
        const badge = document.querySelector('.notification-badge');
        const bellIcon = document.querySelector('.bell-icon');
        
        if (data.count > 0) {
            if (badge) {
                badge.textContent = data.count;
            } else {
                const newBadge = document.createElement('span');
                newBadge.className = 'notification-badge';
                newBadge.textContent = data.count;
                bellIcon.appendChild(newBadge);
            }
        } else {
            if (badge) {
                badge.remove();
            }
        }
    })
    .catch(error => console.error('Error:', error));
}

// Auto refresh notification count every 30 seconds
setInterval(updateNotificationBadgePasien, 30000);
</script>