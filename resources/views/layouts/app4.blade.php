<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Klinik Pratama Dokter Yanti</title>

  <!-- Font & Icon -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

  <!-- CSS -->
  <link rel="stylesheet" href="{{ asset('css/home.css') }}">
  <link rel="stylesheet" href="{{ asset('css/dashboardUser.css') }}">
  <link rel="stylesheet" href="{{ asset('css/obat_all.css') }}">
  <link rel="stylesheet" href="{{ asset('css/layanan.css') }}">
  <link rel="stylesheet" href="{{ asset('css/appointment.css') }}">
  <link rel="stylesheet" href="{{ asset('css/custom-table.css') }}">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">

  <!-- Tailwind (optional for extra styling) -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
  <div class="app">
    <div class="main-wrapper">
      {{-- Navbar --}}
      @include('partials.nav')

      {{-- Main Content --}}
      <div class="main-content">
        @yield('content')
      </div>

      {{-- Footer --}}
      @include('partials.footer2')
    </div>
  </div>

  <!-- ✅ SCRIPT SECTION -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Plugin tambahan -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Script dari Stisla (kalau dipakai) -->
  <script src="{{ asset('assets/js/stisla.js') }}"></script>
  <script src="{{ asset('assets/js/scripts.js') }}"></script>
  <script src="{{ asset('assets/js/custom.js') }}"></script>

  <!-- JS tambahan proyek kamu -->
  <script src="{{ asset('js/nav.js') }}"></script>
  <script src="{{ asset('js/table.js') }}"></script>
</body>
</html>
