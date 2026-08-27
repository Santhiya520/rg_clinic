<div class="nk-header is-light nk-header-fixed is-light">
    <div class="container-xl wide-xl">
        <div class="nk-header-wrap">
            <div class="nk-menu-trigger d-xl-none ms-n1 me-3">
                <a href="#" class="nk-nav-toggle nk-quick-nav-icon" data-target="sidebarMenu">
                    <em class="icon ni ni-menu"></em>
                </a>
            </div>

            <div class="nk-header-brand d-xl-none">
                <a href="{{ route('dashboard') }}" class="logo-link">
                    <img class="logo-light logo-img" src="{{ asset('images/logo.png') }}"
                         alt="logo">
                    <img class="logo-dark logo-img" src="{{ asset('images/logo.png') }}"
                         alt="logo-dark">
                </a>
            </div>

            <div class="nk-header-menu is-light">
                <div class="nk-header-menu-inner">
                    <!-- Page Title Section -->
                    <div class="nk-header-title ms-3">
                        <h4 class="title mb-0 fw-bold">
                            @hasSection('page-title')
                                @yield('page-title')
                            @else
                                {{ $pageTitle ?? 'Dashboard' }}
                            @endif
                        </h4>
                    </div>

                    <!-- Menu Section -->
                    <ul class="nk-menu nk-menu-main">
                        <!-- Add your main menu items here -->
                    </ul>
                </div>
            </div>

            <div class="nk-header-tools">
                <ul class="nk-quick-nav">

                    <!-- User Dropdown -->
                    <li class="dropdown user-dropdown">
                        <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
                            <div class="user-toggle">
                                <div class="user-avatar sm">
                                    <em class="icon ni ni-user-alt"></em>
                                </div>
                                <div class="user-info d-none d-md-block">
                                    <span class="lead-text">{{ Auth::user()->name ?? 'User' }}</span>
                                    <span class="sub-text">{{ Auth::user()->role ?? 'Role' }}</span>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-md dropdown-menu-end">
                            <div class="dropdown-inner user-card-wrap bg-lighter d-none d-md-block">
                                <div class="user-card">
                                    <div class="user-avatar">
                                        <span>{{ substr(Auth::user()->name ?? 'U', 0, 2) }}</span>
                                    </div>
                                    <div class="user-info">
                                        <span class="lead-text">{{ Auth::user()->name ?? 'User' }}</span>
                                        <span class="sub-text">{{ Auth::user()->email ?? 'email@example.com' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown-inner">
                                <ul class="link-list">

                                    {{-- Change Password (NOT for Admin) --}}
                                    @if (auth()->check() && auth()->user()->role !== 'admin')
                                        <li>
                                            <a href="{{ route('password.change') }}" class="nk-menu-link">
                                                <span class="nk-menu-icon">
                                                    <em class="icon bi bi-lock-fill"></em>
                                                </span>
                                                <span class="nk-menu-text">Change Password</span>
                                            </a>
                                        </li>
                                    @endif

                                    <!-- Logout -->
                                    <li>
                                        <a href="#" class="nk-menu-link"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <span class="nk-menu-icon">
                                                <em class="icon bi bi-box-arrow-right"></em>
                                            </span>
                                            <span class="nk-menu-text">Logout</span>
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                            class="d-none">
                                            @csrf
                                        </form>
                                    </li>

                                </ul>
                            </div>

                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
