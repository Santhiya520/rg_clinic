@extends('layouts.app')

@section('title', 'Bulk Order Report')
@section('page-title', 'Bulk Order Report')

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
                            <h5 class="nk-block-title">Bulk Orders Report</h5>
                            <p class="text-soft">Manage and bill your bulk orders</p>
                        </div>
                        <div class="nk-block-head-content">
                            <a href="{{ route('medicines.bulk-order') }}" class="btn btn-primary" style="border-radius: 5px">
                                <em class="icon ni ni-cart"></em> &nbsp; New Bulk Order
                            </a>
                        </div>
                    </div>
                </div>

                @if($bulkOrders->isEmpty())
                    <div class="alert alert-info">
                        <em class="icon ni ni-info"></em>
                        No bulk orders found.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover" id="bulkOrdersTable">
                            <thead>
                                <tr>
                                    <th>Order No</th>
                                    <th>Supplier</th>
                                    <th>Order Date</th>
                                    <th>Items</th>
                                    <th>Total Amount</th>
                                    <th>Payment Status</th>
                                    <th>Order Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bulkOrders as $order)
                                    <tr>
                                        <td>
                                            <strong>{{ $order->invoice_number }}</strong>
                                            @if($order->notes)
                                                <br><small class="text-muted">{{ Str::limit($order->notes, 20) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $order->supplier_name_decoded }}</strong>
                                            @if($order->supplier_phone)
                                                <br><small class="text-muted">{{ $order->supplier_phone }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $order->purchase_date->format('d M Y') }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $order->items->count() }}</span> items
                                            <br>
                                            <small class="text-muted">{{ $order->items->sum('quantity') }} units</small>
                                        </td>
                                        <td>
                                            @if($order->total_amount > 0)
                                                <span class="fw-bold text-primary">₹ {{ number_format($order->total_amount, 2) }}</span>
                                            @else
                                                <span class="text-muted">Not Billed</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($order->total_amount > 0)
                                                <span class="badge bg-{{ $order->payment_status == 'paid' ? 'success' : ($order->payment_status == 'partial' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($order->payment_status) }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($order->total_amount > 0)
                                                <span class="badge bg-success">Billed</span>
                                            @else
                                                <span class="badge bg-warning">Draft</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                @if($order->total_amount == 0)
                                                    <!-- Edit/Bill Order -->
                                                    <a href="{{ route('medicines.edit-bulk-order', $order->id) }}"
                                                       class="btn btn-primary btn-sm" style="border-radius: 6px 0 0 6px" title="Bill Order">
                                                        <em class="icon ni ni-edit"></em> Bill
                                                    </a>
                                                @else
                                                    <!-- View Billed Order -->
                                                    <a href="{{ route('medicine-purchases.show', $order->id) }}"
                                                       class="btn btn-info btn-sm" style="border-radius: 6px 0 0 6px" title="View Order">
                                                        <em class="icon ni ni-eye"></em>
                                                    </a>
                                                @endif

                                                <!-- Print -->
                                                <a href="{{ route('medicines.print-bulk-order', $order->id) }}"
                                                   target="_blank" class="btn btn-secondary btn-sm" title="Print">
                                                    <em class="icon ni ni-printer"></em>
                                                </a>

                                                @if($order->total_amount == 0)
                                                    <!-- Delete -->
                                                    <form action="{{ route('medicines.delete-bulk-order', $order->id) }}"
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" style="border-radius: 0 6px 6px 0"
                                                                onclick="return confirm('Are you sure you want to delete this bulk order?')"
                                                                title="Delete Order">
                                                            <em class="icon ni ni-trash"></em>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-3">
                        {{ $bulkOrders->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Statistics Card -->
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card card-bordered">
                    <div class="card-inner">
                        <div class="card-title-group align-start mb-2">
                            <div class="card-title">
                                <h6 class="title">Total Orders</h6>
                            </div>
                        </div>
                        <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                            <div class="nk-sale-data">
                                <span class="amount">{{ $bulkOrders->total() }}</span>
                                <span class="sub-title">Bulk Orders</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-bordered">
                    <div class="card-inner">
                        <div class="card-title-group align-start mb-2">
                            <div class="card-title">
                                <h6 class="title">Pending Billing</h6>
                            </div>
                        </div>
                        <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                            <div class="nk-sale-data">
                                @php
                                    $pending = $bulkOrders->where('total_amount', 0)->count();
                                @endphp
                                <span class="amount text-warning">{{ $pending }}</span>
                                <span class="sub-title">Orders to Bill</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-bordered">
                    <div class="card-inner">
                        <div class="card-title-group align-start mb-2">
                            <div class="card-title">
                                <h6 class="title">Billed Amount</h6>
                            </div>
                        </div>
                        <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                            @php
                                $totalBilled = $bulkOrders->where('total_amount', '>', 0)->sum('total_amount');
                            @endphp
                            <div class="nk-sale-data">
                                <span class="amount text-success">₹ {{ number_format($totalBilled, 2) }}</span>
                                <span class="sub-title">Total Billed</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#bulkOrdersTable').DataTable({
            responsive: true,
            pageLength: 25,
            order: [[2, 'desc']] // Sort by order date descending
        });
    });
</script>
@endpush
