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