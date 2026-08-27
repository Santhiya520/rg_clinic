@extends('layouts.app')

@section('page-title', 'Edit Prescription - IP Patient')

@section('content')
    <div class="nk-block nk-block-lg">
        <form action="{{ route('inpatient-register.prescription.update', $inpatientRegister) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Patient Info -->
            <div class="card card-preview">
                <div class="card-inner">
                    <div class="nk-block-head">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <p class="text-soft">Patient: <strong>{{ $inpatientRegister->patient->name }}</strong>
                                    ({{ $inpatientRegister->patient->patient_id }}) | IP No:
                                    {{ $inpatientRegister->hospital_ip_no }}</p>
                                <p class="text-soft">Admission: {{ $inpatientRegister->date_of_admission->format('d/m/Y') }}
                                    |
                                    Age: {{ $inpatientRegister->patient->age }} | {{ $inpatientRegister->patient->sex }}</p>
                            </div>
                            <div class="nk-block-head-content">
                                <a href="{{ route('inpatient-register.doctor-ip') }}" class="btn btn-secondary"
                                    style="border-radius: 5px">
                                    <em class="icon ni ni-arrow-left"></em> &nbsp; Back to List
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Diagnosis & Treatment Section -->
            <div class="card card-preview mt-3">
                <div class="card-inner">
                    <h5 class="card-title">Diagnosis & Treatment</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Provisional Diagnosis *</label>
                                <textarea class="form-control" name="provisional_diagnosis" rows="3" required>{{ old('provisional_diagnosis', $inpatientRegister->provisional_diagnosis) }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Investigations</label>
                                <textarea class="form-control" name="investigations" rows="3">{{ old('investigations', $inpatientRegister->investigations) }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Final Diagnosis</label>
                                <textarea class="form-control" name="final_diagnosis" rows="3">{{ old('final_diagnosis', $inpatientRegister->final_diagnosis) }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Treatment</label>
                                <textarea class="form-control" name="treatment" rows="3">{{ old('treatment', $inpatientRegister->treatment) }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Result</label>
                                <select class="form-control" name="result">
                                    <option value="">Select Result</option>
                                    <option value="Cured"
                                        {{ old('result', $inpatientRegister->result) == 'Cured' ? 'selected' : '' }}>Cured
                                    </option>
                                    <option value="Same condition"
                                        {{ old('result', $inpatientRegister->result) == 'Same condition' ? 'selected' : '' }}>
                                        Same condition</option>
                                    <option value="Referred"
                                        {{ old('result', $inpatientRegister->result) == 'Referred' ? 'selected' : '' }}>
                                        Referred</option>
                                    <option value="Expired"
                                        {{ old('result', $inpatientRegister->result) == 'Expired' ? 'selected' : '' }}>
                                        Expired</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">Additional Information</label>
                                <textarea class="form-control" name="additional_info" rows="2">{{ old('additional_info', $inpatientRegister->additional_info) }}</textarea>
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
                        @foreach ($inpatientRegister->medicines as $index => $medicine)
                            <div class="medicine-row border p-3 mb-3">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Medicine *</label>
                                            <select class="form-control medicine-select select2-search"
                                                name="medicines[{{ $index }}][medicine_id]" required>
                                                <option value="">Select Medicine</option>
                                                @foreach ($medicines as $med)
                                                @php
                                                    // Decode the medicine name for display
                                                    $decodedName = \App\Helpers\StringHelper::decodeQuotes($med->name);
                                                @endphp
                                                    <option value="{{ $med->id }}"
                                                        {{ $medicine->medicine_id == $med->id ? 'selected' : '' }}
                                                        data-price="{{ $med->price }}">
                                                        {{ $decodedName }} ({{ $med->category }})
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
                                    
                                            <input type="number" class="form-control price-input"
                                                name="medicines[{{ $index }}][price]"
                                                value="{{ $medicine->price }}" step="0.01" required hidden>
                                       
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label">Instructions</label>
                                            <textarea class="form-control" name="medicines[{{ $index }}][instructions]" rows="2">{{ $medicine->instructions }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div style="display: flex; justify-content:space-between">
                                    <button type="button" class="btn btn-sm btn-danger remove-medicine"
                                        style="border-radius: 5px; margin-top:5px">Remove</button>
                                </div>
                        @endforeach
                    </div>
                        <button type="button" class="btn btn-sm btn-primary" id="add-medicine"
                            style="border-radius: 6px 0">
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
                        @foreach ($inpatientRegister->radiologyTests as $index => $radiology)
                            <div class="radiology-row border p-3 mb-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Radiology Test *</label>
                                            <select class="form-control radiology-select select2-search"
                                                name="radiologies[{{ $index }}][radiology_test_id]" required>
                                                <option value="">Select Radiology Test</option>
                                                @foreach ($radiologyTests as $test)
                                                    <option value="{{ $test->id }}"
                                                        {{ $radiology->radiology_test_id == $test->id ? 'selected' : '' }}
                                                        data-price="{{ $test->price }}">
                                                        {{ $test->name }} 
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
                                            <input type="text" class="form-control radiology-price"
                                                value="{{ $radiology->price }}" hidden>
                                        
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="form-label">Notes</label>
                                            <textarea class="form-control" name="radiologies[{{ $index }}][notes]" rows="2">{{ $radiology->notes }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div style="display: flex; justify-content:space-between">
                                    <button type="button" class="btn btn-sm btn-danger remove-radiology mt-2"
                                        style="border-radius:5px">Remove</button>
                                </div>
                        @endforeach
                    </div>
                        <button type="button" class="btn btn-sm btn-primary" id="add-radiology"
                            style="border-radius: 5px">
                            <em class="icon ni ni-plus"></em> &nbsp; Add Radiology Test
                        </button>
                </div>
                {{-- style="padding:2px 8px" --}}
            </div>
    </div>

    <!-- Lab Tests Section -->
    <div class="card card-preview mt-3">
        <div class="card-inner">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title">Laboratory Tests</h5>
            </div>

            <div id="lab-tests-container">
                @foreach ($inpatientRegister->labTests as $index => $labTest)
                    <div class="lab-test-row border p-3 mb-3">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Lab Test *</label>
                                    <select class="form-control labtest-select select2-search"
                                        name="lab_tests[{{ $index }}][lab_test_id]" required>
                                        <option value="">Select Lab Test</option>
                                        @foreach ($labTests as $test)
                                            <option value="{{ $test->id }}"
                                                {{ $labTest->lab_test_id == $test->id ? 'selected' : '' }}
                                                data-price="{{ $test->price }}">
                                                {{ $test->name }} 
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                                    <input type="text" class="form-control labtest-price"
                                        value="{{ $labTest->price }}" hidden>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">Notes</label>
                                    <textarea class="form-control" name="lab_tests[{{ $index }}][notes]" rows="2">{{ $labTest->notes }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div style="display:flex; justify-content:space-between">
                            <button type="button" class="btn btn-sm btn-danger remove-lab-test mt-2"
                                style="border-radius: 5px">Remove</button>
                        </div>
                    </div>
                @endforeach
            </div>
                <button type="button" class="btn btn-sm btn-primary" style="border-radius: 5px" id="add-lab-test">
                    <em class="icon ni ni-plus"></em> &nbsp; Add Lab Test
                </button>
        </div>
    </div>

    <div class="mt-3 mb-2" style="margin-left: 20px">
        <button type="submit" class="btn btn-primary" style="border-radius: 6px 0 0 6px">
            <em class="icon ni ni-save"></em> &nbsp; Update Prescription
        </button>
        <a href="{{ route('inpatient-register.doctor-ip') }}" class="btn btn-secondary"
            style="border-radius:0 6px 6px 0">Cancel</a>
    </div>
    </form>
    </div>
@endsection

@push('scripts')
    <script>
        let medicineIndex = {{ $inpatientRegister->medicines->count() }};
        let radiologyIndex = {{ $inpatientRegister->radiologyTests->count() }};
        let labTestIndex = {{ $inpatientRegister->labTests->count() }};

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
                                @php
                                    // Decode the medicine name for display
                                    $decodedName = \App\Helpers\StringHelper::decodeQuotes($med->name);
                                @endphp
                                <option value="{{ $med->id }}" data-price="{{ $med->price }}">
                                    {{ $decodedName }} ({{ $med->category }})
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
              <div style="display: flex; justify-content:space-between">
                             <button type="button" class="btn btn-sm btn-danger remove-medicine" style="border-radius: 5px; margin-top:5px">Remove</button>

                           </div>
        </div>
        `;
            $('#medicines-container').append(medicineRow);

            $('.medicine-select:last').select2({
                placeholder: "Search medicine...",
                allowClear: false,
                width: '100%'
            });

            medicineIndex++;
        });

        // Add Radiology Row
        $('#add-radiology').click(function() {
            const radiologyRow = `
        <div class="radiology-row border p-3 mb-3">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Radiology Test *</label>
                        <select class="form-control radiology-select select2-search" name="radiologies[${radiologyIndex}][radiology_test_id]" required>
                            <option value="">Select Radiology Test</option>
                            @foreach ($radiologyTests as $test)
                                <option value="{{ $test->id }}" data-price="{{ $test->price }}">
                                    {{ $test->name }} 
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                 <input type="text" class="form-control radiology-price" hidden>
                  
                <div class="col-md-5">
                    <div class="form-group">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" name="radiologies[${radiologyIndex}][notes]" rows="2"></textarea>
                    </div>
                </div>
            </div>
           <div style="display: flex; justify-content:space-between">
                             <button type="button" class="btn btn-sm btn-danger remove-radiology mt-2" style="border-radius:5px">Remove</button>

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
        <div class="lab-test-row border p-3 mb-3">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Lab Test *</label>
                        <select class="form-control labtest-select select2-search" name="lab_tests[${labTestIndex}][lab_test_id]" required>
                            <option value="">Select Lab Test</option>
                            @foreach ($labTests as $test)
                                <option value="{{ $test->id }}" data-price="{{ $test->price }}">
                                    {{ $test->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                        <input type="text" class="form-control labtest-price" hidden>
                <div class="col-md-5">
                    <div class="form-group">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" name="lab_tests[${labTestIndex}][notes]" rows="2"></textarea>
                    </div>
                </div>
            </div>
             <div style="display:flex; justify-content:space-between">
                            <button type="button" class="btn btn-sm btn-danger remove-lab-test mt-2" style="border-radius: 5px">Remove</button>

                        </div>
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

        // Calculate quantity when timing or days change
        $(document).on('change', '.timing-checkbox, .days-input', function() {
            const row = $(this).closest('.medicine-row');
            calculateQuantity(row);
        });

        // Calculate medicine price when medicine is selected
        $(document).on('change', '.medicine-select', function() {
            const selectedOption = $(this).find('option:selected');
            const price = selectedOption.data('price') || 0;
            const row = $(this).closest('.medicine-row');
            row.find('.price-input').val(price);
        });

        // Price Auto Filling for Radiology and Lab Tests
        $(document).on('change', '.radiology-select', function() {
            let price = $(this).find(':selected').data('price');
            $(this).closest('.radiology-row').find('.radiology-price').val(price);
        });

        $(document).on('change', '.labtest-select', function() {
            let price = $(this).find(':selected').data('price');
            $(this).closest('.lab-test-row').find('.labtest-price').val(price);
        });

        // Function to calculate quantity
        function calculateQuantity(row) {
            const selectedTimings = row.find('.timing-checkbox:checked').length;
            const days = parseInt(row.find('.days-input').val()) || 0;
            const totalQuantity = selectedTimings * days;
            row.find('.quantity-input').val(totalQuantity > 0 ? totalQuantity : '');
        }

        // Initialize Select2
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
