@include('Chatify::layouts.headLinks')
<meta name="user-role" content="{{ Auth::user()->role }}">
<div class="messenger">
    {{-- ----------------------Users/Groups lists side---------------------- --}}
    <div class="messenger-listView {{ !!$id ? 'conversation-active' : '' }}">
        {{-- Header and search bar --}}
        <div class="m-header">
            <nav class="chatify-header-nav">
                <a href="{{ Auth::user()->role === 'dokter' ? '/nakes/dashboard' : '/konsultasi' }}" class="chatify-header-link">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo Klinik" class="chatify-logo">
                    <div class="chatify-title-wrapper">
                        <span class="chatify-title-main">Klinik Pratama</span>
                        <span class="chatify-title-sub">Dokter Yanti</span>
                    </div>
                </a>
                {{-- header buttons --}}
                <div class="m-header-right">
                    <a href="{{ Auth::user()->role === 'dokter' ? '/nakes/dashboard' : '/konsultasi' }}" title="Kembali ke {{ Auth::user()->role === 'dokter' ? 'Dashboard Dokter' : 'Halaman Konsultasi' }}">
                        <i class="fas fa-home"></i>
                    </a>
                    <a href="#" class="listView-x"><i class="fas fa-times"></i></a>
                </div>
            </nav>
            {{-- Search input --}}
            <input type="text" class="messenger-search" placeholder="Cari pesan atau pengguna..." />
            {{-- Tabs --}}
            {{-- <div class="messenger-listView-tabs">
                <a href="#" class="active-tab" data-view="users">
                    <span class="far fa-user"></span> Kontak</a>
            </div> --}}
        </div>
        {{-- tabs and lists --}}
        <div class="m-body contacts-container">
           {{-- Lists [Users/Group] --}}
           {{-- ---------------- [ User Tab ] ---------------- --}}
           <div class="show messenger-tab users-tab app-scroll" data-view="users">
               {{-- Favorites --}}
               <div class="favorites-section">
                <p class="messenger-title"><span>Favorites</span></p>
                <div class="messenger-favorites app-scroll-hidden"></div>
               </div>
               {{-- Contact --}}
               <div class="listOfContacts" style="width: 100%;height: calc(100% - 200px);position: relative;"></div>
           </div>
             {{-- ---------------- [ Search Tab ] ---------------- --}}
           <div class="messenger-tab search-tab app-scroll" data-view="search">
                {{-- items --}}
                <p class="messenger-title"><span>Pencarian</span></p>
                <div class="search-records">
                    <p class="message-hint center-el"><span>Ketik untuk mencari...</span></p>
                </div>
             </div>
        </div>
    </div>

    {{-- ----------------------Messaging side---------------------- --}}
    <div class="messenger-messagingView">
        {{-- header title [conversation name] amd buttons --}}
        <div class="m-header m-header-messaging">
            <nav class="chatify-d-flex chatify-justify-content-between chatify-align-items-center">
                {{-- header back button, avatar and user name --}}
                <div class="header-left-section">
                    <a href="#" class="back-btn show-listView"><i class="fas fa-arrow-left"></i></a>
                    <div class="avatar av-s header-avatar show-infoSide" style="cursor: pointer;" title="Lihat detail pengguna">
                    </div>
                    <a href="#" class="user-name show-infoSide" title="Lihat detail pengguna">{{ config('chatify.name') }}</a>
                </div>
                {{-- header buttons - kosong karena sudah ada di sidebar kiri --}}
            </nav>
            {{-- Internet connection --}}
            <div class="internet-connection">
                <span class="ic-connected">Terhubung</span>
                <span class="ic-connecting">Menghubungkan...</span>
                <span class="ic-noInternet">Tidak ada koneksi internet</span>
            </div>
        </div>

        {{-- Messaging area --}}
        <div class="m-body messages-container app-scroll">
            <div class="messages">
                <p class="message-hint center-el"><span>Pilih percakapan untuk mulai mengirim pesan</span></p>
            </div>
            {{-- Typing indicator --}}
            <div class="typing-indicator">
                <div class="message-card typing">
                    <div class="message">
                        <span class="typing-dots">
                            <span class="dot dot-1"></span>
                            <span class="dot dot-2"></span>
                            <span class="dot dot-3"></span>
                        </span>
                    </div>
                </div>
            </div>

        </div>
        {{-- Send Message Form --}}
        @include('Chatify::layouts.sendForm')
    </div>
    {{-- ---------------------- Info side ---------------------- --}}
    <div class="messenger-infoView app-scroll">
        {{-- nav actions --}}
        <nav>
            <p>Detail Pengguna</p>
            <a href="#"><i class="fas fa-times"></i></a>
        </nav>
        {!! view('Chatify::layouts.info')->render() !!}
    </div>
</div>

@include('Chatify::layouts.modals')
@include('Chatify::layouts.footerLinks')
