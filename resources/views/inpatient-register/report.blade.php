@extends('layouts.app')

@section('title', 'IP Details Report')
@section('page-title', 'IP Details Report')

@section('content')
    <div class="nk-block nk-block-lg">
        <div class="card card-preview">
            <div class="card-inner">
                <!-- Filters -->
                <div class="card-inner border-bottom">
                    <form action="{{ route('ip-report') }}" method="GET" class="row g-3" id="filterForm">
                        <div class="col-md-2">
                            <label class="form-label">From Date</label>
                            <input type="date" class="form-control" name="from_date" value="{{ request('from_date') }}"
                                   id="fromDate">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">To Date</label>
                            <input type="date" class="form-control" name="to_date" value="{{ request('to_date') }}"
                                   id="toDate">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Patient</label>
                            <select class="form-control select2-search" name="patient_id" id="patientSelect"
                                    data-placeholder="Select patient...">
                                <option value=""></option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}"
                                        {{ request('patient_id') == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->patient_id }} - {{ $patient->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">IP Number</label>
                            <input type="text" class="form-control" name="ip_number" value="{{ request('ip_number') }}"
                                   placeholder="IP Number">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select class="form-control" name="status">
                                <option value="">All Status</option>
                                <option value="admitted" {{ request('status') == 'admitted' ? 'selected' : '' }}>Admitted</option>
                                <option value="discharged" {{ request('status') == 'discharged' ? 'selected' : '' }}>Discharged</option>
                                <option value="transferred" {{ request('status') == 'transferred' ? 'selected' : '' }}>Transferred</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Doctor</label>
                            <select class="form-control select2-search" name="medical_officer_id" id="doctorSelect"
                                    data-placeholder="Select doctor...">
                                <option value=""></option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}"
                                        {{ request('medical_officer_id') == $doctor->id ? 'selected' : '' }}>
                                        Dr. {{ $doctor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end gap-2" style="margin-top: 7% !important;">
                            <button type="submit" class="btn btn-primary" style="border-radius: 6px 0 0 6px">
                                <em class="icon ni ni-search"></em> Filter
                            </button>
                            <button type="button" class="btn btn-secondary" id="resetFilters"
                                    style="border-radius: 0 6px 6px 0">
                                <em class="icon ni ni-reload"></em> Reset
                            </button>
                            <button type="button" class="btn btn-success" id="printReport"
                                    style="border-radius: 6px">
                                <em class="icon ni ni-printer"></em> Print
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Results -->
                @if(request()->hasAny(['from_date', 'to_date', 'patient_id', 'ip_number', 'status', 'ward_bed', 'medical_officer_id', 'search']))
                <div class="table-responsive">
                    <table class="table table-hover" id="reportTable">
                        <thead>
                            <tr class="table-light">
                                <th>IP No</th>
                                <th>Admission Date</th>
                                <th>Patient Details</th>
                                <th>Doctor</th>
                                <th>Medicines</th>
                                <th>Lab Tests</th>
                                <th>Radiology</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($inpatients as $inpatient)
                                @php
                                    // Calculate totals
                                    $medicineTotal = $inpatient->medicines->sum('price');
                                    $labTotal = $inpatient->labTests->sum('price');
                                    $radiologyTotal = $inpatient->radiologyTests->sum('price');
                                    $totalAmount = $medicineTotal + $labTotal + $radiologyTotal;

                                    // Calculate admission days
                                    $admissionDays = 0;
                                    if ($inpatient->date_of_admission) {
                                        $endDate = $inpatient->date_of_discharge ?? now();
                                        $admissionDays = \Carbon\Carbon::parse($inpatient->date_of_admission)->diffInDays($endDate) + 1;
                                    }

                                    // Determine status
                                    $statusColors = [
                                        'admitted' => 'success',
                                        'discharged' => 'primary',
                                        'transferred' => 'warning'
                                    ];
                                    $statusClass = $statusColors[$inpatient->status] ?? 'secondary';
                                @endphp
                                <tr>
                                    <td><strong>{{ $inpatient->hospital_ip_no ?? 'N/A' }}</strong></td>
                                    <td>{{ $inpatient->date_of_admission ? \Carbon\Carbon::parse($inpatient->date_of_admission)->format('d M Y') : 'N/A' }}</td>
                                    <td>
                                        <div class="user-info">
                                            <span class="lead-text">{{ $inpatient->patient->name }}</span>
                                            <span class="sub-text">ID: {{ $inpatient->patient->patient_id }}</span>
                                            <span class="sub-text d-block">{{ $inpatient->patient->mobile ?? $inpatient->patient->phone ?? 'N/A' }}</span>
                                            <span class="sub-text"><small>Age: {{ $inpatient->patient->age }}/{{ ucfirst($inpatient->patient->sex) }}</small></span>
                                        </div>
                                    </td>
                                    <td>{{ $inpatient->medicalOfficer->name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge badge-dim bg-info">{{ $inpatient->medicines->count() }}</span>
                                        <small class="d-block">₹{{ number_format($medicineTotal, 2) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-dim bg-warning">{{ $inpatient->labTests->count() }}</span>
                                        <small class="d-block">₹{{ number_format($labTotal, 2) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-dim bg-success">{{ $inpatient->radiologyTests->count() }}</span>
                                        <small class="d-block">₹{{ number_format($radiologyTotal, 2) }}</small>
                                    </td>
                                    <td><strong class="text-success">₹{{ number_format($totalAmount, 2) }}</strong></td>
                                    <td>
                                        <span class="badge badge-dim bg-{{ $statusClass }}">
                                            {{ ucfirst($inpatient->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('ip-report.print', $inpatient) }}" target="_blank"
                                                class="btn btn-sm btn-secondary" title="Print">
                                                <em class="icon ni ni-printer"></em>
                                            </a>
                                            <a href="{{ route('ip-registers.preview', $inpatient) }}"
                                                class="btn btn-sm btn-primary" title="View">
                                                <em class="icon ni ni-eye"></em>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="text-center py-4">
                                        <div class="alert alert-light">
                                            <em class="icon ni ni-info text-muted"></em>
                                            <span class="ms-1">No records found. Try different filter criteria.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if($inpatients->count() > 0)
                        @endif
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <div class="alert alert-light">
                        <em class="icon ni ni-search text-muted" style="font-size: 48px;"></em>
                        <h5 class="mt-3 mb-2">No filters applied</h5>
                        <p class="text-muted">Use the filters above to search for IP register records.</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single {
            height: 45px;
            border: 1px solid #dbdfea;
            border-radius: 4px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 43px;
            padding-left: 12px;
            padding-right: 30px;
        }
        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
            white-space: nowrap;
        }
        .user-info .lead-text {
            font-weight: 600;
            display: block;
        }
        .user-info .sub-text {
            font-size: 12px;
            color: #6c757d;
        }
        table tfoot td {
            font-weight: bold;
            background-color: #f8f9fa;
        }
        .alert-light {
            background-color: #f8f9fa;
            border-color: #e9ecef;
        }
    </style>
@endpush

@push('scripts')
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2 for patient select
            $('#patientSelect').select2({
                placeholder: "Select patient...",
                allowClear: true,
                width: '100%'
            });

            // Initialize Select2 for doctor select
            $('#doctorSelect').select2({
                placeholder: "Select doctor...",
                allowClear: true,
                width: '100%'
            });

            // Reset filters
            $('#resetFilters').on('click', function() {
                $('#filterForm')[0].reset();
                $('#patientSelect').val(null).trigger('change');
                $('#doctorSelect').val(null).trigger('change');
                // Redirect to report page without filters
                window.location.href = '{{ route("ip-report") }}';
            });

            // Set max date for To Date
            $('#fromDate').on('change', function() {
                $('#toDate').attr('min', $(this).val());
            });

            // Print Report - only show when there are results
            @if(isset($inpatients) && $inpatients->count() > 0)
            $('#printReport').on('click', function() {
                const printWindow = window.open('', '_blank');
                const filters = {
                    fromDate: $('#fromDate').val() || 'All',
                    toDate: $('#toDate').val() || 'All',
                    patient: $('#patientSelect option:selected').text() || 'All',
                    doctor: $('#doctorSelect option:selected').text() || 'All',
                    ipNumber: $('input[name="ip_number"]').val() || 'All',
                    wardBed: $('input[name="ward_bed"]').val() || 'All',
                    status: $('select[name="status"] option:selected').text() || 'All'
                };

                printWindow.document.write(`
                    <html>
                        <head>
                            <title>IP Details Report - {{ date('d M Y') }}</title>
                            <style>
                                body { font-family: Arial, sans-serif; margin: 20px; }
                                h1 { text-align: center; color: #333; margin-bottom: 10px; }
                                .filters { margin-bottom: 20px; padding: 10px; background-color: #f5f5f5; border-radius: 5px; }
                                .filter-row { display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 5px; }
                                .filter-item { flex: 1; min-width: 200px; }
                                .filter-label { font-weight: bold; color: #666; }
                                table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 12px; }
                                th { background-color: #f8f9fa; padding: 8px; border: 1px solid #ddd; text-align: left; }
                                td { padding: 6px; border: 1px solid #ddd; }
                                .text-right { text-align: right; }
                                .footer { margin-top: 30px; text-align: center; color: #666; font-size: 11px; }
                                .summary { margin-top: 20px; padding: 10px; background-color: #f8f9fa; border-radius: 5px; }
                                .summary-item { display: inline-block; margin-right: 20px; }
                                @media print {

                                }
                            </style>
                        </head>
                        <body>
                            <h1>IP Details Report</h1>
                            <div class="filters">
                                <div class="filter-row">
                                    <div class="filter-item">
                                        <span class="filter-label">Date Range:</span> ${filters.fromDate} to ${filters.toDate}
                                    </div>
                                    <div class="filter-item">
                                        <span class="filter-label">Patient:</span> ${filters.patient}
                                    </div>
                                    <div class="filter-item">
                                        <span class="filter-label">Doctor:</span> ${filters.doctor}
                                    </div>
                                </div>
                                <div class="filter-row">
                                    <div class="filter-item">
                                        <span class="filter-label">IP Number:</span> ${filters.ipNumber}
                                    </div>
                                    <div class="filter-item">
                                        <span class="filter-label">Ward/Bed:</span> ${filters.wardBed}
                                    </div>
                                    <div class="filter-item">
                                        <span class="filter-label">Status:</span> ${filters.status}
                                    </div>
                                </div>
                                <div class="filter-row">
                                    <div class="filter-item">
                                        <span class="filter-label">Generated on:</span> {{ date('d M Y h:i A') }}
                                    </div>
                                    <div class="filter-item">
                                        <span class="filter-label">Generated by:</span> {{ auth()->user()->name }}
                                    </div>
                                </div>
                            </div>
                            ${$('#reportTable').clone().find('.btn-group').remove().end()[0].outerHTML}


                        </body>
                    </html>
                `);
                printWindow.document.close();
                printWindow.focus();
                setTimeout(() => {
                    printWindow.print();
                }, 500);
            });
            @else
            // Disable print button when no results
            $('#printReport').prop('disabled', true).addClass('btn-disabled');
            @endif

            // Auto-submit on Enter in text fields
            $('input[type="text"]').on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    $('#filterForm').submit();
                }
            });
        });
    </script>
@endpush
