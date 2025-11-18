<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Klinik Pratama Dokter Yanti</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{asset('css/dashboardUser.css')}}">
    <link rel="stylesheet" href="{{asset('css/about.css')}}">
    <link rel="stylesheet" href="{{asset('css/contact.css')}}">
    <link rel="stylesheet" href="{{asset('css/obat_all.css')}}">
    <link rel="stylesheet" href="{{asset('css/profil.css')}}">
    <link rel="stylesheet" href="{{asset('css/telemedicine.css')}}">
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
            @include('partials.footer2')
        </div>
    </div>
    
    <script src="{{ asset('js/nav.js') }}"></script>
</body>
</html>