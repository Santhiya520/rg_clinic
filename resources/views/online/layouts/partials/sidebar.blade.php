<div class="nk-sidebar is-light nk-sidebar-fixed" data-content="sidebarMenu">
    <div class="nk-sidebar-element nk-sidebar-head">
        <div class="nk-sidebar-brand">
            <a href="{{ route('dashboard') }}" class="logo-link nk-sidebar-logo">
                <img class="logo-light logo-img" src="{{ asset('images/logo.png') }}" alt="logo">
                <img class="logo-dark logo-img" src="{{ asset('images/logo.png') }}" alt="logo-dark">
                <img class="logo-small logo-img logo-img-small" src="{{ asset('images/logo.png') }}" alt="logo-small">
            </a>
        </div>
        <div class="nk-menu-trigger me-n2">
            <a href="#" class="nk-nav-toggle nk-quick-nav-icon d-xl-none" data-target="sidebarMenu">
                <!-- Converted ni-arrow-left to bi-arrow-left -->
                <em class="icon bi bi-arrow-left"></em>
            </a>
        </div>
    </div>

    <div class="nk-sidebar-element">
        <div class="nk-sidebar-content">
            <div class="nk-sidebar-menu" data-simplebar>
                <ul class="nk-menu">
                    <!-- Dashboard -->
                    <li class="nk-menu-item">
                        <a href="{{ route('patient.dashboard') }}" class="nk-menu-link">
                            <!-- Converted ni-dashboard to bi-speedometer2 -->
                            <span class="nk-menu-icon"><em class="icon bi bi-speedometer2"></em></span>
                            <span class="nk-menu-text">Dashboard</span>
                        </a>
                    </li>

                    <li class="nk-menu-item">
                        <a href="{{ route('patient.appointments') }}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon bi bi-calendar-plus"></em></span>
                            <span class="nk-menu-text">Online Appointments</span>
                        </a>
                    </li>
                    <li class="nk-menu-item">
                        <a href="{{ route('online.patient.report') }}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon bi bi-person-lines-fill"></em></span>
                            <span class="nk-menu-text">Patient Reports</span>
                        </a>
                    </li>
                    <li class="nk-menu-item">
                        <a href="{{ route('online.radiology.reports') }}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon bi bi-file-earmark-text"></em></span>
                            <span class="nk-menu-text">Radiology Reports</span>
                        </a>
                    </li>
                    <li class="nk-menu-item">
                        <a href="{{ route('online.lab.reports') }}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon bi bi-file-earmark-text"></em></span>
                            <span class="nk-menu-text">Lab Reports</span>
                        </a>
                    </li>
                    <!-- Logout -->
                    <li class="nk-menu-item">
                        <a href="#" class="nk-menu-link"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <!-- Converted ni-signout to bi-box-arrow-right -->
                            <span class="nk-menu-icon"><em class="icon bi bi-box-arrow-right"></em></span>
                            <span class="nk-menu-text">Logout</span>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
