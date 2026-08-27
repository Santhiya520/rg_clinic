<style>
@media screen and (min-width: 750px){
    .mobile-login-btn{
        display: none;
    }
}
</style>
<!-- Header Start -->
<header class="main-header">
    <div class="header-sticky">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <!-- Logo Start -->
                <a class="navbar-brand navbar-logo-img" href="{{ route('index') }}">
                    <img src="{{ asset('front/images/logo.png') }}" alt="Logo" width="100%">
                </a>
                <!-- Logo End -->

                <!-- Main Menu Start -->
                <div class="collapse navbar-collapse main-menu">
                    <div class="nav-menu-wrapper">
                        <ul class="navbar-nav mr-auto" id="menu">
                            <li class="nav-item ">
                                <a class="nav-link {{ request()->routeIs('index') ? 'active' : '' }}" href="{{ route('index') }}">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">About Us</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('services') ? 'active' : '' }}" href="{{ route('services') }}">Services</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('donar') ? 'active' : '' }}" href="{{ route('donar') }}">Donars</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('gallery') ? 'active' : '' }}" href="{{ route('gallery') }}">Gallery</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="https://rotaryscanfoundation.rotaryudumalpetgalaxy.com" target="_blank">Scan Foundation</a>
                            </li>
                            <li class="nav-item mobile-login-btn">
                                <a href="{{ route('patient.login') }}" class="nav-link" target="_blank">Login</a>
                            </li>
                        </ul>
                    </div>
                    <!-- Let's Start Button Start -->
                    <div class="header-btn d-inline-flex">
                        <a href="{{ route('patient.login') }}" class="btn-default" target="_blank">Login</a>
                    </div>
                    <!-- Let's Start Button End -->
                </div>
                <!-- Main Menu End -->
                <div class="navbar-toggle"></div>
            </div>
        </nav>
        <div class="responsive-menu"></div>
    </div>
</header>
<!-- Header End -->
