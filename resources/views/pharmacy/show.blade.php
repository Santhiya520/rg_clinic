@extends('layouts.app')

@section('title', 'Pharmacy - Issue Medicines')
@section('page-title', 'Issue Medicines - ' . ($opRegister->patient->name ?? 'N/A'))

@section('content')
    <div class="nk-block nk-block-lg">
        <form action="{{ route('pharmacy.issue', $opRegister) }}" method="POST">
            @csrf

            <!-- Patient Details -->
            <div class="card card-preview">
                <div class="card-inner">
                    <div class="nk-block-head">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <p class="text-soft">Patient: <strong>{{ $opRegister->patient?->name ?? 'N/A' }}</strong>
                                    ({{ $opRegister->patient?->patient_id ?? 'N/A' }}) | Token:
                                    {{ $opRegister->token_number }}</p>
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

            <!-- Medicines Section -->
            <div class="card card-preview mt-3">
                <div class="card-inner">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Medicines</h5>
                        <button type="button" class="btn btn-sm btn-primary" id="add-medicine" style="border-radius: 5px">
                            <em class="icon ni ni-plus"></em>&nbsp; Add Medicine
                        </button>
                    </div>

                    <div id="medicines-container" class="mt-3">
                        @foreach ($opRegister->medicines as $index => $medicine)
                            <div class="medicine-row border p-3 mb-3">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Medicine *</label>
                                            <select class="form-control medicine-select select2-search"
                                                name="medicines[{{ $index }}][medicine_id]" required >
                                                <option value="">Select Medicine</option>
                                                @foreach ($medicines as $med)
                                                    <option value="{{ $med->id }}"
                                                        {{ $medicine->medicine_id == $med->id ? 'selected' : '' }}
                                                        data-price="{{ $med->price }}">
                                                        {{ $med->name }} ({{ $med->category }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
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
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">No. of Days *</label>
                                            <input type="number" class="form-control days-input"
                                                name="medicines[{{ $index }}][no_of_days]"
                                                value="{{ $medicine->no_of_days }}" min="1" required>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label class="form-label">Qty *</label>
                                            <input type="number" class="form-control quantity-input"
                                                name="medicines[{{ $index }}][quantity]"
                                                value="{{ $medicine->quantity }}" min="1" required readonly>
                                           </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">Price (per unit) *</label>
                                            <input type="number" class="form-control price-input"
                                                name="medicines[{{ $index }}][price]"
                                                value="{{ $medicine->price }}" step="0.01" required readonly>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label">Instructions</label>
                                            <textarea class="form-control" name="medicines[{{ $index }}][instructions]" rows="2">{{ $medicine->instructions }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-danger remove-medicine mt-2" style="border-radius: 5px">Remove</button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Payment Details -->
            <div class="card card-preview mt-3">
                <div class="card-inner">
                    <h5 class="card-title">Payment Details</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Medicine Total:</strong> ₹<span id="medicineTotal">0.00</span></p>
                            <p><strong>Doctor Fees:</strong> ₹<span
                                    id="doctorFeesDisplay">{{ $opRegister->medicalOfficer->consulting_fee ?? 0 }}</span>
                                <input type="hidden" name="doctor_fees"
                                    value="{{ $opRegister->medicalOfficer->consulting_fee ?? 0 }}">
                            </p>
                            <p><strong>Grand Total:</strong> ₹<span id="grandTotalDisplay">0.00</span></p>
                            <p><strong>Paid Amount:</strong>
                                <input type="number" class="form-control d-inline-block w-auto" name="paid_amount"
                                    value="{{ old('paid_amount', $opRegister->total ?: 0) }}" step="0.01"
                                    min="0" required style="width: 120px;">
                            </p>
                            <p><strong>Balance Amount:</strong> ₹<span id="balanceDisplay">0.00</span></p>
                        </div>
                        <div class="col-md-6">
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

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary" id="submitBtn" style="border-radius: 6px 0 0 6px">
                            <em class="icon ni ni-check"></em> &nbsp; Issue Medicines & Update Stock
                        </button>
                        <a href="{{ route('pharmacy.index') }}" class="btn btn-secondary"> &nbsp; Cancel</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

<style>
    .select2-selection--single .select2-selection__rendered {
        color: #444;
        /* line-height: 12px !important; */
    }

</style>

@push('scripts')
    <script>
        let medicineIndex = {{ $opRegister->medicines->count() }};

        // Function to calculate quantity for a medicine row
        function calculateQuantity(row) {
            const morning = row.find('.timing-checkbox[name*="morning"]').is(':checked') ? 1 : 0;
            const afternoon = row.find('.timing-checkbox[name*="afternoon"]').is(':checked') ? 1 : 0;
            const night = row.find('.timing-checkbox[name*="night"]').is(':checked') ? 1 : 0;
            const days = parseInt(row.find('.days-input').val()) || 0;

            const totalDosesPerDay = morning + afternoon + night;
            const quantity = totalDosesPerDay * days;

            return quantity > 0 ? quantity : 0;
        }

        // Function to calculate row total amount (quantity × unit price)
        function calculateRowAmount(row) {
            const quantity = parseInt(row.find('.quantity-input').val()) || 0;
            const unitPrice = parseFloat(row.find('.price-input').val()) || 0;

            return quantity * unitPrice;
        }

        // Function to update quantity for a row
        function updateRowQuantity(row) {
            const quantity = calculateQuantity(row);
            row.find('.quantity-input').val(quantity);
        }

        // Function to update unit price when medicine is selected
        function updateUnitPrice(row) {
            const selectedOption = row.find('.medicine-select option:selected');
            const unitPrice = selectedOption.data('price') || 0;
            row.find('.price-input').val(unitPrice);
        }

        // Add Medicine Row
        $('#add-medicine').click(function() {
            const medicineRow = `
            <div class="medicine-row border p-3 mb-3">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Medicine *</label>
                            <select class="form-control medicine-select select2-search" name="medicines[${medicineIndex}][medicine_id]" required>
                                <option value="">Select Medicine</option>
                                @foreach ($medicines as $med)
                                    <option value="{{ $med->id }}" data-price="{{ $med->price }}">
                                        {{ $med->name }} ({{ $med->category }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label d-block">Timing</label>
                            <div class="d-flex justify-content-center align-items-center gap-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input timing-checkbox" name="medicines[${medicineIndex}][morning]" value="1" id="morning-${medicineIndex}">
                                    <label class="form-check-label" for="morning-${medicineIndex}">Morning</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input timing-checkbox" name="medicines[${medicineIndex}][afternoon]" value="1" id="afternoon-${medicineIndex}">
                                    <label class="form-check-label" for="afternoon-${medicineIndex}">Afternoon</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input timing-checkbox" name="medicines[${medicineIndex}][night]" value="1" id="night-${medicineIndex}">
                                    <label class="form-check-label" for="night-${medicineIndex}">Night</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">No. of Days *</label>
                            <input type="number" class="form-control days-input" name="medicines[${medicineIndex}][no_of_days]" min="1" required>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label class="form-label">Qty *</label>
                            <input type="number" class="form-control quantity-input" name="medicines[${medicineIndex}][quantity]" min="1" required readonly>
                            <small class="form-text text-muted">Auto calculated</small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">Price (per unit) *</label>
                            <input type="number" class="form-control price-input" name="medicines[${medicineIndex}][price]" step="0.01" required readonly>
                            <small class="form-text text-muted">Unit price</small>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label">Instructions</label>
                            <textarea class="form-control" name="medicines[${medicineIndex}][instructions]" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-danger remove-medicine">Remove</button>
            </div>
        `;
            $('#medicines-container').append(medicineRow);

            // Initialize Select2 for the new medicine select
            $('.medicine-select:last').select2({
                placeholder: "Search medicine...",
                allowClear: false,
                width: '100%'
            });

            medicineIndex++;
        });

        // Remove rows
        $(document).on('click', '.remove-medicine', function() {
            $(this).closest('.medicine-row').remove();
            calculateTotals();
        });

        // Update calculations when timing checkboxes change
        $(document).on('change', '.timing-checkbox', function() {
            const row = $(this).closest('.medicine-row');
            updateRowQuantity(row);
            calculateTotals();
        });

        // Update calculations when days change
        $(document).on('input', '.days-input', function() {
            const row = $(this).closest('.medicine-row');
            updateRowQuantity(row);
            calculateTotals();
        });

        // Update unit price when medicine selection changes
        $(document).on('change', '.medicine-select', function() {
            const row = $(this).closest('.medicine-row');
            updateUnitPrice(row);
            calculateTotals();
        });

        // Calculate totals function
        function calculateTotals() {
            let totalMedicineAmount = 0;

            // Calculate sum of (quantity × unit price) for all medicines
            $('.medicine-row').each(function() {
                const quantity = parseInt($(this).find('.quantity-input').val()) || 0;
                const unitPrice = parseFloat($(this).find('.price-input').val()) || 0;
                const rowTotal = quantity * unitPrice;
                totalMedicineAmount += rowTotal;
            });

            const doctorFees = parseFloat('{{ $opRegister->medicalOfficer->consulting_fee ?? 0 }}') || 0;
            const paidAmount = parseFloat($('input[name="paid_amount"]').val()) || 0;
            const grandTotal = totalMedicineAmount + doctorFees;
            const balance = grandTotal - paidAmount;

            $('#medicineTotal').text(totalMedicineAmount.toFixed(2));
            $('#grandTotalDisplay').text(grandTotal.toFixed(2));
            $('#balanceDisplay').text(balance.toFixed(2));
        }

        // Initialize Select2 for existing selects and calculate initial values
        $(document).ready(function() {
            $('.medicine-select').select2({
                placeholder: "Search medicine...",
                allowClear: false,
                width: '100%'
            });

            // Calculate initial quantities for existing rows
            $('.medicine-row').each(function() {
                updateRowQuantity($(this));
            });

            // Initial calculation
            calculateTotals();
        });

        // Recalculate when doctor fees or paid amount changes
        $(document).on('input', 'input[name="doctor_fees"], input[name="paid_amount"]', function() {
            calculateTotals();
        });
    </script>
@endpush
