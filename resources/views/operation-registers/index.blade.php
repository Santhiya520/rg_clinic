@extends('layouts.app')

@section('page-title', 'Operation Register Management')

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
                        <a href="{{ route('operation-registers.create') }}" class="btn btn-primary" style="border-radius: 5px">
                            <em class="icon ni ni-plus"></em>&nbsp; Add New Operation
                        </a>
                    </div>
                </div>
            </div>


            <table class="datatable-init nowrap table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Patient Name</th>
                        <th>IP No</th>
                        <th>Operation</th>
                        <th>Surgeon</th>
                        <th>Date</th>
                        <th>Theatre</th>
                        <th>Ward</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($operationRegisters as $operation)
                    <tr>
                        <td>{{ $operation->id }}</td>
                        <td>
                            <strong>{{ $operation->patient->name }}</strong><br>
                            <small class="text-muted">ID: {{ $operation->patient->patient_id }}</small>
                        </td>
                        <td>{{ $operation->hospital_ip_no }}</td>
                        <td>{{ Str::limit($operation->operation_performed, 30) }}</td>
                        <td>{{ $operation->operatingSurgeon->name ?? 'N/A' }}</td>
                        <td>{{ $operation->date_of_admission->format('d M Y') }}</td>
                        <td>{{ $operation->operation_theatre_type ?? 'N/A' }}</td>
                        <td>{{ $operation->transferred_to_ward }}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('operation-registers.edit', $operation) }}"
                                   class="btn btn-sm btn-outline-secondary">
                                    Edit
                                </a>
                                <a href="{{ route('operation-registers.print', $operation) }}"
                                   class="btn btn-sm btn-outline-info" target="_blank" style="border-radius: 0px">
                                    Print
                                </a>
                                <form action="{{ route('operation-registers.destroy', $operation) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Are you sure you want to delete this operation record?')">
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
