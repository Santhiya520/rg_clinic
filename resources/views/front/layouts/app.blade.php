<!DOCTYPE html>
<html lang="zxx">
<head>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('description', '')">
    <meta name="keywords" content="@yield('keywords', '')">
    <meta name="author" content="Awaiken">

    <!-- Page Title -->
    <link rel="icon" href="{{ asset('images/favicon.jpeg') }}" type="image/jpeg">
    <title>RG Maruthuvamaiyam</title>

    <!-- Google Fonts Css-->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo:ital,wght@0,100..900;1,100..900&amp;family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&amp;display=swap" rel="stylesheet">

    <!-- Bootstrap Css -->
    <link href="{{ asset('front/css/bootstrap.min.css') }}" rel="stylesheet" media="screen">

    <!-- SlickNav Css -->
    <link href="{{ asset('front/css/slicknav.min.css') }}" rel="stylesheet">

    <!-- Swiper Css -->
    <link rel="stylesheet" href="{{ asset('front/css/swiper-bundle.min.css') }}">

    <!-- Font Awesome Icon Css-->
    <link href="{{ asset('front/css/all.css') }}" rel="stylesheet" media="screen">

    <!-- Animated Css -->
    <link href="{{ asset('front/css/animate.css') }}" rel="stylesheet">

    <!-- Magnific Popup Core Css File -->
    <link rel="stylesheet" href="{{ asset('front/css/magnific-popup.css') }}">

    <!-- Mouse Cursor Css File -->
    <link rel="stylesheet" href="{{ asset('front/css/mousecursor.css') }}">

    <!-- Main Custom Css -->
    <link href="{{ asset('front/css/custom.css') }}" rel="stylesheet" media="screen">

    <!-- Additional Styles -->
    @stack('styles')
</head>
<body>

    @include('front.partials.preloader')

    @include('front.partials.topbar')

    @include('front.partials.header')

    <!-- Main Content -->
    @yield('content')

    @include('front.partials.footer')

    <!-- Jquery Library File -->
    <script src="{{ asset('front/js/jquery-3.7.1.min.js') }}"></script>

    <!-- Bootstrap js file -->
    <script src="{{ asset('front/js/bootstrap.min.js') }}"></script>

    <!-- Validator js file -->
    <script src="{{ asset('front/js/validator.min.js') }}"></script>

    <!-- SlickNav js file -->
    <script src="{{ asset('front/js/jquery.slicknav.js') }}"></script>

    <!-- Swiper js file -->
    <script src="{{ asset('front/js/swiper-bundle.min.js') }}"></script>

    <!-- Counter js file -->
    <script src="{{ asset('front/js/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('front/js/jquery.counterup.min.js') }}"></script>

    <!-- Magnific js file -->
    <script src="{{ asset('front/js/jquery.magnific-popup.min.js') }}"></script>

    <!-- SmoothScroll -->
    <script src="{{ asset('front/js/SmoothScroll.js') }}"></script>

    <!-- Parallax js -->
    <script src="{{ asset('front/js/parallaxie.js') }}"></script>

    <!-- MagicCursor js file -->
    <script src="{{ asset('front/js/gsap.min.js') }}"></script>
    <script src="{{ asset('front/js/magiccursor.js') }}"></script>

    <!-- Text Effect js file -->
    <script src="{{ asset('front/js/SplitText.js') }}"></script>
    <script src="{{ asset('front/js/ScrollTrigger.min.js') }}"></script>

    <!-- YTPlayer js File -->
    <script src="{{ asset('front/js/jquery.mb.YTPlayer.min.js') }}"></script>

    <!-- Wow js file -->
    <script src="{{ asset('front/js/wow.js') }}"></script>

    <!-- Main Custom js file -->
    <script src="{{ asset('front/js/function.js') }}"></script>

    <!-- Additional Scripts -->
    @stack('scripts')

    <script>
        // CSRF Token for AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        // Initialize theme functions
        $(document).ready(function() {
            // Your theme initialization code here
        });
    </script>
</body>
</html>
