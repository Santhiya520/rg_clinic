@extends('layouts.app')

@section('title', 'Pharmacy - Issue IP Medicines')
@section('page-title', 'Issue IP Medicines - ' . ($inpatientRegister->patient->name ?? 'N/A'))

@section('content')
    <div class="nk-block nk-block-lg">
        <form action="{{ route('pharmacy.ip.issue', $inpatientRegister) }}" method="POST" id="issueForm">
            @csrf

            <!-- Patient Details -->
            <div class="card card-preview">
                <div class="card-inner">
                    <div class="nk-block-head">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h5 class="nk-block-title">IP Patient Details</h5>
                                <p class="text-soft">
                                    Patient: <strong>{{ $inpatientRegister->patient?->name ?? 'N/A' }}</strong>
                                    ({{ $inpatientRegister->patient?->patient_id ?? 'N/A' }}) |
                                    IP No: {{ $inpatientRegister->hospital_ip_no }} |
                                    Admitted: {{ $inpatientRegister->date_of_admission->format('d/m/Y') }}
                                </p>
                            </div>
                            <div class="nk-block-head-content">
                                <a href="{{ route('pharmacy.index') }}" class="btn btn-secondary" style="border-radius: 5px">
                                    <em class="icon ni ni-arrow-left"></em>&nbsp; Back to List
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lab Tests Section - Today Only -->
            @if($todayLabTests && $todayLabTests->count() > 0)
            <div class="card card-preview mt-3">
                <div class="card-inner">
                    <h5 class="card-title">Lab Tests - Today</h5>
                    <p class="text-muted small">Only today's lab tests are shown below</p>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Test Name</th>
                                    <th>Price</th>
                                    <th>Paid Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($todayLabTests as $labTest)
                                <tr>
                                    <td>{{ $labTest->labTest->name ?? 'N/A' }}</td>
                                    <td>₹{{ number_format($labTest->price, 2) }}</td>
                                    <td>
                                        <input type="hidden" name="lab_tests[{{ $loop->index }}][id]" value="{{ $labTest->id }}">
                                        <input type="number" class="form-control lab-paid-amount"
                                               name="lab_tests[{{ $loop->index }}][paid_amount]"
                                               value="{{ old('lab_tests.'.$loop->index.'.paid_amount', $labTest->price) }}"
                                               min="0" max="{{ $labTest->price }}" step="0.01">
                                    </td>
                                    <td>{{ $labTest->status }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Radiology Tests Section - Today Only -->
            @if($todayRadiologyTests && $todayRadiologyTests->count() > 0)
            <div class="card card-preview mt-3">
                <div class="card-inner">
                    <h5 class="card-title">Radiology Tests - Today</h5>
                    <p class="text-muted small">Only today's radiology tests are shown below</p>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Test Name</th>
                                    <th>Price</th>
                                    <th>Paid Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($todayRadiologyTests as $radiology)
                                <tr>
                                    <td>{{ $radiology->radiologyTest->name ?? 'N/A' }}</td>
                                    <td>₹{{ number_format($radiology->price, 2) }}</td>
                                    <td>
                                        <input type="hidden" name="radiologies[{{ $loop->index }}][id]" value="{{ $radiology->id }}">
                                        <input type="number" class="form-control radiology-paid-amount"
                                               name="radiologies[{{ $loop->index }}][paid_amount]"
                                               value="{{ old('radiologies.'.$loop->index.'.paid_amount', $radiology->price) }}"
                                               min="0" max="{{ $radiology->price }}" step="0.01">
                                    </td>
                                    <td>{{ $radiology->status }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Medicines Section - Today Only -->
            <div class="card card-preview mt-3">
                <div class="card-inner">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">IP Medicines - Today</h5>
                    </div>

                    <p class="text-muted small mb-3">Only today's medicines are shown below</p>

                    <div id="medicines-container" class="mt-3">
                        @foreach ($todayMedicines as $index => $medicine)
                            <div class="medicine-row border p-3 mb-3">
                                <input type="hidden" name="medicines[{{ $index }}][id]" value="{{ $medicine->id }}">

                                <div class="row">
                                    <!-- Medicine Selection -->
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Medicine *</label>
                                            <select class="form-control medicine-select" name="medicines[{{ $index }}][medicine_id]" required>
                                                <option value="">Select Medicine</option>
                                                @foreach ($medicines as $med)
                                                    @php
                                                        $decodedName = \App\Helpers\StringHelper::decodeQuotes($med->name);
                                                    @endphp
                                                    <option value="{{ $med->id }}"
                                                        data-price="{{ $med->price }}"
                                                        data-stock="{{ $med->stock }}"
                                                        {{ $medicine->medicine_id == $med->id ? 'selected' : '' }}>
                                                        {{ $decodedName }} ({{ $med->category }}) - Stock: {{ $med->stock }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <small class="form-text text-muted stock-info" id="stock-info-{{ $index }}">
                                                Available: {{ $medicine->medicine->stock ?? 0 }}
                                            </small>
                                        </div>
                                    </div>

                                    <!-- Timing -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label d-block">Timing *</label>
                                            <div class="d-flex justify-content-center align-items-center gap-4">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input timing-checkbox"
                                                        name="medicines[{{ $index }}][morning]" value="1"
                                                        id="morning-{{ $index }}"
                                                        {{ $medicine->morning ? 'checked' : '' }} >
                                                    <label class="form-check-label" for="morning-{{ $index }}">Morning</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input timing-checkbox"
                                                        name="medicines[{{ $index }}][afternoon]" value="1"
                                                        id="afternoon-{{ $index }}"
                                                        {{ $medicine->afternoon ? 'checked' : '' }} >
                                                    <label class="form-check-label" for="afternoon-{{ $index }}">Afternoon</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input timing-checkbox"
                                                        name="medicines[{{ $index }}][night]" value="1"
                                                        id="night-{{ $index }}"
                                                        {{ $medicine->night ? 'checked' : '' }} >
                                                    <label class="form-check-label" for="night-{{ $index }}">Night</label>
                                                </div>
                                            </div>
                                            <div class="timing-error text-danger small mt-1" id="timing-error-{{ $index }}" style="display: none;">
                                                Please select at least one timing option
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Days -->
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">No. of Days *</label>
                                            <input type="number" class="form-control days-input"
                                                name="medicines[{{ $index }}][no_of_days]"
                                                value="{{ $medicine->no_of_days }}" min="1" required>
                                        </div>
                                    </div>

                                    <!-- Quantity -->
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label class="form-label">Qty *</label>
                                            <input type="number" class="form-control quantity-input"
                                                name="medicines[{{ $index }}][quantity]"
                                                value="{{ $medicine->quantity }}" min="1" required readonly>
                                        </div>
                                    </div>

                                    <!-- Price -->
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">Price (per unit) *</label>
                                            <input type="number" class="form-control price-input"
                                                name="medicines[{{ $index }}][price]"
                                                value="{{ $medicine->price }}" step="0.01" required readonly>
                                        </div>
                                    </div>

                                    <!-- Row Total -->
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">Row Total</label>
                                            <input type="text" class="form-control row-total-input"
                                                value="₹{{ number_format($medicine->quantity * $medicine->price, 2) }}" readonly>
                                        </div>
                                    </div>

                                    <!-- Discount Percentage -->
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">Discount %</label>
                                            <input type="number" class="form-control discount-percentage-input"
                                                name="medicines[{{ $index }}][discount_percentage]"
                                                value="{{ $medicine->discount_percentage ?? 0 }}"
                                                min="0" max="100" step="0.01">
                                        </div>
                                    </div>

                                    <!-- Discount Amount -->
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">Discount Amount</label>
                                            <input type="text" class="form-control discount-amount-input"
                                                name="medicines[{{ $index }}][discount_amount]"
                                                value="{{ $medicine->discount_amount ?? 0 }}"
                                                readonly>
                                        </div>
                                    </div>

                                    <!-- Final Amount -->
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">Final Amount</label>
                                            <input type="text" class="form-control final-amount-input"
                                                value="₹{{ number_format(($medicine->quantity * $medicine->price) - ($medicine->discount_amount ?? 0), 2) }}"
                                                readonly>
                                        </div>
                                    </div>

                                    <!-- Status -->
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label class="form-label">Issued</label>
                                            <div class="form-check">
                                                <input class="form-check-input status-checkbox" type="checkbox"
                                                    name="medicines[{{ $index }}][status]" value="active"
                                                    id="status-{{ $index }}"
                                                    {{ ($medicine->status ?? 'active') == 'active' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="status-{{ $index }}">
                                                    Active
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Instructions -->
                                    <div class="col-12 mt-2">
                                        <div class="form-group">
                                            <label class="form-label">Instructions</label>
                                            <textarea class="form-control" name="medicines[{{ $index }}][instructions]" rows="2">{{ $medicine->instructions }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Remove Button -->
                                <button type="button" class="btn btn-sm btn-danger remove-medicine mt-2">
                                    <em class="icon ni ni-trash"></em> Remove
                                </button>
                            </div>
                        @endforeach
                    </div>

                    <!-- Add Medicine Button -->
                    <button type="button" class="btn btn-sm btn-primary" id="add-medicine">
                        <em class="icon ni ni-plus"></em> Add Medicine for Today
                    </button>
                </div>
            </div>

            <!-- Summary & Payment Section -->
            <div class="card card-preview mt-3">
                <div class="card-inner">
                    <h5 class="card-title">Today's Summary & Payment</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="summary-item">
                                @php
                                    // Calculate totals for TODAY's data only
                                    $totalMedicinesAmount = $todayMedicines->sum(function($m) {
                                        return $m->quantity * $m->price;
                                    });
                                    $totalDiscountAmount = $todayMedicines->sum('discount_amount');
                                    $netMedicineAmount = $totalMedicinesAmount - $totalDiscountAmount;

                                    // Calculate today's lab and radiology totals
                                    $labTotal = $todayLabTests->sum('price') ?? 0;
                                    $radiologyTotal = $todayRadiologyTests->sum('price') ?? 0;

                                    // Grand total for TODAY only
                                    $grandTotal = $netMedicineAmount + $labTotal + $radiologyTotal - ($inpatientRegister->overall_discount_amount ?? 0);

                                    // Get existing paid amount
                                    $existingPaidAmount = $inpatientRegister->paid_amount ?? 0;
                                @endphp

                                <p><strong>Today's Medicines Amount:</strong> ₹<span id="totalMedicinesAmount">{{ number_format($totalMedicinesAmount, 2) }}</span></p>
                                <p><strong>Today's Discount:</strong> ₹<span id="totalDiscountAmount">{{ number_format($totalDiscountAmount, 2) }}</span></p>
                                <p><strong>Net Medicine Amount (Today):</strong> ₹<span id="netMedicineAmount">{{ number_format($netMedicineAmount, 2) }}</span></p>

                                @if($todayLabTests->count() > 0)
                                <p><strong>Lab Tests Amount (Today):</strong> ₹<span id="totalLabAmount">{{ number_format($labTotal, 2) }}</span></p>
                                @endif

                                @if($todayRadiologyTests->count() > 0)
                                <p><strong>Radiology Tests Amount (Today):</strong> ₹<span id="totalRadiologyAmount">{{ number_format($radiologyTotal, 2) }}</span></p>
                                @endif

                                <hr>
                                <p><strong>Today's Bill Total:</strong> ₹<span id="grandTotalDisplay">{{ number_format($grandTotal, 2) }}</span></p>
                                <p class="text-muted small">
                                    <em>Note: This is today's bill only. Cumulative total in register: ₹{{ number_format($inpatientRegister->total ?? 0, 2) }}</em>
                                </p>
                                <p><strong>Paid Amount (Today):</strong>
                                    <input type="number" class="form-control d-inline-block w-auto" name="paid_amount"
                                        value="{{ old('paid_amount', 0) }}"
                                        step="0.01" min="0" required style="width: 120px;">
                                </p>
                                <p><strong>Balance Amount (Today):</strong> ₹<span id="balanceDisplay">{{ number_format($grandTotal, 2) }}</span></p>
                                <p class="text-muted small">
                                    <em>Already Paid (Total): ₹{{ number_format($existingPaidAmount, 2) }}</em>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Payment Type -->
                            <div class="form-group">
                                <label class="form-label">Payment Type *</label>
                                <select class="form-control" name="payment_type" required>
                                    <option value="">Select Payment Type</option>
                                    <option value="cash" {{ $inpatientRegister->payment_type == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="card" {{ $inpatientRegister->payment_type == 'card' ? 'selected' : '' }}>Card</option>
                                    <option value="gpay" {{ $inpatientRegister->payment_type == 'gpay' ? 'selected' : '' }}>GPay</option>
                                </select>
                            </div>

                            <!-- Payment Reference -->
                            <div class="form-group">
                                <label class="form-label">Payment Reference (Transaction ID/Card Last 4)</label>
                                <input type="text" class="form-control" name="payment_reference"
                                       value="{{ $inpatientRegister->payment_reference }}">
                            </div>

                            <!-- Payment Status -->
                            <div class="form-group">
                                <label class="form-label">Payment Status (Today)</label>
                                <select class="form-control" name="paid_status" required>
                                    <option value="pending" {{ ($inpatientRegister->paid_status ?? 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="partial" {{ ($inpatientRegister->paid_status ?? 'pending') == 'partial' ? 'selected' : '' }}>Partial</option>
                                    <option value="paid" {{ ($inpatientRegister->paid_status ?? 'pending') == 'paid' ? 'selected' : '' }}>Paid</option>
                                </select>
                            </div>

                            <!-- Overall Discount for Today -->
                            <div class="form-group">
                                <label class="form-label">Overall Discount % (Today)</label>
                                <input type="number" class="form-control" id="overallDiscountPercentage"
                                    name="overall_discount_percentage"
                                    value="{{ old('overall_discount_percentage', 0) }}"
                                    min="0" max="100" step="0.01">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Overall Discount Amount (Today)</label>
                                <input type="text" class="form-control" id="overallDiscountAmount"
                                    name="overall_discount_amount"
                                    value="{{ old('overall_discount_amount', 0) }}"
                                    readonly>
                            </div>

                            <!-- Stock Confirmation -->
                            <div class="mt-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="confirmStock" required>
                                    <label class="form-check-label" for="confirmStock">
                                        I confirm that all medicines are available in stock and ready to be issued.
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="mt-3">
                <button type="submit" class="btn btn-primary" id="submitBtn" style="border-radius: 6px 0 0 6px">
                    <em class="icon ni ni-check"></em> &nbsp; Issue Today's IP Medicines & Collect Payment
                </button>
                <a href="{{ route('pharmacy.index') }}" class="btn btn-secondary"> &nbsp; Cancel</a>
            </div>
        </form>
    </div>
@endsection

@push('styles')
<style>
    .medicine-row {
        position: relative;
        background: #f9f9f9;
        border-radius: 5px;
    }

    .remove-medicine {
        position: absolute;
        top: 10px;
        right: 10px;
    }

    .summary-item p {
        margin-bottom: 8px;
        font-size: 14px;
    }

    .stock-info {
        font-size: 11px;
        color: #666;
    }

    .stock-warning {
        color: #e74c3c;
        font-weight: bold;
    }
</style>
@endpush

@push('scripts')
<script>
    let medicineIndex = {{ $todayMedicines->count() }};

    $(document).ready(function() {
        // Initialize Select2 for medicine selection
        $('.medicine-select').select2({
            placeholder: "Search medicine...",
            allowClear: false,
            width: '100%'
        });

        // Calculate initial totals
        calculateAllTotals();

        // Add new medicine row
        $('#add-medicine').click(function() {
            const medicineRow = createMedicineRow(medicineIndex);
            $('#medicines-container').append(medicineRow);

            // Initialize Select2 for new row
            $('.medicine-select:last').select2({
                placeholder: "Search medicine...",
                allowClear: false,
                width: '100%'
            });

            medicineIndex++;

            // Recalculate totals
            calculateAllTotals();
        });

        // Remove medicine row
        $(document).on('click', '.remove-medicine', function() {
            if (confirm('Are you sure you want to remove this medicine?')) {
                $(this).closest('.medicine-row').remove();
                calculateAllTotals();
            }
        });

        // Medicine selection change
        $(document).on('change', '.medicine-select', function() {
            const row = $(this).closest('.medicine-row');
            updateMedicinePrice(row);
            calculateRowTotals(row);
            calculateAllTotals();
        });

        // Timing checkbox change
        $(document).on('change', '.timing-checkbox', function() {
            const row = $(this).closest('.medicine-row');
            calculateRowQuantity(row);
            calculateRowTotals(row);
            calculateAllTotals();
        });

        // Days input change
        $(document).on('input', '.days-input', function() {
            const row = $(this).closest('.medicine-row');
            calculateRowQuantity(row);
            calculateRowTotals(row);
            calculateAllTotals();
        });

        // Discount percentage change
        $(document).on('input', '.discount-percentage-input', function() {
            const row = $(this).closest('.medicine-row');
            calculateRowDiscount(row);
            calculateRowTotals(row);
            calculateAllTotals();
        });

        // Overall discount percentage change
        $(document).on('input', '#overallDiscountPercentage', function() {
            calculateOverallDiscount();
            calculateAllTotals();
        });

        // Payment inputs change
        $(document).on('input', 'input[name="paid_amount"], .lab-paid-amount, .radiology-paid-amount', function() {
            calculateAllTotals();
        });

        // Form validation
        $('#issueForm').on('submit', function(e) {
            let isValid = true;

            // Check each medicine row
            $('.medicine-row').each(function(index) {
                const timingChecked = $(this).find('.timing-checkbox:checked').length > 0;
                const errorDiv = $(this).find('.timing-error');

                if (!timingChecked) {
                    errorDiv.show();
                    isValid = false;
                } else {
                    errorDiv.hide();
                }

                // Check stock availability
                const medicineId = $(this).find('.medicine-select').val();
                const requiredQty = parseInt($(this).find('.quantity-input').val()) || 0;
                const availableStock = parseInt($(this).find('.medicine-select option:selected').data('stock')) || 0;

                if (medicineId && requiredQty > availableStock) {
                    alert(`Insufficient stock for selected medicine. Available: ${availableStock}, Required: ${requiredQty}`);
                    isValid = false;
                }
            });

            // Check payment type
            const paymentType = $('select[name="payment_type"]').val();
            if (!paymentType) {
                alert('Please select payment type');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
                return false;
            }

            return true;
        });
    });

    // Create new medicine row HTML
    function createMedicineRow(index) {
        return `
            <div class="medicine-row border p-3 mb-3">
                <div class="row">
                    <!-- Medicine Selection -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Medicine *</label>
                            <select class="form-control medicine-select" name="medicines[${index}][medicine_id]" required>
                                <option value="">Select Medicine</option>
                                @foreach ($medicines as $med)
                                    @php
                                        $decodedName = \App\Helpers\StringHelper::decodeQuotes($med->name);
                                    @endphp
                                    <option value="{{ $med->id }}"
                                        data-price="{{ $med->price }}"
                                        data-stock="{{ $med->stock }}">
                                        {{ $decodedName }} ({{ $med->category }}) - Stock: {{ $med->stock }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted stock-info" id="stock-info-${index}">
                                Select a medicine
                            </small>
                        </div>
                    </div>

                    <!-- Timing -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label d-block">Timing *</label>
                            <div class="d-flex justify-content-center align-items-center gap-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input timing-checkbox"
                                        name="medicines[${index}][morning]" value="1"
                                        id="morning-${index}" >
                                    <label class="form-check-label" for="morning-${index}">Morning</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input timing-checkbox"
                                        name="medicines[${index}][afternoon]" value="1"
                                        id="afternoon-${index}" >
                                    <label class="form-check-label" for="afternoon-${index}">Afternoon</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input timing-checkbox"
                                        name="medicines[${index}][night]" value="1"
                                        id="night-${index}" >
                                    <label class="form-check-label" for="night-${index}">Night</label>
                                </div>
                            </div>
                            <div class="timing-error text-danger small mt-1" id="timing-error-${index}" style="display: none;">
                                Please select at least one timing option
                            </div>
                        </div>
                    </div>

                    <!-- Days -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">No. of Days *</label>
                            <input type="number" class="form-control days-input"
                                name="medicines[${index}][no_of_days]" min="1" required>
                        </div>
                    </div>

                    <!-- Quantity -->
                    <div class="col-md-1">
                        <div class="form-group">
                            <label class="form-label">Qty *</label>
                            <input type="number" class="form-control quantity-input"
                                name="medicines[${index}][quantity]" min="1" required readonly>
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">Price (per unit) *</label>
                            <input type="number" class="form-control price-input"
                                name="medicines[${index}][price]" step="0.01" required readonly>
                        </div>
                    </div>

                    <!-- Row Total -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">Row Total</label>
                            <input type="text" class="form-control row-total-input" readonly>
                        </div>
                    </div>

                    <!-- Discount Percentage -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">Discount %</label>
                            <input type="number" class="form-control discount-percentage-input"
                                name="medicines[${index}][discount_percentage]"
                                value="0" min="0" max="100" step="0.01">
                        </div>
                    </div>

                    <!-- Discount Amount -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">Discount Amount</label>
                            <input type="text" class="form-control discount-amount-input"
                                name="medicines[${index}][discount_amount]" value="0" readonly>
                        </div>
                    </div>

                    <!-- Final Amount -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">Final Amount</label>
                            <input type="text" class="form-control final-amount-input" readonly>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="col-md-1">
                        <div class="form-group">
                            <label class="form-label">Issued</label>
                            <div class="form-check">
                                <input class="form-check-input status-checkbox" type="checkbox"
                                    name="medicines[${index}][status]" value="active"
                                    id="status-${index}" checked>
                                <label class="form-check-label" for="status-${index}">
                                    Active
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Instructions -->
                    <div class="col-12 mt-2">
                        <div class="form-group">
                            <label class="form-label">Instructions</label>
                            <textarea class="form-control" name="medicines[${index}][instructions]" rows="2"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Remove Button -->
                <button type="button" class="btn btn-sm btn-danger remove-medicine mt-2">
                    <em class="icon ni ni-trash"></em> Remove
                </button>
            </div>
        `;
    }

    // Calculate row quantity based on timing and days
    function calculateRowQuantity(row) {
        const morning = row.find('.timing-checkbox[name*="morning"]').is(':checked') ? 1 : 0;
        const afternoon = row.find('.timing-checkbox[name*="afternoon"]').is(':checked') ? 1 : 0;
        const night = row.find('.timing-checkbox[name*="night"]').is(':checked') ? 1 : 0;
        const days = parseInt(row.find('.days-input').val()) || 0;

        const totalDosesPerDay = morning + afternoon + night;
        const quantity = totalDosesPerDay * days;

        row.find('.quantity-input').val(quantity > 0 ? quantity : 0);

        // Check stock
        checkStockAvailability(row);
    }

    // Update medicine price when selected
    function updateMedicinePrice(row) {
        const selectedOption = row.find('.medicine-select option:selected');
        const unitPrice = parseFloat(selectedOption.data('price')) || 0;
        const stock = parseInt(selectedOption.data('stock')) || 0;

        row.find('.price-input').val(unitPrice);

        // Update stock info
        const stockInfo = row.find('.stock-info');
        if (selectedOption.val()) {
            stockInfo.text(`Available: ${stock}`);
            if (stock < 1) {
                stockInfo.addClass('stock-warning');
            } else {
                stockInfo.removeClass('stock-warning');
            }
        } else {
            stockInfo.text('Select a medicine');
            stockInfo.removeClass('stock-warning');
        }
    }

    // Check stock availability
    function checkStockAvailability(row) {
        const selectedOption = row.find('.medicine-select option:selected');
        const requiredQty = parseInt(row.find('.quantity-input').val()) || 0;
        const availableStock = parseInt(selectedOption.data('stock')) || 0;
        const stockInfo = row.find('.stock-info');

        if (selectedOption.val() && requiredQty > 0) {
            if (requiredQty > availableStock) {
                stockInfo.text(`Insufficient stock! Available: ${availableStock}, Required: ${requiredQty}`);
                stockInfo.addClass('stock-warning');
            } else {
                stockInfo.text(`Available: ${availableStock}, Required: ${requiredQty}`);
                stockInfo.removeClass('stock-warning');
            }
        }
    }

    // Calculate row discount
    function calculateRowDiscount(row) {
        const quantity = parseInt(row.find('.quantity-input').val()) || 0;
        const unitPrice = parseFloat(row.find('.price-input').val()) || 0;
        const discountPercentage = parseFloat(row.find('.discount-percentage-input').val()) || 0;

        const rowTotal = quantity * unitPrice;
        const discountAmount = (rowTotal * discountPercentage) / 100;

        row.find('.discount-amount-input').val(discountAmount.toFixed(2));
    }

    // Calculate row totals
    function calculateRowTotals(row) {
        const quantity = parseInt(row.find('.quantity-input').val()) || 0;
        const unitPrice = parseFloat(row.find('.price-input').val()) || 0;
        const discountAmount = parseFloat(row.find('.discount-amount-input').val()) || 0;

        const rowTotal = quantity * unitPrice;
        const finalAmount = rowTotal - discountAmount;

        row.find('.row-total-input').val('₹' + rowTotal.toFixed(2));
        row.find('.final-amount-input').val('₹' + finalAmount.toFixed(2));
    }

    // Calculate overall discount
    function calculateOverallDiscount() {
        const totalMedicinesAmount = parseFloat($('#totalMedicinesAmount').text()) || 0;
        const overallDiscountPercentage = parseFloat($('#overallDiscountPercentage').val()) || 0;

        const overallDiscountAmount = (totalMedicinesAmount * overallDiscountPercentage) / 100;

        $('#overallDiscountAmount').val(overallDiscountAmount.toFixed(2));
    }

    // Calculate all totals - TODAY'S DATA ONLY
    function calculateAllTotals() {
        let totalMedicinesAmount = 0;
        let totalDiscountAmount = 0;

        // Calculate medicine-wise totals
        $('.medicine-row').each(function() {
            const quantity = parseInt($(this).find('.quantity-input').val()) || 0;
            const unitPrice = parseFloat($(this).find('.price-input').val()) || 0;
            const discountAmount = parseFloat($(this).find('.discount-amount-input').val()) || 0;

            const rowTotal = quantity * unitPrice;
            totalMedicinesAmount += rowTotal;
            totalDiscountAmount += discountAmount;
        });

        // Calculate lab tests total
        let totalLabAmount = 0;
        $('.lab-paid-amount').each(function() {
            const value = parseFloat($(this).val()) || 0;
            totalLabAmount += value;
        });

        // Calculate radiology tests total
        let totalRadiologyAmount = 0;
        $('.radiology-paid-amount').each(function() {
            const value = parseFloat($(this).val()) || 0;
            totalRadiologyAmount += value;
        });

        // Calculate overall discount
        const overallDiscountPercentage = parseFloat($('#overallDiscountPercentage').val()) || 0;
        const overallDiscountAmount = (totalMedicinesAmount * overallDiscountPercentage) / 100;
        $('#overallDiscountAmount').val(overallDiscountAmount.toFixed(2));

        // Total discount (medicine-wise + overall)
        const totalDiscount = totalDiscountAmount + overallDiscountAmount;

        // Net medicine amount
        const netMedicineAmount = totalMedicinesAmount - totalDiscount;

        // Update display
        $('#totalMedicinesAmount').text(totalMedicinesAmount.toFixed(2));
        $('#totalDiscountAmount').text(totalDiscount.toFixed(2));
        $('#netMedicineAmount').text(netMedicineAmount.toFixed(2));

        // Update lab and radiology displays
        $('#totalLabAmount').text(totalLabAmount.toFixed(2));
        $('#totalRadiologyAmount').text(totalRadiologyAmount.toFixed(2));

        // Calculate grand total (TODAY ONLY)
        const grandTotal = netMedicineAmount + totalLabAmount + totalRadiologyAmount;
        $('#grandTotalDisplay').text(grandTotal.toFixed(2));

        // Calculate balance (TODAY ONLY)
        const paidAmount = parseFloat($('input[name="paid_amount"]').val()) || 0;
        const balance = grandTotal - paidAmount;
        $('#balanceDisplay').text(balance.toFixed(2));
    }
</script>
@endpush
