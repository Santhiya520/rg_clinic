@extends('layouts.app')

@section('title', 'Medicine Purchases')
@section('page-title', 'Medicine Purchases')

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
                            <h4 class="title nk-block-title">Medicine Purchases</h4>
                        </div>
                        <div class="nk-block-head-content">
                            <a href="{{ route('medicine-purchases.create') }}" class="btn btn-primary"
                                style="border-radius: 6px 0 0 6px">
                                <em class="icon ni ni-plus"></em> &nbsp; New Purchase
                            </a>
                            <a href="{{ route('stock-report') }}" class="btn btn-info"
                                style="border-radius: 0 6px 6px 0">
                                <em class="icon ni ni-package"></em> &nbsp; Stock Report
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Filters Section - Similar to OP Report -->
                <div class="card-inner border-bottom mb-4">
                    <form action="{{ route('medicine-purchases.index') }}" method="GET" class="row g-3" id="filterForm">
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
                            <label class="form-label">Supplier Name</label>
                            <input type="text" class="form-control" name="supplier_name"
                                value="{{ request('supplier_name') }}" placeholder="Supplier Name">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Payment Status</label>
                            <select class="form-control" name="payment_status">
                                <option value="">All Status</option>
                                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="partial" {{ request('payment_status') == 'partial' ? 'selected' : '' }}>Partial</option>
                                <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end gap-2 mt-3" style="margin-top: 7% !important;">
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
                        </div>
                    </form>
                </div>

                <!-- Summary Cards -->
                @if(request()->hasAny(['from_date', 'to_date', 'invoice_no', 'supplier_name', 'payment_status']))
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h6 class="card-title">Total Amount</h6>
                                    <h3 class="mb-0">₹ {{ number_format($totalAmount, 2) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h6 class="card-title">Total Paid</h6>
                                    <h3 class="mb-0">₹ {{ number_format($totalPaid, 2) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h6 class="card-title">Total Due</h6>
                                    <h3 class="mb-0">₹ {{ number_format($totalDue, 2) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Results -->
                @if(request()->hasAny(['from_date', 'to_date', 'invoice_no', 'supplier_name', 'payment_status']))
                    <div class="table-responsive">
                        <table class="table table-hover" id="purchasesTable">
                            <thead>
                                <tr class="table-light">
                                    <th>Invoice No</th>
                                    <th>Supplier</th>
                                    <th>Purchase Date</th>
                                    <th>Total Amount</th>
                                    <th>Paid Amount</th>
                                    <th>Due Amount</th>
                                    <th>Payment Status</th>
                                    <th>Created</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($purchases as $purchase)
                                    <tr>
                                        <td>
                                            <div class="user-info">
                                                <span class="lead-text">{{ $purchase->invoice_number }}</span>
                                                @if ($purchase->notes)
                                                    <span class="sub-text">{{ Str::limit($purchase->notes, 30) }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ $purchase->supplier_name }}</span>
                                            @if ($purchase->supplier_phone)
                                                <br><small class="text-muted">{{ $purchase->supplier_phone }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $purchase->purchase_date->format('d M Y') }}</td>
                                        <td>
                                            <span class="fw-bold text-primary">₹ {{ number_format($purchase->total_amount, 2) }}</span>
                                        </td>
                                        <td>₹ {{ number_format($purchase->paid_amount, 2) }}</td>
                                        <td>
                                            <span class="fw-bold {{ $purchase->due_amount > 0 ? 'text-danger' : 'text-success' }}">
                                                ₹ {{ number_format($purchase->due_amount, 2) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span style="padding: 3px 8px"
                                                class="badge badge-dim bg-{{ $purchase->payment_status == 'paid' ? 'success' : ($purchase->payment_status == 'partial' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($purchase->payment_status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $purchase->created_at->format('d M Y') }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('medicine-purchases.print', $purchase->id) }}"
                                                    target="_blank" class="btn btn-info" style="border-radius: 6px 0 0 6px">
                                                    <em class="icon ni ni-printer"></em>
                                                </a>
                                                <a href="{{ route('medicine-purchases.show', $purchase) }}"
                                                    class="btn btn-sm btn-primary" title="View Details" style="border-radius: 0px">
                                                    <em class="icon ni ni-eye"></em>
                                                </a>
                                                <a href="{{ route('medicine-purchases.edit', $purchase) }}"
                                                    class="btn btn-sm btn-warning" title="Edit Purchase" style="border-radius: 0px">
                                                    <em class="icon ni ni-edit"></em>
                                                </a>
                                                <form action="{{ route('medicine-purchases.destroy', $purchase) }}"
                                                    method="POST" class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" style="height:40px" title="Delete Purchase"
                                                        onclick="return confirm('Are you sure you want to delete this purchase? This will also reduce the stock quantities.')">
                                                        <em class="icon ni ni-trash"></em>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <div class="alert alert-light">
                                                <em class="icon ni ni-info text-muted"></em>
                                                <span class="ms-1">No records found. Try different filter criteria.</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $purchases->withQueryString()->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="alert alert-light">
                            <em class="icon ni ni-search text-muted" style="font-size: 48px;"></em>
                            <h5 class="mt-3 mb-2">No filters applied</h5>
                            <p class="text-muted">Use the filters above to search for medicine purchase records.</p>
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
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Set max date for To Date
        $('#fromDate').on('change', function() {
            $('#toDate').attr('min', $(this).val());
        });

        // Reset filters
        $('#resetFilters').on('click', function() {
            $('#filterForm')[0].reset();
            window.location.href = '{{ route('medicine-purchases.index') }}';
        });

        // Print Report
        @if(isset($purchases) && $purchases->count() > 0)
            $('#printReport').on('click', function() {
                const printWindow = window.open('', '_blank');
                const filters = {
                    fromDate: $('input[name="from_date"]').val() || 'All',
                    toDate: $('input[name="to_date"]').val() || 'All',
                    invoiceNo: $('input[name="invoice_no"]').val() || 'All',
                    supplierName: $('input[name="supplier_name"]').val() || 'All',
                    paymentStatus: $('select[name="payment_status"] option:selected').text() || 'All'
                };

                printWindow.document.write(`
                    <html>
                        <head>
                            <title>Medicine Purchase Report - {{ date('d M Y') }}</title>
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
                                .footer { margin-top: 30px; text-align: center; color: #666; font-size: 11px; }
                                .summary { margin-top: 20px; padding: 10px; background-color: #f8f9fa; border-radius: 5px; }
                                .summary-item { display: inline-block; margin-right: 20px; }
                            </style>
                        </head>
                        <body>
                            <h1>Medicine Purchase Report</h1>
                            <div class="filters">
                                <div class="filter-row">
                                    <div class="filter-item">
                                        <span class="filter-label">Date Range:</span> ${filters.fromDate} to ${filters.toDate}
                                    </div>
                                    <div class="filter-item">
                                        <span class="filter-label">Invoice No:</span> ${filters.invoiceNo}
                                    </div>
                                    <div class="filter-item">
                                        <span class="filter-label">Supplier:</span> ${filters.supplierName}
                                    </div>
                                </div>
                                <div class="filter-row">
                                    <div class="filter-item">
                                        <span class="filter-label">Payment Status:</span> ${filters.paymentStatus}
                                    </div>
                                    <div class="filter-item">
                                        <span class="filter-label">Generated on:</span> {{ date('d M Y h:i A') }}
                                    </div>
                                    <div class="filter-item">
                                        <span class="filter-label">Generated by:</span> {{ auth()->user()->name }}
                                    </div>
                                </div>
                            </div>
                            <div class="summary">
                                <div class="summary-item"><strong>Total Amount:</strong> ₹ {{ number_format($totalAmount, 2) }}</div>
                                <div class="summary-item"><strong>Total Paid:</strong> ₹ {{ number_format($totalPaid, 2) }}</div>
                                <div class="summary-item"><strong>Total Due:</strong> ₹ {{ number_format($totalDue, 2) }}</div>
                            </div>
                            ${$('#purchasesTable').clone().find('.btn-group').remove().end()[0].outerHTML}
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
