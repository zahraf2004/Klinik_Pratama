<div class="navbar-bg"></div>
      <nav class="navbar navbar-expand-lg main-navbar">
        <form class="form-inline mr-auto">
          <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
            <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li>
          </ul>          
        </form>
        <ul class="navbar-nav navbar-right">
          <li class="dropdown dropdown-list-toggle">
            <a href="#" data-toggle="dropdown" class="nav-link notification-toggle nav-link-lg {{ isset($unreadCount) && $unreadCount > 0 ? 'beep' : '' }}">
              <i class="far fa-bell"></i>
              @if(isset($unreadCount) && $unreadCount > 0)
                <span class="badge badge-danger navbar-badge">{{ $unreadCount }}</span>
              @endif
            </a>
            <div class="dropdown-menu dropdown-list dropdown-menu-right">
              <div class="dropdown-header">Notifikasi
                <div class="float-right">
                  <a href="#" onclick="markAllAsRead()">Tandai semua terbaca</a>
                </div>
              </div>
              <div class="dropdown-list-content dropdown-list-icons" id="notification-list">
                @if(isset($notifications) && $notifications->count() > 0)
                  @foreach($notifications as $notification)
                  <a href="{{ $notification->action_url ?? '#' }}" 
                     class="dropdown-item {{ !$notification->is_read ? 'dropdown-item-unread' : '' }}"
                     onclick="markAsRead({{ $notification->id }})">
                    <div class="dropdown-item-icon {{ $notification->color }} text-white">
                      <i class="{{ $notification->icon }}"></i>
                    </div>
                    <div class="dropdown-item-desc">
                      {!! $notification->message !!}
                      <div class="time {{ !$notification->is_read ? 'text-primary' : '' }}">
                        {{ $notification->created_at->diffForHumans() }}
                      </div>
                    </div>
                  </a>
                  @endforeach
                @else
                  <div class="dropdown-item text-center text-muted">
                    Tidak ada notifikasi
                  </div>
                @endif
              </div>
            </div>
          </li>
          <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user" role="button" aria-haspopup="true" aria-expanded="false">
              <img alt="image" src="{{ Auth::user()->avatar ?? asset('assets/img/avatar/avatar-1.png') }}" class="rounded-circle mr-1" style="width: 30px; height: 30px; object-fit: cover;">
              <div class="d-sm-none d-lg-inline-block">Hi, {{ Auth::user()->name }}</div>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
              
              
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  @csrf
              </form>

              <a href="#" class="dropdown-item has-icon text-danger"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                  <i class="fas fa-sign-out-alt"></i> Keluar
              </a>
            </div>
          </li>
        </ul>
      </nav>

<script>
function markAsRead(notificationId) {
    fetch(`/notifications/mark-read/${notificationId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update UI - remove unread class
            const notificationElement = event.target.closest('.dropdown-item');
            if (notificationElement) {
                notificationElement.classList.remove('dropdown-item-unread');
                const timeElement = notificationElement.querySelector('.time');
                if (timeElement) {
                    timeElement.classList.remove('text-primary');
                }
            }
            updateNotificationBadge();
        }
    })
    .catch(error => console.error('Error:', error));
}

function markAllAsRead() {
    fetch('/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update UI - remove all unread classes
            document.querySelectorAll('.dropdown-item-unread').forEach(item => {
                item.classList.remove('dropdown-item-unread');
            });
            document.querySelectorAll('.time.text-primary').forEach(time => {
                time.classList.remove('text-primary');
            });
            updateNotificationBadge();
        }
    })
    .catch(error => console.error('Error:', error));
}

function updateNotificationBadge() {
    fetch('/notifications/unread-count')
    .then(response => response.json())
    .then(data => {
        const badge = document.querySelector('.navbar-badge');
        const bellIcon = document.querySelector('.notification-toggle');
        
        if (data.count > 0) {
            if (badge) {
                badge.textContent = data.count;
            } else {
                // Create badge if doesn't exist
                const newBadge = document.createElement('span');
                newBadge.className = 'badge badge-danger navbar-badge';
                newBadge.textContent = data.count;
                bellIcon.appendChild(newBadge);
            }
            bellIcon.classList.add('beep');
        } else {
            if (badge) {
                badge.remove();
            }
            bellIcon.classList.remove('beep');
        }
    })
    .catch(error => console.error('Error:', error));
}

// Auto refresh notification count every 30 seconds
setInterval(updateNotificationBadge, 30000);
</script>