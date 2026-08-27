@extends('layouts.app')

@section('title', 'Pharmacy - Medicine Stock')
@section('page-title', 'Medicine Stock')

@section('content')
<div class="nk-block nk-block-lg">
    <div class="card card-preview">
        <div class="card-inner">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Medicine Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Current Stock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($medicines as $medicine)
                        <tr class="{{ $medicine->isLowStock() ? 'table-warning' : '' }} {{ $medicine->isOutOfStock() ? 'table-danger' : '' }}">
                            <td>
                                <strong>{{ $medicine->name }}</strong>
                                @if($medicine->isLowStock())
                                <span class="badge badge-warning ms-1">Low Stock</span>
                                @endif
                                @if($medicine->isOutOfStock())
                                <span class="badge badge-danger ms-1">Out of Stock</span>
                                @endif
                            </td>
                            <td>{{ $medicine->category_name }}</td>
                            <td>₹{{ number_format($medicine->price, 2) }}</td>
                            <td>
                                <span class="fw-bold {{ $medicine->isLowStock() ? 'text-warning' : ($medicine->isOutOfStock() ? 'text-danger' : 'text-success') }}">
                                    {{ $medicine->stock }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $medicine->status == 'active' ? 'success' : 'secondary' }}">
                                    {{ $medicine->status_name }}
                                </span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#updateStockModal{{ $medicine->id }}">
                                    Update Stock
                                </button>
                            </td>
                        </tr>

                        <!-- Update Stock Modal -->
                        <div class="modal fade" id="updateStockModal{{ $medicine->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Update Stock - {{ $medicine->name }}</h5>
                                        <button type="button" class="close" data-bs-dismiss="modal">
                                            <span>&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('pharmacy.update-stock', $medicine) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label class="form-label">Current Stock</label>
                                                <input type="number" class="form-control" value="{{ $medicine->stock }}" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">New Stock Quantity *</label>
                                                <input type="number" class="form-control" name="stock" value="{{ $medicine->stock }}" min="0" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Update Stock</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
