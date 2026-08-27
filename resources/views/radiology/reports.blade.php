@extends('layouts.app')

@section('title', 'Radiology Reports')
@section('page-title', 'Radiology Reports')

@section('content')
<div class="nk-block nk-block-lg">
    <div class="card card-preview">
        <div class="card-inner">
            <!-- Filter Form -->
            <div class="card-inner border-bottom no-print">
                <form action="{{ route('radiology.reports') }}" method="GET" class="form-validate" id="reportForm">
                    @csrf
                    <div class="row g-3">
                        <!-- From Date -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">From Date</label>
                                <input type="date" class="form-control" name="from_date"
                                       value="{{ $fromDate }}" id="fromDate">
                            </div>
                        </div>

                        <!-- To Date -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">To Date</label>
                                <input type="date" class="form-control" name="to_date"
                                       value="{{ $toDate }}" id="toDate">
                            </div>
                        </div>

                        <!-- Patient Search Dropdown -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Patient (Optional)</label>
                                <select name="patient_id" class="form-control js-select2"
                                        id="patientSelect" data-placeholder="Select Patient (All patients)">
                                    <option value="">All Patients</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}"
                                            {{ $patientId == $patient->id ? 'selected' : '' }}>
                                            {{ $patient->name }} ({{ $patient->patient_id }}) - {{ $patient->mobile }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Search Input -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Search (Name/ID/Mobile)</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search"
                                           value="{{ $search }}" placeholder="Search...">
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="col-md-12">
                            <div class="form-group mt-2">
                                <button type="submit" name="search_btn" value="1" class="btn btn-primary">
                                    <em class="icon ni ni-search"></em> Search
                                </button>
                                <button type="button" class="btn btn-info" onclick="printReportData()">
                                    <em class="icon ni ni-printer"></em> Print Report
                                </button>
                                <a href="{{ route('radiology.reports') }}" class="btn btn-secondary">
                                    <em class="icon ni ni-reload"></em> Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Results Section -->
            <div class="card-inner" id="resultsSection">
                @if($searchPerformed)
                    @if($totalRecords > 0)
                        <!-- OP Patients Results -->
                        @if($opResults->count() > 0)
                            <div class="mb-5">
                                <h6 class="title mb-3 border-bottom pb-2">
                                    <i class="fas fa-user-md text-primary mr-2"></i>
                                    OP Patients Radiology Tests ({{ $opResults->total() }})
                                </h6>

                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr class="bg-light">
                                                <th>Token No</th>
                                                <th>Patient Details</th>
                                                <th>Doctor</th>
                                                <th>Date & Time</th>
                                                <th>Tests Count</th>
                                                <th>Total Amount</th>
                                                <th class="no-print">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($opResults as $result)
                                                @php
                                                    $tests = $result->radiologyTests;
                                                    $totalAmount = $tests->sum('price');
                                                @endphp
                                                <tr>
                                                    <td><strong>#{{ $result->token_number }}</strong></td>
                                                    <td>
                                                        <strong>{{ $result->patient->name }}</strong><br>
                                                        <small class="text-muted">
                                                            ID: {{ $result->patient->patient_id }} |
                                                            {{ $result->patient->mobile }} |
                                                            {{ $result->patient->age }}y, {{ $result->patient->sex }}
                                                        </small>
                                                    </td>
                                                    <td>{{ $result->medicalOfficer->name ?? 'N/A' }}</td>
                                                    <td>{{ $result->created_at->format('d/m/Y h:i A') }}</td>
                                                    <td>
                                                        <span class="badge badge-dim bg-primary">{{ $tests->count() }}</span>
                                                        <br>
                                                        <small class="text-muted">
                                                            Completed: {{ $tests->where('status', 'completed')->count() }}<br>
                                                            Pending: {{ $tests->where('status', 'pending')->count() }}
                                                        </small>
                                                    </td>
                                                    <td>₹{{ number_format($totalAmount, 2) }}</td>
                                                    <td class="no-print">
                                                        <a href="{{ route('radiology.op.show', $result) }}"
                                                           class="btn btn-sm btn-primary" title="View">
                                                            <em class="icon ni ni-eye"></em>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                @if($opResults->hasPages())
                                    <div class="mt-3 no-print">
                                        {{ $opResults->appends(request()->except(['page', 'op_page']))->links() }}
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- IP Patients Results -->
                        @if($ipResults->count() > 0)
                            <div class="mb-5">
                                <h6 class="title mb-3 border-bottom pb-2">
                                    <i class="fas fa-procedures text-info mr-2"></i>
                                    IP Patients Radiology Tests ({{ $ipResults->total() }})
                                </h6>

                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr class="bg-light">
                                                <th>IP No</th>
                                                <th>Patient Details</th>
                                                <th>Admission Date</th>
                                                <th>Doctor</th>
                                                <th>Tests Count</th>
                                                <th>Total Amount</th>
                                                <th class="no-print">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($ipResults as $result)
                                                @php
                                                    $tests = $result->radiologyTests;
                                                    $totalAmount = $tests->sum('price');
                                                @endphp
                                                <tr>
                                                    <td><strong>{{ $result->hospital_ip_no }}</strong></td>
                                                    <td>
                                                        <strong>{{ $result->patient->name }}</strong><br>
                                                        <small class="text-muted">
                                                            ID: {{ $result->patient->patient_id }} |
                                                            {{ $result->patient->mobile }} |
                                                            {{ $result->patient->age }}y, {{ $result->patient->sex }}
                                                        </small>
                                                    </td>
                                                    <td>{{ $result->date_of_admission->format('d/m/Y') }}</td>
                                                    <td>{{ $result->doctor->name ?? 'N/A' }}</td>
                                                    <td>
                                                        <span class="badge badge-dim bg-primary">{{ $tests->count() }}</span>
                                                        <br>
                                                        <small class="text-muted">
                                                            Completed: {{ $tests->where('status', 'completed')->count() }}<br>
                                                            Pending: {{ $tests->where('status', 'pending')->count() }}
                                                        </small>
                                                    </td>
                                                    <td>₹{{ number_format($totalAmount, 2) }}</td>
                                                    <td class="no-print">
                                                        <a href="{{ route('radiology.ip.show', $result) }}"
                                                           class="btn btn-sm btn-primary" title="View">
                                                            <em class="icon ni ni-eye"></em>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                @if($ipResults->hasPages())
                                    <div class="mt-3 no-print">
                                        {{ $ipResults->appends(request()->except(['page', 'ip_page']))->links() }}
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Manual Tests Results -->
                        @if($manualResults->count() > 0)
                            <div class="mb-5">
                                <h6 class="title mb-3 border-bottom pb-2">
                                    <i class="fas fa-file-medical text-success mr-2"></i>
                                    Manual Radiology Tests ({{ $manualResults->total() }})
                                </h6>

                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr class="bg-light">
                                                <th>Bill No</th>
                                                <th>Patient Details</th>
                                                <th>Created By</th>
                                                <th>Date & Time</th>
                                                <th>Tests Count</th>
                                                <th>Total Amount</th>
                                                <th>Status</th>
                                                <th class="no-print">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($manualResults as $result)
                                                <tr>
                                                    <td><strong>{{ $result->reference_no }}</strong></td>
                                                    <td>
                                                        <strong>{{ $result->patient->name }}</strong><br>
                                                        <small class="text-muted">
                                                            ID: {{ $result->patient->patient_id }} |
                                                            {{ $result->patient->mobile }} |
                                                            {{ $result->patient->age }}y, {{ $result->patient->sex }}
                                                        </small>
                                                    </td>
                                                    <td>{{ $result->user->name ?? 'N/A' }}</td>
                                                    <td>{{ $result->created_at->format('d/m/Y h:i A') }}</td>
                                                    <td>
                                                        <span class="badge badge-dim bg-primary">{{ $result->items->count() }}</span>
                                                        <br>
                                                        <small class="text-muted">
                                                            Completed: {{ $result->items->where('status', 'completed')->count() }}<br>
                                                            Pending: {{ $result->items->where('status', 'pending')->count() }}
                                                        </small>
                                                    </td>
                                                    <td>₹{{ number_format($result->total_amount, 2) }}</td>
                                                    <td>
                                                        @if($result->test_status == 'completed')
                                                            <span class="badge badge-success">Completed</span>
                                                        @elseif($result->test_status == 'pending')
                                                            <span class="badge badge-warning">Pending</span>
                                                        @else
                                                            <span class="badge badge-secondary">{{ ucfirst($result->test_status) }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="no-print">
                                                        <a href="{{ route('manual-radiology-tests.show', $result) }}"
                                                           class="btn btn-sm btn-primary" title="View">
                                                            <em class="icon ni ni-eye"></em>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                @if($manualResults->hasPages())
                                    <div class="mt-3 no-print">
                                        {{ $manualResults->appends(request()->except(['page', 'manual_page']))->links() }}
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Summary -->
                        <div class="alert alert-light mt-4">
                            <div class="row">
                                <div class="col-md-3">
                                    <strong>Total Records:</strong> {{ $totalRecords }}
                                </div>
                                <div class="col-md-3">
                                    <strong>OP Tests:</strong> {{ $opResults->total() ?? 0 }}
                                </div>
                                <div class="col-md-3">
                                    <strong>IP Tests:</strong> {{ $ipResults->total() ?? 0 }}
                                </div>
                                <div class="col-md-3">
                                    <strong>Manual Tests:</strong> {{ $manualResults->total() ?? 0 }}
                                </div>
                            </div>
                        </div>

                    @else
                        <!-- No results found -->
                        <div class="text-center py-5">
                            <em class="icon ni ni-search text-muted" style="font-size: 3rem;"></em>
                            <h5 class="mt-3">No records found</h5>
                            <p class="text-muted">Try changing your search criteria</p>
                            <div class="mt-3">
                                <a href="{{ route('radiology.reports') }}" class="btn btn-primary">
                                    <em class="icon ni ni-reload"></em> Clear Filters
                                </a>
                            </div>
                        </div>
                    @endif
                @else
                    <!-- Initial state - no search performed yet -->
                    <div class="text-center py-5">
                        <em class="icon ni ni-search text-muted" style="font-size: 3rem;"></em>
                        <h5 class="mt-3">Search Radiology Reports</h5>
                        <p class="text-muted">Please use the filters above and click "Search" to find radiology reports</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .js-select2 + .select2-container {
        width: 100% !important;
    }
    .alert-light {
        background-color: #f8f9fa;
        border-color: #e9ecef;
    }
    .title {
        color: #526484;
        font-weight: 600;
    }
    .badge-dim {
        opacity: 0.9;
    }
    .no-print {
        display: block;
    }

    @media print {
        .no-print {
            display: none !important;
        }
        body {
            margin: 0;
            padding: 10px;
            font-size: 12px;
        }
        .table {
            border-collapse: collapse;
            width: 100%;
            font-size: 11px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 5px;
        }
        .table th {
            background-color: #f5f5f5;
        }
        h6.title {
            font-size: 14px;
            margin-bottom: 10px;
        }
        .alert-light {
            padding: 8px;
            margin: 10px 0;
            font-size: 11px;
        }
        .nk-block, .card, .card-inner {
            border: none !important;
            box-shadow: none !important;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize Select2 for patient dropdown
    $('#patientSelect').select2({
        placeholder: "Select Patient (All patients)",
        allowClear: true
    });

    // Date validation
    $('#fromDate, #toDate').on('change', function() {
        var fromDate = $('#fromDate').val();
        var toDate = $('#toDate').val();

        if (fromDate && toDate && new Date(toDate) < new Date(fromDate)) {
            alert('To Date cannot be before From Date');
            $('#toDate').val('');
        }
    });

    // Show loading indicator when form is submitted
    $('#reportForm').on('submit', function() {
        $('button[type="submit"]').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Searching...').prop('disabled', true);
    });
});

// Print Report Function - Only prints results data
function printReportData() {
    // Get current date and time
    var currentDate = new Date().toLocaleString();

    // Get filter values for the print header
    var fromDate = $('#fromDate').val() || 'All';
    var toDate = $('#toDate').val() || 'All';
    var patientName = $('#patientSelect option:selected').text() || 'All Patients';
    var searchTerm = $('input[name="search"]').val() || '';

    // Create a new window for printing
    var printWindow = window.open('', '_blank', 'width=900,height=600');

    // Get the results section HTML
    var resultsHTML = document.getElementById('resultsSection').innerHTML;

    // Create print document
    var printContent = `
        <!DOCTYPE html>
        <html>
        <head>
            <title>Radiology Reports - Print</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 20px;
                    font-size: 12px;
                }
                .print-header {
                    text-align: center;
                    margin-bottom: 20px;
                    border-bottom: 2px solid #333;
                    padding-bottom: 10px;
                }
                .print-header h2 {
                    margin: 0;
                    color: #333;
                    font-size: 18px;
                }
                .print-header h3 {
                    margin: 5px 0;
                    color: #666;
                    font-size: 14px;
                }
                .print-info {
                    margin-bottom: 15px;
                    font-size: 11px;
                }
                .print-info table {
                    width: 100%;
                    border-collapse: collapse;
                }
                .print-info td {
                    padding: 3px;
                    border: none;
                }
                .section-title {
                    background-color: #f5f5f5;
                    padding: 8px;
                    margin: 15px 0 10px 0;
                    border-left: 4px solid #007bff;
                    font-weight: bold;
                    font-size: 13px;
                }
                .table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 15px;
                    font-size: 11px;
                }
                .table th {
                    background-color: #f8f9fa;
                    border: 1px solid #dee2e6;
                    padding: 6px;
                    text-align: left;
                    font-weight: bold;
                }
                .table td {
                    border: 1px solid #dee2e6;
                    padding: 5px;
                    vertical-align: top;
                }
                .badge {
                    display: inline-block;
                    padding: 2px 6px;
                    font-size: 10px;
                    border-radius: 3px;
                    margin: 1px;
                }
                .badge-primary {
                    background-color: #007bff;
                    color: white;
                }
                .badge-success {
                    background-color: #28a745;
                    color: white;
                }
                .badge-warning {
                    background-color: #ffc107;
                    color: #212529;
                }
                .badge-secondary {
                    background-color: #6c757d;
                    color: white;
                }
                .summary {
                    margin-top: 20px;
                    padding: 10px;
                    background-color: #f8f9fa;
                    border: 1px solid #dee2e6;
                    font-size: 11px;
                }
                .summary .row {
                    display: flex;
                    justify-content: space-between;
                }
                .summary .col {
                    flex: 1;
                    padding: 0 5px;
                }
                .footer {
                    margin-top: 20px;
                    text-align: right;
                    font-size: 10px;
                    color: #666;
                    border-top: 1px solid #ddd;
                    padding-top: 10px;
                }
                @page {
                    size: A4 portrait;
                    margin: 15mm;
                }
                @media print {
                    body {
                        margin: 0;
                        padding: 0;
                    }
                }
            </style>
        </head>
        <body onload="window.print();">
            <div class="print-header">
                <h2>RADIOLOGY REPORTS</h2>
                <h3>Radiology Department</h3>
            </div>

            <div class="print-info">
                <table>
                    <tr>
                        <td><strong>Report Date:</strong> ${currentDate}</td>
                        <td><strong>Date Range:</strong> ${fromDate} to ${toDate}</td>
                    </tr>
                    <tr>
                        <td><strong>Patient:</strong> ${patientName}</td>
                        <td><strong>Search Term:</strong> ${searchTerm || 'None'}</td>
                    </tr>
                </table>
            </div>

            ${resultsHTML}

            <div class="footer">
                <p>Printed on: ${currentDate}</p>
                <p>Printed by: {{ auth()->user()->name ?? 'System' }}</p>
            </div>
        </body>
        </html>
    `;

    // Write content to print window
    printWindow.document.open();
    printWindow.document.write(printContent);
    printWindow.document.close();

    // Focus on the print window
    printWindow.focus();
}
</script>
@endpush
