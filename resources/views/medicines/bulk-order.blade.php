@extends('layouts.app')

@section('title', 'Bulk Order Management')
@section('page-title', 'Bulk Order Management')

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

        @if (session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{ session('warning') }}
                <button type="button" class="close" data-bs-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> {{ session('error') }}
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
                            <h5 class="nk-block-title">Suppliers with Low Stock Medicines</h5>
                            <p class="text-soft">Select a supplier to create bulk order for low stock medicines</p>
                        </div>
                        <div class="nk-block-head-content">
                            <a href="{{ route('medicines.bulk-order-report') }}" class="btn btn-primary" style="border-radius: 5px">
                                <em class="icon ni ni-list"></em> &nbsp; View Bulk Orders
                            </a>
                        </div>
                    </div>
                </div>

                @if($suppliers->isEmpty())
                    <div class="alert alert-info">
                        <em class="icon ni ni-info"></em>
                        No suppliers found with low stock medicines.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Supplier Name</th>
                                    <th>Contact Person</th>
                                    <th>Phone</th>
                                    <th>Low Stock Medicines</th>
                                    <th>Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($suppliers as $supplier)
                                    <tr>
                                        <td>
                                            <strong>{{ \App\Helpers\StringHelper::decodeQuotes($supplier->name) }}</strong>
                                            @if($supplier->email)
                                                <br><small class="text-muted">{{ $supplier->email }}</small>
                                            @endif
                                        </td>
                                        <td>{{ \App\Helpers\StringHelper::decodeQuotes($supplier->contact_person ?? 'N/A') }}</td>
                                        <td>{{ $supplier->phone ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-warning">{{ $supplier->low_stock_count }}</span> medicines
                                            <br>
                                            <small class="text-muted">
                                                @foreach($supplier->medicines->take(3) as $medicine)
                                                    {{ \App\Helpers\StringHelper::decodeQuotes($medicine->name) }} ({{ $medicine->stock }}),
                                                @endforeach
                                                @if($supplier->low_stock_count > 3)
                                                    ...
                                                @endif
                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $supplier->status == 'active' ? 'success' : 'danger' }}">
                                                {{ ucfirst($supplier->status) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a href="{{ route('medicines.create-bulk-order', $supplier->id) }}"
                                                   class="btn btn-primary btn-sm" style="border-radius: 5px">
                                                    <em class="icon ni ni-cart"></em> &nbsp;Order
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <!-- Information Card -->
        <div class="card card-preview mt-4">
            <div class="card-inner">
                <h6 class="title">About Bulk Orders</h6>
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list list-sm">
                            <li><em class="icon ni ni-check-circle text-success"></em> Automatically identifies low stock medicines (≤ 10 units)</li>
                            <li><em class="icon ni ni-check-circle text-success"></em> Creates order list for supplier</li>
                            <li><em class="icon ni ni-check-circle text-success"></em> Prices can be added later when billing</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list list-sm">
                            <li><em class="icon ni ni-check-circle text-success"></em> Stock is updated only after billing</li>
                            <li><em class="icon ni ni-check-circle text-success"></em> Batch numbers and expiry dates added during billing</li>
                            <li><em class="icon ni ni-check-circle text-success"></em> Track all bulk orders in reports</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
