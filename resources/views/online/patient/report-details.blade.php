@extends('online.layouts.app')

@section('title', 'Patient Report - ' . $patient->name)

@section('content')
<div class="nk-block nk-block-lg">
    <div class="card card-preview">
        <div class="card-inner">
            <!-- Header Actions -->
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">My Medical Report</h3>
                        <div class="nk-block-des text-soft">
                            <p>Complete medical history of {{ $patient->name }}</p>
                            @if($hasFilter && $dateRange)
                                <p class="text-success"><em class="icon ni ni-calendar"></em> Showing records for: {{ $dateRange }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="nk-block-head-content">
                        @if($hasFilter)
                        <button onclick="window.print()" class="btn btn-primary">
                            <em class="icon ni ni-printer"></em>&nbsp; Print Report
                        </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Display error message if any -->
            @if(session('error'))
                <div class="alert alert-danger alert-icon">
                    <em class="icon ni ni-cross-circle"></em> {{ session('error') }}
                </div>
            @endif

            <!-- Date Filter Form -->
            <div class="card card-bordered mb-4">
                <div class="card-inner">
                    <h6 class="title mb-3">Filter by Date Range</h6>
                    <form method="GET" action="{{ route('online.patient.report') }}" class="row g-3" id="dateFilterForm">
                        <div class="col-md-4">
                            <label for="from_date" class="form-label">From Date *</label>
                            <input type="date" class="form-control" id="from_date" name="from_date"
                                   value="{{ $fromDate ?? '' }}" max="{{ date('Y-m-d') }}" required>
                            <div class="invalid-feedback">Please select from date</div>
                        </div>
                        <div class="col-md-4">
                            <label for="to_date" class="form-label">To Date *</label>
                            <input type="date" class="form-control" id="to_date" name="to_date"
                                   value="{{ $toDate ?? '' }}" max="{{ date('Y-m-d') }}" required>
                            <div class="invalid-feedback">Please select to date</div>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <div class="btn-group w-100">
                                <button type="submit" class="btn btn-primary">
                                    <em class="icon ni ni-filter"></em> Apply Filter
                                </button>
                                <a href="{{ route('online.patient.report') }}" class="btn btn-secondary">
                                    <em class="icon ni ni-reload"></em> Clear
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Quick Date Filters -->
                    <div class="mt-3">
                        <small class="text-muted">Quick Filters:</small>
                        <div class="btn-group btn-group-sm mt-1">
                            <button type="button" class="btn btn-outline-light quick-filter" data-days="7">
                                Last 7 Days
                            </button>
                            <button type="button" class="btn btn-outline-light quick-filter" data-days="30">
                                Last 30 Days
                            </button>
                            <button type="button" class="btn btn-outline-light quick-filter" data-days="90">
                                Last 3 Months
                            </button>
                            <button type="button" class="btn btn-outline-light quick-filter" data-days="365">
                                Last Year
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Show message when no filter is applied -->
            @if(!$hasFilter)
                <div class="text-center p-5 border rounded bg-light">
                    <em class="icon ni ni-calendar text-muted" style="font-size: 4rem;"></em>
                    <h4 class="mt-3">Select Date Range to View Reports</h4>
                    <p class="text-muted mb-4">Please select a date range using the filter above to view your medical records.</p>

                    <div class="alert alert-info text-left">
                        <em class="icon ni ni-info"></em>
                        <strong>Note:</strong>
                        <ul class="mt-2 mb-0 pl-3">
                            <li>Select both "From Date" and "To Date"</li>
                            <li>You can use quick filters for common time periods</li>
                            <li>Records will be displayed for the selected date range only</li>
                        </ul>
                    </div>
                </div>
            @else
                <!-- Only show the following content when filter is applied -->

                <!-- Check if any records exist for the filtered period -->
                @if($totalOpVisits == 0 && $totalIpAdmissions == 0 && $totalOperations == 0)
                    <div class="alert alert-warning alert-icon">
                        <em class="icon ni ni-info"></em>
                        <strong>No records found for the selected date range ({{ $dateRange }}).</strong>
                        Please try a different date range.
                    </div>
                @else
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
                                    <td>{{ $patient->mobile }}</td>
                                </tr>
                                <tr>
                                    <th>Age & Gender</th>
                                    <td>{{ $patient->age }} years, {{ ucfirst($patient->sex) }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $patient->email }}</td>
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
                                                        <a href="{{ route('online.patient.op.view', $register) }}"
                                                            class="btn btn-sm btn-outline-primary"
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
                                    <p class="mt-2">No OP records found for the selected date range.</p>
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
                                                    $isDischarged = !empty($register->discharge_date);
                                                @endphp
                                                <tr>
                                                    <td><strong>{{ $register->ip_no }}</strong></td>
                                                    <td>{{ $register->created_at->format('d M Y') }}</td>
                                                    <td>
                                                        @if ($isDischarged)
                                                            {{ $register->discharge_date->format('d M Y') }}
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ Str::limit($register->diagnosis, 30) }}
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
                                                        <a href="{{ route('online.patient.ip.view', $register) }}"
                                                            class="btn btn-sm btn-outline-primary"
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
                                    <p class="mt-2">No IP records found for the selected date range.</p>
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
                                                <th>Assistant</th>
                                                <th>Anesthetist</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($patient->operationRegisters as $operation)
                                                <tr>
                                                    <td><strong>OPR{{ str_pad($operation->id, 5, '0', STR_PAD_LEFT) }}</strong></td>
                                                    <td>{{ $operation->created_at->format('d M Y') }}</td>
                                                    <td>{{ $operation->operation ?? 'N/A' }}</td>
                                                    <td>{{ $operation->operatingSurgeon->name ?? 'N/A' }}</td>
                                                    <td>{{ $operation->assistantSurgeon->name ?? 'N/A' }}</td>
                                                    <td>{{ $operation->anaesthetist->name ?? 'N/A' }}</td>
                                                    <td>
                                                        <span style="padding: 3px 8px"
                                                            class="badge bg-success">
                                                            Completed
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('online.patient.operation.view', $operation) }}"
                                                            class="btn btn-sm btn-outline-primary"
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
                                    <p class="mt-2">No operation records found for the selected date range.</p>
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

                    <!-- Services Summary -->
                    <div class="card card-bordered mt-4">
                        <div class="card-inner">
                            <h5 class="title mb-3">Services Summary</h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="p-3 bg-light rounded text-center">
                                        <h3 class="text-primary">{{ $totalRadiologyTests }}</h3>
                                        <h6 class="title">Radiology Tests</h6>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 bg-light rounded text-center">
                                        <h3 class="text-info">{{ $totalLabTests }}</h3>
                                        <h6 class="title">Lab Tests</h6>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 bg-light rounded text-center">
                                        <h3 class="text-success">{{ $totalMedicines }}</h3>
                                        <h6 class="title">Medicines Prescribed</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
            <!-- End of conditional content (only shown when filter is applied) -->

        </div>
    </div>
</div>

<style>
    @media print {
        .btn,
        .nav-tabs,
        .tab-content>.tab-pane:not(.active),
        .card-bordered:first-child,
        .alert {
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
        .card-bordered {
            border: 1px solid #000 !important;
        }
    }
</style>

@push('scripts')
    <script>
        // Initialize tabs (only when they exist)
        $(document).ready(function() {
            if ($('#patientTabs').length) {
                $('#patientTabs a').on('click', function(e) {
                    e.preventDefault();
                    $(this).tab('show');
                });
            }

            // Set max date for "to_date" to today
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('to_date').max = today;
            document.getElementById('from_date').max = today;

            // Set "from_date" max to "to_date" if to_date is selected
            document.getElementById('to_date').addEventListener('change', function() {
                document.getElementById('from_date').max = this.value;
            });

            // Set "to_date" min to "from_date" if from_date is selected
            document.getElementById('from_date').addEventListener('change', function() {
                document.getElementById('to_date').min = this.value;
            });

            // Quick filter buttons
            document.querySelectorAll('.quick-filter').forEach(button => {
                button.addEventListener('click', function() {
                    const days = parseInt(this.dataset.days);
                    const toDate = new Date();
                    const fromDate = new Date();
                    fromDate.setDate(fromDate.getDate() - days);

                    document.getElementById('from_date').value = fromDate.toISOString().split('T')[0];
                    document.getElementById('to_date').value = toDate.toISOString().split('T')[0];
                    document.getElementById('dateFilterForm').submit();
                });
            });

            // Form validation
            document.getElementById('dateFilterForm').addEventListener('submit', function(e) {
                const fromDate = document.getElementById('from_date').value;
                const toDate = document.getElementById('to_date').value;

                if (!fromDate || !toDate) {
                    e.preventDefault();
                    alert('Please select both From Date and To Date.');
                    return false;
                }

                if (new Date(fromDate) > new Date(toDate)) {
                    e.preventDefault();
                    alert('From Date cannot be greater than To Date.');
                    return false;
                }

                return true;
            });
        });
    </script>
@endpush
@endsection
