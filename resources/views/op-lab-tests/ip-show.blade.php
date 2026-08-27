@extends('layouts.app')

@section('title', 'IP Lab Tests')
@section('page-title', 'IP Lab Tests - ' . $inpatientRegister->patient->name)

@section('content')
<div class="nk-block nk-block-lg">
    <div class="card card-preview">
        <div class="card-inner">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <p class="text-soft">Patient: <strong>{{ $inpatientRegister->patient->name }}</strong>
                            ({{ $inpatientRegister->patient->patient_id }}) | IP No: {{ $inpatientRegister->hospital_ip_no }}</p>
                        <p class="text-soft">Admission: {{ $inpatientRegister->date_of_admission->format('d/m/Y') }}</p>
                    </div>
                    <div class="nk-block-head-content">
                        <a href="{{ route('lab.index') }}" class="btn btn-secondary">
                            <em class="icon ni ni-arrow-left"></em>&nbsp; Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Test Name</th>
                            <th>Status</th>
                            <th>Result</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($inpatientRegister->labTests as $test)
                            <tr>
                                <td>{{ $test->created_at->format('d/m/Y h:i A') }}</td>
                                <td>{{ $test->labTest->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $test->status == 'completed' ? 'success' : ($test->status == 'cancelled' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($test->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if ($test->result)
                                        <span class="text-success">Available</span>
                                        <small class="d-block text-muted">{{ Str::limit($test->result, 50) }}</small>
                                    @else
                                        <span class="text-muted">Not Available</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        @if($test->status == 'completed')
                                            <a href="{{ route('lab.ip.edit', $test) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a href="{{ route('lab.ip.bill', $test) }}" class="btn btn-sm btn-primary" target="_blank">
                                                <i class="fas fa-print"></i> Print
                                            </a>
                                        @else
                                            <a href="{{ route('lab.ip.edit', $test) }}"
                                                class="btn btn-sm btn-primary" style="border-radius: 5px">
                                                Update Result
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
</div>
@endsection
