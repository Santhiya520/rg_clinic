@extends('layouts.app')

@section('page-title', 'Doctor IP Register')

@section('content')
<div class="nk-block nk-block-lg">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="close" data-bs-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <div class="card card-preview">
        <div class="card-inner">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h5 class="nk-block-title">Active Inpatients</h5>
                        <p>Manage prescriptions for admitted patients</p>
                    </div>
                </div>
            </div>

            <table class="datatable-init nowrap table" data-ordering="false">
                <thead>
                    <tr>
                        <th>IP No</th>
                        <th>Patient Details</th>
                        <th>Admission Date</th>
                        <th>Provisional Diagnosis</th>
                        <th>Prescription Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inpatients as $inpatient)
                    <tr>
                        <td>{{ $inpatient->hospital_ip_no }}</td>
                        <td>
                            <strong>{{ $inpatient->patient->patient_id }}</strong><br>
                            <small>{{ $inpatient->patient->name }}</small><br>
                            <small class="text-muted">Age: {{ $inpatient->patient->age }} | {{ $inpatient->patient->sex }}</small>
                        </td>
                        <td>{{ $inpatient->date_of_admission->format('d/m/Y') }}</td>
                        <td>
                            @if($inpatient->provisional_diagnosis)
                                <span class="text-success">Diagnosed</span>
                            @else
                                <span class="text-warning" style="padding:3px 8px" >Pending</span>
                            @endif
                        </td>
                        <td>
                            @if($inpatient->medicines->count() > 0 || $inpatient->radiologyTests->count() > 0 || $inpatient->labTests->count() > 0)
                                <span class="badge bg-success" style="padding:2px 8px">Prescribed</span>
                            @else
                                <span class="badge bg-warning" style="padding:2px 8px">No Prescription</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group">

                                    <a href="{{ route('inpatient-register.prescription.create', $inpatient) }}" class="btn btn-sm btn-outline-success" style="border-radius: 5px">
                                        Add
                                    </a>
                                @if($inpatient->medicines->count() > 0 || $inpatient->radiologyTests->count() > 0 || $inpatient->labTests->count() > 0)
                                    <a href="{{ route('inpatient-register.prescription.edit', $inpatient) }}" class="btn btn-sm btn-outline-info">
                                        Edit
                                    </a>
                                    <a href="{{ route('inpatient-register.prescription.view', $inpatient) }}" class="btn btn-sm btn-outline-warning">
                                        View
                                    </a>
                                @else
                                @endif

                            </div>
                        </td>
                    </tr>

                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
