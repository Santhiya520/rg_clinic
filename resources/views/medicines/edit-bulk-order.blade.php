@extends('layouts.app')

@section('title', 'Edit Bulk Order')
@section('page-title', 'Edit Bulk Order: ' . $bulkOrder->invoice_number)

@section('content')
    <div class="nk-block nk-block-lg">
        <form action="{{ route('medicines.update-bulk-order', $bulkOrder->id) }}" method="POST" id="bulkOrderForm">
            @csrf
            @method('PUT')

            <div class="card card-preview">
                <div class="card-inner">
                    <!-- Bulk Order Header (Read-only) -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Order Number</label>
                                <input type="text" class="form-control" value="{{ $bulkOrder->invoice_number }}" readonly style="border-radius: 5px">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Order Date</label>
                                <input type="text" class="form-control"
                                    value="{{ $bulkOrder->purchase_date->format('d M, Y') }}" readonly style="border-radius: 5px">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Supplier</label>
                                <input type="text" class="form-control" value="{{ $bulkOrder->supplier_name_decoded }}" readonly style="border-radius: 5px">
                            </div>
                        </div>
                    </div>

                    <!-- Supplier Information (Read-only) -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h6 class="title border-bottom pb-2">Supplier Information</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Supplier Name</label>
                                        <input type="text" class="form-control" value="{{ $bulkOrder->supplier_name_decoded }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Phone</label>
                                        <input type="text" class="form-control" value="{{ $bulkOrder->supplier_phone ?? 'N/A' }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Address</label>
                                        <textarea class="form-control" readonly>{{ $bulkOrder->supplier_address ?? 'N/A' }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <h6 class="title border-bottom pb-2">Order Items</h6>
                    <div class="alert alert-info">
                        <em class="icon ni ni-info"></em>
                        <strong>Note:</strong> You can update batch numbers, expiry dates, and quantities for each medicine.
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
                                @foreach($bulkOrder->items as $index => $item)
                                    <tr>
                                        <td>
                                            <strong>{{ $item->medicine->decoded_name ?? 'N/A' }}</strong>
                                            <br><small class="text-muted">{{ $item->medicine->generic_name ?? '' }}</small>
                                            <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                                            <input type="hidden" name="items[{{ $index }}][medicine_id]" value="{{ $item->medicine_id }}">
                                        </td>
                                        <td>{{ $item->medicine->category ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $item->medicine->stock == 0 ? 'danger' : ($item->medicine->stock <= 5 ? 'warning' : 'info') }}">
                                                {{ $item->medicine->stock ?? 0 }}
                                            </span>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control batch-number"
                                                name="items[{{ $index }}][batch_number]"
                                                value="{{ $item->batch_number == 'BATCH-TBD' ? '' : $item->batch_number }}"
                                                placeholder="Enter batch number"
                                                required style="border-radius: 5px">
                                        </td>
                                        <td>
                                            @php
                                                $expiryDate = $item->expiry_date ? \Carbon\Carbon::parse($item->expiry_date)->format('Y-m-d') : '';
                                            @endphp
                                            <input type="date" class="form-control expiry-date"
                                                name="items[{{ $index }}][expiry_date]"
                                                value="{{ $expiryDate }}"
                                                min="{{ date('Y-m-d', strtotime('+1 month')) }}"
                                                required style="border-radius: 5px">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control quantity-input"
                                                name="items[{{ $index }}][quantity]"
                                                value="{{ $item->quantity }}"
                                                required style="border-radius: 5px">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control"
                                                name="items[{{ $index }}][notes]"
                                                value="{{ $item->notes ?? '' }}"
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
                            placeholder="Additional notes for this bulk order...">{{ $bulkOrder->notes }}</textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <a href="{{ route('medicines.bulk-order-report') }}" class="btn btn-secondary" style="border-radius: 5px">
                                <em class="icon ni ni-arrow-left"></em> &nbsp; Back
                            </a>
                        </div>
                        <div class="col-md-6 text-end">
                            <button type="submit" class="btn btn-primary" style="border-radius: 6px 0 0 6px">
                                <em class="icon ni ni-save"></em> &nbsp; Update Bulk Order
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
        // Validate expiry date is not in the past
        const expiryInputs = document.querySelectorAll('.expiry-date');
        expiryInputs.forEach(input => {
            input.addEventListener('change', function() {
                const selectedDate = new Date(this.value);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                const minDate = new Date();
                minDate.setMonth(minDate.getMonth() + 1);

                if (selectedDate <= today) {
                    alert('Expiry date must be in the future');
                    this.value = '';
                } else if (selectedDate < minDate) {
                    if (!confirm('Expiry date is less than 1 month from now. Are you sure?')) {
                        this.value = '';
                    }
                }
            });
        });

        // Quantity validation

        // Form validation

        // Add dynamic batch number generation on focus if empty
        const batchInputs = document.querySelectorAll('.batch-number');
        batchInputs.forEach(input => {
            input.addEventListener('focus', function() {
                if (!this.value) {
                    const row = this.closest('tr');
                    const medicineName = row.querySelector('td strong').textContent.trim();
                    const initials = medicineName.substring(0, 3).toUpperCase();
                    const date = new Date();
                    const dateStr = date.getFullYear().toString().substr(-2) +
                                   String(date.getMonth() + 1).padStart(2, '0') +
                                   String(date.getDate()).padStart(2, '0');
                    this.value = `BATCH-${initials}-${dateStr}`;
                }
            });
        });
    });
</script>

<style>
.is-invalid {
    border-color: #dc3545 !important;
    background-color: #fff8f8;
}
.is-invalid:focus {
    border-color: #dc3545 !important;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}
</style>
@endpush
