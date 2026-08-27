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
                        <a href="{{ route('dashboard') }}" class="nk-menu-link">
                            <!-- Converted ni-dashboard to bi-speedometer2 -->
                            <span class="nk-menu-icon"><em class="icon bi bi-speedometer2"></em></span>
                            <span class="nk-menu-text">Dashboard</span>
                        </a>
                    </li>

                    <!-- Master (User Management) -->
                    <li class="nk-menu-item has-sub">
                        <a href="#" class="nk-menu-link nk-menu-toggle">
                            <!-- Converted ni-users to bi-database-fill -->
                            <span class="nk-menu-icon"><em class="icon bi bi-database-fill"></em></span>
                            <span class="nk-menu-text">Master</span>
                        </a>
                        <ul class="nk-menu-sub">
                            @if (auth()->check() && auth()->user()->role === 'admin')
                                <li class="nk-menu-item">
                                    <a href="{{ route('users.index') }}" class="nk-menu-link">
                                        <span class="nk-menu-text">Users</span>
                                    </a>
                                </li>
                            @endif

                            @if (
                                (auth()->check() && auth()->user()->role === 'reception') ||
                                    (auth()->check() && auth()->user()->role === 'pharmacy') ||
                                    (auth()->check() && auth()->user()->role === 'admin'))
                                <li class="nk-menu-item">
                                    <a href="{{ route('suppliers.index') }}" class="nk-menu-link">
                                        <span class="nk-menu-text">Suppliers</span>
                                    </a>
                                </li>
                            @endif
                            @if (
                                (auth()->check() && auth()->user()->role === 'reception') ||
                                    auth()->user()->role === 'pharmacy' ||
                                    auth()->user()->role === 'doctor' ||
                                    auth()->user()->role === 'admin')
                                <li class="nk-menu-item">
                                    <a href="{{ route('medicines.index') }}" class="nk-menu-link">
                                        <span class="nk-menu-text">Medicines</span>
                                    </a>
                                </li>
                            @endif
                            @if ((auth()->check() && auth()->user()->role === 'radiology') || auth()->user()->role === 'admin')
                                <li class="nk-menu-item">
                                    <a href="{{ route('radiology-tests.index') }}" class="nk-menu-link">
                                        <span class="nk-menu-text">Radiology Tests</span>
                                    </a>
                                </li>
                            @endif
                            @if ((auth()->check() && auth()->user()->role === 'lab') || auth()->user()->role === 'admin')
                                <li class="nk-menu-item">
                                    <a href="{{ route('lab-tests.index') }}" class="nk-menu-link">
                                        <span class="nk-menu-text">Lab Tests</span>
                                    </a>
                                </li>
                            @endif
                            @if ((auth()->check() && auth()->user()->role === 'lab') || auth()->user()->role === 'admin')
                                <li class="nk-menu-item">
                                    <a href="{{ route('lab-sub-tests.index') }}" class="nk-menu-link">
                                        <span class="nk-menu-text">Lab Sub Tests</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>

                    <li class="nk-menu-item has-sub">
                        <a href="#" class="nk-menu-link nk-menu-toggle">
                            <!-- Converted ni-users to bi-database-fill -->
                            <span class="nk-menu-icon"><em class="icon bi bi-database-fill"></em></span>
                            <span class="nk-menu-text">Website</span>
                        </a>
                        <ul class="nk-menu-sub">
                            @if (auth()->check() && auth()->user()->role === 'admin')
                                <li class="nk-menu-item">
                                    <a href="{{ route('website.slider.index') }}" class="nk-menu-link">
                                        <span class="nk-menu-text">Slider Images</span>
                                    </a>
                                </li>
                                <li class="nk-menu-item">
                                    <a href="{{ route('website.gallery.index') }}" class="nk-menu-link">
                                        <span class="nk-menu-text">Gallery Images</span>
                                    </a>
                                </li>
                                <li class="nk-menu-item">
                                    <a href="{{ route('website.service.index') }}" class="nk-menu-link">
                                        <span class="nk-menu-text">Services</span>
                                    </a>
                                </li>
                                <li class="nk-menu-item">
                                    <a href="{{ route('website.team.index') }}" class="nk-menu-link">
                                        <span class="nk-menu-text">Team Members</span>
                                    </a>
                                </li>
                                <li class="nk-menu-item">
                                    <a href="{{ route('website.review.index') }}" class="nk-menu-link">
                                        <span class="nk-menu-text">Reviews</span>
                                    </a>
                                </li>
                                <li class="nk-menu-item">
                                    <a href="{{ route('website.donor.index') }}" class="nk-menu-link">
                                        <span class="nk-menu-text">Donors</span>
                                    </a>
                                </li>
                            @endif
                            <li class="nk-menu-item">
                                <a href="{{ route('website.enquiry.index') }}" class="nk-menu-link">
                                    <span class="nk-menu-text">Enquiries</span>
                                </a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="{{ route('website.notice.index') }}" class="nk-menu-link">
                                    <span class="nk-menu-text">Notice Board</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nk-menu-item">
                        <a href="{{ route('camp-pharmacy.index') }}" class="nk-menu-link">
                            <span class="nk-menu-icon">
                                <em class="icon bi bi-hospital-fill"></em>
                            </span>
                            <span class="nk-menu-text">Camp</span>
                        </a>
                    </li>
                    <li class="nk-menu-item">
                        <a href="{{ route('free-camp-pharmacy.index') }}" class="nk-menu-link">
                            <span class="nk-menu-icon">
                                <em class="icon bi bi-hospital-fill"></em>
                            </span>
                            <span class="nk-menu-text">Free Camp</span>
                        </a>
                    </li>
                    <li class="nk-menu-item">
                        <a href="{{ route('camp-new.index') }}" class="nk-menu-link">
                            <span class="nk-menu-icon">
                                <em class="icon bi bi-hospital-fill"></em>
                            </span>
                            <span class="nk-menu-text">Camp New</span>
                        </a>
                    </li>

                    <li class="nk-menu-item">
                        <a href="{{ route('patients.index') }}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon bi bi-person-fill"></em></span>
                            <span class="nk-menu-text">Patients</span>
                        </a>
                    </li>
                    @if ((auth()->check() && auth()->user()->role === 'reception') || auth()->user()->role === 'admin')
                        <li class="nk-menu-item">
                            <a href="{{ route('op-registers.index') }}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon bi bi-journal-text"></em></span>
                                <span class="nk-menu-text">OP Entries</span>
                            </a>
                        </li>
                    @endif
                    @if ((auth()->check() && auth()->user()->role === 'doctor') || auth()->user()->role === 'admin')
                        <li class="nk-menu-item">
                            <a href="{{ route('op-registers.doctor-op') }}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon bi bi-person-badge"></em> </span>
                                <span class="nk-menu-text">Doctor OP Entries</span>
                            </a>
                        </li>
                    @endif
                    @if ((auth()->check() && auth()->user()->role === 'reception') || auth()->user()->role === 'admin')
                        <li class="nk-menu-item">
                            <a href="{{ route('inpatient-register.index') }}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon bi bi-journal-text"></em></span>
                                <span class="nk-menu-text">Inpatient Register</span>
                            </a>
                        </li>
                    @endif
                    @if ((auth()->check() && auth()->user()->role === 'doctor') || auth()->user()->role === 'admin')
                        <li class="nk-menu-item">
                            <a href="{{ route('inpatient-register.doctor-ip') }}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon bi bi-journal-text"></em></span>
                                <span class="nk-menu-text">Doctor IP Register</span>
                            </a>
                        </li>
                    @endif
                    @if ((auth()->check() && auth()->user()->role === 'pharmacy') || auth()->user()->role === 'admin')
                        <li class="nk-menu-item">
                            <a href="{{ route('pharmacy.index') }}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon bi bi-capsule-pill"></em></span>
                                <span class="nk-menu-text">Pharmacy</span>
                            </a>
                        </li>
                    @endif
                    @if ((auth()->check() && auth()->user()->role === 'radiology') || auth()->user()->role === 'admin')
                        <li class="nk-menu-item">
                            <a href="{{ route('radiology.index') }}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon bi bi-heart-pulse"></em></span>
                                <span class="nk-menu-text">Radiology</span>
                            </a>
                        </li>
                        <li class="nk-menu-item">
                            <a href="{{ route('manual-radiology-tests.index') }}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon bi bi-heart-pulse"></em></span>
                                <span class="nk-menu-text">Manual Radiology</span>
                            </a>
                        </li>
                    @endif
                    @if ((auth()->check() && auth()->user()->role === 'lab') || auth()->user()->role === 'admin' || auth()->user()->role === 'pharmacy')
                        <li class="nk-menu-item">
                            <a href="{{ route('op-lab-tests.index') }}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon bi bi-virus"></em></span>
                                <span class="nk-menu-text">Lab Tests</span>
                            </a>
                        </li>
                        <li class="nk-menu-item">
                            <a href="{{ route('manual-lab-tests.index') }}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon bi bi-virus"></em></span>
                                <span class="nk-menu-text">Manual Lab Tests</span>
                            </a>
                        </li>
                    @endif
                    @if ((auth()->check() && auth()->user()->role === 'reception') || auth()->user()->role === 'admin')
                        <li class="nk-menu-item">
                            <a href="{{ route('operation-registers.index') }}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon bi bi-scissors"></em></span>
                                <span class="nk-menu-text">Operation Register</span>
                            </a>
                        </li>
                    @endif
                    @if (
                        (auth()->check() && auth()->user()->role === 'reception') ||
                            auth()->user()->role === 'admin' ||
                            auth()->user()->role === 'doctor')
                        <li class="nk-menu-item">
                            <a href="{{ route('report') }}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon bi bi-file-earmark-text"></em></span>
                                <span class="nk-menu-text">OP Reports</span>
                            </a>
                        </li>
                        <li class="nk-menu-item">
                            <a href="{{ route('ip-report') }}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon bi bi-file-earmark-text"></em></span>
                                <span class="nk-menu-text">IP Report</span>
                            </a>
                        </li>
                        <li class="nk-menu-item">
                            <a href="{{ route('patient-reports.report') }}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon bi bi-person-lines-fill"></em></span>
                                <span class="nk-menu-text">Patient Reports</span>
                            </a>
                        </li>
                    @endif
                    @if ((auth()->check() && auth()->user()->role === 'radiology') || auth()->user()->role === 'admin')
                        <li class="nk-menu-item">
                            <a href="{{ route('radiology.reports') }}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon bi bi-file-earmark-text"></em></span>
                                <span class="nk-menu-text">Radiology Reports</span>
                            </a>
                        </li>
                    @endif
                    @if ((auth()->check() && auth()->user()->role === 'lab') || auth()->user()->role === 'admin')
                        <li class="nk-menu-item">
                            <a href="{{ route('lab.reports') }}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon bi bi-file-earmark-text"></em></span>
                                <span class="nk-menu-text">Lab Reports</span>
                            </a>
                        </li>
                    @endif

                    @if ((auth()->check() && auth()->user()->role === 'pharmacy') || auth()->user()->role === 'admin')
                        <li class="nk-menu-item">
                            <a href="{{ route('pharmacy.reports.op') }}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon bi bi-file-earmark-text"></em></span>
                                <span class="nk-menu-text">Pharmacy OP Reports</span>
                            </a>
                        </li>
                        <li class="nk-menu-item">
                            <a href="{{ route('pharmacy.reports.ip') }}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon bi bi-file-earmark-text"></em></span>
                                <span class="nk-menu-text">Pharmacy IP Reports</span>
                            </a>
                        </li>
                    @endif
                    @if ((auth()->check() && auth()->user()->role === 'admin') || auth()->user()->role === 'pharmacy')
                        {{-- Medicine Purchase --}}
                        <li class="nk-menu-item">
                            <a href="{{ route('medicine-purchases.index') }}" class="nk-menu-link">
                                <span class="nk-menu-icon">
                                    <em class="icon bi bi-cart"></em>
                                </span>
                                <span class="nk-menu-text">Medicine Purchase</span>
                            </a>
                        </li>
                        {{-- Medicine Purchase --}}
                        <li class="nk-menu-item">
                            <a href="{{ route('medicine-sales.index') }}" class="nk-menu-link">
                                <span class="nk-menu-icon">
                                    <em class="icon bi bi-cart"></em>
                                </span>
                                <span class="nk-menu-text">Medicine Sale</span>
                            </a>
                        </li>

                        <li class="nk-menu-item">
                            <a href="{{ route('medicines.bulk-order') }}" class="nk-menu-link">
                                <span class="nk-menu-icon">
                                    <em class="icon bi bi-cart"></em>
                                </span>
                                <span class="nk-menu-text">Medicine Bulk Order</span>
                            </a>
                        </li>

                        <li class="nk-menu-item">
                            <a href="{{ route('medicines.bulk-order-report') }}" class="nk-menu-link">
                                <span class="nk-menu-icon">
                                    <em class="icon bi bi-cart"></em>
                                </span>
                                <span class="nk-menu-text">Bulk Order Report</span>
                            </a>
                        </li>


                        {{-- Stock Report --}}
                        <li class="nk-menu-item">
                            <a href="{{ route('stock-report') }}" class="nk-menu-link">
                                <span class="nk-menu-icon">
                                    <em class="icon bi bi-box-seam"></em>
                                </span>
                                <span class="nk-menu-text">Stock Report</span>
                            </a>
                        </li>
                    @endif


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
