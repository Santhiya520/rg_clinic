@extends('layouts.app')

@section('page-title', 'View Prescription - IP Patient')

@section('content')
<div class="nk-block nk-block-lg">
    <!-- Patient Info -->
    <div class="card card-preview">
        <div class="card-inner">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <p class="text-soft">Patient: <strong>{{ $inpatientRegister->patient->name }}</strong>
                            ({{ $inpatientRegister->patient->patient_id }}) | IP No: {{ $inpatientRegister->hospital_ip_no }}</p>
                        <p class="text-soft">Admission: {{ $inpatientRegister->date_of_admission->format('d/m/Y') }} |
                            Age: {{ $inpatientRegister->patient->age }} | {{ $inpatientRegister->patient->sex }}</p>
                        @if($inpatientRegister->date_of_discharge)
                            <p class="text-soft">Discharge: {{ $inpatientRegister->date_of_discharge->format('d/m/Y') }} |
                                Status: <span class="badge bg-secondary" style="padding:2px 8px" >Discharged</span></p>
                        @else
                            <p class="text-soft">Status: <span class="badge bg-success">Active</span></p>
                        @endif
                    </div>
                    <div class="nk-block-head-content">
                        <div class="btn-group">
                            <a href="{{ route('inpatient-register.prescription.edit', $inpatientRegister) }}" class="btn btn-primary">
                                <em class="icon ni ni-edit"></em> &nbsp; Edit Prescription
                            </a>
                            <a href="{{ route('inpatient-register.doctor-ip') }}" class="btn btn-secondary">
                                <em class="icon ni ni-arrow-left"></em> &nbsp; Back to List
                            </a>
                        </div>
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
                        <label class="form-label"><strong>Provisional Diagnosis</strong></label>
                        <div class="form-control-plaintext">
                            {!! nl2br(e($inpatientRegister->provisional_diagnosis ?? 'Not specified')) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><strong>Investigations</strong></label>
                        <div class="form-control-plaintext">
                            {!! nl2br(e($inpatientRegister->investigations ?? 'Not specified')) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><strong>Final Diagnosis</strong></label>
                        <div class="form-control-plaintext">
                            {!! nl2br(e($inpatientRegister->final_diagnosis ?? 'Not specified')) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><strong>Treatment</strong></label>
                        <div class="form-control-plaintext">
                            {!! nl2br(e($inpatientRegister->treatment ?? 'Not specified')) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><strong>Result</strong></label>
                        <div class="form-control-plaintext">
                            @if($inpatientRegister->result)
                                <span style="padding:2px 8px"  class="badge bg-{{ $inpatientRegister->result == 'Cured' ? 'success' : ($inpatientRegister->result == 'Same condition' ? 'warning' : ($inpatientRegister->result == 'Referred' ? 'info' : 'danger')) }}">
                                    {{ $inpatientRegister->result }}
                                </span>
                            @else
                                Not specified
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label"><strong>Additional Information</strong></label>
                        <div class="form-control-plaintext">
                            {!! nl2br(e($inpatientRegister->additional_info ?? 'Not specified')) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Medicines Section -->
    @if($inpatientRegister->medicines->count() > 0)
        <div class="card card-preview mt-3">
            <div class="card-inner">
                <h5 class="card-title">Medicines Prescribed</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Medicine</th>
                                <th>Timing</th>
                                <th>Days</th>
                                <th>Quantity</th>
                                <th>Instructions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($inpatientRegister->medicines as $medicine)
                                <tr>
                                    <td>
                                        <strong>{{ $medicine->created_at->format('d/m/Y') }}</strong>
                                    </td>
                                    <td>
                                        @php
                                            // Decode the medicine name for display
                                            $decodedName = \App\Helpers\StringHelper::decodeQuotes($medicine->medicine->name);
                                        @endphp
                                        <strong>{{ $decodedName ?? 'N/A' }}</strong>
                                        @if($medicine->medicine->generic_name ?? false)
                                            <br><small class="text-muted">({{ $medicine->medicine->generic_name }})</small>
                                        @endif
                                        @if($medicine->medicine->category ?? false)
                                            <br><small class="text-muted">{{ $medicine->medicine->category }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($medicine->morning || $medicine->afternoon || $medicine->night)
                                            <div class="d-flex flex-wrap gap-1">
                                                @if($medicine->morning)
                                                    <span class="badge bg-light text-dark" style="padding:2px 8px" >M</span>
                                                @endif
                                                @if($medicine->afternoon)
                                                    <span class="badge bg-light text-dark" style="padding:2px 8px" >A</span>
                                                @endif
                                                @if($medicine->night)
                                                    <span class="badge bg-light text-dark" style="padding:2px 8px" >N</span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-muted" style="padding:2px 8px" >Not specified</span>
                                        @endif
                                    </td>
                                    <td>{{ $medicine->no_of_days }} days</td>
                                    <td>{{ $medicine->quantity }}</td>
                                    <td>{!! nl2br(e($medicine->instructions ?? 'No instructions')) !!}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- Radiology Tests Section -->
    @if($inpatientRegister->radiologyTests->count() > 0)
        <div class="card card-preview mt-3">
            <div class="card-inner">
                <h5 class="card-title">Radiology Tests</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Test Name</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($inpatientRegister->radiologyTests as $radiology)
                                <tr>
                                    <td>
                                        <strong>{{ $radiology->created_at->format('d/m/Y') }}</strong>
                                    </td>
                                    <td><strong>{{ $radiology->radiologyTest->name ?? 'N/A' }}</strong></td>
                                    <td>{!! nl2br(e($radiology->notes ?? 'No notes')) !!}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- Lab Tests Section -->
    @if($inpatientRegister->labTests->count() > 0)
        <div class="card card-preview mt-3">
            <div class="card-inner">
                <h5 class="card-title">Laboratory Tests</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Test Name</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($inpatientRegister->labTests as $labTest)
                                <tr>
                                    <td>
                                        <strong>{{ $labTest->created_at->format('d/m/Y') }}</strong>
                                    </td>
                                    <td><strong>{{ $labTest->labTest->name ?? 'N/A' }}</strong></td>
                                    <td>{!! nl2br(e($labTest->notes ?? 'No notes')) !!}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- Summary Section -->
    <div class="card card-preview mt-3">
        <div class="card-inner">
            <h5 class="card-title">Prescription Summary</h5>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label"><strong>Total Medicines</strong></label>
                        <div class="form-control-plaintext">
                            {{ $inpatientRegister->medicines->count() }}
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label"><strong>Total Radiology Tests</strong></label>
                        <div class="form-control-plaintext">
                            {{ $inpatientRegister->radiologyTests->count() }}
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label"><strong>Total Lab Tests</strong></label>
                        <div class="form-control-plaintext">
                            {{ $inpatientRegister->labTests->count() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('inpatient-register.prescription.edit', $inpatientRegister) }}" class="btn btn-primary" style="border-radius: 6px 0 0 6px">
            <em class="icon ni ni-edit" ></em>&nbsp; Edit Prescription
        </a>
        <a href="{{ route('inpatient-register.doctor-ip') }}" class="btn btn-secondary">Back to List</a>

        @if(!$inpatientRegister->date_of_discharge)
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#dischargeModal">
                <em class="icon ni ni-logout"></em>&nbsp; Discharge Patient
            </button>
        @endif
    </div>
</div>

<!-- Discharge Modal -->
@if(!$inpatientRegister->date_of_discharge)
<div class="modal fade" id="dischargeModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Discharge Patient</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('inpatient-register.discharge', $inpatientRegister) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Date of Discharge *</label>
                                <input type="date" class="form-control" name="date_of_discharge" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Result *</label>
                                <select class="form-control" name="result" required>
                                    <option value="">Select Result</option>
                                    <option value="Cured">Cured</option>
                                    <option value="Same condition">Same condition</option>
                                    <option value="Referred">Referred</option>
                                    <option value="Expired">Expired</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">Final Diagnosis *</label>
                                <textarea class="form-control" name="final_diagnosis" rows="3" required>{{ $inpatientRegister->final_diagnosis }}</textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">Additional Information</label>
                                <textarea class="form-control" name="additional_info" rows="2">{{ $inpatientRegister->additional_info }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Discharge Patient</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@push('styles')
<style>
    .form-control-plaintext {
        padding: 0.5rem 0 !important;
        min-height: auto;
        border: none;
        background: transparent;
    }
    .badge {
        font-size: 0.75em;
    }
    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
        white-space: nowrap;
    }
    .table td {
        vertical-align: middle;
    }
    .gap-1 {
        gap: 4px;
    }
</style>
@endpush
