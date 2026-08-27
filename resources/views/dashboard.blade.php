@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Medical Dashboard')

@section('content')
<div class="nk-block">
    <div class="row g-gs">
        <!-- Today's OP Count -->
        <div class="col-lg-3 col-sm-6">
            <div class="card h-100 bg-primary">
                <div class="nk-cmwg nk-cmwg1">
                    <div class="card-inner pt-3">
                        <div class="d-flex justify-content-between">
                            <div class="flex-item">
                                <div class="text-white d-flex flex-wrap">
                                    <span class="fs-2 me-1">{{ $todayOpCount }}</span>
                                    <span class="align-self-end fs-14px pb-1">
                                        <em class="icon ni ni-arrow-long-up"></em>{{ $opGrowth }}%
                                    </span>
                                </div>
                                <h6 class="text-white">Today's OP</h6>
                            </div>
                            <div class="card-icon">
                                <em class="icon ni ni-user-fill" style="font-size: 2rem;"></em>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Patients -->
        <div class="col-lg-3 col-sm-6">
            <div class="card h-100 bg-info">
                <div class="nk-cmwg nk-cmwg1">
                    <div class="card-inner pt-3">
                        <div class="d-flex justify-content-between">
                            <div class="flex-item">
                                <div class="text-white d-flex flex-wrap">
                                    <span class="fs-2 me-1">{{ $totalPatients }}</span>
                                </div>
                                <h6 class="text-white">Total Patients</h6>
                            </div>
                            <div class="card-icon">
                                <em class="icon ni ni-users-fill" style="font-size: 2rem;"></em>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Tests -->
        <div class="col-lg-3 col-sm-6">
            <div class="card h-100 bg-warning">
                <div class="nk-cmwg nk-cmwg1">
                    <div class="card-inner pt-3">
                        <div class="d-flex justify-content-between">
                            <div class="flex-item">
                                <div class="text-white d-flex flex-wrap">
                                    <span class="fs-2 me-1">{{ $pendingTests }}</span>
                                </div>
                                <h6 class="text-white">Pending Tests</h6>
                            </div>
                            <div class="card-icon">
                                <em class="icon ni ni-file-text-fill" style="font-size: 2rem;"></em>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Revenue -->
        <div class="col-lg-3 col-sm-6">
            <div class="card h-100 bg-success">
                <div class="nk-cmwg nk-cmwg1">
                    <div class="card-inner pt-3">
                        <div class="d-flex justify-content-between">
                            <div class="flex-item">
                                <div class="text-white d-flex flex-wrap">
                                    <span class="fs-2 me-1">₹{{ number_format($monthlyRevenue, 0) }}</span>
                                    <span class="align-self-end fs-14px pb-1">
                                        <em class="icon ni ni-arrow-long-up"></em>{{ $revenueGrowth }}%
                                    </span>
                                </div>
                                <h6 class="text-white">Monthly Revenue</h6>
                            </div>
                            <div class="card-icon">
                                <em class="icon ni ni-coin" style="font-size: 2rem;"></em>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent OP Registrations -->
        <div class="col-xxl-8 col-lg-7">
            <div class="card card-full">
                <div class="card-inner">
                    <div class="card-title-group">
                        <div class="card-title">
                            <h6 class="title">Today's OP Registrations</h6>
                        </div>
                        <div class="card-tools">
                            <a href="{{ route('op-registers.index') }}" class="link">View All</a>
                        </div>
                    </div>
                </div>
                <div class="card-inner p-0">
                    <div class="nk-tb-list nk-tb-orders">
                        <div class="nk-tb-item nk-tb-head">
                            <div class="nk-tb-col"><span>Token No</span></div>
                            <div class="nk-tb-col"><span>Patient</span></div>
                            <div class="nk-tb-col"><span>Doctor</span></div>
                            <div class="nk-tb-col"><span>Time</span></div>
                            <div class="nk-tb-col"><span>Status</span></div>
                            <div class="nk-tb-col text-end"><span>Action</span></div>
                        </div>
                        @forelse($recentRegisters as $register)
                        <div class="nk-tb-item">
                            <div class="nk-tb-col">
                                <span class="tb-lead">#{{ $register->token_number }}</span>
                            </div>
                            <div class="nk-tb-col">
                                <span class="tb-sub">{{ $register->patient->name }}</span>
                            </div>
                            <div class="nk-tb-col">
                                <span class="tb-sub">{{ $register->medicalOfficer->name ?? 'N/A' }}</span>
                            </div>
                            <div class="nk-tb-col">
                                <span class="tb-sub">{{ $register->created_at->format('h:i A') }}</span>
                            </div>
                            <div class="nk-tb-col">
                                <span class="badge badge-dot bg-{{ $register->status == 'completed' ? 'success' : ($register->status == 'in_progress' ? 'warning' : 'primary') }}">{{ ucfirst($register->status) }}</span>
                            </div>
                            <div class="nk-tb-col text-end">
                                <a href="{{ route('op-registers.prescription-view', $register) }}" class="btn btn-sm btn-outline-primary">View</a>
                            </div>
                        </div>
                        @empty
                        <div class="nk-tb-item">
                            <div class="nk-tb-col text-center py-4" colspan="6">
                                <em class="icon ni ni-info text-muted" style="font-size: 2rem;"></em>
                                <p class="mt-2 text-muted">No OP registrations today</p>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="col-xxl-4 col-lg-5">
            <div class="card card-full">
                <div class="card-inner">
                    <div class="card-title-group">
                        <div class="card-title">
                            <h6 class="title">Quick Statistics</h6>
                        </div>
                    </div>
                </div>
                <div class="card-inner">
                    <ul class="gy-3">
                        <li class="border-bottom border-light pb-3">
                            <div class="d-flex justify-content-between align-center">
                                <div class="lead-text">Lab Tests Today</div>
                                <div class="badge badge-pill bg-info">{{ $todayLabTests }}</div>
                            </div>
                        </li>
                        <li class="border-bottom border-light py-3">
                            <div class="d-flex justify-content-between align-center">
                                <div class="lead-text">Radiology Tests Today</div>
                                <div class="badge badge-pill bg-warning">{{ $todayRadiologyTests }}</div>
                            </div>
                        </li>
                        <li class="border-bottom border-light py-3">
                            <div class="d-flex justify-content-between align-center">
                                <div class="lead-text">Medicines Prescribed</div>
                                <div class="badge badge-pill bg-success">{{ $todayMedicines }}</div>
                            </div>
                        </li>
                        <li class="pt-3">
                            <div class="d-flex justify-content-between align-center">
                                <div class="lead-text">Available Doctors</div>
                                <div class="badge badge-pill bg-primary">{{ $availableDoctors }}</div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Low Stock Medicines -->
        <div class="col-xxl-6 col-lg-6">
            <div class="card card-full">
                <div class="card-inner">
                    <div class="card-title-group">
                        <div class="card-title">
                            <h6 class="title">Low Stock Medicines</h6>
                        </div>
                        <div class="card-tools">
                            {{-- <a href="{{ route('medicine-purchases.stock-report') }}" class="link">View All</a> --}}
                        </div>
                    </div>
                </div>
                <div class="card-inner p-0">
                    <div class="nk-tb-list nk-tb-medicine">
                        <div class="nk-tb-item nk-tb-head">
                            <div class="nk-tb-col"><span>Medicine</div>
                            <div class="nk-tb-col"><span>Current Stock</span></div>
                            <div class="nk-tb-col"><span>Category</span></div>
                            <div class="nk-tb-col text-end"><span>Action</span></div>
                        </div>
                        @forelse($lowStockMedicines as $medicine)
                        <div class="nk-tb-item">
                            <div class="nk-tb-col">
                                <span class="tb-lead">{{ $medicine->name }}</span>
                                <span class="tb-sub">{{ $medicine->generic_name }}</span>
                            </div>
                            <div class="nk-tb-col">
                                <span class="badge badge-dot bg-{{ $medicine->current_stock == 0 ? 'danger' : 'warning' }}">
                                    {{ $medicine->current_stock }} units
                                </span>
                            </div>
                            <div class="nk-tb-col">
                                <span class="tb-sub">{{ $medicine->category }}</span>
                            </div>
                            <div class="nk-tb-col text-end">
                                {{-- <a href="{{ route('medicine-purchases.create') }}" class="btn btn-sm btn-outline-primary">Purchase</a> --}}
                            </div>
                        </div>
                        @empty
                        <div class="nk-tb-item">
                            <div class="nk-tb-col text-center py-4" colspan="4">
                                <em class="icon ni ni-check-circle text-success" style="font-size: 2rem;"></em>
                                <p class="mt-2 text-muted">All medicines are well stocked</p>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Test Results -->
        <div class="col-xxl-6 col-lg-6">
            <div class="card card-full">
                <div class="card-inner">
                    <div class="card-title-group">
                        <div class="card-title">
                            <h6 class="title">Pending Test Results</h6>
                        </div>
                        <div class="card-tools">
                            <a href="{{ route('op-lab-tests.index') }}" class="link">View All</a>
                        </div>
                    </div>
                </div>
                <div class="card-inner p-0">
                    <div class="nk-tb-list nk-tb-tests">
                        <div class="nk-tb-item nk-tb-head">
                            <div class="nk-tb-col"><span>Patient</span></div>
                            <div class="nk-tb-col"><span>Test Type</span></div>
                            <div class="nk-tb-col"><span>Test Name</span></div>
                            <div class="nk-tb-col text-end"><span>Action</span></div>
                        </div>
                        @forelse($pendingTestResults as $test)
                        <div class="nk-tb-item">
                            <div class="nk-tb-col">
                                <span class="tb-lead">{{ $test->opRegister->patient->name }}</span>
                                <span class="tb-sub">#{{ $test->opRegister->token_number }}</span>
                            </div>
                            <div class="nk-tb-col">
                                <span class="badge badge-dot bg-{{ $test instanceof App\Models\OpLabTest ? 'info' : 'warning' }}">
                                    {{ $test instanceof App\Models\OpLabTest ? 'Lab' : 'Radiology' }}
                                </span>
                            </div>
                            <div class="nk-tb-col">
                                <span class="tb-sub">
                                    @if($test instanceof App\Models\OpLabTest)
                                        {{ $test->labTest->name }}
                                    @else
                                        {{ $test->radiologyTest->name }}
                                    @endif
                                </span>
                            </div>
                            <div class="nk-tb-col text-end">
                                @if($test instanceof App\Models\OpLabTest)
                                    <a href="{{ route('op-lab-tests.edit', $test) }}" class="btn btn-sm btn-outline-primary">Update</a>
                                @else
                                    <a href="{{ route('radiology.op.edit', $test) }}" class="btn btn-sm btn-outline-primary">Update</a>
                                @endif
                            </div>
                        </div>
                        @empty
                        <div class="nk-tb-item">
                            <div class="nk-tb-col text-center py-4" colspan="4">
                                <em class="icon ni ni-check-circle text-success" style="font-size: 2rem;"></em>
                                <p class="mt-2 text-muted">No pending test results</p>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
