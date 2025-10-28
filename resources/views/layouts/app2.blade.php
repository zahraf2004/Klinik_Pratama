<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klinik Pratama Dokter Yanti</title>
    <!-- General CSS Files -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- CSS Libraries -->

    <!-- Template CSS -->
    <link rel="stylesheet" href="assets2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets2/css/owl.carousel.min.css">
    <link rel="stylesheet" href="assets2/css/slicknav.css">
    <link rel="stylesheet" href="assets2/css/flaticon.css">
    <link rel="stylesheet" href="assets2/css/gijgo.css">
    <link rel="stylesheet" href="assets2/css/animate.min.css">
    <link rel="stylesheet" href="assets2/css/animated-headline.css">
    <link rel="stylesheet" href="assets2/css/magnific-popup.css">
    <link rel="stylesheet" href="assets2/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets2/css/themify-icons.css">
    <link rel="stylesheet" href="assets2/css/slick.css">
    <link rel="stylesheet" href="assets2/css/nice-select.css">
    <link rel="stylesheet" href="assets2/css/style.css">
    
    <link rel="stylesheet" href="{{ asset('css/homepage.css') }}">
</head>
<body>
    <div class="app">
        <div class="main-wrapper">
            {{-- Navbar --}}
            @include('partials.navbar2')

            {{-- Main Content --}}
            <div class="main-content">
                @yield('content')
            </div>
        </div>
    </div>


    <script src="./assets22/js/vendor/modernizr-3.5.0.min.js"></script>
    <!-- Jquery, Popper, Bootstrap -->
    <script src="./assets2/js/vendor/jquery-1.12.4.min.js"></script>
    <script src="./assets2/js/popper.min.js"></script>
    <script src="./assets2/js/bootstrap.min.js"></script>
    <!-- Jquery Mobile Menu -->
    <script src="./assets2/js/jquery.slicknav.min.js"></script>

    <!-- Jquery Slick , Owl-Carousel Plugins -->
    <script src="./assets2/js/owl.carousel.min.js"></script>
    <script src="./assets2/js/slick.min.js"></script>
    <!-- One Page, Animated-HeadLin -->
    <script src="./assets2/js/wow.min.js"></script>
    <script src="./assets2/js/animated.headline.js"></script>
    <script src="./assets2/js/jquery.magnific-popup.js"></script>

    <!-- Date Picker -->
    <script src="./assets2/js/gijgo.min.js"></script>
    <!-- Nice-select, sticky -->
    <script src="./assets2/js/jquery.nice-select.min.js"></script>
    <script src="./assets2/js/jquery.sticky.js"></script>

    <!-- counter , waypoint,Hover Direction -->
    <script src="./assets2/js/jquery.counterup.min.js"></script>
    <script src="./assets2/js/waypoints.min.js"></script>
    <script src="./assets2/js/jquery.countdown.min.js"></script>
    <script src="./assets2/js/hover-direction-snake.min.js"></script>

    <!-- contact js -->
    <script src="./assets2/js/contact.js"></script>
    <script src="./assets2/js/jquery.form.js"></script>
    <script src="./assets2/js/jquery.validate.min.js"></script>
    <script src="./assets2/js/mail-script.js"></script>
    <script src="./assets2/js/jquery.ajaxchimp.min.js"></script>

    <!-- Jquery Plugins, main Jquery -->	
    <script src="./assets2/js/plugins.js"></script>
    <script src="./assets2/js/main.js"></script>
</body>
</html>