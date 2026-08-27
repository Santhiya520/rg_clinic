@extends('layouts.app')

@section('title', 'Create Bulk Order')
@section('page-title', 'Create Bulk Order')

@section('content')
    <div class="nk-block nk-block-lg">
        <form action="{{ route('medicines.store-bulk-order') }}" method="POST" id="bulkOrderForm">
            @csrf

            <div class="card card-preview">
                <div class="card-inner">
                    <!-- Bulk Order Header -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Order Number *</label>
                                <input type="text" class="form-control" name="invoice_number"
                                    value="{{ $invoiceNumber }}" readonly style="border-radius: 5px">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Order Date *</label>
                                <input type="date" class="form-control" name="purchase_date"
                                    value="{{ date('Y-m-d') }}" required style="border-radius: 5px">
                            </div>
                        </div>
                    </div>

                    <!-- Supplier Information -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h6 class="title border-bottom pb-2">Supplier Information</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Supplier Name</label>
                                        <input type="text" class="form-control"
                                            value="{{ \App\Helpers\StringHelper::decodeQuotes($supplier->name) }}" readonly>
                                        <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">
                                        <input type="hidden" name="supplier_name" value="{{ $supplier->name }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Contact Person</label>
                                        <input type="text" class="form-control"
                                            value="{{ \App\Helpers\StringHelper::decodeQuotes($supplier->contact_person ?? 'N/A') }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Phone</label>
                                        <input type="text" class="form-control"
                                            value="{{ $supplier->phone ?? 'N/A' }}" readonly>
                                        <input type="hidden" name="supplier_phone" value="{{ $supplier->phone }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Address</label>
                                        <textarea class="form-control" readonly>{{ \App\Helpers\StringHelper::decodeQuotes($supplier->address ?? 'N/A') }}</textarea>
                                        <input type="hidden" name="supplier_address" value="{{ $supplier->address }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Low Stock Medicines -->
                    <h6 class="title border-bottom pb-2">Low Stock Medicines</h6>
                    <div class="alert alert-info">
                        <em class="icon ni ni-info"></em>
                        <strong>Note:</strong> These are medicines with stock ≤ 10 units. Enter batch number and expiry date for each.
                        Prices will be added later when billing this order.
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="20%">Medicine Name</th>
                                    <th width="10%">Category</th>
                                    <th width="10%">Current Stock</th>
                                    <th width="15%">Batch Number *</th>
                                    <th width="15%">Expiry Date *</th>
                                    <th width="10%">Order Quantity *</th>
                                    <th width="20%">Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lowStockMedicines as $index => $medicine)
                                    <tr>
                                        <td>
                                            <strong>{{ $medicine->decoded_name }}</strong>
                                            <br><small class="text-muted">{{ $medicine->generic_name ?? '' }}</small>
                                        </td>
                                        <td>{{ $medicine->category }}</td>
                                        <td>
                                            <span class="badge bg-{{ $medicine->stock == 0 ? 'danger' : ($medicine->stock <= 5 ? 'warning' : 'info') }}">
                                                {{ $medicine->stock }}
                                            </span>
                                        </td>
                                        <td>
                                            <input type="hidden" name="items[{{ $index }}][medicine_id]" value="{{ $medicine->id }}">
                                            <input type="text" class="form-control"
                                                name="items[{{ $index }}][batch_number]"
                                                value=""
                                                placeholder="Enter batch number"
                                                required style="border-radius: 5px">
                                        </td>
                                        <td>
                                            <input type="date" class="form-control"
                                                name="items[{{ $index }}][expiry_date]"
                                                value="{{ date('Y-m-d', strtotime('+2 years')) }}"
                                                min="{{ date('Y-m-d', strtotime('+1 month')) }}"
                                                required style="border-radius: 5px">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control quantity-input"
                                                name="items[{{ $index }}][quantity]"
                                                value=""
                                                 required style="border-radius: 5px">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control"
                                                name="items[{{ $index }}][notes]"
                                                placeholder="Special instructions" style="border-radius: 5px">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Order Notes -->
                    <div class="form-group mt-4">
                        <label class="form-label">Order Notes</label>
                        <textarea class="form-control" name="notes" rows="3"
                            placeholder="Additional notes for this bulk order..."></textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <a href="{{ route('medicines.bulk-order') }}" class="btn btn-secondary" style="border-radius: 5px">
                                <em class="icon ni ni-arrow-left"></em> &nbsp; Back
                            </a>
                        </div>
                        <div class="col-md-6 text-end">
                            <button type="submit" class="btn btn-primary" style="border-radius: 6px 0 0 6px">
                                <em class="icon ni ni-save"></em> &nbsp; Create Bulk Order
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Optional: Add dynamic batch number generation based on medicine


        // Validate expiry date is not in the past
        const expiryInputs = document.querySelectorAll('input[name$="[expiry_date]"]');
        expiryInputs.forEach(input => {
            input.addEventListener('change', function() {
                const selectedDate = new Date(this.value);
                const today = new Date();
                today.setHours(0, 0, 0, 0);

                if (selectedDate <= today) {
                    alert('Expiry date must be in the future');
                    this.value = '';
                }
            });
        });


    });
</script>
@endpush
