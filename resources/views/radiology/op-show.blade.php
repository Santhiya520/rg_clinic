@extends('layouts.app')

@section('title', 'OP Radiology Tests')
@section('page-title', 'Radiology Tests - ' . $opRegister->patient->name)

@section('content')
<div class="nk-block nk-block-lg">
    <div class="card card-preview">
        <div class="card-inner">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <p class="text-soft">Patient: <strong>{{ $opRegister->patient->name }}</strong>
                            ({{ $opRegister->patient->patient_id }}) | Token: {{ $opRegister->token_number }}</p>
                    </div>
                    <div class="nk-block-head-content">
                        <a href="{{ route('radiology.index') }}" class="btn btn-secondary">
                            <em class="icon ni ni-arrow-left"></em>&nbsp; Back to List
                        </a>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Test Name</th>
                            <th>Status</th>
                            <th>Result</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($opRegister->radiologyTests as $test)
                            <tr>
                                <td>{{ $test->radiologyTest->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $test->status == 'completed' ? 'success' : ($test->status == 'cancelled' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($test->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if ($test->result_document)
                                        <a href="{{ asset('uploads/radiology-documents/' . $test->result_document) }}"
                                            target="_blank" class="btn btn-sm btn-outline-primary">
                                            View Document
                                        </a>
                                    @else
                                        <span class="text-muted">Not Available</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('radiology.op.edit', $test) }}"
                                        class="btn btn-sm btn-primary" style="border-radius: 5px">
                                        Update Result
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
