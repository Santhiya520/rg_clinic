@extends('layouts.app')

@section('title', 'Expiry Medicine Report')
@section('page-title', 'Expiry Medicine Report')

@section('content')
<div class="nk-block nk-block-lg">
    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-light-danger">
                <div class="card-body text-center">
                    <h4 class="text-danger">{{ $expired->count() }}</h4>
                    <p class="mb-0">Expired Medicines</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-light-warning">
                <div class="card-body text-center">
                    <h4 class="text-warning">{{ $nearExpiry->count() }}</h4>
                    <p class="mb-0">Near Expiry ({{ $days }} Days)</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-light-success">
                <div class="card-body text-center">
                    <h4 class="text-success">{{ $valid->count() }}</h4>
                    <p class="mb-0">Valid Medicines</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-light-info">
                <div class="card-body text-center">
                    <h4 class="text-info">₹ {{ number_format($expiredStockValue, 2) }}</h4>
                    <p class="mb-0">Expired Stock Value</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-preview">
        <div class="card-inner">
            <!-- Filter Section -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <form action="{{ route('expiry-report') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Filter</label>
                            <select name="filter" class="form-select">
                                <option value="all" {{ $filter == 'all' ? 'selected' : '' }}>All Medicines</option>
                                <option value="expired" {{ $filter == 'expired' ? 'selected' : '' }}>Expired Only</option>
                                <option value="near_expiry" {{ $filter == 'near_expiry' ? 'selected' : '' }}>Near Expiry Only</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-select">
                                <option value="">All Categories</option>
                                @foreach($categories as $key => $cat)
                                    <option value="{{ $key }}" {{ $category == $key ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Near Expiry Days</label>
                            <input type="number" name="days" class="form-control" value="{{ $days }}" min="1" max="365">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <em class="icon ni ni-search"></em> Filter
                            </button>
                            <a href="{{ route('expiry-report') }}" class="btn btn-outline-secondary">
                                <em class="icon ni ni-refresh"></em> Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Header Section -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5 class="title">Expiry Medicine Report</h5>
                    <p class="text-soft">Report as of {{ now()->format('d M, Y') }} | Showing {{ $filteredMedicines->count() }} medicines</p>
                </div>
            </div>

            <!-- Expiry Report Table -->
            <table class="datatable-init table" id="expiryTable">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Medicine Name</th>
                        <th>Category</th>
                        <th>Expiry Date</th>
                        <th>Days Left</th>
                        <th>Stock</th>
                        <th>Price</th>
                        <th>Stock Value</th>
                        <th>Supplier</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($filteredMedicines as $index => $medicine)
                    @php
                        $daysLeft = $medicine->expiry_date ? $medicine->expiry_date->diffInDays(now(), false) : null;
                        $isExpired = $medicine->expiry_date && $medicine->expiry_date->startOfDay()->lt(now()->startOfDay());
                        $isNearExpiry = !$isExpired && $daysLeft !== null && $daysLeft >= 0 && $daysLeft <= $days;
                    @endphp
                    <tr class="{{ $isExpired ? 'table-danger' : ($isNearExpiry ? 'table-warning' : '') }}">
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ \App\Helpers\StringHelper::decodeQuotes($medicine->name) }}</strong>
                            @if($medicine->description)
                            <br><small class="text-muted">{{ Str::limit(\App\Helpers\StringHelper::decodeQuotes($medicine->description), 50) }}</small>
                            @endif
                        </td>
                        <td>{{ $medicine->category }}</td>
                        <td>
                            <strong>{{ $medicine->expiry_date ? $medicine->expiry_date->format('d M Y') : 'N/A' }}</strong>
                        </td>
                        <td>
                            @if($isExpired)
                                <span class="badge bg-danger">Expired {{ abs(intval($daysLeft)) }} days ago</span>
                            @elseif($isNearExpiry)
                                <span class="badge bg-warning text-dark">{{ intval($daysLeft) }} days left</span>
                            @elseif($daysLeft !== null)
                                <span class="badge bg-success">{{ intval($daysLeft) }} days left</span>
                            @else
                                <span class="badge bg-secondary">N/A</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $medicine->stock > 20 ? 'success' : ($medicine->stock > 0 ? 'warning' : 'danger') }}">
                                {{ $medicine->stock }}
                            </span>
                        </td>
                        <td>₹ {{ number_format($medicine->price, 2) }}</td>
                        <td>
                            <strong class="text-primary">₹ {{ number_format($medicine->stock * $medicine->price, 2) }}</strong>
                        </td>
                        <td>{{ $medicine->supplier ? \App\Helpers\StringHelper::decodeQuotes($medicine->supplier->name) : 'N/A' }}</td>
                        <td>
                            @if($isExpired)
                                <span class="badge bg-danger">Expired</span>
                            @elseif($isNearExpiry)
                                <span class="badge bg-warning text-dark">Near Expiry</span>
                            @else
                                <span class="badge bg-success">Valid</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">
                            <em class="icon ni ni-package fs-2"></em>
                            <p class="mt-2">No medicines found matching the filter criteria.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="table-primary">
                    <tr>
                        <td colspan="5" class="text-end"><strong>Total Stock Value:</strong></td>
                        <td colspan="5"><strong class="text-primary">₹ {{ number_format($totalStockValue, 2) }}</strong></td>
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
        .btn, .alert, form {
            display: none !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
    }

    #expiryTable_wrapper .dataTables_filter input {
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 4px 8px;
    }

    .dataTables_length select {
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 4px;
    }

    .table-danger {
        background-color: rgba(220, 53, 69, 0.1) !important;
    }

    .table-warning {
        background-color: rgba(255, 193, 7, 0.1) !important;
    }
</style>
@endpush
