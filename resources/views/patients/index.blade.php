@extends('layouts.app')

@section('page-title', 'Patient Management')

@section('content')
    <div class="nk-block nk-block-lg">

        @if (session('success'))
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
                        </div>
                        <div class="nk-block-head-content">
                            <a href="{{ route('patients.create') }}" class="btn btn-primary" style="border-radius: 5px">
                                <em class="icon ni ni-plus"></em> &nbsp; Register New Patient
                            </a>
                        </div>
                    </div>
                </div>
                <table class="datatable-init nowrap table">
                    <thead>
                        <tr>
                            <th>Patient ID</th>
                            <th>Name</th>
                            <th>Mobile</th>
                            <th>Age</th>
                            <th>Sex</th>
                            <th>Total Visits</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($patients as $patient)
                            <tr>
                                <td><strong>{{ $patient->patient_id }}</strong></td>
                                <td>{{ $patient->name }}</td>
                                <td>{{ $patient->mobile ?? 'N/A' }}</td>
                                <td>{{ $patient->age }}</td>
                                <td>{{ ucfirst($patient->sex) }}</td>
                                <td>
                                    <span class="badge badge-primary">{{ $patient->op_registers_count }}</span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('op-registers.create') }}?patient_id={{ $patient->id }}"
                                            class="btn btn-sm btn-outline-success" title="Add OP Visit" >
                                            <em class="icon ni ni-plus"></em>
                                        </a>
                                        <a href="{{ route('patients.edit', $patient) }}"
                                            class="btn btn-sm btn-outline-primary" style="border-radius: 0px">
                                            Edit
                                        </a>
                                        <form action="{{ route('patients.destroy', $patient) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Are you sure you want to delete this patient?')">
                                                Delete
                                            </button>
                                        </form>
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
