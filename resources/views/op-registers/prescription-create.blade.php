@extends('layouts.app')

@section('page-title', 'Add Prescription')

@section('content')
    <div class="nk-block nk-block-lg">
        <form action="{{ route('op-registers.prescription.store', ['opRegister' => $opRegister->id]) }}" method="POST">
            @csrf

            <!-- Patient Information Section -->
            <div class="card card-preview mb-3">
                <div class="card-inner">
                    <div class="nk-block-head">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h5 class="card-title">Patient Information</h5>
                            </div>
                            <div class="nk-block-head-content">
                                <span class="badge bg-primary">New Prescription</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Name</label>
                                <div class="form-control-plaintext border rounded p-2 bg-light">
                                    <strong>{{ $opRegister->patient?->name ?? 'N/A' }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-label">Age</label>
                                <div class="form-control-plaintext border rounded p-2 bg-light">
                                    {{ $opRegister->patient?->age ?? 'N/A' }} years
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-label">Sex</label>
                                <div class="form-control-plaintext border rounded p-2 bg-light">
                                    {{ $opRegister->patient?->sex ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-label">OP No *</label>
                                <input type="text" class="form-control" name="op_no"
                                    value="{{ old('op_no', $opRegister->op_no) }}" placeholder="Enter OP number" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Token</label>
                                <div class="form-control-plaintext border rounded p-2 bg-light">
                                    <strong>{{ $opRegister->token_number }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-label">Weight (kg)</label>
                                <input type="text" class="form-control" name="weight"
                                    value="{{ old('weight', $opRegister->weight) }}" placeholder="Weight">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-label">Height (cm)</label>
                                <input type="text" class="form-control" name="height"
                                    value="{{ old('height', $opRegister->height) }}" placeholder="Height">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-label">Pulse</label>
                                <input type="text" class="form-control" name="pluse"
                                    value="{{ old('pluse', $opRegister->pluse) }}" placeholder="Pulse rate">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-label">SpO₂</label>
                                <input type="text" class="form-control" name="spo2"
                                    value="{{ old('spo2', $opRegister->spo2) }}" placeholder="SpO₂ %">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-label">BP</label>
                                <input type="text" class="form-control" name="bp"
                                    value="{{ old('bp', $opRegister->bp) }}" placeholder="e.g., 120/80">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-label">Temperature</label>
                                <input type="text" class="form-control" name="temparature"
                                    value="{{ old('temparature', $opRegister->temparature) }}" placeholder="°C">
                            </div>
                        </div>
                    </div>

                    <!-- Comorbidities and History -->
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Comorbidities</label>
                                <textarea class="form-control" name="comorbidities" rows="3" placeholder="Enter patient comorbidities">{{ old('comorbidities', $opRegister->patient?->comorbidities) }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Medical History</label>
                                <textarea class="form-control" name="history" rows="3" placeholder="Enter medical history">{{ old('history', $opRegister->patient?->history) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Diagnosis & Treatment Section -->
            <div class="card card-preview">
                <div class="card-inner">
                    <div class="nk-block-head">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h5 class="card-title">Diagnosis & Treatment</h5>
                                <p class="text-soft">Patient ID:
                                    <strong>{{ $opRegister->patient?->patient_id ?? 'N/A' }}</strong>
                                </p>
                            </div>
                            <div class="nk-block-head-content">
                                <a href="{{ route('op-registers.doctor-op') }}" class="btn btn-secondary">
                                    <em class="icon ni ni-arrow-left"></em> &nbsp;Back to List
                                </a>
                            </div>
                        </div>
                    </div>
                    <h5 class="card-title">Diagnosis & Treatment</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Provisional Diagnosis *</label>
                                <textarea class="form-control" name="provisional_diagnosis" rows="2" required>{{ old('provisional_diagnosis') }}</textarea>
                                <input type="text" value="{{ $opRegister->id }}" name="op_register_id" hidden>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Investigations</label>
                                <textarea class="form-control" name="investigations" rows="2">{{ old('investigations') }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Final Diagnosis</label>
                                <textarea class="form-control" name="final_diagnosis" rows="2">{{ old('final_diagnosis') }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Treatment</label>
                                <textarea class="form-control" name="treatment" rows="2">{{ old('treatment') }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Result</label>
                                <select class="form-control" name="result">
                                    <option value="">Select Result</option>
                                    <option value="cured" {{ old('result') == 'cured' ? 'selected' : '' }}>Cured</option>
                                    <option value="improved" {{ old('result') == 'improved' ? 'selected' : '' }}>Improved
                                    </option>
                                    <option value="not_improved" {{ old('result') == 'not_improved' ? 'selected' : '' }}>
                                        Not Improved</option>
                                    <option value="referred" {{ old('result') == 'referred' ? 'selected' : '' }}>Referred
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <select class="form-control" name="status" required>
                                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>
                                        Active</option>
                                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>
                                        Completed</option>
                                    <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>
                                        Cancelled</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">Additional Information</label>
                                <textarea class="form-control" name="additional_information" rows="2">{{ old('additional_information') }}</textarea>
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
                    </div>

                    <div id="medicines-container">
                        <!-- Medicine rows will be added dynamically -->
                    </div>
                    <button type="button" class="btn btn-sm btn-primary" id="add-medicine">
                        <em class="icon ni ni-plus"></em> &nbsp; Add Medicine
                    </button>
                </div>
            </div>

            <!-- Radiology Tests Section -->
            <div class="card card-preview mt-3">
                <div class="card-inner">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Radiology Tests</h5>
                    </div>

                    <div id="radiology-container">
                        <!-- Radiology rows will be added dynamically -->
                    </div>
                    <button type="button" class="btn btn-sm btn-primary" id="add-radiology">
                        <em class="icon ni ni-plus"></em>&nbsp; Add Radiology Test
                    </button>
                </div>
            </div>

            <!-- Lab Tests Section -->
            <div class="card card-preview mt-3">
                <div class="card-inner">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Laboratory Tests</h5>
                    </div>

                    <div id="lab-tests-container">
                        <!-- Lab test rows will be added dynamically -->
                    </div>
                    <button type="button" class="btn btn-sm btn-primary mt-3" id="add-lab-test">
                        <em class="icon ni ni-plus"></em>&nbsp; Add Lab Test
                    </button>
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary" style="border-radius: 6px 0 0 6px">
                    <em class="icon ni ni-save"></em> Save Prescription
                </button>
                <a href="{{ route('op-registers.doctor-op') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection

<style>
    .select2-selection--single .select2-selection__rendered {
        color: #444;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #444;
        line-height: 15px;
    }

    textarea.form-control {
        height: auto !important;
        min-height: 30px !important;
    }

    .form-control-plaintext {
        min-height: 38px;
        padding: 0.375rem 0.75rem;
        background-color: #f8f9fa;
        border-radius: 0.375rem;
    }

    .injection-routes {
        display: none;
        margin-top: 10px;
        padding: 10px;
        background-color: #f8f9fa;
        border-radius: 4px;
        border: 1px solid #dee2e6;
    }

    .injection-routes.active {
        display: block;
    }

    .sub-tests-container {
        margin-top: 15px;
        padding: 15px;
        background-color: #f8f9fa;
        border-radius: 4px;
        border: 1px solid #dee2e6;
    }

    .sub-test-item {
        padding: 8px;
        border-bottom: 1px solid #dee2e6;
    }

    .sub-test-item:last-child {
        border-bottom: none;
    }

    .sub-test-item .form-check {
        margin-bottom: 0;
    }

    .sub-test-info {
        font-size: 0.9rem;
        color: #6c757d;
        margin-left: 25px;
    }
</style>

@push('scripts')
    <script>
        let medicineIndex = 0;
        let radiologyIndex = 0;
        let labTestIndex = 0;

        // Store lab sub tests data
        let labSubTestsData = {};

        // Add Medicine Row
        $('#add-medicine').click(function() {
            const medicineRow = `
        <div class="medicine-row border p-3 mb-3">
            <div class="row">
                <div class="col-md-3 pe-0">
                    <div class="form-group">
                        <label class="form-label">Medicine *</label>
                        <select class="form-control medicine-select select2-search" name="medicines[${medicineIndex}][medicine_id]" required data-medicine-index="${medicineIndex}">
                            <option value="">Select Medicine</option>
                            @foreach ($medicines as $med)
                                @php
                                    $decodedName = \App\Helpers\StringHelper::decodeQuotes($med->name);
                                @endphp
                                <option value="{{ $med->id }}" data-price="{{ $med->price }}" data-category="{{ strtolower($med->category) }}">
                                    {{ $decodedName }} ({{ $med->category }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6 pe-0">
                    <div class="form-group">
                        <label class="form-label d-block">Timing</label>
                        <div class="d-flex flex-wrap gap-3 mb-2">
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
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input timing-checkbox sos-checkbox" name="medicines[${medicineIndex}][sos]" value="1" id="sos-${medicineIndex}">
                                <label class="form-check-label" for="sos-${medicineIndex}">SOS</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input timing-checkbox ml-checkbox" name="medicines[${medicineIndex}][ml]" value="1" id="ml-${medicineIndex}">
                                <label class="form-check-label" for="ml-${medicineIndex}">ML</label>
                            </div>
                        </div>

                        <!-- Injection Routes (Hidden by default) -->
                        <div class="injection-routes" id="injection-routes-${medicineIndex}">
                            <label class="form-label d-block mb-2">Injection Route</label>
                            <div class="d-flex flex-wrap gap-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input route-checkbox" name="medicines[${medicineIndex}][im_route]" value="1" id="im-route-${medicineIndex}">
                                    <label class="form-check-label" for="im-route-${medicineIndex}">IM</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input route-checkbox" name="medicines[${medicineIndex}][iv_route]" value="1" id="iv-route-${medicineIndex}">
                                    <label class="form-check-label" for="iv-route-${medicineIndex}">IV</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input route-checkbox" name="medicines[${medicineIndex}][id_route]" value="1" id="id-route-${medicineIndex}">
                                    <label class="form-check-label" for="id-route-${medicineIndex}">ID</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input route-checkbox" name="medicines[${medicineIndex}][sub_q_route]" value="1" id="sub-q-route-${medicineIndex}">
                                    <label class="form-check-label" for="sub-q-route-${medicineIndex}">SUB-Q</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 pe-0">
                    <div class="form-group">
                        <label class="form-label">No. of Days</label>
                        <input type="number" class="form-control days-input" name="medicines[${medicineIndex}][no_of_days]" min="1">
                    </div>
                </div>
                <div class="col-md-1 pe-0">
                    <div class="form-group">
                        <label class="form-label">Qty *</label>
                        <input type="number" class="form-control quantity-input" name="medicines[${medicineIndex}][quantity]" min="1" required>
                    </div>
                </div>

                <input type="number" class="form-control price-input" name="medicines[${medicineIndex}][price]" step="0.01" required hidden>

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

        // Handle medicine category change
        $(document).on('change', '.medicine-select', function() {
            const row = $(this).closest('.medicine-row');
            const selectedOption = $(this).find('option:selected');
            const category = selectedOption.data('category') || '';
            const medicineIndex = $(this).data('medicine-index');

            // Show/hide injection routes based on category
            const injectionRoutes = row.find(`#injection-routes-${medicineIndex}`);

            if (category.includes('inject') || category.includes('injection')) {
                injectionRoutes.addClass('active');
            } else {
                injectionRoutes.removeClass('active');
                // Uncheck all route checkboxes
                row.find('.route-checkbox').prop('checked', false);
            }

            // Calculate price
            calculateTotalPrice(row);
        });

        // Calculate quantity when timing or days change
        $(document).on('change', '.timing-checkbox, .days-input, .sos-checkbox, .ml-checkbox', function() {
            const row = $(this).closest('.medicine-row');
            calculateQuantity(row);
            calculateTotalPrice(row);
        });

        // Calculate medicine price when medicine is selected
        $(document).on('change', '.medicine-select', function() {
            const row = $(this).closest('.medicine-row');
            calculateTotalPrice(row);
        });

        // Function to calculate quantity
        function calculateQuantity(row) {
            const selectedOption = row.find('.medicine-select option:selected');
            const category = selectedOption.data('category') || '';

            // Check if medicine is syrup or suspension
            const isLiquidMedicine = category.includes('syrup') || category.includes('suspension') ||
                category.includes('syrups') || category.includes('suspensions');

            // If it's syrup or suspension, don't auto-calculate quantity
            if (isLiquidMedicine) {
                // Clear the calculated quantity if it was previously set
                const quantityInput = row.find('.quantity-input');
                if (quantityInput.val() && quantityInput.val() !== '1') {
                    quantityInput.val('');
                }
                return;
            }
            const selectedTimings = row.find('.timing-checkbox:checked').not('.sos-checkbox, .ml-checkbox').length;

            // Get number of days
            const days = parseInt(row.find('.days-input').val()) || 0;

            // Calculate total quantity
            const totalQuantity = selectedTimings * days;

            // Update quantity field
            row.find('.quantity-input').val(totalQuantity > 0 ? totalQuantity : '');
        }

        // Function to calculate total price
        function calculateTotalPrice(row) {
            const selectedOption = row.find('.medicine-select option:selected');
            const unitPrice = parseFloat(selectedOption.data('price')) || 0;
            const quantity = parseInt(row.find('.quantity-input').val()) || 1;

            const totalPrice = unitPrice;

            // Update price field
            row.find('.price-input').val(totalPrice > 0 ? totalPrice.toFixed(2) : unitPrice.toFixed(2));
        }

        // Add Radiology Row
        $('#add-radiology').click(function() {
            const radiologyRow = `
        <div class="radiology-row border p-3 mb-3">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Radiology Test *</label>
                        <select class="form-control radiology-select select2-search"
                            name="radiologies[${radiologyIndex}][radiology_test_id]" required>
                            <option value="">Select Radiology Test</option>
                            @foreach ($radiologyTests as $test)
                                <option value="{{ $test->id }}" data-price="{{ $test->price }}">
                                    {{ $test->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <input type="text" class="form-control radiology-price" name="radiologies[${radiologyIndex}][price]" hidden>

                <div class="col-md-5">
                    <div class="form-group">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" name="radiologies[${radiologyIndex}][notes]" rows="2"></textarea>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-sm btn-danger remove-radiology mt-2">Remove</button>
        </div>
    `;
            $('#radiology-container').append(radiologyRow);

            $('.radiology-select:last').select2({
                placeholder: "Search radiology test...",
                allowClear: false,
                width: '100%'
            });

            radiologyIndex++;
        });

        // Add Lab Test Row
        $('#add-lab-test').click(function() {
            const labTestRow = `
        <div class="lab-test-row border p-3 mb-3" data-lab-index="${labTestIndex}">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Lab Test *</label>
                        <select class="form-control labtest-select select2-search"
                            name="lab_tests[${labTestIndex}][lab_test_id]" required data-lab-index="${labTestIndex}">
                            <option value="">Select Lab Test</option>
                            @foreach ($labTests as $test)
                                <option value="{{ $test->id }}" data-price="{{ $test->price }}" data-sub-tests="{{ json_encode($test->subTests) }}">
                                    {{ $test->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <input type="text" class="form-control labtest-price" name="lab_tests[${labTestIndex}][price]" hidden>

                <div class="col-md-5">
                    <div class="form-group">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" name="lab_tests[${labTestIndex}][notes]" rows="2"></textarea>
                    </div>
                </div>
            </div>

            <!-- Sub Tests Container -->
            <div class="sub-tests-container" id="sub-tests-${labTestIndex}" style="display: none;">
                <h6 class="mb-3">Select Sub Tests</h6>
                <div class="sub-tests-list" id="sub-tests-list-${labTestIndex}">
                    <!-- Sub tests will be loaded here dynamically -->
                </div>
            </div>

            <button type="button" class="btn btn-sm btn-danger remove-lab-test mt-2">Remove</button>
        </div>
    `;
            $('#lab-tests-container').append(labTestRow);

            $('.labtest-select:last').select2({
                placeholder: "Search lab test...",
                allowClear: false,
                width: '100%'
            });

            labTestIndex++;
        });

        // Handle lab test selection change
        $(document).on('change', '.labtest-select', function() {
            const labTestRow = $(this).closest('.lab-test-row');
            const labIndex = $(this).data('lab-index');
            const selectedOption = $(this).find('option:selected');
            const subTestsData = selectedOption.data('sub-tests');
            const price = selectedOption.data('price');

            // Set price
            labTestRow.find('.labtest-price').val(price);

            // Load sub tests
            if (subTestsData && subTestsData.length > 0) {
                const subTestsContainer = $(`#sub-tests-${labIndex}`);
                const subTestsList = $(`#sub-tests-list-${labIndex}`);

                subTestsList.empty();

                subTestsData.forEach(function(subTest, index) {
                    const subTestHtml = `
                        <div class="sub-test-item">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input sub-test-checkbox"
                                    name="lab_tests[${labIndex}][sub_tests][${subTest.id}][checked]"
                                    value="1"
                                    id="sub-test-${labIndex}-${subTest.id}"
                                    data-sub-test-id="${subTest.id}"
                                    data-lab-index="${labIndex}">
                                <label class="form-check-label" for="sub-test-${labIndex}-${subTest.id}">
                                    <strong>${subTest.name}</strong>
                                </label>
                                <div class="sub-test-info">
                                    <span>Unit: ${subTest.unit || 'N/A'}</span> |
                                    <span>Normal Range: ${subTest.normal_range || 'N/A'}</span>
                                </div>
                                <input type="hidden" name="lab_tests[${labIndex}][sub_tests][${subTest.id}][lab_sub_test_id]" value="${subTest.id}">
                                <input type="hidden" name="lab_tests[${labIndex}][sub_tests][${subTest.id}][test_name]" value="${subTest.name}">
                                <input type="hidden" name="lab_tests[${labIndex}][sub_tests][${subTest.id}][unit]" value="${subTest.unit || ''}">
                                <input type="hidden" name="lab_tests[${labIndex}][sub_tests][${subTest.id}][normal_range]" value="${subTest.normal_range || ''}">
                                <input type="hidden" name="lab_tests[${labIndex}][sub_tests][${subTest.id}][order]" value="${subTest.order || index}">
                            </div>
                        </div>
                    `;
                    subTestsList.append(subTestHtml);
                });

                subTestsContainer.show();
            } else {
                $(`#sub-tests-${labIndex}`).hide();
            }
        });

        // Handle sub test checkbox change
        $(document).on('change', '.sub-test-checkbox', function() {
            const labIndex = $(this).data('lab-index');
            const subTestId = $(this).data('sub-test-id');
            const isChecked = $(this).prop('checked');

            // You can add additional logic here if needed
        });

        // Price Auto Filling
        $(document).on('change', '.radiology-select', function() {
            let price = $(this).find(':selected').data('price');
            $(this).closest('.radiology-row').find('.radiology-price').val(price);
        });

        $(document).on('change', '.labtest-select', function() {
            let price = $(this).find(':selected').data('price');
            $(this).closest('.lab-test-row').find('.labtest-price').val(price);
        });

        // Remove rows
        $(document).on('click', '.remove-medicine', function() {
            $(this).closest('.medicine-row').remove();
        });

        $(document).on('click', '.remove-radiology', function() {
            $(this).closest('.radiology-row').remove();
        });

        $(document).on('click', '.remove-lab-test', function() {
            $(this).closest('.lab-test-row').remove();
        });

        // Initialize Select2 for any existing selects
        $(document).ready(function() {
            $('.medicine-select').select2({
                placeholder: "Search medicine...",
                allowClear: false,
                width: '100%'
            });

            $('.radiology-select').select2({
                placeholder: "Search radiology test...",
                allowClear: false,
                width: '100%'
            });

            $('.labtest-select').select2({
                placeholder: "Search lab test...",
                allowClear: false,
                width: '100%'
            });
        });
    </script>
@endpush
