@extends('layouts.app')

@section('title', 'Lab Dashboard')
@section('page-title', 'Lab Dashboard')

@section('content')
<div class="nk-block nk-block-lg">
    <div class="card card-preview">
        <div class="card-inner">
            <!-- Tabs Navigation -->
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#opTab">
                        <em class="icon ni ni-users-fill"></em> OP Lab ({{ $opRegisters->count() }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#ipTab">
                        <em class="icon ni ni-bed-fill"></em> IP Lab ({{ $inpatientRegisters->count() }})
                    </a>
                </li>
            </ul>

            <!-- Tabs Content -->
            <div class="tab-content">
                <!-- OP Lab Tab -->
                <div class="tab-pane active" id="opTab">
                    <div class="table-responsive mt-3">
                        <table class="datatable-init table">
                            <thead>
                                <tr>
                                    <th>Token No</th>
                                    <th>Patient</th>
                                    <th>Patient ID</th>
                                    <th>Doctor</th>
                                    <th>Tests</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($opRegisters as $opRegister)
                                    @php
                                        $totalTests = $opRegister->labTests->count();
                                        $pendingTests = $opRegister->labTests->where('status', 'pending')->count();
                                        $totalAmount = $opRegister->labTests->sum('price');
                                        $paidAmount = $opRegister->labTests->sum('paid_amount');
                                    @endphp
                                    <tr>
                                        <td><strong>#{{ $opRegister->token_number }}</strong></td>
                                        <td>
                                            <div class="user-info">
                                                <span class="lead-text">{{ $opRegister->patient->name }}</span>
                                                <span class="sub-text">{{ $opRegister->patient->phone }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $opRegister->patient->patient_id }}</td>
                                        <td>{{ $opRegister->medicalOfficer->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge badge-dim bg-primary">{{ $totalTests }} Tests</span>
                                            @if($pendingTests > 0)
                                                <br><small class="text-danger">{{ $pendingTests }} pending</small>
                                            @endif
                                        </td>
                                        <td>{{ $opRegister->created_at->format('d M Y') }}</td>
                                        <td>
                                            <a href="{{ route('lab.op.show', $opRegister) }}" class="btn btn-sm btn-primary"
                                                style="border-radius: 6px 0 0 6px" title="View">
                                                <em class="icon ni ni-eye"></em>
                                            </a>
                                            <a href="{{ route('lab.op.print', $opRegister) }}" target="_blank"
                                                class="btn btn-sm btn-info" title="Print">
                                                <em class="icon ni ni-printer"></em>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- IP Lab Tab -->
                <div class="tab-pane" id="ipTab">
                    <div class="table-responsive mt-3">
                        <table class="datatable-init table">
                            <thead>
                                <tr>
                                    <th>IP No</th>
                                    <th>Patient</th>
                                    <th>Patient ID</th>
                                    <th>Admission Date</th>
                                    <th>Tests</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($inpatientRegisters as $inpatient)
                                    @php
                                        $totalTests = $inpatient->labTests->count();
                                        $pendingTests = $inpatient->labTests->where('status', 'pending')->count();
                                        $totalAmount = $inpatient->labTests->sum('price');
                                    @endphp
                                    <tr>
                                        <td><strong>{{ $inpatient->hospital_ip_no }}</strong></td>
                                        <td>
                                            <div class="user-info">
                                                <span class="lead-text">{{ $inpatient->patient->name }}</span>
                                                <span class="sub-text">{{ $inpatient->patient->phone ?? 'N/A' }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $inpatient->patient->patient_id }}</td>
                                        <td>{{ $inpatient->date_of_admission->format('d M Y') }}</td>
                                        <td>
                                            <span class="badge badge-dim bg-primary">{{ $totalTests }} Tests</span>
                                            @if($pendingTests > 0)
                                                <br><small class="text-danger">{{ $pendingTests }} pending</small>
                                            @endif
                                        </td>
                                        <td>{{ $inpatient->created_at->format('d M Y') }}</td>
                                        <td>
                                            <a href="{{ route('lab.ip.show', $inpatient) }}" class="btn btn-sm btn-primary"
                                                style="border-radius: 6px 0 0 6px" title="View">
                                                <em class="icon ni ni-eye"></em>
                                            </a>
                                            <a href="{{ route('lab.ip.print', $inpatient) }}" target="_blank"
                                                class="btn btn-sm btn-info" title="Print">
                                                <em class="icon ni ni-printer"></em>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .nav-tabs .nav-link {
        padding: 0.75rem 1.5rem;
        font-weight: 500;
    }
    .user-info {
        display: flex;
        flex-direction: column;
    }
    .user-info .lead-text {
        font-weight: 600;
    }
    .user-info .sub-text {
        font-size: 0.85em;
        color: #6c757d;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Get active tab from localStorage
        const activeTab = localStorage.getItem('lab_active_tab') || 'op';

        // Activate the tab
        $(`a[href="#${activeTab}Tab"]`).tab('show');

        // Store active tab when clicked
        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
            const activeTab = $(e.target).attr('href').replace('#', '').replace('Tab', '');
            localStorage.setItem('lab_active_tab', activeTab);
        });
    });
</script>
@endpush
