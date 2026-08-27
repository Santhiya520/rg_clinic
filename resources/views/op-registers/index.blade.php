@extends('layouts.app')

@section('page-title', 'OP Register Management')

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
                            <a href="{{ route('op-registers.create') }}" class="btn btn-primary" style="border-radius: 5px" >
                                <em class="icon ni ni-plus"></em> &nbsp; New OP Entry
                            </a>
                        </div>
                    </div>
                </div>
                <table class="datatable-init nowrap table" data-ordering="false">
                    <thead>
                        <tr>
                            <th>OP No</th>
                            <th>Token No</th>
                            <th>Patient ID & Name</th>
                            <th>Date</th>
                            <th>Medical Officer</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($registers as $index => $register)
                            <tr>
                                <td>{{ $register->op_no }}</td>
                                <td>{{ $register->token_number }}</td>
                                <td>
                                    <strong>{{ $register->patient->patient_id }}</strong><br>
                                    <small>{{ $register->patient->name }}</small>
                                </td>
                                <td>{{ $register->date->format('d/m/Y') }}</td>
                                <td>{{ $register->medicalOfficer->name ?? 'N/A' }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('op-registers.edit', $register) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            Edit
                                        </a>
                                        <form action="{{ route('op-registers.destroy', $register) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Are you sure you want to delete this OP entry?')">
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
