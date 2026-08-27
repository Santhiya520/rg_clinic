@extends('layouts.app')

@section('page-title', 'OP Register Management')

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
            <table class="datatable-init nowrap table" data-ordering="false">
                <thead>
                    <tr>
                        <th>Token No</th>
                        <th>Patient ID & Name</th>
                        <th>Date</th>
                        <th>Medical Officer</th>
                        <th>Prescription</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($registers as $index => $register)
                    <tr>
                        <td>{{$register->token_number }}</td>
                        <td>
                            <strong>{{ $register->patient->patient_id }}</strong><br>
                            <small>{{ $register->patient->name }}</small>
                        </td>
                        <td>{{ $register->date->format('d/m/Y') }}</td>
                        <td>{{ $register->medicalOfficer->name ?? 'N/A' }}</td>
                        <td>
                            @if($register->provisional_diagnosis || $register->medicines->count() > 0)
                                <span class="badge bg-success" style="padding:2px 8px" >Completed</span>
                            @else
                                <span class="badge bg-warning" style="padding:2px 8px" >Pending</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group">

                                @if($register->provisional_diagnosis || $register->medicines->count() > 0)
                                    <a href="{{ route('op-registers.prescription.edit', $register) }}" class="btn btn-sm btn-outline-info">
                                        Edit
                                    </a>
                                    <a href="{{ route('op-registers.prescription-view', $register) }}" class="btn btn-sm btn-outline-warning">
                                        view
                                    </a>
                                    <a href="{{ route('op-registers.doctor-print', $register) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       title="Print Prescription"
                                       target="_blank">
                                       Print
                                    </a>
                                @else
                                    <a href="{{ route('op-registers.prescription.create', $register) }}" class="btn btn-sm btn-outline-success" style="border-radius: 5px">
                                        Add Prescription
                                    </a>
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
