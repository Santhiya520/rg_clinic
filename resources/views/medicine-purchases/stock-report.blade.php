@extends('layouts.app')

@section('title', 'Medicine Stock Report')
@section('page-title', 'Medicine Stock Report')

@section('content')
<div class="nk-block nk-block-lg">
    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-light-primary">
                <div class="card-body text-center">
                    <h4 class="text-primary">{{ $totalMedicines }}</h4>
                    <p class="mb-0">Total Medicines</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-light-success">
                <div class="card-body text-center">
                    <h4 class="text-success">₹ {{ number_format($totalStockValue, 2) }}</h4>
                    <p class="mb-0">Total Stock Value</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-light-info">
                <div class="card-body text-center">
                    <h4 class="text-info">{{ $medicines->sum('stock') }}</h4>
                    <p class="mb-0">Total Stock Quantity</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-preview">
        <div class="card-inner">
            <!-- Header Section -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5 class="title">Medicine Stock Summary</h5>
                    <p class="text-soft">Current stock status as of {{ now()->format('d M, Y') }}</p>
                </div>

            </div>

            <!-- Stock Summary Table -->
                <table class="datatable-init table" id="stockTable">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Medicine Name</th>
                            <th>Category</th>
                            <th>Current Stock</th>
                            <th>Purchase Price</th>
                            <th>Stock Value</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($medicines as $index => $medicine)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $medicine->name }}</strong>
                                @if($medicine->description)
                                <br><small class="text-muted">{{ Str::limit($medicine->description, 50) }}</small>
                                @endif
                            </td>
                            <td>{{ $medicine->category }}</td>
                            <td>
                                <span style="padding: 3px 8px" class="badge bg-{{ $medicine->stock > 20 ? 'success' : ($medicine->stock > 0 ? 'warning' : 'danger') }}">
                                    {{ $medicine->stock }}
                                </span>
                            </td>
                            <td>₹ {{ $medicine->purchase_price > 0 ? number_format($medicine->purchase_price, 2) : 'N/A' }}</td>
                            <td>
                                <strong class="text-primary">₹ {{ number_format($medicine->stock_value, 2) }}</strong>
                            </td>
                            <td>
                                <a href="{{ route('medicine-purchases.transactions', $medicine->id) }}"
                                   class="btn btn-sm btn-outline-primary"
                                   title="View Transactions" style="border-radius: 5px">
                                    <em class="icon ni ni-eye"></em> &nbsp; Preview
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <em class="icon ni ni-package fs-2"></em>
                                <p class="mt-2">No medicines found in stock.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-primary">
                        <tr>
                            <td colspan="5" class="text-end"><strong>Total Stock Value:</strong></td>
                            <td colspan="2"><strong class="text-primary">₹ {{ number_format($totalStockValue, 2) }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    @media print {
        .btn, .alert {
            display: none !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
    }

    /* DataTable custom styling */
    #stockTable_wrapper .dataTables_filter input {
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 4px 8px;
    }

    .dataTables_length select {
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 4px;
    }
</style>
@endpush
