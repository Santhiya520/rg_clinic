<!DOCTYPE html>
<html lang="zxx" class="js">
<head>
    <meta charset="utf-8">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="A powerful and conceptual apps base dashboard template that especially build for developers and programmers.">
    <link rel="icon" href="{{ asset('images/favicon.jpeg') }}" type="image/jpeg">
    <title>@yield('title', 'Admin Dashboard') | RG Maruthuvamaiyam</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/dashlite9b70.css?ver=3.3.0') }}">
    <link id="skin-default" rel="stylesheet" href="{{ asset('css/theme9b70.css?ver=3.3.0') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<!-- In head section -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-91615293-4"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());
        gtag('config', 'UA-91615293-4');
    </script>
</head>

<body class="nk-body ui-rounder has-sidebar ui-light">
    <div class="nk-app-root">
        <div class="nk-main">
            <!-- Admin Sidebar -->
            @include('online.layouts.partials.sidebar')

            <div class="nk-wrap">
                <!-- Admin Header -->
                @include('online.layouts.partials.header')
                <!-- Main Content -->
                <div class="nk-content nk-content-fluid">
                    <div class="container-xl wide-xl">
                        <div class="nk-content-body">
                            @yield('content')
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                @include('online.layouts.partials.footer')
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/bundle9b70.js?ver=3.3.0') }}"></script>
    <script src="{{ asset('js/scripts9b70.js?ver=3.3.0') }}"></script>
    <script src="{{ asset('js/demo-settings9b70.js?ver=3.3.0') }}"></script>

<!-- Before closing body tag -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Page Specific Scripts -->
    @stack('scripts')
</body>
</html>
