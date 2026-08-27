@extends('layouts.app')

@section('title', 'Pharmacy - IP Report')
@section('page-title', 'In Patient Pharmacy Report')

@section('content')
<div class="nk-block nk-block-lg">
    <!-- View IP Details Modal -->
    <div class="modal fade" id="viewIpModal" tabindex="-1" aria-labelledby="viewIpModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewIpModalLabel">IP Patient Details</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="ipModalBody">
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
            <form method="GET" action="{{ route('pharmacy.reports.ip') }}" class="row g-3" id="filterForm">
                <div class="col-md-3">
                    <label class="form-label">From Date</label>
                    <input type="date" class="form-control" name="from_date"
                           value="{{ $fromDate }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">To Date</label>
                    <input type="date" class="form-control" name="to_date"
                           value="{{ $toDate }}" required>
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary mr-2">
                        <em class="icon ni ni-filter"></em> Filter
                    </button>
                        <a href="{{ route('pharmacy.reports.ip.print', ['from_date' => $fromDate, 'to_date' => $toDate]) }}"
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
            <h5 class="card-title">IP Pharmacy Report Summary</h5>
            <div class="row">
                <div class="col-md-3">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h6 class="card-title">Total Patients</h6>
                            <h3 class="text-primary">{{ $ipRegisters->count() }}</h3>
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
                <h4>In Patient Pharmacy Report</h4>
                <p>Period: {{ \Carbon\Carbon::parse($fromDate)->format('d/m/Y') }} to {{ \Carbon\Carbon::parse($toDate)->format('d/m/Y') }}</p>
                <p>Generated on: {{ now()->format('d/m/Y h:i A') }}</p>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3 d-print-none">
                <h5 class="card-title mb-0">IP Patient Details</h5>
                {{-- <button type="button" class="btn btn-sm btn-primary" onclick="window.print()">
                    <em class="icon ni ni-printer"></em> Print
                </button> --}}
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr class="table-light">
                            <th width="60">#</th>
                            <th width="100">Admission Date</th>
                            <th width="100">IP No</th>
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
                        @forelse($ipRegisters as $index => $ip)
                        @php
                            $medicineTotal = $ip->medicines->sum(function($m) {
                                return ($m->quantity * $m->price) - ($m->discount_amount ?? 0);
                            });
                            $labTotal = $ip->ipLabTests->sum('paid_amount');
                            $radiologyTotal = $ip->ipRadiologies->sum('paid_amount');
                            $discount = $ip->overall_discount_amount ?? 0;
                            $grandTotal = $medicineTotal + $labTotal + $radiologyTotal - $discount;
                            $paid = $ip->paid_amount ?? 0;
                            $balance = $grandTotal - $paid;
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $ip->date_of_admission->format('d/m/Y') }}</td>
                            <td>{{ $ip->hospital_ip_no ?? 'N/A' }}</td>
                            <td>
                                <strong>{{ $ip->patient->name ?? 'N/A' }}</strong><br>
                                <small>{{ $ip->patient->patient_id ?? 'N/A' }}</small>
                            </td>
                            <td class="text-right">₹{{ number_format($medicineTotal, 2) }}</td>
                            <td class="text-right">₹{{ number_format($labTotal, 2) }}</td>
                            <td class="text-right">₹{{ number_format($radiologyTotal, 2) }}</td>
                            <td class="text-right text-danger">₹{{ number_format($discount, 2) }}</td>
                            <td class="text-right text-primary"><strong>₹{{ number_format($grandTotal, 2) }}</strong></td>
                            <td class="text-right text-success">₹{{ number_format($paid, 2) }}</td>
                            <td class="text-right text-danger">₹{{ number_format($balance, 2) }}</td>
                            <td class="d-print-none">
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-outline-primary view-ip-btn"
                                            data-ip-id="{{ $ip->id }}" title="View">
                                        <em class="icon ni ni-eye"></em>
                                    </button>
                                    @if(in_array($ip->paid_status ?? '', ['paid', 'partial']))
                                    <a href="{{ route('pharmacy.ip.bill', $ip) }}" target="_blank" class="btn btn-outline-info" title="Bill">
                                        <em class="icon ni ni-printer"></em>
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="13" class="text-center py-4">
                                <div class="text-muted">
                                    <em class="icon ni ni-info text-lg"></em>
                                    <p class="mt-2">No IP records found for the selected period</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($ipRegisters->count() > 0)
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

    /* Print-only header (hidden on screen) */
    .print-header {
        display: none;
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

    /* Print styles */
    @media print {
        body * {
            visibility: hidden !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .print-table,
        .print-table * {
            visibility: visible !important;
        }

        .print-table {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            background: white !important;
        }

        .btn,
        .nav-tabs,
        .card-title:not(.print-title),
        .card-header,
        .card-footer,
        .modal,
        .modal-backdrop,
        .print-summary,
        .filter-section,
        .d-print-none {
            display: none !important;
        }

        .card {
            border: none !important;
            box-shadow: none !important;
            margin: 0 !important;
            padding: 0 !important;
            background: transparent !important;
        }

        .card-inner {
            padding: 0 !important;
            background: transparent !important;
        }

        .print-header {
            display: block !important;
            text-align: center;
            margin-bottom: 20px !important;
            padding-bottom: 10px !important;
            border-bottom: 2px solid #000 !important;
            page-break-inside: avoid !important;
        }

        .print-header h4 {
            margin: 0 0 5px 0 !important;
            font-size: 18px !important;
            font-weight: bold !important;
        }

        .print-header p {
            margin: 0 0 3px 0 !important;
            font-size: 14px !important;
        }

        table {
            border-collapse: collapse !important;
            width: 100% !important;
            font-size: 12px !important;
            page-break-inside: auto !important;
        }

        thead {
            display: table-header-group !important;
        }

        tfoot {
            display: table-footer-group !important;
        }

        tr {
            page-break-inside: avoid !important;
            page-break-after: auto !important;
        }

        th,
        td {
            border: 1px solid #000 !important;
            padding: 4px 8px !important;
            text-align: left !important;
        }

        th {
            background-color: #f5f5f5 !important;
            font-weight: bold !important;
        }

        tfoot th {
            background-color: #e0e0e0 !important;
        }

        /* Hide action and status columns in print */
        td:nth-last-child(1),
        td:nth-last-child(2),
        th:nth-last-child(1),
        th:nth-last-child(2) {
            display: none !important;
        }

        /* Ensure no background colors in print */
        .table-light,
        .table-light th {
            background-color: #f8f9fa !important;
            -webkit-print-color-adjust: exact !important;
            color-adjust: exact !important;
        }

        /* Remove any colors that might not print well */
        .text-primary,
        .text-success,
        .text-danger,
        .text-info,
        .text-warning {
            color: #000 !important;
        }

        /* Adjust column widths for print */
        td:nth-child(1) { width: 5% !important; }
        td:nth-child(2),
        td:nth-child(3) { width: 8% !important; }
        td:nth-child(4) { width: 18% !important; }
        td:nth-child(5),
        td:nth-child(6),
        td:nth-child(7),
        td:nth-child(8),
        td:nth-child(9),
        td:nth-child(10),
        td:nth-child(11) { width: 9% !important; }

        /* Force page breaks if needed */
        @page {
            margin: 0.5cm !important;
            size: portrait !important;
        }

        /* Remove any unwanted elements */
        .nk-block-lg,
        .nk-block,
        .content,
        .container,
        .row,
        .col-md-12 {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            min-width: 100% !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Handle View IP button click
    $('.view-ip-btn').on('click', function() {
        const ipId = $(this).data('ip-id');
        loadIpDetails(ipId);
    });

    // Function to load IP details via AJAX
    function loadIpDetails(ipId) {
        // Show loading in modal
        $('#ipModalBody').html(`
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Loading patient details...</p>
            </div>
        `);

        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('viewIpModal'));
        modal.show();

        // AJAX request to get IP details
        $.ajax({
            url: '/pharmacy/ip/' + ipId + '/details', // Update this route as needed
            type: 'GET',
            success: function(response) {
                $('#ipModalBody').html(response.html);
            },
            error: function(xhr) {
                $('#ipModalBody').html(`
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
