@extends('layouts.app')

@section('title', 'Edit Inpatient Record')
@section('page-title', 'Edit Inpatient Record')

@section('content')
<div class="nk-block nk-block-lg">
    <form action="{{ route('inpatient-register.update', $inpatientRegister) }}" method="POST">
        @csrf
        @method('PUT')

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Add hidden patient_id field -->
        <input type="hidden" name="patient_id" value="{{ $inpatientRegister->patient_id }}">

        <div class="card card-preview">
            <div class="card-inner">
                <div class="nk-block-head">
                    <div class="nk-block-head-content">
                        <h5 class="nk-block-title">Patient Information</h5>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">Patient</label>
                            <input type="text" class="form-control" value="{{ $inpatientRegister->patient->name }} (ID: {{ $inpatientRegister->patient->patient_id }}) - {{ $inpatientRegister->patient->mobile }} - {{ $inpatientRegister->patient->age }} years - {{ $inpatientRegister->patient->sex }}" readonly>
                            <small class="form-text text-muted">Patient details are linked from patient records and cannot be changed here.</small>
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
                            <label class="form-label">Hospital IP No. *</label>
                            <input type="text" class="form-control" name="hospital_ip_no"
                                   value="{{ old('hospital_ip_no', $inpatientRegister->hospital_ip_no) }}" required
                                   placeholder="Enter IP number">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Medical Officer *</label>
                            <select name="medical_officer_id" class="form-control js-select2" required id="doctorSelect">
                                <option value="">Select Medical Officer</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}"
                                        {{ old('medical_officer_id', $inpatientRegister->medical_officer_id) == $doctor->id ? 'selected' : '' }}>
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
                                   value="{{ old('date_of_admission', $inpatientRegister->date_of_admission->format('Y-m-d')) }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Date of Discharge</label>
                            <input type="date" class="form-control" name="date_of_discharge"
                                   value="{{ old('date_of_discharge', $inpatientRegister->date_of_discharge ? $inpatientRegister->date_of_discharge->format('Y-m-d') : '') }}" >
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
                              placeholder="Enter provisional diagnosis">{{ old('provisional_diagnosis', $inpatientRegister->provisional_diagnosis) }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Investigations</label>
                    <textarea class="form-control" name="investigations" rows="2"
                              placeholder="Enter investigations if any">{{ old('investigations', $inpatientRegister->investigations) }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Final Diagnosis *</label>
                    <textarea class="form-control" name="final_diagnosis" rows="3" required
                              placeholder="Enter final diagnosis">{{ old('final_diagnosis', $inpatientRegister->final_diagnosis) }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Treatment *</label>
                    <textarea class="form-control" name="treatment" rows="3" required
                              placeholder="Enter treatment details">{{ old('treatment', $inpatientRegister->treatment) }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Result *</label>
                            <select name="result" class="form-control" required>
                                <option value="">Select Result</option>
                                <option value="Cured" {{ old('result', $inpatientRegister->result) == 'Cured' ? 'selected' : '' }}>Cured</option>
                                <option value="Same condition" {{ old('result', $inpatientRegister->result) == 'Same condition' ? 'selected' : '' }}>Same condition</option>
                                <option value="Referred" {{ old('result', $inpatientRegister->result) == 'Referred' ? 'selected' : '' }}>Referred</option>
                                <option value="Expired" {{ old('result', $inpatientRegister->result) == 'Expired' ? 'selected' : '' }}>Expired</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Additional Information</label>
                    <textarea class="form-control" name="additional_info" rows="2"
                              placeholder="Enter any additional information">{{ old('additional_info', $inpatientRegister->additional_info) }}</textarea>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary" style="border-radius: 6px 0 6px 0">
                        <em class="icon ni ni-check"></em> &nbsp; Update Record
                    </button>
                    <a href="{{ route('inpatient-register.show', $inpatientRegister) }}" class="btn btn-info" style="border-radius: 6px 0">
                        <em class="icon ni ni-eye"></em> &nbsp; View Record
                    </a>
                    <a href="{{ route('inpatient-register.index') }}" class="btn btn-secondary" style="border-radius: 6px 0">
                        <em class="icon ni ni-arrow-left"></em> &nbsp; Back to List
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
        padding-left: 0;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 43px;
    }
    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #6c757d;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    console.log('Initializing Select2...');

    // Initialize Select2
    $('.js-select2').select2({
        placeholder: "Select an option",
        allowClear: true,
        width: '100%'
    });

    console.log('Select2 initialized successfully');

    // Date validation
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
