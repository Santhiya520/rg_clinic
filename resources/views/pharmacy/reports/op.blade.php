@extends('layouts.app')

@section('title', 'Pharmacy - OP Report')
@section('page-title', 'Out Patient Pharmacy Report')

@section('content')
    <div class="nk-block nk-block-lg">
        <!-- View OP Details Modal -->
        <div class="modal fade" id="viewOpModal" tabindex="-1" aria-labelledby="viewOpModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewOpModalLabel">OP Patient Details</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="opModalBody">
                        <!-- Content will be loaded via AJAX -->
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Loading patient details...</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Filter Card -->
        <div class="card card-preview">
            <div class="card-inner">
                <h5 class="card-title">Filter Report</h5>
                <form method="GET" action="{{ route('pharmacy.reports.op') }}" class="row g-3" id="filterForm">
                    <div class="col-md-3">
                        <label class="form-label">From Date</label>
                        <input type="date" class="form-control" name="from_date" value="{{ $fromDate }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">To Date</label>
                        <input type="date" class="form-control" name="to_date" value="{{ $toDate }}" required>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary mr-2">
                            <em class="icon ni ni-filter"></em> Filter
                        </button>
                        <a href="{{ route('pharmacy.reports.op.print', ['from_date' => $fromDate, 'to_date' => $toDate]) }}"
                            class="btn btn-secondary mr-2" target="_blank">
                            <em class="icon ni ni-printer"></em> Print Report
                        </a>
                        <a href="{{ route('pharmacy.index') }}" class="btn btn-outline-primary">
                            <em class="icon ni ni-arrow-left"></em> Back to Pharmacy
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Report Summary -->
        <div class="card card-preview mt-3 print-summary">
            <div class="card-inner">
                <h5 class="card-title">OP Pharmacy Report Summary</h5>
                <div class="row">
                    <div class="col-md-3">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h6 class="card-title">Total Patients</h6>
                                <h3 class="text-primary">{{ $opRegisters->count() }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h6 class="card-title">Medicines Amount</h6>
                                <h3 class="text-success">₹{{ number_format($totalMedicineAmount, 2) }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h6 class="card-title">Lab Amount</h6>
                                <h3 class="text-info">₹{{ number_format($totalLabAmount, 2) }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h6 class="card-title">Radiology Amount</h6>
                                <h3 class="text-warning">₹{{ number_format($totalRadiologyAmount, 2) }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-3">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h6 class="card-title">Total Discount</h6>
                                <h3 class="text-danger">₹{{ number_format($totalDiscount, 2) }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h6 class="card-title">Grand Total</h6>
                                <h3 class="text-dark">₹{{ number_format($grandTotalAmount, 2) }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h6 class="card-title">Total Paid</h6>
                                <h3 class="text-success">₹{{ number_format($totalPaid, 2) }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h6 class="card-title">Total Balance</h6>
                                <h3 class="text-danger">₹{{ number_format($totalBalance, 2) }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Details Table -->
        <div class="card card-preview mt-3 print-table">
            <div class="card-inner">
                <!-- Print Header (only shows when printing) -->
                <div class="print-header">
                    <h4>Out Patient Pharmacy Report</h4>
                    <p>Period: {{ \Carbon\Carbon::parse($fromDate)->format('d/m/Y') }} to
                        {{ \Carbon\Carbon::parse($toDate)->format('d/m/Y') }}</p>
                    <p>Generated on: {{ now()->format('d/m/Y h:i A') }}</p>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3 d-print-none">
                    <h5 class="card-title mb-0">OP Patient Details</h5>
                    {{-- <button type="button" class="btn btn-sm btn-primary" onclick="window.print()">
                    <em class="icon ni ni-printer"></em> Print
                </button> --}}
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="table-light">
                                <th width="60">#</th>
                                <th width="100">Date</th>
                                <th width="100">Token No</th>
                                <th>Patient Details</th>
                                <th width="100">Medicines</th>
                                <th width="100">Lab</th>
                                <th width="100">Radiology</th>
                                <th width="100">Discount</th>
                                <th width="100">Total</th>
                                <th width="100">Paid</th>
                                <th width="100">Balance</th>
                                <th width="80" class="d-print-none">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($opRegisters as $index => $op)
                                @php
                                    $medicineTotal = $op->medicines->sum(function ($m) {
                                        return $m->quantity * $m->price - ($m->discount_amount ?? 0);
                                    });
                                    $labTotal = $op->labTests->sum('paid_amount');
                                    $radiologyTotal = $op->radiologies->sum('paid_amount');
                                    $doctorFees = $op->medicalOfficer->consulting_fee ?? 0;
                                    $discount = $op->overall_discount_amount ?? 0;
                                    $grandTotal =
                                        $medicineTotal + $labTotal + $radiologyTotal + $doctorFees - $discount;
                                    $paid = $op->paid_amount ?? 0;
                                    $balance = $grandTotal - $paid;
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $op->date->format('d/m/Y') }}</td>
                                    <td>{{ $op->token_number ?? 'N/A' }}</td>
                                    <td>
                                        <strong>{{ $op->patient->name ?? 'N/A' }}</strong><br>
                                        <small>{{ $op->patient->patient_id ?? 'N/A' }}</small>
                                    </td>
                                    <td class="text-right">₹{{ number_format($medicineTotal, 2) }}</td>
                                    <td class="text-right">₹{{ number_format($labTotal, 2) }}</td>
                                    <td class="text-right">₹{{ number_format($radiologyTotal, 2) }}</td>
                                    <td class="text-right text-danger">₹{{ number_format($discount, 2) }}</td>
                                    <td class="text-right text-primary">
                                        <strong>₹{{ number_format($grandTotal, 2) }}</strong></td>
                                    <td class="text-right text-success">₹{{ number_format($paid, 2) }}</td>
                                    <td class="text-right text-danger">₹{{ number_format($balance, 2) }}</td>
                                    <td class="d-print-none">
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-outline-primary view-op-btn"
                                                data-op-id="{{ $op->id }}" title="View">
                                                <em class="icon ni ni-eye"></em>
                                            </button>
                                            @if (in_array($op->paid_status ?? '', ['paid', 'partial']))
                                                <a href="{{ route('pharmacy.op.bill', $op) }}" target="_blank"
                                                    class="btn btn-outline-info" title="Bill">
                                                    <em class="icon ni ni-printer"></em>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="text-center py-4">
                                        <div class="text-muted">
                                            <em class="icon ni ni-info text-lg"></em>
                                            <p class="mt-2">No OP records found for the selected period</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if ($opRegisters->count() > 0)
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="4" class="text-right">TOTAL:</th>
                                    <th class="text-right">₹{{ number_format($totalMedicineAmount, 2) }}</th>
                                    <th class="text-right">₹{{ number_format($totalLabAmount, 2) }}</th>
                                    <th class="text-right">₹{{ number_format($totalRadiologyAmount, 2) }}</th>
                                    <th class="text-right">₹{{ number_format($totalDiscount, 2) }}</th>
                                    <th class="text-right">₹{{ number_format($grandTotalAmount, 2) }}</th>
                                    <th class="text-right">₹{{ number_format($totalPaid, 2) }}</th>
                                    <th class="text-right">₹{{ number_format($totalBalance, 2) }}</th>
                                    <th colspan="2"></th>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Default styles */
        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
        }

        .text-right {
            text-align: right;
        }

        /* Modal Styling */
        .modal-body .patient-details {
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .modal-body .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 4fr));
            gap: 15px;
        }

        .modal-body .detail-item {
            margin-bottom: 10px;
        }

        .modal-body .detail-item label {
            font-weight: bold;
            color: #495057;
            display: block;
            margin-bottom: 3px;
        }

        .modal-body .detail-item span {
            color: #212529;
        }

        /* Print-only header (hidden on screen) */
        .print-header {
            display: none;
        }

        /* Print styles */
        /* Print styles */
        @media print {
            body * {
                visibility: hidden !important;
            }

            /* Show only the report table section */
            .print-table,
            .print-table * {
                visibility: visible !important;
            }

            .print-table {
                position: absolute !important;
                left: 0 !important;
                top: 0 !important;
                width: 100% !important;
                background: white !important;
            }

            /* Hide all other elements */
            .btn,
            .nav-tabs,
            .card-header,
            .card-footer,
            .modal,
            .modal-backdrop,
            .print-summary,
            .d-print-none,
            .nk-block-header,
            .sidebar,
            .header,
            .footer,
            .no-print {
                display: none !important;
            }

            /* Style the table for print */
            .table-bordered {
                border: 1px solid #000 !important;
                border-collapse: collapse !important;
            }

            .table-bordered th,
            .table-bordered td {
                border: 1px solid #000 !important;
                padding: 6px 8px !important;
                font-size: 11px !important;
            }

            /* Ensure table header appears on each page */
            thead {
                display: table-header-group !important;
            }

            /* Ensure footer appears at bottom */
            tfoot {
                display: table-footer-group !important;
                border-top: 2px solid #000 !important;
            }

            /* Print header */
            .print-header {
                display: block !important;
                text-align: center;
                margin-bottom: 20px !important;
                padding-bottom: 10px !important;
                border-bottom: 2px solid #000 !important;
            }

            .print-header h4 {
                margin: 0 0 5px 0 !important;
                font-size: 16px !important;
                font-weight: bold !important;
            }

            .print-header p {
                margin: 0 !important;
                font-size: 12px !important;
            }

            /* Page margins */
            @page {
                margin: 1cm !important;
                size: A4 portrait !important;
            }

            /* Remove background colors for better printing */
            .table-light,
            .table-light th {
                background-color: #f8f9fa !important;
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
            }

            /* Fix text alignment for print */
            .text-right {
                text-align: right !important;
            }

            /* Hide action column */
            td:last-child,
            th:last-child {
                display: none !important;
            }

            /* Adjust column widths for better print layout */
            th:nth-child(1),
            td:nth-child(1) {
                width: 30px !important;
            }

            th:nth-child(2),
            td:nth-child(2),
            th:nth-child(3),
            td:nth-child(3) {
                width: 80px !important;
            }

            th:nth-child(4),
            td:nth-child(4) {
                width: 200px !important;
            }

            th:nth-child(5),
            td:nth-child(5),
            th:nth-child(6),
            td:nth-child(6),
            th:nth-child(7),
            td:nth-child(7),
            th:nth-child(8),
            td:nth-child(8),
            th:nth-child(9),
            td:nth-child(9),
            th:nth-child(10),
            td:nth-child(10),
            th:nth-child(11),
            td:nth-child(11) {
                width: 90px !important;
            }

            /* Ensure table fits within page */
            .table-responsive {
                overflow: visible !important;
            }

            /* Force page breaks after every 20 rows */
            tr {
                page-break-inside: avoid !important;
            }

            /* Remove card styling for print */
            .card {
                border: none !important;
                box-shadow: none !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .card-inner {
                padding: 0 !important;
                background: transparent !important;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            // Handle View OP button click
            $('.view-op-btn').on('click', function() {
                const opId = $(this).data('op-id');
                loadOpDetails(opId);
            });

            // Function to load OP details via AJAX
            function loadOpDetails(opId) {
                // Show loading in modal
                $('#opModalBody').html(`
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Loading patient details...</p>
            </div>
        `);

                // Show modal
                const modal = new bootstrap.Modal(document.getElementById('viewOpModal'));
                modal.show();

                // AJAX request to get OP details
                $.ajax({
                    url: '/pharmacy/op/' + opId + '/details',
                    type: 'GET',
                    success: function(response) {
                        $('#opModalBody').html(response.html);
                    },
                    error: function(xhr) {
                        $('#opModalBody').html(`
                    <div class="alert alert-danger">
                        <em class="icon ni ni-alert-circle"></em>
                        Error loading patient details. Please try again.
                    </div>
                `);
                    }
                });
            }
        });
    </script>
@endpush
