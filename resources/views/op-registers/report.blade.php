    @extends('layouts.app')

    @section('title', 'OP Details Report')
    @section('page-title', 'OP Details Report')

    @section('content')
        <div class="nk-block nk-block-lg">
            <div class="card card-preview">
                <div class="card-inner">
                    <!-- Filters -->
                    <div class="card-inner border-bottom">
                        <form action="{{ route('report') }}" method="GET" class="row g-3" id="filterForm">
                            <div class="col-md-2">
                                <label class="form-label">From Date</label>
                                <input type="date" class="form-control" name="from_date"
                                    value="{{ request('from_date') }}" id="fromDate">
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
                                    @foreach ($patients as $patient)
                                        <option value="{{ $patient->id }}"
                                            {{ request('patient_id') == $patient->id ? 'selected' : '' }}>
                                            {{ $patient->patient_id }} - {{ $patient->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Doctor</label>
                                <select class="form-control select2-search" name="medical_officer_id" id="doctorSelect"
                                    data-placeholder="Select doctor...">
                                    <option value=""></option>
                                    @foreach ($doctors as $doctor)
                                        <option value="{{ $doctor->id }}"
                                            {{ request('medical_officer_id') == $doctor->id ? 'selected' : '' }}>
                                            Dr. {{ $doctor->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">OP Number</label>
                                <input type="text" class="form-control" name="op_no" value="{{ request('op_no') }}"
                                    placeholder="OP Number">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Token No</label>
                                <input type="text" class="form-control" name="token_number"
                                    value="{{ request('token_number') }}" placeholder="Token Number">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Status</label>
                                <select class="form-control" name="status">
                                    <option value="">All Status</option>
                                    <option value="registered" {{ request('status') == 'registered' ? 'selected' : '' }}>
                                        Registered</option>
                                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>
                                        In
                                        Progress</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                                        Completed</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                                        Cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-4 d-flex align-items-end gap-2 mt-3" style="margin-top: 7% !important;">
                                <button type="submit" class="btn btn-primary" style="border-radius: 6px 0 0 6px">
                                    <em class="icon ni ni-search"></em> Filter
                                </button>
                                <button type="button" class="btn btn-secondary" id="resetFilters"
                                    style="border-radius: 0 6px 6px 0">
                                    <em class="icon ni ni-reload"></em> Reset
                                </button>
                                <button type="button" class="btn btn-success" id="printReport" style="border-radius: 6px">
                                    <em class="icon ni ni-printer"></em> Print
                                </button>
                                <button type="button" class="btn btn-secondary" id="fromPrintReport"
                                    style="border-radius: 6px">
                                    <em class="icon ni ni-printer"></em> Form
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Results -->
                    @if (request()->hasAny(['from_date', 'to_date', 'patient_id', 'medical_officer_id', 'op_no', 'token_number', 'status']))
                        <div class="table-responsive">
                            <table class="table table-hover" id="reportTable">
                                <thead>
                                    <tr class="table-light">
                                        <th>OP No</th>
                                        <th>Date</th>
                                        <th>Token No</th>
                                        <th>Patient Details</th>
                                        <th>Doctor</th>
                                        <th>Vitals</th>
                                        <th>Radiology Tests</th>
                                        <th>Lab Tests</th>
                                        <th>Total Amount</th>
                                        <th>Paid Amount</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($opRegisters as $opRegister)
                                        @php
                                            $radiologyTotal = $opRegister->radiologyTests->sum('price');
                                            $radiologyPaid = $opRegister->radiologyTests->sum('paid_amount');
                                            $labTotal = $opRegister->labTests->sum('price');
                                            $labPaid = $opRegister->labTests->sum('paid_amount');
                                            $totalAmount = $radiologyTotal + $labTotal;
                                            $totalPaid = $radiologyPaid + $labPaid;
                                        @endphp
                                        <tr>
                                            <td><strong>{{ $opRegister->op_no ?? 'N/A' }}</strong></td>
                                            <td>{{ $opRegister->created_at->format('d M Y') }}</td>
                                            <td><span
                                                    class="badge badge-dim bg-primary">#{{ $opRegister->token_number }}</span>
                                            </td>
                                            <td>
                                                <div class="user-info">
                                                    <span class="lead-text">{{ $opRegister->patient->name }}</span>
                                                    <span class="sub-text">ID:
                                                        {{ $opRegister->patient->patient_id }}</span>
                                                    <span
                                                        class="sub-text d-block">{{ $opRegister->patient->mobile ?? ($opRegister->patient->phone ?? 'N/A') }}</span>
                                                    <span class="sub-text"><small>Age:
                                                            {{ $opRegister->patient->age }}/{{ ucfirst($opRegister->patient->sex) }}</small></span>
                                                </div>
                                            </td>
                                            <td>{{ $opRegister->medicalOfficer->name ?? 'N/A' }}</td>
                                            <td>
                                                @if ($opRegister->weight || $opRegister->height || $opRegister->bp || $opRegister->pluse)
                                                    <small class="d-block">W: {{ $opRegister->weight ?? 'N/A' }}</small>
                                                    <small class="d-block">H: {{ $opRegister->height ?? 'N/A' }}</small>
                                                    <small class="d-block">BP: {{ $opRegister->bp ?? 'N/A' }}</small>
                                                    <small class="d-block">P: {{ $opRegister->pluse ?? 'N/A' }}</small>
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span
                                                    class="badge badge-dim bg-info">{{ $opRegister->radiologyTests->count() }}</span>
                                                <small class="d-block">₹{{ number_format($radiologyTotal, 2) }}</small>
                                                <small class="text-muted">Paid:
                                                    ₹{{ number_format($radiologyPaid, 2) }}</small>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge badge-dim bg-warning">{{ $opRegister->labTests->count() }}</span>
                                                <small class="d-block">₹{{ number_format($labTotal, 2) }}</small>
                                                <small class="text-muted">Paid: ₹{{ number_format($labPaid, 2) }}</small>
                                            </td>
                                            <td><strong
                                                    class="text-success">₹{{ number_format($totalAmount, 2) }}</strong>
                                            </td>
                                            <td><strong class="text-primary">₹{{ number_format($totalPaid, 2) }}</strong>
                                            </td>
                                            <td>
                                                @php
                                                    $statusColors = [
                                                        'registered' => 'secondary',
                                                        'in_progress' => 'info',
                                                        'completed' => 'success',
                                                        'cancelled' => 'danger',
                                                    ];
                                                @endphp
                                                <span
                                                    class="badge badge-dim bg-{{ $statusColors[$opRegister->status] ?? 'secondary' }}">
                                                    {{ ucfirst(str_replace('_', ' ', $opRegister->status)) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group">

                                                    <a href="{{ route('op-registers.preview', $opRegister) }}"
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
                                                    <span class="ms-1">No records found. Try different filter
                                                        criteria.</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="alert alert-light">
                                <em class="icon ni ni-search text-muted" style="font-size: 48px;"></em>
                                <h5 class="mt-3 mb-2">No filters applied</h5>
                                <p class="text-muted">Use the filters above to search for OP register records.</p>
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
                    window.location.href = '{{ route('report') }}';
                });

                // Set max date for To Date
                $('#fromDate').on('change', function() {
                    $('#toDate').attr('min', $(this).val());
                });

                // Print Report - only show when there are results
                @if (isset($opRegisters) && $opRegisters->count() > 0)
                    $('#printReport').on('click', function() {
                        const printWindow = window.open('', '_blank');
                        const filters = {
                            fromDate: $('#fromDate').val() || 'All',
                            toDate: $('#toDate').val() || 'All',
                            patient: $('#patientSelect option:selected').text() || 'All',
                            doctor: $('#doctorSelect option:selected').text() || 'All',
                            opNo: $('input[name="op_no"]').val() || 'All',
                            tokenNo: $('input[name="token_number"]').val() || 'All',
                            status: $('select[name="status"] option:selected').text() || 'All'
                        };

                        printWindow.document.write(`
                        <html>
                            <head>
                                <title>OP Details Report - {{ date('d M Y') }}</title>
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
                                </style>
                            </head>
                            <body>
                                <h1>OP Details Report</h1>
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
                                            <span class="filter-label">OP No:</span> ${filters.opNo}
                                        </div>
                                        <div class="filter-item">
                                            <span class="filter-label">Token No:</span> ${filters.tokenNo}
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
            // From Print Report - Exact Clinic Register Format with all filtered records
$('#fromPrintReport').on('click', function() {
    const printWindow = window.open('', '_blank');
    const filters = {
        fromDate: $('#fromDate').val() || 'All',
        toDate: $('#toDate').val() || 'All',
        patient: $('#patientSelect option:selected').text() || 'All',
        doctor: $('#doctorSelect option:selected').text() || 'All',
        opNo: $('input[name="op_no"]').val() || 'All',
        tokenNo: $('input[name="token_number"]').val() || 'All',
        status: $('select[name="status"] option:selected').text() || 'All'
    };

    let tableRows = '';
    let slNo = 1;

    @if(isset($opRegisters) && $opRegisters->count() > 0)
        @foreach($opRegisters as $opRegister)
            tableRows += `
                <tr>
                    <td>${slNo++}.</td>
                    <td>
                        {{ addslashes($opRegister->patient->name ?? 'N/A') }}
                        @if($opRegister->patient->address)<br>{{ addslashes($opRegister->patient->address) }}@endif
                    </td>
                    <td>{{ $opRegister->patient->mobile ?? ($opRegister->patient->phone ?? 'N/A') }}</td>
                    <td>{{ $opRegister->patient->age ?? 'N/A' }}</td>
                    <td>{{ ucfirst($opRegister->patient->sex ?? 'N/A') }}</td>
                    <td>{{ $opRegister->token_number ?? 'N/A' }}</td>
                    <td>{{ addslashes($opRegister->provisional_diagnosis ?? 'N/A') }}</td>
                    <td>
                        {{ addslashes($opRegister->investigations ?? 'N/A') }}
                        @if($opRegister->labTests->count() > 0)<br>Lab Tests: {{ $opRegister->labTests->count() }}@endif
                        @if($opRegister->radiologyTests->count() > 0)<br>Radiology: {{ $opRegister->radiologyTests->count() }}@endif
                    </td>
                    <td>{{ addslashes($opRegister->final_diagnosis ?? 'N/A') }}</td>
                    <td>
                        {{ addslashes($opRegister->treatment ?? 'N/A') }}
                        @if($opRegister->medicines->count() > 0)<br>Medicines: {{ $opRegister->medicines->count() }}@endif
                    </td>
                    <td>{{ $opRegister->result ?? 'Cured' }}</td>
                    <td>{{ $opRegister->additional_info ?? '-' }}</td>
                    <td>
                        @if($opRegister->medicalOfficer)
                            {{ strtoupper(substr($opRegister->medicalOfficer->name, 0, 3)) }}
                        @else
                            N/A
                        @endif
                    </td>
                </tr>
            `;
        @endforeach

        // Add empty rows to fill the page (optional)
        for(let i = {{ $opRegisters->count() }}; i < 15; i++) {
            tableRows += `
                <tr>
                    <td>${i+1}.</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            `;
        }
    @else
        tableRows = `<tr><td colspan="13" style="text-align: center;">No records found</td></tr>`;
    @endif

    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Clinic Register - {{ date('d/m/Y') }}</title>
            <style>
                @page {
                    size: A4 landscape;
                    margin: 10mm 15mm;
                }
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 0;
                }
                .page {
                    width: 100%;
                    padding: 5px;
                    box-sizing: border-box;
                }
                .top-row {
                    display: flex;
                    justify-content: space-between;
                    font-size: 15px;
                    margin-bottom: 10px;
                }
                h3, h4, p {
                    text-align: left;
                    margin: 4px 0;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    font-size: 11px;
                }
                th, td {
                    border: 1px solid #000;
                    padding: 4px;
                    vertical-align: top;
                }
                th {
                    text-align: center;
                    font-weight: bold;
                }
                .note {
                    margin-top: 10px;
                    font-size: 14px;
                }
                .filter-info {
                    margin-bottom: 15px;
                    padding: 8px;
                    background-color: #f9f9f9;
                    border: 1px solid #ddd;
                    font-size: 11px;
                }
                .filter-row {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 15px;
                }
                .filter-label {
                    font-weight: bold;
                }
            </style>
        </head>
        <body>
            <div class="page">
                <div class="top-row">
                    <div>System of Medicine: Allopathy</div>
                    <div><b>CLINIC / CONSULTING ROOM</b></div>
                    <div>Tamil Nadu Clinical Establishment Regulation Act Registration no. : TN/CLN/2024/OP001</div>
                </div>

                <p><b>Name of the Doctor</b> : &nbsp; &nbsp; ${filters.doctor}</p>
                <p><b>Register of Patients (Outpatient)</b></p>
                <p><b>Date</b> : ${filters.fromDate != 'All' ? filters.fromDate : 'All'} to ${filters.toDate != 'All' ? filters.toDate : 'All'}</p>

                <table>
                    <thead>
                        <tr>
                            <th>Serial No</th>
                            <th>Name of the Patient and address</th>
                            <th>Mobile No. / Contact No. if available</th>
                            <th>Age</th>
                            <th>Sex</th>
                            <th>Token No.</th>
                            <th>Provisional Diagnosis</th>
                            <th>Investigations if any</th>
                            <th>Final diagnosis</th>
                            <th>Treatment</th>
                            <th>Result<br>Cured / Same condition / Referred</th>
                            <th>Additional information if any</th>
                            <th>Initial of the Medical officer</th>
                         </tr>
                        <tr>
                            <th>(1)</th><th>(2)</th><th>(3)</th><th>(4)</th><th>(5)</th><th>(6)</th>
                            <th>(7)</th><th>(8)</th><th>(9)</th><th>(10)</th><th>(11)</th><th>(12)</th><th>(13)</th>
                         </tr>
                    </thead>
                    <tbody>
                        ${tableRows}
                    </tbody>
                </table>

                <p class="note">
                    Note: If electronic records are maintained and / or existing registers capture this information,
                    a monthly print outs / copy shall be taken authenticated by the Hospital authorities.
                </p>
            </div>

            <script>
                window.onload = function() {
                    setTimeout(function() {
                        window.print();
                    }, 500);
                };
                document.addEventListener('keydown', function(e) {
                    if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                        e.preventDefault();
                        window.print();
                    }
                });
            <\/script>
        </body>
        </html>
    `);

    printWindow.document.close();
    printWindow.focus();
    setTimeout(() => {
        printWindow.print();
    }, 500);
});
        </script>
    @endpush
