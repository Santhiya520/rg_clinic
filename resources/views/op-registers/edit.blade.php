@extends('layouts.app')

@section('page-title', 'Edit OP Register Entry')

@section('content')
    <div class="nk-block nk-block-lg">
        <div class="card card-preview">
            <div class="card-inner">
                <div class="nk-block-head">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h5 class="nk-block-title">Edit OP Register Entry #{{ $opRegister->token_number }}</h5>
                        </div>
                        <div class="nk-block-head-content">
                            <a href="{{ route('op-registers.index') }}" class="btn btn-secondary">
                                <em class="icon ni ni-arrow-left"></em> Back to OP Register
                            </a>
                        </div>
                    </div>
                </div>

                <form action="{{ route('op-registers.update', $opRegister) }}" method="POST" id="opRegisterForm">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Date *</label>
                                <input type="date" class="form-control" name="date"
                                    value="{{ old('date', $opRegister->date->format('Y-m-d')) }}" required>
                                @error('date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">OP Number</label>
                                <input type="text" class="form-control" name="op_no" id="opNoField"
                                    value="{{ old('op_no', $opRegister->op_no ?? '') }}" required
                                    style="background-color: #f8f9fa;">
                                @error('op_no')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">OP number</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Token Number</label>
                                <input type="text" class="form-control" value="{{ $opRegister->token_number }}" readonly
                                    style="background-color: #f8f9fa;">
                                <small class="form-text text-muted">Token number cannot be changed</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Select Patient *</label>
                                <select class="form-control select2-search" name="patient_id" id="patientSelect" required
                                    data-placeholder="Search by name, mobile, or patient ID...">
                                    <option value=""></option>
                                    @foreach ($patients as $patient)
                                        <option value="{{ $patient->id }}"
                                            {{ old('patient_id', $opRegister->patient_id) == $patient->id ? 'selected' : '' }}
                                            data-patient-id="{{ $patient->patient_id }}" 
                                            data-name="{{ $patient->name }}"
                                            data-age="{{ $patient->age }}" 
                                            data-sex="{{ $patient->sex }}"
                                            data-mobile="{{ $patient->mobile }}" 
                                            data-address="{{ $patient->address }}"
                                            data-comorbidities="{{ $patient->comorbidities ?? '' }}"
                                            data-history="{{ $patient->history ?? '' }}">
                                            {{ $patient->patient_id }} - {{ $patient->name }}
                                            ({{ $patient->age }}/{{ ucfirst($patient->sex) }}) -
                                            {{ $patient->mobile ?? 'No Mobile' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('patient_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">Start typing name, mobile number, or patient ID to search</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Medical Officer (Doctor) *</label>
                                <select class="form-control select2-search" name="medical_officer_id" id="doctorSelect" required>
                                    <option value="">Select Doctor</option>
                                    @foreach ($doctors as $doctor)
                                        <option value="{{ $doctor->id }}"
                                            {{ old('medical_officer_id', $opRegister->medical_officer_id) == $doctor->id ? 'selected' : '' }}>
                                            Dr. {{ $doctor->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('medical_officer_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                   

                    <!-- Patient Details Display -->
                    <div class="row mb-4 mt-3" id="patientDetails" style="display: none;">
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Patient Details</h6>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <strong>Patient ID:</strong> <span id="displayPatientId"></span>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Name:</strong> <span id="displayName"></span>
                                        </div>
                                        <div class="col-md-2">
                                            <strong>Age:</strong> <span id="displayAge"></span>
                                        </div>
                                        <div class="col-md-2">
                                            <strong>Sex:</strong> <span id="displaySex"></span>
                                        </div>
                                        <div class="col-md-2">
                                            <strong>Mobile:</strong> <span id="displayMobile"></span>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-12">
                                            <strong>Address:</strong> <span id="displayAddress"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Vitals Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">Vitals</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="form-label">Weight (kg)</label>
                                                <input type="text" class="form-control" name="weight" 
                                                    value="{{ old('weight', $opRegister->weight) }}" placeholder="e.g., 65">
                                                @error('weight')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="form-label">Height (cm)</label>
                                                <input type="text" class="form-control" name="height" 
                                                    value="{{ old('height', $opRegister->height) }}" placeholder="e.g., 170">
                                                @error('height')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="form-label">Pulse (bpm)</label>
                                                <input type="text" class="form-control" name="pluse" 
                                                    value="{{ old('pluse', $opRegister->pluse) }}" placeholder="e.g., 72">
                                                @error('pluse')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="form-label">SpO2 (%)</label>
                                                <input type="text" class="form-control" name="spo2" 
                                                    value="{{ old('spo2', $opRegister->spo2) }}" placeholder="e.g., 98">
                                                @error('spo2')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="form-label">BP (mmHg)</label>
                                                <input type="text" class="form-control" name="bp" 
                                                    value="{{ old('bp', $opRegister->bp) }}" placeholder="e.g., 120/80">
                                                @error('bp')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="form-label">Temperature (°C/°F)</label>
                                                <input type="text" class="form-control" name="temparature" 
                                                    value="{{ old('temparature', $opRegister->temparature) }}" placeholder="e.g., 98.6">
                                                @error('temparature')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Medical History Section -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">Comorbidities</h6>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <textarea class="form-control" name="comorbidities" id="comorbidities" rows="6" 
                                            placeholder="Enter any existing medical conditions (e.g., Diabetes, Hypertension, Asthma...)">{{ old('comorbidities', $opRegister->comorbidities) }}</textarea>
                                        @error('comorbidities')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        <small class="form-text text-muted">Will be auto-filled from patient record but can be edited</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">Medical History</h6>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <textarea class="form-control" name="history" id="history" rows="6" 
                                            placeholder="Enter medical history, past surgeries, allergies, etc.">{{ old('history', $opRegister->history) }}</textarea>
                                        @error('history')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        <small class="form-text text-muted">Will be auto-filled from patient record but can be edited</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">Additional Information</label>
                                <textarea class="form-control" name="additional_information" rows="3" placeholder="Any additional notes...">{{ old('additional_information', $opRegister->additional_information) }}</textarea>
                                @error('additional_information')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <button type="submit" class="btn btn-primary">
                            <em class="icon ni ni-save"></em> Update OP Entry
                        </button>
                        <a href="{{ route('op-registers.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single {
            height: 45px;
            border: 1px solid #dbdfea;
            border-radius: 4px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 43px;
            padding-left: 12px;
            padding-right: 30px;
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
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #6576ff;
            color: white;
        }
        .select2-container .select2-selection--single {
            height: 45px;
        }
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            padding: 0.75rem 1.25rem;
        }
        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }
    </style>
@endpush

@push('scripts')
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            console.log('Document ready, initializing Select2...');

            // Check if jQuery is loaded
            if (typeof jQuery === 'undefined') {
                console.error('jQuery is not loaded!');
                return;
            }

            // Check if Select2 is loaded
            if (typeof $.fn.select2 === 'undefined') {
                console.error('Select2 is not loaded!');
                return;
            }

            // Initialize Select2 for patient select
            $('#patientSelect').select2({
                placeholder: "Search by name, mobile, or patient ID...",
                allowClear: true,
                width: '100%'
            });

            // Initialize Select2 for doctor select
            $('#doctorSelect').select2({
                placeholder: "Select Doctor",
                allowClear: true,
                width: '100%'
            });

            // Initialize Select2 for status select
            $('#statusSelect').select2({
                placeholder: "Select Status",
                allowClear: false,
                width: '100%'
            });

            console.log('Select2 initialized successfully');

            const patientDetails = document.getElementById('patientDetails');

            // Handle patient selection change
            $('#patientSelect').on('change', function() {
                const patientId = this.value;
                const selectedOption = $(this).find('option:selected');

                if (patientId) {
                    // Display patient details from data attributes
                    document.getElementById('displayPatientId').textContent = selectedOption.data('patient-id') || '';
                    document.getElementById('displayName').textContent = selectedOption.data('name') || '';
                    document.getElementById('displayAge').textContent = selectedOption.data('age') || '';
                    document.getElementById('displaySex').textContent = selectedOption.data('sex') || '';
                    document.getElementById('displayMobile').textContent = selectedOption.data('mobile') || 'N/A';
                    document.getElementById('displayAddress').textContent = selectedOption.data('address') || 'N/A';
                    
                    // Fill comorbidities and history textareas from patient data if empty
                    if (!$('#comorbidities').val().trim()) {
                        $('#comorbidities').val(selectedOption.data('comorbidities') || '');
                    }
                    if (!$('#history').val().trim()) {
                        $('#history').val(selectedOption.data('history') || '');
                    }
                    
                    patientDetails.style.display = 'block';
                } else {
                    patientDetails.style.display = 'none';
                }
            });

            // Show patient details on page load
            if ($('#patientSelect').val()) {
                setTimeout(() => {
                    $('#patientSelect').trigger('change');
                }, 100);
            }

            // Form submission handling
            $('#opRegisterForm').on('submit', function() {
                $(this).find('button[type="submit"]').prop('disabled', true).html(
                    '<em class="icon ni ni-loader"></em> Updating...');
            });
        });
    </script>
@endpush