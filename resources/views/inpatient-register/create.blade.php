@extends('layouts.app')

@section('title', 'Add Inpatient Record')
@section('page-title', 'Add Inpatient Record')

@section('content')
<div class="nk-block nk-block-lg">
    <form action="{{ route('inpatient-register.store') }}" method="POST">
        @csrf

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card card-preview">
            <div class="card-inner">
                <div class="nk-block-head">
                    <div class="nk-block-head-content">
                        <h5 class="nk-block-title">Patient Selection</h5>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">Select Patient *</label>
                            <select name="patient_id" class="form-control js-select2" required id="patientSelect"
                                    data-placeholder="Select Patient">
                                <option value=""></option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}"
                                        {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->name }} (ID: {{ $patient->patient_id }}) - {{ $patient->mobile }} - {{ $patient->age }} years - {{ $patient->sex }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">
                                All patient details (address, mobile, age, sex) will be automatically retrieved from patient records.
                            </small>
                        </div>
                    </div>
                </div>

                <div class="nk-block-head mt-4">
                    <div class="nk-block-head-content">
                        <h5 class="nk-block-title">Admission & Discharge Details</h5>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Medical Officer *</label>
                            <select name="medical_officer_id" class="form-control js-select2" required
                                    id="doctorSelect" data-placeholder="Select Medical Officer">
                                <option value=""></option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}"
                                        {{ old('medical_officer_id') == $doctor->id ? 'selected' : '' }}>
                                        Dr. {{ $doctor->name }}
                                        @if($doctor->designation)
                                            - {{ $doctor->designation }}
                                        @endif
                                        @if($doctor->doctor_id)
                                            (ID: {{ $doctor->doctor_id }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">
                                Select the consulting medical officer/doctor
                            </small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Date of Admission *</label>
                            <input type="date" class="form-control" name="date_of_admission"
                                   value="{{ old('date_of_admission', date('Y-m-d')) }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Date of Discharge</label>
                            <input type="date" class="form-control" name="date_of_discharge"
                                   value="{{ old('date_of_discharge') }}" >
                        </div>
                    </div>
                </div>

                <div class="nk-block-head mt-4">
                    <div class="nk-block-head-content">
                        <h5 class="nk-block-title">Medical Information</h5>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Provisional Diagnosis *</label>
                    <textarea class="form-control" name="provisional_diagnosis" rows="3" required
                              placeholder="Enter provisional diagnosis">{{ old('provisional_diagnosis') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Investigations</label>
                    <textarea class="form-control" name="investigations" rows="2"
                              placeholder="Enter investigations if any">{{ old('investigations') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Final Diagnosis *</label>
                    <textarea class="form-control" name="final_diagnosis" rows="3" required
                              placeholder="Enter final diagnosis">{{ old('final_diagnosis') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Treatment *</label>
                    <textarea class="form-control" name="treatment" rows="3" required
                              placeholder="Enter treatment details">{{ old('treatment') }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Result *</label>
                            <select name="result" class="form-control" required id="resultSelect">
                                <option value="">Select Result</option>
                                <option value="Cured" {{ old('result') == 'Cured' ? 'selected' : '' }}>Cured</option>
                                <option value="Same condition" {{ old('result') == 'Same condition' ? 'selected' : '' }}>Same condition</option>
                                <option value="Referred" {{ old('result') == 'Referred' ? 'selected' : '' }}>Referred</option>
                                <option value="Expired" {{ old('result') == 'Expired' ? 'selected' : '' }}>Expired</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Additional Information</label>
                    <textarea class="form-control" name="additional_info" rows="2"
                              placeholder="Enter any additional information">{{ old('additional_info') }}</textarea>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <em class="icon ni ni-save"></em> Create Record
                    </button>
                    <a href="{{ route('inpatient-register.index') }}" class="btn btn-secondary">
                        <em class="icon ni ni-arrow-left"></em> Back to List
                    </a>
                </div>
            </div>
        </div>
    </form>
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
    console.log('Initializing Select2...');

    // Check if Select2 is available
    if (typeof $.fn.select2 === 'undefined') {
        console.error('Select2 not loaded. Loading dynamically...');
        // Load Select2 dynamically
        $.getScript('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', function() {
            initializeSelect2();
        });
    } else {
        initializeSelect2();
    }

    function initializeSelect2() {
        console.log('Select2 available, initializing...');

        // Initialize patient select
        $('#patientSelect').select2({
            placeholder: "Select Patient",
            allowClear: true,
            width: '100%'
        });

        // Initialize doctor select
        $('#doctorSelect').select2({
            placeholder: "Select Medical Officer",
            allowClear: true,
            width: '100%'
        });

        console.log('Select2 initialized successfully');
    }

    // Set minimum date for date fields
    var today = new Date().toISOString().split('T')[0];
    $('input[name="date_of_admission"]').attr('min', '2000-01-01');
    $('input[name="date_of_discharge"]').attr('min', '2000-01-01');

    // Validate discharge date is after admission date
    $('input[name="date_of_admission"], input[name="date_of_discharge"]').on('change', function() {
        var admissionDate = $('input[name="date_of_admission"]').val();
        var dischargeDate = $('input[name="date_of_discharge"]').val();

        if (admissionDate && dischargeDate && new Date(dischargeDate) < new Date(admissionDate)) {
            alert('Date of discharge cannot be before date of admission');
            $('input[name="date_of_discharge"]').val('');
        }
    });
});
</script>
@endpush
