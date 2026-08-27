@extends('layouts.app')

@section('title', 'Purchase Details')
@section('page-title', 'Purchase Details')

@section('content')
<div class="nk-block nk-block-lg">
    <div class="card card-preview">
        <div class="card-inner">
            <!-- Purchase Header -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>Purchase Information</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th width="40%">Invoice Number:</th>
                            <td>{{ $medicinePurchase->invoice_number }}</td>
                        </tr>
                        <tr>
                            <th>Purchase Date:</th>
                            <td>
                                @php
                                    try {
                                        $purchaseDate = $medicinePurchase->purchase_date instanceof \Carbon\Carbon
                                            ? $medicinePurchase->purchase_date
                                            : \Carbon\Carbon::parse($medicinePurchase->purchase_date);
                                        echo $purchaseDate->format('d M, Y');
                                    } catch (\Exception $e) {
                                        echo $medicinePurchase->purchase_date;
                                    }
                                @endphp
                            </td>
                        </tr>
                        <tr>
                            <th>Supplier Name:</th>
                            <td>{{ \App\Helpers\StringHelper::decodeQuotes($medicinePurchase->supplier_name) }}</td>
                        </tr>
                        <tr>
                            <th>Supplier Phone:</th>
                            <td>{{ $medicinePurchase->supplier_phone ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Supplier Address:</th>
                            <td>{{ $medicinePurchase->supplier_address ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Payment Status:</th>
                            <td>
                                <span class="badge badge-{{ $medicinePurchase->payment_status == 'paid' ? 'success' : ($medicinePurchase->payment_status == 'partial' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($medicinePurchase->payment_status) }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5>Financial Information</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th width="40%">Total Amount:</th>
                            <td>₹{{ number_format($medicinePurchase->total_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Paid Amount:</th>
                            <td>₹{{ number_format($medicinePurchase->paid_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Due Amount:</th>
                            <td>₹{{ number_format($medicinePurchase->due_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Notes:</th>
                            <td>{{ $medicinePurchase->notes ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Purchase Items -->
            <h6 class="title border-bottom pb-2">Purchase Items</h6>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Medicine</th>
                            <th>Batch No</th>
                            <th>Expiry Date</th>
                            <th>Quantity</th>
                            <th>Purchase Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($medicinePurchase->items as $item)
                        <tr>
                            <td>
                                {{ $item->medicine->name ?? 'N/A' }} - {{ $item->medicine->generic_name ?? 'N/A' }}
                                <br><small class="text-muted">{{ $item->medicine->category ?? 'N/A' }}</small>
                            </td>
                            <td>{{ $item->batch_number }}</td>
                            <td>
                                {{ $item->expiry_date }} <!-- Display as is (MM/YYYY format) -->
                            </td>
                            <td>{{ $item->quantity }}</td>
                            <td>₹{{ number_format($item->purchase_price, 2) }}</td>
                            <td>₹{{ number_format($item->total_amount, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="table-primary">
                            <td colspan="5" class="text-end"><strong>Grand Total:</strong></td>
                            <td><strong>₹{{ number_format($medicinePurchase->total_amount, 2) }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Action Buttons -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <a href="{{ route('medicine-purchases.index') }}" class="btn btn-secondary" style="border-radius: 5px">
                        <em class="icon ni ni-arrow-left"></em> &nbsp; Back to List
                    </a>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('medicine-purchases.edit', $medicinePurchase->id) }}" class="btn btn-primary" style="border-radius: 6px 0 0 6px">
                        <em class="icon ni ni-edit"></em> &nbsp; Edit Purchase
                    </a>
                    <form action="{{ route('medicine-purchases.destroy', $medicinePurchase->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this purchase? This will also adjust stock quantities.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <em class="icon ni ni-trash"></em> &nbsp; Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
