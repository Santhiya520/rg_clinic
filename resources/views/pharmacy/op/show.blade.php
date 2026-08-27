@extends('layouts.app')

@section('title', 'Pharmacy - Issue OP Medicines')
@section('page-title', 'Issue OP Medicines - ' . ($opRegister->patient->name ?? 'N/A'))

@section('content')
    <div class="nk-block nk-block-lg">
        <form action="{{ route('pharmacy.op.issue', $opRegister) }}" method="POST" id="issueForm">
            @csrf

            <!-- Patient Details -->
            <div class="card card-preview">
                <div class="card-inner">
                    <div class="nk-block-head">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h5 class="nk-block-title">OP Patient Details</h5>
                                <p class="text-soft">
                                    Patient: <strong>{{ $opRegister->patient?->name ?? 'N/A' }}</strong>
                                    ({{ $opRegister->patient?->patient_id ?? 'N/A' }}) |
                                    Token: {{ $opRegister->token_number }} |
                                    Date: {{ $opRegister->date->format('d/m/Y') }}
                                </p>
                            </div>
                            <div class="nk-block-head-content">
                                <a href="{{ route('pharmacy.index') }}" class="btn btn-secondary"
                                    style="border-radius: 5px">
                                    <em class="icon ni ni-arrow-left"></em>&nbsp; Back to List
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lab Tests Section -->
            @if ($opRegister->labTests && $opRegister->labTests->count() > 0)
                <div class="card card-preview mt-3">
                    <div class="card-inner">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0">Lab Tests</h5>
                            <button type="button" class="btn btn-sm btn-danger" id="remove-all-lab-tests">
                                <em class="icon ni ni-trash"></em> Remove All
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table" id="lab-tests-table">
                                <thead>
                                    <tr>
                                        <th width="50">
                                            <input type="checkbox" id="select-all-lab-tests">
                                        </th>
                                        <th>Test Name</th>
                                        <th>Price</th>
                                        <th>Paid Amount</th>
                                        <th>Status</th>
                                        <th width="80">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($opRegister->labTests as $index => $labTest)
                                        <tr data-lab-id="{{ $labTest->id }}">
                                            <td>
                                                <input type="checkbox" class="lab-test-checkbox" data-id="{{ $labTest->id }}">
                                            </td>
                                            <td>{{ $labTest->labTest->name ?? 'N/A' }}</td>
                                            <td>₹{{ number_format($labTest->price, 2) }}</td>
                                            <td>
                                                <input type="hidden" name="lab_tests[{{ $index }}][id]"
                                                    value="{{ $labTest->id }}">
                                                <input type="number" class="form-control lab-paid-amount"
                                                    name="lab_tests[{{ $index }}][paid_amount]"
                                                    value="{{ old('lab_tests.' . $index . '.paid_amount', $labTest->paid_amount ?? $labTest->price) }}"
                                                    min="0" max="{{ $labTest->price }}" step="0.01"
                                                    {{ $labTest->status == 'paid' ? 'readonly' : '' }}>
                                            </td>
                                            <td>
                                                <span class="badge {{ $labTest->status == 'paid' ? 'bg-success' : ($labTest->status == 'pending' ? 'bg-warning' : 'bg-secondary') }}">
                                                    {{ ucfirst($labTest->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($labTest->status != 'paid')
                                                    <button type="button" class="btn btn-sm btn-danger remove-lab-test" data-id="{{ $labTest->id }}">
                                                        <em class="icon ni ni-trash"></em>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Radiology Tests Section -->
            @if ($opRegister->radiologies && $opRegister->radiologies->count() > 0)
                <div class="card card-preview mt-3">
                    <div class="card-inner">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0">Radiology Tests</h5>
                            <button type="button" class="btn btn-sm btn-danger" id="remove-all-radiology-tests">
                                <em class="icon ni ni-trash"></em> Remove All
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table" id="radiology-tests-table">
                                <thead>
                                    <tr>
                                        <th width="50">
                                            <input type="checkbox" id="select-all-radiology-tests">
                                        </th>
                                        <th>Test Name</th>
                                        <th>Price</th>
                                        <th>Paid Amount</th>
                                        <th>Status</th>
                                        <th width="80">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($opRegister->radiologies as $index => $radiology)
                                        <tr data-radiology-id="{{ $radiology->id }}">
                                            <td>
                                                <input type="checkbox" class="radiology-test-checkbox" data-id="{{ $radiology->id }}">
                                            </td>
                                            <td>{{ $radiology->radiologyTest->name ?? 'N/A' }}</td>
                                            <td>₹{{ number_format($radiology->price, 2) }}</td>
                                            <td>
                                                <input type="hidden" name="radiologies[{{ $index }}][id]"
                                                    value="{{ $radiology->id }}">
                                                <input type="number" class="form-control radiology-paid-amount"
                                                    name="radiologies[{{ $index }}][paid_amount]"
                                                    value="{{ old('radiologies.' . $index . '.paid_amount', $radiology->paid_amount ?? $radiology->price) }}"
                                                    min="0" max="{{ $radiology->price }}" step="0.01"
                                                    {{ $radiology->status == 'paid' ? 'readonly' : '' }}>
                                            </td>
                                            <td>
                                                <span class="badge {{ $radiology->status == 'paid' ? 'bg-success' : ($radiology->status == 'pending' ? 'bg-warning' : 'bg-secondary') }}">
                                                    {{ ucfirst($radiology->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($radiology->status != 'paid')
                                                    <button type="button" class="btn btn-sm btn-danger remove-radiology-test" data-id="{{ $radiology->id }}">
                                                        <em class="icon ni ni-trash"></em>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Medicines Section -->
            <div class="card card-preview mt-3">
                <div class="card-inner">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">OP Medicines</h5>
                    </div>

                    <div id="medicines-container" class="mt-3">
                        @foreach ($opRegister->medicines as $index => $medicine)
                            <div class="medicine-row border p-3 mb-3">
                                <input type="hidden" name="medicines[{{ $index }}][id]"
                                    value="{{ $medicine->id }}">

                                <div class="row">
                                    <!-- Medicine Selection -->
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Medicine *</label>
                                            <select class="form-control medicine-select"
                                                name="medicines[{{ $index }}][medicine_id]" required>
                                                <option value="">Select Medicine</option>
                                                @foreach ($medicines as $med)
                                                    @php
                                                        $decodedName = \App\Helpers\StringHelper::decodeQuotes(
                                                            $med->name,
                                                        );
                                                    @endphp
                                                    <option value="{{ $med->id }}" data-price="{{ $med->price }}"
                                                        data-stock="{{ $med->stock }}"
                                                        {{ $medicine->medicine_id == $med->id ? 'selected' : '' }}>
                                                        {{ $decodedName }} ({{ $med->category }}) - Stock:
                                                        {{ $med->stock }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <small class="form-text text-muted stock-info"
                                                id="stock-info-{{ $index }}">
                                                Available: {{ $medicine->medicine->stock ?? 0 }}
                                            </small>
                                        </div>
                                    </div>

                                    <!-- Timing -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label d-block">Timing</label>
                                            <div class="d-flex justify-content-center align-items-center gap-4">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input timing-checkbox"
                                                        name="medicines[{{ $index }}][morning]" value="1"
                                                        id="morning-{{ $index }}"
                                                        {{ $medicine->morning ? 'checked' : '' }}>
                                                    <label class="form-check-label"
                                                        for="morning-{{ $index }}">Morning</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input timing-checkbox"
                                                        name="medicines[{{ $index }}][afternoon]" value="1"
                                                        id="afternoon-{{ $index }}"
                                                        {{ $medicine->afternoon ? 'checked' : '' }}>
                                                    <label class="form-check-label"
                                                        for="afternoon-{{ $index }}">Afternoon</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input timing-checkbox"
                                                        name="medicines[{{ $index }}][night]" value="1"
                                                        id="night-{{ $index }}"
                                                        {{ $medicine->night ? 'checked' : '' }}>
                                                    <label class="form-check-label"
                                                        for="night-{{ $index }}">Night</label>
                                                </div>
                                            </div>
                                            <div class="timing-error text-danger small mt-1"
                                                id="timing-error-{{ $index }}" style="display: none;">
                                                Please select at least one timing option
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Days -->
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">No. of Days</label>
                                            <input type="number" class="form-control days-input"
                                                name="medicines[{{ $index }}][no_of_days]"
                                                value="{{ $medicine->no_of_days }}">
                                        </div>
                                    </div>

                                    <!-- Quantity -->
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label class="form-label">Qty *</label>
                                            <input type="number" class="form-control quantity-input"
                                                name="medicines[{{ $index }}][quantity]"
                                                value="{{ $medicine->quantity }}" min="1" required>
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
                                                value="₹{{ number_format($medicine->quantity * $medicine->price, 2) }}"
                                                readonly>
                                        </div>
                                    </div>

                                    <!-- Discount Percentage -->
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">Discount %</label>
                                            <input type="number" class="form-control discount-percentage-input"
                                                name="medicines[{{ $index }}][discount_percentage]"
                                                value="{{ $medicine->discount_percentage ?? 0 }}" min="0"
                                                max="100" step="0.01">
                                        </div>
                                    </div>

                                    <!-- Discount Amount -->
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">Discount Amount</label>
                                            <input type="text" class="form-control discount-amount-input"
                                                name="medicines[{{ $index }}][discount_amount]"
                                                value="{{ $medicine->discount_amount ?? 0 }}" readonly>
                                        </div>
                                    </div>

                                    <!-- Final Amount -->
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">Final Amount</label>
                                            <input type="text" class="form-control final-amount-input"
                                                value="₹{{ number_format($medicine->quantity * $medicine->price - ($medicine->discount_amount ?? 0), 2) }}"
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

                                <button type="button" class="btn btn-sm btn-danger remove-medicine mt-2">
                                    <em class="icon ni ni-trash"></em> Remove
                                </button>
                            </div>
                        @endforeach
                    </div>

                    <button type="button" class="btn btn-sm btn-primary" id="add-medicine">
                        <em class="icon ni ni-plus"></em> Add Medicine
                    </button>
                </div>
            </div>

            <!-- Summary & Payment Section -->
            <div class="card card-preview mt-3">
                <div class="card-inner">
                    <h5 class="card-title">Summary & Payment</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="summary-item">
                                @php
                                    // Calculate totals in PHP
                                    $totalMedicinesAmount = $opRegister->medicines->sum(function ($m) {
                                        return $m->quantity * $m->price;
                                    });
                                    $totalDiscountAmount = $opRegister->medicines->sum('discount_amount');
                                    $netMedicineAmount = $totalMedicinesAmount - $totalDiscountAmount;
                                    $labTotal = $opRegister->labTests->sum('price');
                                    $radiologyTotal = $opRegister->radiologies->sum('price');
                                    $doctorFeesAmount = $doctorFees ?? 0;
                                    $injectionFeesAmount = $injectionFees ?? 0;
                                    $procedureAmount = $procedureAmount ?? 0;
                                    $subtotal =
                                        $netMedicineAmount +
                                        $labTotal +
                                        $radiologyTotal +
                                        $doctorFeesAmount +
                                        $injectionFeesAmount +
                                        $procedureAmount;
                                    $gstPercentage = $opRegister->gst_percentage ?? 0;
                                    $gstAmount = ($subtotal * $gstPercentage) / 100;
                                    $grandTotal = $subtotal + $gstAmount;
                                    $roundedGrandTotal = round($grandTotal); // Round off grand total
                                @endphp

                                <p><strong>Total Medicines Amount:</strong> ₹<span
                                        id="totalMedicinesAmount">{{ number_format($totalMedicinesAmount, 2) }}</span></p>
                                <p><strong>Total Discount:</strong> ₹<span
                                        id="totalDiscountAmount">{{ number_format($totalDiscountAmount, 2) }}</span></p>
                                <p><strong>Net Medicine Amount:</strong> ₹<span
                                        id="netMedicineAmount">{{ number_format($netMedicineAmount, 2) }}</span></p>

                                @if ($opRegister->labTests && $opRegister->labTests->count() > 0)
                                    <p><strong>Lab Tests Amount:</strong> ₹<span
                                            id="totalLabAmount">{{ number_format($labTotal, 2) }}</span></p>
                                @endif

                                @if ($opRegister->radiologies && $opRegister->radiologies->count() > 0)
                                    <p><strong>Radiology Tests Amount:</strong> ₹<span
                                            id="totalRadiologyAmount">{{ number_format($radiologyTotal, 2) }}</span></p>
                                @endif

                                <p><strong>Doctor Fees:</strong>
                                    <input type="number" class="form-control d-inline-block w-auto" name="doctor_fees"
                                        value="{{ old('doctor_fees', $doctorFees ?? 0) }}" step="0.01" min="0"
                                        style="width: 120px;">
                                </p>

                                <p><strong>Injection Fees:</strong>
                                    <input type="number" class="form-control d-inline-block w-auto"
                                        name="injection_fees" value="{{ old('injection_fees', $injectionFees ?? 0) }}"
                                        step="0.01" min="0" style="width: 120px;">
                                </p>

                                <p><strong>Procedure Amount:</strong>
                                    <input type="number" class="form-control d-inline-block w-auto"
                                        name="procedure_amount" value="{{ old('procedure_amount', $procedureAmount ?? 0) }}"
                                        step="0.01" min="0" style="width: 120px;">
                                </p>

                                <hr>

                                <p><strong>Subtotal:</strong> ₹<span
                                        id="subtotalDisplay">{{ number_format($subtotal, 2) }}</span></p>

                                    <input type="number" class="form-control d-inline-block w-auto"
                                        name="gst_percentage" value="0"
                                        step="0.01" min="0" style="width: 100px;" hidden>

                                <!-- HIDDEN INPUT FIELD FOR GST AMOUNT - FIXED: now properly saved and submitted -->
                                <input type="hidden" name="gst_amount" id="gstAmountHidden" value="{{ number_format($gstAmount, 2) }}">

                                <p><strong>Grand Total:</strong> ₹<span
                                        id="grandTotalDisplay">{{ number_format($roundedGrandTotal, 2) }}</span>
                                    <small class="text-muted">(Rounded off)</small>
                                </p>
                                <input type="hidden" name="grand_total_rounded" id="grandTotalRounded" value="{{ $roundedGrandTotal }}">

                                <p><strong>Paid Amount:</strong>
                                    <input type="number" class="form-control d-inline-block w-auto" name="paid_amount"
                                        value="{{ old('paid_amount', $opRegister->paid_amount ?: 0) }}" step="0.01"
                                        min="0" required style="width: 120px;">
                                </p>
                                <p><strong>Balance Amount:</strong> ₹<span
                                        id="balanceDisplay">{{ number_format($roundedGrandTotal - ($opRegister->paid_amount ?? 0), 2) }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Payment Type -->
                            <div class="form-group">
                                <label class="form-label">Payment Type </label>
                                <select class="form-control" name="payment_type">
                                    <option value="">Select Payment Type</option>
                                    <option value="cash" {{ $opRegister->payment_type == 'cash' ? 'selected' : '' }}>
                                        Cash</option>
                                    <option value="card" {{ $opRegister->payment_type == 'card' ? 'selected' : '' }}>
                                        Card</option>
                                    <option value="gpay" {{ $opRegister->payment_type == 'gpay' ? 'selected' : '' }}>
                                        GPay</option>
                                </select>
                            </div>

                            <!-- Payment Reference -->
                            <div class="form-group">
                                <label class="form-label">Payment Reference (Transaction ID/Card Last 4)</label>
                                <input type="text" class="form-control" name="payment_reference"
                                    value="{{ $opRegister->payment_reference }}">
                            </div>

                            <!-- Payment Status -->
                            <div class="form-group">
                                <label class="form-label">Payment Status</label>
                                <select class="form-control" name="paid_status" required>
                                    <option value="pending" {{ $opRegister->paid_status == 'pending' ? 'selected' : '' }}>
                                        Pending</option>
                                    <option value="partial" {{ $opRegister->paid_status == 'partial' ? 'selected' : '' }}>
                                        Partial</option>
                                    <option value="paid" {{ $opRegister->paid_status == 'paid' ? 'selected' : '' }}>Paid
                                    </option>
                                </select>
                            </div>

                            <!-- Overall Discount -->
                            <div class="form-group">
                                <label class="form-label">Overall Discount %</label>
                                <input type="number" class="form-control" id="overallDiscountPercentage"
                                    name="overall_discount_percentage"
                                    value="{{ $opRegister->overall_discount_percentage ?? 0 }}" min="0"
                                    max="100" step="0.01">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Overall Discount Amount</label>
                                <input type="text" class="form-control" id="overallDiscountAmount"
                                    name="overall_discount_amount"
                                    value="{{ number_format($opRegister->overall_discount_amount ?? 0, 2) }}" readonly>
                            </div>

                            <!-- Round Off Options -->
                            <div class="form-group mt-3">
                                <label class="form-label">Round Off Preference</label>
                                <select class="form-control" id="roundOffPreference">
                                    <option value="nearest">Round to Nearest Rupee</option>
                                    <option value="up">Round Up</option>
                                    <option value="down">Round Down</option>
                                </select>
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

            <!-- Hidden inputs for removed tests -->
            <input type="hidden" name="removed_lab_tests" id="removedLabTests" value="">
            <input type="hidden" name="removed_radiologies" id="removedRadiologies" value="">

            <!-- Submit Buttons -->
            <div class="mt-3">
                <button type="submit" class="btn btn-primary" id="submitBtn" style="border-radius: 6px 0 0 6px">
                    <em class="icon ni ni-check"></em> &nbsp; Issue Medicines & Collect Payment
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

        .badge {
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
        }

        .bg-success {
            background-color: #28a745;
            color: white;
        }

        .bg-warning {
            background-color: #ffc107;
            color: #212529;
        }

        .bg-secondary {
            background-color: #6c757d;
            color: white;
        }

        .round-off-badge {
            font-size: 12px;
            background-color: #e8f4fd;
            color: #0c63e4;
            padding: 2px 8px;
            border-radius: 4px;
            margin-left: 8px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        let medicineIndex = {{ $opRegister->medicines->count() }};
        let removedLabTests = [];
        let removedRadiologies = [];

        // Round off function
        function roundOffValue(value, method = 'nearest') {
            if (method === 'nearest') {
                return Math.round(value);
            } else if (method === 'up') {
                return Math.ceil(value);
            } else if (method === 'down') {
                return Math.floor(value);
            }
            return value;
        }

        $(document).ready(function() {
            // Initialize Select2
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

            // Quantity input change
            $(document).on('input', '.quantity-input', function() {
                const row = $(this).closest('.medicine-row');
                calculateRowTotals(row);
                calculateAllTotals();
            });

            // Price input change
            $(document).on('input', '.price-input', function() {
                const row = $(this).closest('.medicine-row');
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

            // Round off preference change
            $(document).on('change', '#roundOffPreference', function() {
                calculateAllTotals();
            });

            // Payment inputs change
            $(document).on('input',
                'input[name="doctor_fees"], input[name="injection_fees"], input[name="procedure_amount"], input[name="paid_amount"], .lab-paid-amount, .radiology-paid-amount',
                function() {
                    calculateAllTotals();
                });

            // GST percentage change - also updates hidden field
            $(document).on('input', 'input[name="gst_percentage"]', function() {
                calculateAllTotals();
            });

            // Lab test removal
            $(document).on('click', '.remove-lab-test', function() {
                const testId = $(this).data('id');
                if (confirm('Are you sure you want to remove this lab test?')) {
                    const row = $(this).closest('tr');
                    removedLabTests.push(testId);
                    $('#removedLabTests').val(JSON.stringify(removedLabTests));
                    row.remove();
                    calculateAllTotals();
                }
            });

            // Radiology test removal
            $(document).on('click', '.remove-radiology-test', function() {
                const testId = $(this).data('id');
                if (confirm('Are you sure you want to remove this radiology test?')) {
                    const row = $(this).closest('tr');
                    removedRadiologies.push(testId);
                    $('#removedRadiologies').val(JSON.stringify(removedRadiologies));
                    row.remove();
                    calculateAllTotals();
                }
            });

            // Select all lab tests
            $('#select-all-lab-tests').change(function() {
                $('.lab-test-checkbox').prop('checked', $(this).prop('checked'));
            });

            // Select all radiology tests
            $('#select-all-radiology-tests').change(function() {
                $('.radiology-test-checkbox').prop('checked', $(this).prop('checked'));
            });

            // Remove all selected lab tests
            $('#remove-all-lab-tests').click(function() {
                const selectedTests = $('.lab-test-checkbox:checked');
                if (selectedTests.length === 0) {
                    alert('Please select at least one lab test to remove');
                    return;
                }

                if (confirm(`Are you sure you want to remove ${selectedTests.length} lab test(s)?`)) {
                    selectedTests.each(function() {
                        const testId = $(this).data('id');
                        removedLabTests.push(testId);
                        $(this).closest('tr').remove();
                    });
                    $('#removedLabTests').val(JSON.stringify(removedLabTests));
                    $('#select-all-lab-tests').prop('checked', false);
                    calculateAllTotals();
                }
            });

            // Remove all selected radiology tests
            $('#remove-all-radiology-tests').click(function() {
                const selectedTests = $('.radiology-test-checkbox:checked');
                if (selectedTests.length === 0) {
                    alert('Please select at least one radiology test to remove');
                    return;
                }

                if (confirm(`Are you sure you want to remove ${selectedTests.length} radiology test(s)?`)) {
                    selectedTests.each(function() {
                        const testId = $(this).data('id');
                        removedRadiologies.push(testId);
                        $(this).closest('tr').remove();
                    });
                    $('#removedRadiologies').val(JSON.stringify(removedRadiologies));
                    $('#select-all-radiology-tests').prop('checked', false);
                    calculateAllTotals();
                }
            });

            // Form validation
            $('#issueForm').on('submit', function(e) {
                let isValid = true;

                // Check payment type
                const paymentType = $('select[name="payment_type"]').val();
                if (!paymentType) {
                    alert('Please select payment type');
                    isValid = false;
                }

                // Check if any active medicines have quantity
                $('.medicine-row').each(function() {
                    const isActive = $(this).find('.status-checkbox').is(':checked');
                    const quantity = parseInt($(this).find('.quantity-input').val()) || 0;

                    if (isActive && quantity <= 0) {
                        alert('Active medicines must have quantity greater than 0');
                        isValid = false;
                        return false;
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    return false;
                }

                return true;
            });
        });

        // Create new medicine row
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
                            <label class="form-label">No. of Days</label>
                            <input type="number" class="form-control days-input"
                                name="medicines[${index}][no_of_days]" >
                        </div>
                    </div>

                    <!-- Quantity -->
                    <div class="col-md-1">
                        <div class="form-group">
                            <label class="form-label">Qty *</label>
                            <input type="number" class="form-control quantity-input"
                                name="medicines[${index}][quantity]" min="1" required >
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

        // Calculate row quantity
        function calculateRowQuantity(row) {
            const morning = row.find('.timing-checkbox[name*="morning"]').is(':checked') ? 1 : 0;
            const afternoon = row.find('.timing-checkbox[name*="afternoon"]').is(':checked') ? 1 : 0;
            const night = row.find('.timing-checkbox[name*="night"]').is(':checked') ? 1 : 0;
            const days = parseInt(row.find('.days-input').val()) || 0;

            const totalDosesPerDay = morning + afternoon + night;
            const quantity = totalDosesPerDay * days;

            row.find('.quantity-input').val(quantity > 0 ? quantity : 0);
            checkStockAvailability(row);
        }

        // Update medicine price
        function updateMedicinePrice(row) {
            const selectedOption = row.find('.medicine-select option:selected');
            const unitPrice = parseFloat(selectedOption.data('price')) || 0;
            const stock = parseInt(selectedOption.data('stock')) || 0;

            row.find('.price-input').val(unitPrice);

            const stockInfo = row.find('.stock-info');
            if (selectedOption.val()) {
                stockInfo.text(`Available: ${stock}`);
                stockInfo.toggleClass('stock-warning', stock < 1);
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

        // Calculate all totals - WITH ROUND OFF and update hidden GST field
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

            // Calculate lab tests total from visible rows
            let totalLabAmount = 0;
            $('#lab-tests-table tbody tr').each(function() {
                const paidAmount = parseFloat($(this).find('.lab-paid-amount').val()) || 0;
                totalLabAmount += paidAmount;
            });

            // Calculate radiology tests total from visible rows
            let totalRadiologyAmount = 0;
            $('#radiology-tests-table tbody tr').each(function() {
                const paidAmount = parseFloat($(this).find('.radiology-paid-amount').val()) || 0;
                totalRadiologyAmount += paidAmount;
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
            if ($('#totalLabAmount').length) {
                $('#totalLabAmount').text(totalLabAmount.toFixed(2));
            }
            if ($('#totalRadiologyAmount').length) {
                $('#totalRadiologyAmount').text(totalRadiologyAmount.toFixed(2));
            }

            // Get additional fees
            const doctorFees = parseFloat($('input[name="doctor_fees"]').val()) || 0;
            const injectionFees = parseFloat($('input[name="injection_fees"]').val()) || 0;
            const procedureAmount = parseFloat($('input[name="procedure_amount"]').val()) || 0;

            // Calculate subtotal
            const subtotal = netMedicineAmount + totalLabAmount + totalRadiologyAmount + doctorFees + injectionFees + procedureAmount;
            $('#subtotalDisplay').text(subtotal.toFixed(2));

            // Calculate GST (applies only to medicine amount as per medical billing standards)
            const gstPercentage = parseFloat($('input[name="gst_percentage"]').val()) || 0;
            const gstAmount = (netMedicineAmount * gstPercentage) / 100;
            // IMPORTANT: Update hidden GST amount field so it gets submitted with form
            $('#gstAmountHidden').val(gstAmount.toFixed(2));

            // Calculate grand total before round off
            const grandTotalBeforeRound = subtotal + gstAmount;

            // Get round off preference
            const roundOffMethod = $('#roundOffPreference').val();

            // Apply round off
            const roundedGrandTotal = roundOffValue(grandTotalBeforeRound, roundOffMethod);

            // Display rounded grand total
            $('#grandTotalDisplay').text(roundedGrandTotal.toFixed(2));

            // Store rounded value in hidden field
            $('#grandTotalRounded').val(roundedGrandTotal);

            // Calculate balance using rounded grand total
            const paidAmount = parseFloat($('input[name="paid_amount"]').val()) || 0;
            const balance = roundedGrandTotal - paidAmount;
            $('#balanceDisplay').text(balance.toFixed(2));
        }
    </script>
@endpush