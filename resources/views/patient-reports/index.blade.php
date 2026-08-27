@extends('layouts.app')

@section('title', 'Patient Reports')
@section('page-title', 'Patient Reports')

@section('content')
<div class="nk-block nk-block-lg">
    <div class="card card-preview">
        <div class="card-inner">
            <!-- Filters -->
            <div class="card-inner border-bottom">
                <form action="{{ route('patient-reports.report') }}" method="GET" class="row g-3" id="filterForm">
                    
                    <div class="col-md-3">
                        <label class="form-label">From Date</label>
                        <input type="date" class="form-control" name="from_date" value="{{ request('from_date') }}" 
                               id="fromDate">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">To Date</label>
                        <input type="date" class="form-control" name="to_date" value="{{ request('to_date') }}" 
                               id="toDate">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Patient</label>
                        <select class="form-control select2-search" name="patient_id" id="patientSelect"
                                data-placeholder="Select patient...">
                            <option value=""></option>
                            @foreach($patientsForDropdown as $patient)
                                <option value="{{ $patient->id }}" 
                                    {{ request('patient_id') == $patient->id ? 'selected' : '' }}>
                                    {{ $patient->patient_id }} - {{ $patient->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Search</label>
                        <input type="text" class="form-control" name="search" value="{{ request('search') }}" 
                               placeholder="Name, ID, Phone...">
                    </div>
                    <div class="col-md-2 d-flex align-items-end gap-2" style="margin-top: 7% !important;">
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
            @if(request()->hasAny(['search', 'patient_id', 'from_date', 'to_date']))
            <div class="table-responsive">
                <table class="table table-hover" id="reportTable">
                    <thead>
                        <tr class="table-light">
                            <th>Patient ID</th>
                            <th>Patient Details</th>
                            <th>OP Visits</th>
                            <th>IP Admissions</th>
                            <th>Radiology Tests</th>
                            <th>Lab Tests</th>
                            <th>Medicines</th>
                            <th>Operations</th>
                            <th>Total Amount</th>
                            <th>Last Visit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($patients as $patient)
                        @php
                            $opVisits = $patient->opRegisters->count();
                            $ipAdmissions = $patient->inpatientRegisters->count();

                            // OP Data
                            $opRadiologyTests = $patient->opRegisters->sum(function($register) {
                                return $register->radiologyTests->count();
                            });
                            $opLabTests = $patient->opRegisters->sum(function($register) {
                                return $register->labTests->count();
                            });
                            $opMedicines = $patient->opRegisters->sum(function($register) {
                                return $register->medicines->count();
                            });
                            $opAmount = $patient->opRegisters->sum(function($register) {
                                return $register->radiologyTests->sum('price') +
                                       $register->labTests->sum('price') +
                                       $register->medicines->sum('price');
                            });

                            // IP Data
                            $ipRadiologyTests = $patient->inpatientRegisters->sum(function($register) {
                                return $register->radiologyTests->count();
                            });
                            $ipLabTests = $patient->inpatientRegisters->sum(function($register) {
                                return $register->labTests->count();
                            });
                            $ipMedicines = $patient->inpatientRegisters->sum(function($register) {
                                return $register->medicines->count();
                            });
                            $ipAmount = $patient->inpatientRegisters->sum(function($register) {
                                return $register->radiologyTests->sum('price') +
                                       $register->labTests->sum('price') +
                                       $register->medicines->sum('price');
                            });

                            // Totals
                            $totalRadiologyTests = $opRadiologyTests + $ipRadiologyTests;
                            $totalLabTests = $opLabTests + $ipLabTests;
                            $totalMedicines = $opMedicines + $ipMedicines;
                            $totalAmount = $opAmount + $ipAmount;

                            $lastVisit = $patient->opRegisters->concat($patient->inpatientRegisters)
                                ->sortByDesc('created_at')
                                ->first();
                        @endphp
                        <tr>
                            <td><strong>{{ $patient->patient_id }}</strong></td>
                            <td>
                                <div class="user-info">
                                    <span class="lead-text">{{ $patient->name }}</span>
                                    <span class="sub-text">{{ $patient->mobile ?? $patient->phone ?? 'N/A' }}</span>
                                    <span class="sub-text d-block">Age: {{ $patient->age }}, {{ ucfirst($patient->sex) }}</span>
                                    <span class="sub-text"><small>{{ $patient->address ?? 'No Address' }}</small></span>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-dim bg-primary">{{ $opVisits }}</span>
                                @if($opAmount > 0)
                                    <small class="d-block text-muted">₹{{ number_format($opAmount, 2) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-dim bg-secondary">{{ $ipAdmissions }}</span>
                                @if($ipAmount > 0)
                                    <small class="d-block text-muted">₹{{ number_format($ipAmount, 2) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-dim bg-info">{{ $totalRadiologyTests }}</span>
                            </td>
                            <td>
                                <span class="badge badge-dim bg-warning">{{ $totalLabTests }}</span>
                            </td>
                            <td>
                                <span class="badge badge-dim bg-success">{{ $totalMedicines }}</span>
                            </td>
                            <td>
                                <span class="badge badge-dim bg-danger">{{ $patient->operationRegisters->count() ?? 0 }}</span>
                            </td>
                            <td>
                                <strong class="text-success">₹{{ number_format($totalAmount, 2) }}</strong>
                            </td>
                            <td>
                                @if($lastVisit)
                                    {{ $lastVisit->created_at->format('d M Y') }}
                                    <small class="d-block text-muted">
                                        @if($lastVisit instanceof \App\Models\OpRegister)
                                            OP Visit
                                        @else
                                            IP Admission
                                        @endif
                                    </small>
                                @else
                                    <span class="text-muted">No visits</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('patient-reports.details', $patient) }}"
                                       class="btn btn-sm btn-primary" title="View Details">
                                       Details
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center py-4">
                                <div class="alert alert-light">
                                    <em class="icon ni ni-info text-muted"></em>
                                    <span class="ms-1">No patients found. Try different filter criteria.</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($patients->count() > 0)
                    @endif
                </table>
            </div>
            @else
            <div class="text-center py-5">
                <div class="alert alert-light">
                    <em class="icon ni ni-search text-muted" style="font-size: 48px;"></em>
                    <h5 class="mt-3 mb-2">No filters applied</h5>
                    <p class="text-muted">Use the filters above to search for patient records.</p>
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

            // Reset filters
            $('#resetFilters').on('click', function() {
                $('#filterForm')[0].reset();
                $('#patientSelect').val(null).trigger('change');
                // Redirect to report page without filters
                window.location.href = '{{ route("patient-reports.report") }}';
            });

            // Set max date for To Date
            $('#fromDate').on('change', function() {
                $('#toDate').attr('min', $(this).val());
            });

            // Print Report - only show when there are results
            @if(isset($patients) && $patients->count() > 0)
            $('#printReport').on('click', function() {
                const printWindow = window.open('', '_blank');
                const filters = {
                    patient: $('#patientSelect option:selected').text() || 'All',
                    fromDate: $('#fromDate').val() || 'All',
                    toDate: $('#toDate').val() || 'All',
                    search: $('input[name="search"]').val() || 'All'
                };

                printWindow.document.write(`
                    <html>
                        <head>
                            <title>Patient Reports - {{ date('d M Y') }}</title>
                            <style>
                                body { font-family: Arial, sans-serif; margin: 20px; }
                                h1 { text-align: center; color: #333; margin-bottom: 10px; }
                                .filters { margin-bottom: 20px; padding: 10px; background-color: #f5f5f5; border-radius: 5px; }
                                .filter-row { display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 5px; }
                                .filter-item { flex: 1; min-width: 200px; }
                                .filter-label { font-weight: bold; color: #666; }
                                table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 11px; }
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
                            <h1>Patient Reports</h1>
                            <div class="filters">
                                <div class="filter-row">
                                    <div class="filter-item">
                                        <span class="filter-label">Patient:</span> ${filters.patient}
                                    </div>
                                    <div class="filter-item">
                                        <span class="filter-label">Date Range:</span> ${filters.fromDate} to ${filters.toDate}
                                    </div>
                                    <div class="filter-item">
                                        <span class="filter-label">Search:</span> ${filters.search}
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
                            <div class="summary">
                                @php
                                    $totalOpAmount = $patients->sum(fn($p) => $p->opRegisters->sum(function($register) {
                                        return $register->radiologyTests->sum('price') +
                                               $register->labTests->sum('price') +
                                               $register->medicines->sum('price');
                                    }));
                                    $totalIpAmount = $patients->sum(fn($p) => $p->inpatientRegisters->sum(function($register) {
                                        return $register->radiologyTests->sum('price') +
                                               $register->labTests->sum('price') +
                                               $register->medicines->sum('price');
                                    }));
                                @endphp
                            </div>
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

            // Auto-submit on Enter in search field
            $('input[name="search"]').on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    $('#filterForm').submit();
                }
            });
        });
    </script>
@endpush