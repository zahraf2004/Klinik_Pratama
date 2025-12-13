<div class="main-sidebar">
  <aside id="sidebar-wrapper">
    <div class="sidebar-brand">
      <a href="/dashboard-admin"><img src="{{ asset('img/logo1_copy.png') }}" alt="logo" height="60px" style="margin-bottom=:5px;" ></a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
      <a href="/dashboard-admin"><img src="{{ asset('img/logo.png') }}" alt="logo" height="30px"  ></a>
    </div>
    <ul class="sidebar-menu">
      @if(Auth::user()->role === 'admin')
        <li class="menu-header">Dashboard</li>
        <li><a class="nav-link" href="/dashboard-admin"><i class="fa-solid fa-house"></i> <span>Dashboard</span></a></li>
        <li class="menu-header">Janji Berobat</li>
        <li><a class="nav-link" href="/data-janji-berobat"><i class="fa-regular fa-calendar-days"></i> <span>Data Janji Berobat</span></a></li>
        <li class="menu-header">Data Klinik</li>
        <li><a class="nav-link" href="/admin/data-nakes"><i class="fa-duotone fa-solid fa-users"></i> <span>Tenaga Kesehatan</span></a></li>
        <li><a class="nav-link" href="/data-obat"><i class="fa-solid fa-pills"></i> <span>Data Obat</span></a></li>      
        <li class="menu-header">Data Pengguna</li>
        <li><a class="nav-link" href="/data-pasien"><i class="fa-solid fa-bed-pulse"></i> <span>Data Pasien</span></a></li>
      @elseif(in_array(Auth::user()->role, ['dokter', 'bidan', 'perawat']))
        <li class="menu-header">Dashboard</li>
        <li><a class="nav-link" href="{{ route('dokter.Dashboard') }}"><i class="fa-solid fa-house"></i> <span>Dashboard</span></a></li>
        <li class="menu-header">Janji Temu</li>
        <li><a class="nav-link" href="/nakes/janji-temu"><i class="fa-regular fa-calendar-days"></i> <span>Janji Temu</span></a></li>
        <li class="menu-header">Konsultasi</li>
        <li><a class="nav-link" href="/chatify"><i class="fa-solid fa-comments"></i> <span>Chat Pasien</span></a></li>
      @else
        {{-- Menu untuk Pasien --}}
        <li class="menu-header">Dashboard</li>
        <li><a class="nav-link" href="{{ route('obat.index') }}"><i class="fa-solid fa-house"></i> <span>Dashboard</span></a></li>
        <li class="menu-header">Layanan</li>
        <li><a class="nav-link" href="{{ route('appointment.index') }}"><i class="fa-regular fa-calendar-days"></i> <span>Janji Berobat</span></a></li>
        <li><a class="nav-link" href="{{ route('konsultasi.index') }}"><i class="fa-solid fa-comments"></i> <span>Konsultasi</span></a></li>
        <li class="menu-header">Pembayaran</li>
        <li><a class="nav-link" href="{{ route('payment.index') }}"><i class="fa-solid fa-credit-card"></i> <span>Pembayaran</span></a></li>
        <li><a class="nav-link" href="{{ route('payment.history') }}"><i class="fa-solid fa-history"></i> <span>Riwayat Pembayaran</span></a></li>
        <li class="menu-header">Profil</li>
        <li><a class="nav-link" href="{{ route('pasien.profil') }}"><i class="fa-solid fa-user"></i> <span>Profil Saya</span></a></li>
      @endif 
  </aside>
</div> 