@extends('layouts.app')

@section('title', 'Patient Report - ' . $patient->name)
@section('page-title', 'Patient Report - ' . $patient->name)

@section('content')
    <div class="nk-block nk-block-lg">
        <div class="card card-preview">
            <div class="card-inner">
                <!-- Header Actions -->
                <div class="nk-block-head">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                        </div>
                        <div class="nk-block-head-content">
                            <a href="{{ route('patient-reports.report') }}" class="btn btn-secondary"
                                style="border-radius: 6px 0 0 6px">
                                <em class="icon ni ni-arrow-left"></em> &nbsp; Back to Reports
                            </a>
                            <a href="{{ route('patient-reports.print', $patient) }}" target="_blank"
                                class="btn btn-primary">
                                <em class="icon ni ni-printer"></em>&nbsp; Print Report
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Patient Summary -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="title">Patient Information</h6>
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">Patient Name</th>
                                <td>{{ $patient->name }}</td>
                            </tr>
                            <tr>
                                <th>Patient ID</th>
                                <td>{{ $patient->patient_id }}</td>
                            </tr>
                            <tr>
                                <th>Mobile Number</th>
                                <td>{{ $patient->phone }}</td>
                            </tr>
                            <tr>
                                <th>Age & Gender</th>
                                <td>{{ $patient->age }} years, {{ $patient->gender }}</td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td>{{ $patient->address ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="title">Medical Summary</h6>
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">OP Visits</th>
                                <td><span class="badge bg-primary" style="padding: 3px 8px">{{ $totalOpVisits }}</span></td>
                            </tr>
                            <tr>
                                <th>IP Admissions</th>
                                <td><span class="badge bg-secondary"
                                        style="padding: 3px 8px">{{ $totalIpAdmissions }}</span></td>
                            </tr>
                            <tr>
                                <th>Operations</th>
                                <td><span class="badge bg-danger" style="padding: 3px 8px">{{ $totalOperations }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th>Total Amount</th>
                                <td><strong>₹{{ number_format($totalAmount, 2) }}</strong></td>
                            </tr>
                            <tr>
                                <th>OP Amount</th>
                                <td>₹{{ number_format($totalOpAmount, 2) }}</td>
                            </tr>
                            <tr>
                                <th>IP Amount</th>
                                <td>₹{{ number_format($totalIpAmount, 2) }}</td>
                            </tr>
                            @if ($totalOperationAmount > 0)
                                <tr>
                                    <th>Operation Amount</th>
                                    <td>₹{{ number_format($totalOperationAmount, 2) }}</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>

                <!-- Tabs for OP, IP and Operation Records -->
                <ul class="nav nav-tabs" id="patientTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="op-tab" data-toggle="tab" href="#op">OP Records
                            ({{ $totalOpVisits }})</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="ip-tab" data-toggle="tab" href="#ip">IP Records
                            ({{ $totalIpAdmissions }})</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="operation-tab" data-toggle="tab" href="#operation">Operations
                            ({{ $totalOperations }})</a>
                    </li>
                </ul>

                <div class="tab-content mt-3" id="patientTabsContent">
                    <!-- OP Records Tab -->
                    <div class="tab-pane fade show active" id="op" role="tabpanel">
                        @if ($patient->opRegisters->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Token No</th>
                                            <th>Date</th>
                                            <th>Doctor</th>
                                            <th>Radiology Tests</th>
                                            <th>Lab Tests</th>
                                            <th>Medicines</th>
                                            <th>Total Amount</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($patient->opRegisters as $register)
                                            @php
                                                $radiologyCount = $register->radiologyTests->count();
                                                $labCount = $register->labTests->count();
                                                $medicineCount = $register->medicines->count();
                                                $registerTotal =
                                                    $register->radiologyTests->sum('price') +
                                                    $register->labTests->sum('price') +
                                                    $register->medicines->sum('price');
                                            @endphp
                                            <tr>
                                                <td><strong>#{{ $register->token_number }}</strong></td>
                                                <td>{{ $register->created_at->format('d M Y') }}</td>
                                                <td>{{ $register->medicalOfficer->name ?? 'N/A' }}</td>
                                                <td>
                                                    @if ($radiologyCount > 0)
                                                        <span class="badge bg-info"
                                                            style="padding: 3px 8px">{{ $radiologyCount }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($labCount > 0)
                                                        <span class="badge bg-warning"
                                                            style="padding: 3px 8px">{{ $labCount }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($medicineCount > 0)
                                                        <span class="badge bg-success"
                                                            style="padding: 3px 8px">{{ $medicineCount }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>₹{{ number_format($registerTotal, 2) }}</td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $register->status == 'completed' ? 'success' : 'warning' }}"
                                                        style="padding: 3px 8px">
                                                        {{ ucfirst($register->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('op-registers.preview', $register) }}"
                                                        class="btn btn-sm btn-outline-primary" target="_blank"
                                                        style="border-radius: 5px">
                                                        <em class="icon ni ni-eye"></em> &nbsp; View
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center p-4">
                                <em class="icon ni ni-info text-warning" style="font-size: 3rem;"></em>
                                <p class="mt-2">No OP records found for this patient.</p>
                            </div>
                        @endif
                    </div>

                    <!-- IP Records Tab -->
                    <div class="tab-pane fade" id="ip" role="tabpanel">
                        @if ($patient->inpatientRegisters->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>IP No</th>
                                            <th>Admission Date</th>
                                            <th>Discharge Date</th>
                                            <th>Diagnosis</th>
                                            <th>Radiology Tests</th>
                                            <th>Lab Tests</th>
                                            <th>Medicines</th>
                                            <th>Total Amount</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($patient->inpatientRegisters as $register)
                                            @php
                                                $radiologyCount = $register->radiologyTests->count();
                                                $labCount = $register->labTests->count();
                                                $medicineCount = $register->medicines->count();
                                                $registerTotal =
                                                    $register->radiologyTests->sum('price') +
                                                    $register->labTests->sum('price') +
                                                    $register->medicines->sum('price');
                                                $isDischarged = !empty($register->date_of_discharge);
                                            @endphp
                                            <tr>
                                                <td><strong>{{ $register->hospital_ip_no }}</strong></td>
                                                <td>{{ $register->date_of_admission->format('d M Y') }}</td>
                                                <td>
                                                    @if ($isDischarged)
                                                        {{ $register->date_of_discharge->format('d M Y') }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ Str::limit($register->provisional_diagnosis, 30) }}
                                                </td>
                                                <td>
                                                    @if ($radiologyCount > 0)
                                                        <span class="badge bg-info"
                                                            style="padding: 3px 8px">{{ $radiologyCount }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($labCount > 0)
                                                        <span class="badge bg-warning"
                                                            style="padding: 3px 8px">{{ $labCount }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($medicineCount > 0)
                                                        <span class="badge bg-success"
                                                            style="padding: 3px 8px">{{ $medicineCount }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>₹{{ number_format($registerTotal, 2) }}</td>
                                                <td>
                                                    @if ($isDischarged)
                                                        <span class="badge bg-secondary"
                                                            style="padding: 3px 8px">Discharged</span>
                                                    @else
                                                        <span class="badge bg-success"
                                                            style="padding: 3px 8px">Active</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('inpatient-register.prescription.view', $register) }}"
                                                        class="btn btn-sm btn-outline-primary" target="_blank"
                                                        style="border-radius: 5px">
                                                        <em class="icon ni ni-eye"></em> View
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center p-4">
                                <em class="icon ni ni-info text-warning" style="font-size: 3rem;"></em>
                                <p class="mt-2">No IP records found for this patient.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Operation Records Tab -->
                    <div class="tab-pane fade" id="operation" role="tabpanel">
                        @if ($patient->operationRegisters->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Operation ID</th>
                                            <th>Date</th>
                                            <th>Operation</th>
                                            <th>Surgeon</th>
                                            <th>Theatre</th>
                                            <th>Duration</th>
                                            <th>Ward</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($patient->operationRegisters as $operation)
                                            @php
                                                $duration = '';
                                                if (
                                                    $operation->operation_start_time &&
                                                    $operation->operation_end_time
                                                ) {
                                                    $start = \Carbon\Carbon::parse($operation->operation_start_time);
                                                    $end = \Carbon\Carbon::parse($operation->operation_end_time);
                                                    $duration = $start->diff($end)->format('%Hh %Im');
                                                }
                                            @endphp
                                            <tr>
                                                <td><strong>{{ $operation->hospital_ip_no }}</strong></td>
                                                <td>{{ $operation->date_of_admission->format('d M Y') }}</td>
                                                <td>{{ $operation->operation_performed }}</td>
                                                <td>{{ $operation->operatingSurgeon->name ?? 'N/A' }}</td>
                                                <td>{{ $operation->operation_theatre_type }}</td>
                                                <td>{{ $duration }}</td>
                                                <td>{{ $operation->transferred_to_ward }}</td>
                                                <td>
                                                    <span style="padding: 3px 8px"
                                                        class="badge bg-{{ $operation->status == 'scheduled'
                                                            ? 'warning'
                                                            : ($operation->status == 'in_progress'
                                                                ? 'info'
                                                                : ($operation->status == 'completed'
                                                                    ? 'success'
                                                                    : 'danger')) }}">
                                                        {{ ucfirst(str_replace('_', ' ', $operation->status)) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('operation-registers.show', $operation) }}"
                                                        class="btn btn-sm btn-outline-primary" target="_blank"
                                                        style="border-radius: 5px">
                                                        <em class="icon ni ni-eye"></em> &nbsp; View
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center p-4">
                                <em class="icon ni ni-info text-warning" style="font-size: 3rem;"></em>
                                <p class="mt-2">No operation records found for this patient.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Summary Statistics -->
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card card-bordered">
                            <div class="card-inner">
                                <h6 class="title">OP Summary</h6>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="text-center p-2">
                                            <div class="h6 text-primary">{{ $totalOpVisits }}</div>
                                            <small class="text-soft">Visits</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-center p-2">
                                            <div class="h6 text-success">₹ {{ number_format($totalOpAmount, 2) }}</div>
                                            <small class="text-soft">Amount</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card card-bordered">
                            <div class="card-inner">
                                <h6 class="title">IP Summary</h6>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="text-center p-2">
                                            <div class="h6 text-secondary">{{ $totalIpAdmissions }}</div>
                                            <small class="text-soft">Admissions</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-center p-2">
                                            <div class="h6 text-success">₹{{ number_format($totalIpAmount, 2) }}</div>
                                            <small class="text-soft">Amount</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card card-bordered">
                            <div class="card-inner">
                                <h6 class="title">Operations Summary</h6>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="text-center p-2">
                                            <div class="h6 text-danger">{{ $totalOperations }}</div>
                                            <small class="text-soft">Operations</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-center p-2">
                                            <div class="h6 text-success">₹{{ number_format($totalOperationAmount, 2) }}
                                            </div>
                                            <small class="text-soft">Amount</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom Actions -->
                <div class="mt-4 text-center">
                    <a href="{{ route('patient-reports.report') }}" class="btn btn-secondary"
                        style="border-radius: 6px 0 0 6px">
                        <em class="icon ni ni-arrow-left"></em> &nbsp; Back to Reports
                    </a>
                    <button onclick="window.print()" class="btn btn-primary">
                        <em class="icon ni ni-printer"></em>&nbsp; Print Report
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {

            .btn,
            .nav-tabs,
            .tab-content>.tab-pane:not(.active) {
                display: none;
            }

            .card {
                border: none;
            }

            .table {
                border: 1px solid #000;
            }

            .table-bordered th,
            .table-bordered td {
                border: 1px solid #000;
            }

            .nk-block-head {
                display: none;
            }

            .text-center:last-child {
                display: none;
            }

            .card-bordered {
                border: 1px solid #000 !important;
            }
        }
    </style>

    @push('scripts')
        <script>
            // Initialize tabs
            $(document).ready(function() {
                $('#patientTabs a').on('click', function(e) {
                    e.preventDefault();
                    $(this).tab('show');
                });
            });
        </script>
    @endpush
@endsection
