@extends('online.layouts.app')

@section('title', 'Patient Dashboard')
@section('page-title', 'My Medical Dashboard')

@section('content')
<div class="nk-block">
    <div class="row g-gs">
        <!-- Today's Appointments -->
        <div class="col-lg-3 col-sm-6">
            <div class="card h-100 bg-primary">
                <div class="card-inner pt-3">
                    <div class="d-flex justify-content-between">
                        <div class="flex-item">
                            <div class="text-white d-flex flex-wrap">
                                <span class="fs-2 me-1">{{ $todayAppointments }}</span>
                            </div>
                            <h6 class="text-white">Today's Appointments</h6>
                        </div>
                        <div class="card-icon">
                            <em class="icon ni ni-calendar-alt" style="font-size: 2rem;"></em>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Visits -->
        <div class="col-lg-3 col-sm-6">
            <div class="card h-100 bg-info">
                <div class="card-inner pt-3">
                    <div class="d-flex justify-content-between">
                        <div class="flex-item">
                            <div class="text-white d-flex flex-wrap">
                                <span class="fs-2 me-1">{{ $totalVisits }}</span>
                            </div>
                            <h6 class="text-white">Total Visits</h6>
                        </div>
                        <div class="card-icon">
                            <em class="icon ni ni-hospital" style="font-size: 2rem;"></em>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Payments -->
        <div class="col-lg-3 col-sm-6">
            <div class="card h-100 bg-warning">
                <div class="card-inner pt-3">
                    <div class="d-flex justify-content-between">
                        <div class="flex-item">
                            <div class="text-white d-flex flex-wrap">
                                <span class="fs-2 me-1">₹{{ number_format($pendingPayments, 0) }}</span>
                            </div>
                            <h6 class="text-white">Pending Payments</h6>
                        </div>
                        <div class="card-icon">
                            <em class="icon ni ni-money" style="font-size: 2rem;"></em>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Completed Treatments -->
        <div class="col-lg-3 col-sm-6">
            <div class="card h-100 bg-success">
                <div class="card-inner pt-3">
                    <div class="d-flex justify-content-between">
                        <div class="flex-item">
                            <div class="text-white d-flex flex-wrap">
                                <span class="fs-2 me-1">{{ $completedTreatments }}</span>
                            </div>
                            <h6 class="text-white">Completed Treatments</h6>
                        </div>
                        <div class="card-icon">
                            <em class="icon ni ni-check-circle" style="font-size: 2rem;"></em>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Visits -->
        <div class="col-xxl-8 col-lg-7">
            <div class="card card-full">
                <div class="card-inner">
                    <div class="card-title-group">
                        <div class="card-title">
                            <h6 class="title">Recent Medical Visits</h6>
                        </div>
                        <div class="card-tools">
                            <a href="{{ route('online.patient.reports') }}" class="link">View All</a>
                        </div>
                    </div>
                </div>
                <div class="card-inner p-0">
                    <div class="nk-tb-list nk-tb-orders">
                        <div class="nk-tb-item nk-tb-head">
                            <div class="nk-tb-col"><span>Date</span></div>
                            <div class="nk-tb-col"><span>Token No</span></div>
                            <div class="nk-tb-col"><span>Doctor</span></div>
                            <div class="nk-tb-col"><span>Diagnosis</span></div>
                            <div class="nk-tb-col"><span>Status</span></div>
                            <div class="nk-tb-col text-end"><span>Action</span></div>
                        </div>
                        @forelse($recentVisits as $visit)
                        <div class="nk-tb-item">
                            <div class="nk-tb-col">
                                <span class="tb-sub">{{ $visit->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="nk-tb-col">
                                <span class="tb-lead">#{{ $visit->token_number }}</span>
                            </div>
                            <div class="nk-tb-col">
                                <span class="tb-sub">{{ $visit->medicalOfficer->name ?? 'N/A' }}</span>
                            </div>
                            <div class="nk-tb-col">
                                <span class="tb-sub">{{ Str::limit($visit->provisional_diagnosis, 20) }}</span>
                            </div>
                            <div class="nk-tb-col">
                                <span class="badge badge-dot bg-{{ $visit->status == 'completed' ? 'success' : ($visit->status == 'in_progress' ? 'warning' : 'primary') }}">
                                    {{ ucfirst($visit->status) }}
                                </span>
                            </div>
                            <div class="nk-tb-col text-end">
                                <a href="{{ route('online.patient.op.view', $visit) }}" class="btn btn-sm btn-outline-primary">View</a>
                            </div>
                        </div>
                        @empty
                        <div class="nk-tb-item">
                            <div class="nk-tb-col text-center py-4" colspan="6">
                                <em class="icon ni ni-info text-muted" style="font-size: 2rem;"></em>
                                <p class="mt-2 text-muted">No recent visits found</p>
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
                            <h6 class="title">Medical Summary</h6>
                        </div>
                    </div>
                </div>
                <div class="card-inner">
                    <ul class="gy-3">
                        <li class="border-bottom border-light pb-3">
                            <div class="d-flex justify-content-between align-center">
                                <div class="lead-text">Lab Tests Done</div>
                                <div class="badge badge-pill bg-info">{{ $labTests->count() }}</div>
                            </div>
                        </li>
                        <li class="border-bottom border-light py-3">
                            <div class="d-flex justify-content-between align-center">
                                <div class="lead-text">Radiology Tests</div>
                                <div class="badge badge-pill bg-warning">{{ $radiologyTests->count() }}</div>
                            </div>
                        </li>
                        <li class="border-bottom border-light py-3">
                            <div class="d-flex justify-content-between align-center">
                                <div class="lead-text">Active Prescriptions</div>
                                <div class="badge badge-pill bg-success">{{ $prescriptions->count() }}</div>
                            </div>
                        </li>
                        <li class="pt-3">
                            <div class="d-flex justify-content-between align-center">
                                <div class="lead-text">Upcoming Appointments</div>
                                <div class="badge badge-pill bg-primary">{{ $upcomingAppointments->count() }}</div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Upcoming Appointments -->
        <div class="col-xxl-6 col-lg-6">
            <div class="card card-full">
                <div class="card-inner">
                    <div class="card-title-group">
                        <div class="card-title">
                            <h6 class="title">Upcoming Appointments</h6>
                        </div>
                        <div class="card-tools">
                            <a href="{{ route('online.patient.reports') }}" class="link">View All</a>
                        </div>
                    </div>
                </div>
                <div class="card-inner p-0">
                    <div class="nk-tb-list nk-tb-medicine">
                        <div class="nk-tb-item nk-tb-head">
                            <div class="nk-tb-col"><span>Date & Time</div>
                            <div class="nk-tb-col"><span>Doctor</span></div>
                            <div class="nk-tb-col"><span>Token #</span></div>
                            <div class="nk-tb-col text-end"><span>Status</span></div>
                        </div>
                        @forelse($upcomingAppointments as $appointment)
                        <div class="nk-tb-item">
                            <div class="nk-tb-col">
                                <span class="tb-lead">{{ $appointment->created_at->format('d M Y') }}</span>
                                <span class="tb-sub">{{ $appointment->created_at->format('h:i A') }}</span>
                            </div>
                            <div class="nk-tb-col">
                                <span class="tb-sub">{{ $appointment->medicalOfficer->name ?? 'Not Assigned' }}</span>
                            </div>
                            <div class="nk-tb-col">
                                <span class="tb-lead">#{{ $appointment->token_number }}</span>
                            </div>
                            <div class="nk-tb-col text-end">
                                <span class="badge badge-dot bg-{{ $appointment->status == 'completed' ? 'success' : 'warning' }}">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </div>
                        </div>
                        @empty
                        <div class="nk-tb-item">
                            <div class="nk-tb-col text-center py-4" colspan="4">
                                <em class="icon ni ni-calendar text-muted" style="font-size: 2rem;"></em>
                                <p class="mt-2 text-muted">No upcoming appointments</p>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Test Results -->
        <div class="col-xxl-6 col-lg-6">
            <div class="card card-full">
                <div class="card-inner">
                    <div class="card-title-group">
                        <div class="card-title">
                            <h6 class="title">Recent Test Results</h6>
                        </div>
                        <div class="card-tools">
                            <a href="{{ route('online.lab.reports') }}" class="link">View All</a>
                        </div>
                    </div>
                </div>
                <div class="card-inner p-0">
                    <div class="nk-tb-list nk-tb-tests">
                        <div class="nk-tb-item nk-tb-head">
                            <div class="nk-tb-col"><span>Test Name</span></div>
                            <div class="nk-tb-col"><span>Type</span></div>
                            <div class="nk-tb-col"><span>Date</span></div>
                            <div class="nk-tb-col text-end"><span>Status</span></div>
                        </div>
                        @php
                            $allTests = collect([]);
                            foreach($labTests as $test) {
                                $allTests->push((object)[
                                    'name' => $test->labTest->name ?? 'Unknown Test',
                                    'type' => 'Lab Test',
                                    'date' => $test->created_at,
                                    'status' => $test->status == 'completed' ? 'Completed' : 'Pending',
                                    'id' => $test->id
                                ]);
                            }
                            foreach($radiologyTests as $test) {
                                $allTests->push((object)[
                                    'name' => $test->radiologyTest->name ?? 'Unknown Test',
                                    'type' => 'Radiology',
                                    'date' => $test->created_at,
                                    'status' => $test->status == 'completed' ? 'Completed' : 'Pending',
                                    'id' => $test->id
                                ]);
                            }
                            $recentTests = $allTests->sortByDesc('date')->take(5);
                        @endphp
                        @forelse($recentTests as $test)
                        <div class="nk-tb-item">
                            <div class="nk-tb-col">
                                <span class="tb-lead">{{ $test->name }}</span>
                            </div>
                            <div class="nk-tb-col">
                                <span class="badge badge-dot bg-{{ $test->type == 'Lab Test' ? 'info' : 'warning' }}">
                                    {{ $test->type }}
                                </span>
                            </div>
                            <div class="nk-tb-col">
                                <span class="tb-sub">{{ \Carbon\Carbon::parse($test->date)->format('d M Y') }}</span>
                            </div>
                            <div class="nk-tb-col text-end">
                                <span class="badge badge-dot bg-{{ $test->status == 'Completed' ? 'success' : 'warning' }}">
                                    {{ $test->status }}
                                </span>
                            </div>
                        </div>
                        @empty
                        <div class="nk-tb-item">
                            <div class="nk-tb-col text-center py-4" colspan="4">
                                <em class="icon ni ni-file-text text-muted" style="font-size: 2rem;"></em>
                                <p class="mt-2 text-muted">No test results available</p>
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
