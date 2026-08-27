    @extends('layouts.app')

    @section('page-title', 'Edit Operation Register')

    @section('content')
    <div class="nk-block nk-block-lg">
        <div class="card card-preview">
            <div class="card-inner">
                <div class="nk-block-head">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h5 class="nk-block-title">Edit Operation Register</h5>
                        </div>
                        <div class="nk-block-head-content">
                            <a href="{{ route('operation-registers.index') }}" class="btn btn-secondary" style="border-radius: 5px">
                                <em class="icon ni ni-arrow-left"></em> &nbsp; Back to Operations
                            </a>
                        </div>
                    </div>
                </div>

                <form action="{{ route('operation-registers.update', $operationRegister) }}" method="POST" id="editOperationForm">
                    @csrf
                    @method('PUT')

                    <!-- Patient Information -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <h6 class="title border-bottom pb-2">Patient Information</h6>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Patient *</label>
                                <select name="patient_id" id="patientSelect" class="form-control js-select2" required
                                        data-placeholder="Select Patient">
                                    <option value=""></option>
                                    @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}"
                                        {{ old('patient_id', $operationRegister->patient_id) == $patient->id ? 'selected' : '' }}
                                        data-phone="{{ $patient->phone }}"
                                        data-age="{{ $patient->age }}"
                                        data-gender="{{ $patient->gender }}"
                                        data-address="{{ $patient->address }}">
                                        {{ $patient->name }} (ID: {{ $patient->patient_id }})
                                    </option>
                                    @endforeach
                                </select>
                                @error('patient_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Date of Admission *</label>
                                <input type="date" name="date_of_admission" class="form-control"
                                    value="{{ old('date_of_admission', $operationRegister->date_of_admission->format('Y-m-d')) }}" required>
                                @error('date_of_admission')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Hospital IP No *</label>
                                <input type="text" name="hospital_ip_no" class="form-control"
                                    value="{{ old('hospital_ip_no', $operationRegister->hospital_ip_no) }}" required>
                                @error('hospital_ip_no')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Patient Info Display -->
                    <div id="patient-info" class="p-3 bg-light rounded mt-2 mb-3" style="display: none;">
                        <div class="row">
                            <div class="col-md-3">
                                <small><strong>Phone:</strong> <span id="patient-phone"></span></small>
                            </div>
                            <div class="col-md-3">
                                <small><strong>Age:</strong> <span id="patient-age"></span></small>
                            </div>
                            <div class="col-md-3">
                                <small><strong>Gender:</strong> <span id="patient-gender"></span></small>
                            </div>
                            <div class="col-md-3">
                                <small><strong>Address:</strong> <span id="patient-address"></span></small>
                            </div>
                        </div>
                    </div>

                    <!-- Operation Information -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <h6 class="title border-bottom pb-2">Operation Information</h6>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Operation Theatre Type</label>
                                <select name="operation_theatre_type" id="theatreTypeSelect" class="form-control js-select2"
                                        data-placeholder="Select Theatre Type">
                                    <option value=""></option>
                                    <option value="Diabetes and Physician" {{ old('operation_theatre_type', $operationRegister->operation_theatre_type) == 'Diabetes and Physician' ? 'selected' : '' }}>Diabetes and Physician</option>
                                    <option value="Consultant Paediatrics" {{ old('operation_theatre_type', $operationRegister->operation_theatre_type) == 'Consultant Paediatrics' ? 'selected' : '' }}>Consultant Paediatrics</option>
                                    <option value="Nephrology" {{ old('operation_theatre_type', $operationRegister->operation_theatre_type) == 'Nephrology' ? 'selected' : '' }}>Nephrology</option>
                                    <option value="Oncologist" {{ old('operation_theatre_type', $operationRegister->operation_theatre_type) == 'Oncologist' ? 'selected' : '' }}>Oncologist</option>
                                    <option value="Gastroenterology" {{ old('operation_theatre_type', $operationRegister->operation_theatre_type) == 'Gastroenterology' ? 'selected' : '' }}>Gastroenterology</option>
                                    <option value="Maternity" {{ old('operation_theatre_type', $operationRegister->operation_theatre_type) == 'Maternity' ? 'selected' : '' }}>Maternity</option>
                                    <option value="General" {{ old('operation_theatre_type', $operationRegister->operation_theatre_type) == 'General' ? 'selected' : '' }}>General</option>
                                    <option value="Ortho" {{ old('operation_theatre_type', $operationRegister->operation_theatre_type) == 'Ortho' ? 'selected' : '' }}>Ortho</option>
                                    <option value="Cardiac" {{ old('operation_theatre_type', $operationRegister->operation_theatre_type) == 'Cardiac' ? 'selected' : '' }}>Cardiac</option>
                                    <option value="Neuro" {{ old('operation_theatre_type', $operationRegister->operation_theatre_type) == 'Neuro' ? 'selected' : '' }}>Neuro</option>
                                    <option value="ENT" {{ old('operation_theatre_type', $operationRegister->operation_theatre_type) == 'ENT' ? 'selected' : '' }}>ENT</option>
                                    <option value="Eye" {{ old('operation_theatre_type', $operationRegister->operation_theatre_type) == 'Eye' ? 'selected' : '' }}>Eye</option>
                                    <option value="Dental" {{ old('operation_theatre_type', $operationRegister->operation_theatre_type) == 'Dental' ? 'selected' : '' }}>Dental</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Transferred to Ward *</label>
                                <input type="text" name="transferred_to_ward" class="form-control"
                                    value="{{ old('transferred_to_ward', $operationRegister->transferred_to_ward) }}" required>
                                @error('transferred_to_ward')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Provisional Diagnosis</label>
                                <textarea name="provisional_diagnosis" class="form-control" rows="2">{{ old('provisional_diagnosis', $operationRegister->provisional_diagnosis) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Investigations (if any)</label>
                                <textarea name="investigations" class="form-control" rows="2">{{ old('investigations', $operationRegister->investigations) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Operation Performed *</label>
                                <textarea name="operation_performed" class="form-control" rows="2" required>{{ old('operation_performed', $operationRegister->operation_performed) }}</textarea>
                                @error('operation_performed')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Staff Information -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <h6 class="title border-bottom pb-2">Staff Information</h6>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Operating Surgeon *</label>
                                <select name="operating_surgeon_id" id="operatingSurgeonSelect" class="form-control js-select2" required
                                        data-placeholder="Select Operating Surgeon">
                                    <option value=""></option>
                                    @foreach($medicalOfficers as $doctor)
                                    <option value="{{ $doctor->id }}"
                                        {{ old('operating_surgeon_id', $operationRegister->operating_surgeon_id) == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('operating_surgeon_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Assistant Surgeon</label>
                                <select name="assistant_surgeon_id" id="assistantSurgeonSelect" class="form-control js-select2"
                                        data-placeholder="Select Assistant Surgeon">
                                    <option value=""></option>
                                    @foreach($medicalOfficers as $doctor)
                                    <option value="{{ $doctor->id }}"
                                        {{ old('assistant_surgeon_id', $operationRegister->assistant_surgeon_id) == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Anaesthetist</label>
                                <select name="anaesthetist_id" id="anaesthetistSelect" class="form-control js-select2"
                                        data-placeholder="Select Anaesthetist">
                                    <option value=""></option>
                                    @foreach($medicalOfficers as $doctor)
                                    <option value="{{ $doctor->id }}"
                                        {{ old('anaesthetist_id', $operationRegister->anaesthetist_id) == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Staff reception Assisted</label>
                                <select name="staff_reception_id" id="staffreceptionSelect" class="form-control js-select2"
                                        data-placeholder="Select Staff reception">
                                    <option value=""></option>
                                    @foreach($receptions as $reception)
                                    <option value="{{ $reception->id }}"
                                        {{ old('staff_reception_id', $operationRegister->staff_reception_id) == $reception->id ? 'selected' : '' }}>
                                        {{ $reception->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Operation Time -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Operation Start Time *</label>
                                <input type="time" name="operation_start_time" class="form-control"
                                    value="{{ old('operation_start_time', $operationRegister->operation_start_time->format('H:i')) }}" required>
                                @error('operation_start_time')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Operation End Time *</label>
                                <input type="time" name="operation_end_time" class="form-control"
                                    value="{{ old('operation_end_time', $operationRegister->operation_end_time->format('H:i')) }}" required>
                                @error('operation_end_time')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Medical Officer *</label>
                                <select name="medical_officer_id" id="medicalOfficerSelect" class="form-control js-select2" required
                                        data-placeholder="Select Medical Officer">
                                    <option value=""></option>
                                    @foreach($medicalOfficers as $doctor)
                                    <option value="{{ $doctor->id }}"
                                        {{ old('medical_officer_id', $operationRegister->medical_officer_id) == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('medical_officer_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Operation Notes</label>
                                <textarea name="operation_notes" class="form-control" rows="3">{{ old('operation_notes', $operationRegister->operation_notes) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Additional Information (if any)</label>
                                <textarea name="additional_information" class="form-control" rows="2">{{ old('additional_information', $operationRegister->additional_information) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary" style="border-radius: 6px 0 0 6px">
                            <em class="icon ni ni-save"></em> &nbsp; Update Operation Register
                        </button>
                        <a href="{{ route('operation-registers.index') }}" class="btn btn-secondary" >
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endsection

    @push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single {
            height: 45px;
            border: 1px solid #dbdfea;
            border-radius: 4px;
            padding: 8px 12px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 28px;
            padding-left: 0;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 43px;
        }
        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #6c757d;
        }
        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 1px solid #dbdfea;
            border-radius: 4px;
            padding: 6px 10px;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #6576ff;
            color: white;
        }
    </style>
    @endpush

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
    $(document).ready(function() {
        console.log('Initializing Select2 for Edit Operation Register...');

        // Function to initialize Select2
        function initializeSelect2() {
            if (typeof $.fn.select2 !== 'undefined') {
                // Initialize all Select2 dropdowns
                $('.js-select2').each(function() {
                    var placeholder = $(this).data('placeholder') || 'Select an option';
                    $(this).select2({
                        placeholder: placeholder,
                        allowClear: true,
                        width: '100%'
                    });
                });

                console.log('Select2 initialized successfully');
            } else {
                console.error('Select2 not available');
                // Load Select2 dynamically
                $.getScript('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', function() {
                    initializeSelect2();
                });
            }
        }

        // Initialize Select2
        initializeSelect2();

        // Function to update patient info
        function updatePatientInfo() {
            var selectedOption = $('#patientSelect').find('option:selected');
            if (selectedOption.val()) {
                $('#patient-phone').text(selectedOption.data('phone') || 'N/A');
                $('#patient-age').text((selectedOption.data('age') || 'N/A') + ' years');
                $('#patient-gender').text(selectedOption.data('gender') || 'N/A');
                $('#patient-address').text(selectedOption.data('address') || 'N/A');
                $('#patient-info').show();
            } else {
                $('#patient-info').hide();
            }
        }

        // Show patient info on page load
        updatePatientInfo();

        // Show patient info when patient is changed
        $('#patientSelect').on('change', updatePatientInfo);

        // Validate time (end time should be after start time)
        $('input[name="operation_start_time"], input[name="operation_end_time"]').on('change', function() {
            var startTime = $('input[name="operation_start_time"]').val();
            var endTime = $('input[name="operation_end_time"]').val();

            if (startTime && endTime) {
                var start = new Date('2000-01-01T' + startTime);
                var end = new Date('2000-01-01T' + endTime);

                if (end <= start) {
                    alert('Operation end time must be after start time');
                    $('input[name="operation_end_time"]').val('');
                    $('input[name="operation_end_time"]').focus();
                }
            }
        });

        // Form submission handling
        $('#editOperationForm').on('submit', function() {
            $(this).find('button[type="submit"]').prop('disabled', true).html(
                '<em class="icon ni ni-loader"></em> Updating...');
        });
    });
    </script>
    @endpush
