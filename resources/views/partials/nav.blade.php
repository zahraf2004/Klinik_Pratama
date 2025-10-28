<!-- Navbar -->
    <nav class="navbar">
        <div class="logo">
            <a href="/dashboard"><img src="{{ asset('img/Logo.png') }}" alt="logo" height="40px" ></a>
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
                    <a href="/pilih-Dokter"><i class="fas fa-stethoscope"></i> Konsultasi Dokter Online</a>
                    <a href="/Janji-Berobat"><i class="fa-regular fa-calendar"></i> Janji berobat</a>
                    <a href="/layanan-kami"><i class="fas fa-capsules"></i> Layanan lainnya</a>
                </div>
            </li>
            <li><a href="/tentang-kami">Tentang Kami</a></li>
            <li><a href="/kontak-kami">Kontak</a></li>            
        </ul>
        
        @guest
            <a href="{{ route('login') }}" class="btn ">Masuk</a>
        @endguest
        @auth
            <!-- Dropdown Profil -->
            <div class="user-dropdown">
                <div class="user-profile" id="profileDropdownBtn">
                    <i class="fa-solid fa-circle-user fa-2xl"></i>
                    <div class="profile-name">{{ Auth::user()->name }}</div>
                    <i class="fas fa-caret-down"></i>
                </div>
                
                <div class="dropdown-menu" id="profileDropdownMenu">
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                </div>
            </div>
        @endauth
        
        <div class="hamburger" id="hamburger">
            <i class="fas fa-bars"></i>
        </div>
    </nav>