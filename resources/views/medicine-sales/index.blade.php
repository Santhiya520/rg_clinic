@extends('layouts.app')

@section('title', 'Medicine Sales')
@section('page-title', 'Medicine Sales')

@section('content')
<div class="nk-block nk-block-lg">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="close" data-bs-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <div class="card card-preview">
        <div class="card-inner">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h5 class="nk-block-title">Medicine Sales List</h5>
                    </div>
                    <div class="nk-block-head-content">
                        <a href="{{ route('medicine-sales.create') }}" class="btn btn-primary" style="border-radius: 6px">
                            <em class="icon ni ni-plus"></em> &nbsp; New Sale/Use
                        </a>
                    </div>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="card-inner border-bottom mb-4">
                <form action="{{ route('medicine-sales.index') }}" method="GET" class="row g-3" id="filterForm">
                    <div class="col-md-3">
                        <label class="form-label">From Date</label>
                        <input type="date" class="form-control" name="from_date"
                            value="{{ request('from_date') }}" id="fromDate">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">To Date</label>
                        <input type="date" class="form-control" name="to_date"
                            value="{{ request('to_date') }}" id="toDate">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Invoice No</label>
                        <input type="text" class="form-control" name="invoice_no"
                            value="{{ request('invoice_no') }}" placeholder="Invoice Number">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Sale Type</label>
                        <select class="form-control" name="sale_type">
                            <option value="">All Types</option>
                            <option value="customer" {{ request('sale_type') == 'customer' ? 'selected' : '' }}>Customer</option>
                            <option value="radiology-use" {{ request('sale_type') == 'radiology-use' ? 'selected' : '' }}>Radiology Use</option>
                            <option value="lab-use" {{ request('sale_type') == 'lab-use' ? 'selected' : '' }}>Lab Use</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Customer/Dept</label>
                        <input type="text" class="form-control" name="customer_name"
                            value="{{ request('customer_name') }}" placeholder="Customer/Department Name">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Payment Status</label>
                        <select class="form-control" name="payment_status">
                            <option value="">All Status</option>
                            <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="partial" {{ request('payment_status') == 'partial' ? 'selected' : '' }}>Partial</option>
                            <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="internal" {{ request('payment_status') == 'internal' ? 'selected' : '' }}>Internal Use</option>
                        </select>
                    </div>
                    <div class="col-md-6 pt-4">
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
                        <button type="button" class="btn btn-info" id="exportExcel" style="border-radius: 6px">
                            <em class="icon ni ni-file-excel"></em> Export
                        </button>
                    </div>
                </form>
            </div>

           
            <!-- Results -->
            @if(request()->hasAny(['from_date', 'to_date', 'invoice_no', 'sale_type', 'customer_name', 'payment_status']))
                <div class="table-responsive">
                    <table class="table table-hover" id="medicineSalesTable">
                        <thead>
                            <tr class="table-light">
                                <th>Invoice No</th>
                                <th>Customer/Dept</th>
                                <th>Sale Date</th>
                                <th>Amount</th>
                                <th>Discount</th>
                                <th>Net Amount</th>
                                <th>Payment</th>
                                <th>Balance</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sales as $sale)
                                <tr>
                                    <td>
                                        <div class="user-info">
                                            <span class="lead-text">{{ $sale->invoice_number }}</span>
                                            @if ($sale->notes)
                                                <span class="sub-text">{{ Str::limit($sale->notes, 30) }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($sale->type == 'customer')
                                            <span class="fw-bold">{{ $sale->customer_name }}</span>
                                            @if ($sale->customer_phone)
                                                <br><small class="text-muted">{{ $sale->customer_phone }}</small>
                                            @endif
                                        @else
                                            <span class="fw-bold">{{ $sale->department_name }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $sale->sale_date->format('d M Y') }}</td>
                                    <td>
                                        <span class="fw-bold text-primary">₹ {{ number_format($sale->sub_total, 2) }}</span>
                                    </td>
                                    <td>
                                        <span class="text-danger">₹ {{ number_format($sale->total_discount, 2) }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold">₹ {{ number_format($sale->grand_total, 2) }}</span>
                                    </td>
                                    <td>
                                        @if($sale->is_internal)
                                            <span class="badge badge-dim bg-secondary">Internal</span>
                                        @else
                                            <span class="badge badge-dim bg-{{ $sale->payment_status == 'paid' ? 'success' : ($sale->payment_status == 'partial' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($sale->payment_status) }}
                                            </span>
                                            <br><small class="text-muted">₹ {{ number_format($sale->paid_amount, 2) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if(($sale->grand_total - $sale->paid_amount) > 0)
                                            <span class="text-danger fw-bold">
                                                ₹ {{ number_format($sale->grand_total - $sale->paid_amount, 2) }}
                                            </span>
                                        @else
                                            <span class="text-success">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('medicine-sales.print', $sale->id) }}" target="_blank" class="btn btn-info" style="border-radius: 6px 0 0 6px">
                                                <em class="icon ni ni-printer"></em>
                                            </a>
                                            <a href="{{ route('medicine-sales.show', $sale) }}" class="btn btn-sm btn-primary" title="View Details" style="border-radius: 0">
                                                <em class="icon ni ni-eye"></em>
                                            </a>
                                            <a href="{{ route('medicine-sales.edit', $sale) }}" class="btn btn-sm btn-warning" title="Edit Sale" style="border-radius: 0">
                                                <em class="icon ni ni-edit"></em>
                                            </a>
                                            <form action="{{ route('medicine-sales.destroy', $sale) }}" method="POST" class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" style="height:40px" title="Delete Sale"
                                                    onclick="return confirm('Are you sure? This will restore stock quantities.')">
                                                    <em class="icon ni ni-trash"></em>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center py-4">
                                        <div class="alert alert-light">
                                            <em class="icon ni ni-info text-muted"></em>
                                            <span class="ms-1">No records found. Try different filter criteria.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                        <!-- Table Footer with Totals -->
                        @if($sales->count() > 0)
                            <tfoot>
                                <tr class="table-secondary">
                                    <td colspan="3" class="text-end fw-bold">Total:</td>
                                    <td class="fw-bold">₹ {{ number_format($sales->sum('sub_total'), 2) }}</td>
                                    <td class="fw-bold">₹ {{ number_format($sales->sum('total_discount'), 2) }}</td>
                                    <td class="fw-bold">₹ {{ number_format($sales->sum('grand_total'), 2) }}</td>
                                    <td class="fw-bold">₹ {{ number_format($sales->sum('paid_amount'), 2) }}</td>
                                    <td class="fw-bold">₹ {{ number_format($sales->sum('grand_total')-$sales->sum('paid_amount'), 2) }}</td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>

              
            @else
                <div class="text-center py-5">
                    <div class="alert alert-light">
                        <em class="icon ni ni-search text-muted" style="font-size: 48px;"></em>
                        <h5 class="mt-3 mb-2">No filters applied</h5>
                        <p class="text-muted">Use the filters above to search for medicine sale records.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
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

    .alert-light {
        background-color: #f8f9fa;
        border-color: #e9ecef;
    }

    .btn-group .btn {
        border-radius: 0;
    }

    .btn-group .btn:first-child {
        border-radius: 6px 0 0 6px;
    }

    .btn-group .btn:last-child {
        border-radius: 0 6px 6px 0;
    }

    tfoot td {
        font-weight: bold;
        background-color: #f8f9fa;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script>
    $(document).ready(function() {
        // Set max date for To Date
        $('#fromDate').on('change', function() {
            $('#toDate').attr('min', $(this).val());
        });

        // Reset filters
        $('#resetFilters').on('click', function() {
            $('#filterForm')[0].reset();
            window.location.href = '{{ route('medicine-sales.index') }}';
        });

        // Print Report
        @if(isset($sales) && $sales->count() > 0)
            $('#printReport').on('click', function() {
                const printWindow = window.open('', '_blank');
                const filters = {
                    fromDate: $('input[name="from_date"]').val() || 'All',
                    toDate: $('input[name="to_date"]').val() || 'All',
                    invoiceNo: $('input[name="invoice_no"]').val() || 'All',
                    saleType: $('select[name="sale_type"] option:selected').text() || 'All',
                    customerName: $('input[name="customer_name"]').val() || 'All',
                    paymentStatus: $('select[name="payment_status"] option:selected').text() || 'All'
                };

                printWindow.document.write(`
                    <html>
                        <head>
                            <title>Medicine Sales Report - {{ date('d M Y') }}</title>
                            <style>
                                body { font-family: Arial, sans-serif; margin: 20px; }
                                h1 { text-align: center; color: #333; margin-bottom: 10px; }
                                .filters { margin-bottom: 20px; padding: 10px; background-color: #f5f5f5; border-radius: 5px; }
                                .filter-row { display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 5px; }
                                .filter-item { flex: 1; min-width: 200px; }
                                .filter-label { font-weight: bold; color: #666; }
                                table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 11px; }
                                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                                th { background-color: #f8f9fa; font-weight: bold; }
                                .footer { margin-top: 30px; text-align: center; color: #666; font-size: 11px; }
                                .summary { margin-top: 20px; padding: 10px; background-color: #f8f9fa; border-radius: 5px; }
                                .summary-item { display: inline-block; margin-right: 20px; }
                                .text-right { text-align: right; }
                            </style>
                        </head>
                        <body>
                            <h1>Medicine Sales Report</h1>
                            <div class="filters">
                                <div class="filter-row">
                                    <div class="filter-item">
                                        <span class="filter-label">Date Range:</span> ${filters.fromDate} to ${filters.toDate}
                                    </div>
                                    <div class="filter-item">
                                        <span class="filter-label">Invoice No:</span> ${filters.invoiceNo}
                                    </div>
                                    <div class="filter-item">
                                        <span class="filter-label">Sale Type:</span> ${filters.saleType}
                                    </div>
                                </div>
                                <div class="filter-row">
                                    <div class="filter-item">
                                        <span class="filter-label">Customer/Dept:</span> ${filters.customerName}
                                    </div>
                                    <div class="filter-item">
                                        <span class="filter-label">Payment Status:</span> ${filters.paymentStatus}
                                    </div>
                                    <div class="filter-item">
                                        <span class="filter-label">Generated on:</span> {{ date('d M Y h:i A') }}
                                    </div>
                                </div>
                            </div>
                            ${$('#medicineSalesTable').clone().find('.btn-group').remove().end()[0].outerHTML}
                            <div class="footer">
                                <p>This is a computer generated report. Signature not required.</p>
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

            // Export to Excel
            $('#exportExcel').on('click', function() {
                // Clone the table to avoid modifying the original
                var table = $('#medicineSalesTable').clone();

                // Remove action buttons column
                table.find('th:last-child, td:last-child').remove();

                // Remove any buttons from the table
                table.find('.btn-group, .btn, button').remove();

                // Create workbook
                var wb = XLSX.utils.book_new();
                var ws = XLSX.utils.table_to_sheet(table[0]);

                // Adjust column widths
                ws['!cols'] = [
                    {wch:15}, {wch:12}, {wch:20}, {wch:12},
                    {wch:12}, {wch:12}, {wch:12}, {wch:12},
                    {wch:12}, {wch:12}
                ];

                // Add title
                XLSX.utils.sheet_add_aoa(ws, [['Medicine Sales Report']], {origin: 'A1'});
                XLSX.utils.sheet_add_aoa(ws, [[`Generated on: {{ date('d M Y h:i A') }}`]], {origin: 'A2'});

                // Add filters info
                var filters = [];
                if ($('input[name="from_date"]').val()) filters.push(`From: ${$('input[name="from_date"]').val()}`);
                if ($('input[name="to_date"]').val()) filters.push(`To: ${$('input[name="to_date"]').val()}`);
                if ($('input[name="invoice_no"]').val()) filters.push(`Invoice: ${$('input[name="invoice_no"]').val()}`);
                if ($('select[name="sale_type"]').val()) filters.push(`Type: ${$('select[name="sale_type"] option:selected').text()}`);
                if ($('input[name="customer_name"]').val()) filters.push(`Customer: ${$('input[name="customer_name"]').val()}`);

                XLSX.utils.sheet_add_aoa(ws, [filters.join(' | ')], {origin: 'A3'});

                // Add to workbook
                XLSX.utils.book_append_sheet(wb, ws, 'Medicine Sales');

                // Save file
                XLSX.writeFile(wb, `medicine_sales_${new Date().toISOString().slice(0,19)}.xlsx`);
            });
        @else
            $('#printReport, #exportExcel').prop('disabled', true).addClass('btn-disabled');
        @endif

        // Auto-submit on Enter in text fields
        $('input[type="text"], input[type="date"]').on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                $('#filterForm').submit();
            }
        });

        // Date range validation
        $('#fromDate, #toDate').on('change', function() {
            var fromDate = $('#fromDate').val();
            var toDate = $('#toDate').val();
            if (fromDate && toDate && fromDate > toDate) {
                alert('From Date cannot be greater than To Date');
                $('#toDate').val('');
            }
        });
    });
</script>
@endpush
