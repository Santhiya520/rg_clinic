@extends('layouts.app')

@section('title', 'Lab Tests - OP Patient')
@section('page-title', 'Lab Tests - ' . $opRegister->patient->name)

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
                        @php
                            $completedTestsCount = $opRegister->labTests->where('status', 'completed')->count();
                        @endphp
                        @if($completedTestsCount > 0)
                            <a href="{{ route('lab.op.print-all', $opRegister) }}" target="_blank" class="btn btn-success" style="border-radius: 5px; margin-right: 10px;">
                                <em class="icon ni ni-printer"></em> &nbsp; Print All ({{ $completedTestsCount }})
                            </a>
                        @endif
                        <a href="{{ route('lab.index') }}" class="btn btn-secondary" style="border-radius: 5px">
                            <em class="icon ni ni-arrow-left"></em> &nbsp; Back to Dashboard
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
                        @foreach($opRegister->labTests as $test)
                        <tr>
                            <td>{{ $test->labTest->name }}</td>
                            <td>
                                <span style="padding: 3px 8px" class="badge bg-{{ $test->status == 'completed' ? 'success' : ($test->status == 'cancelled' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($test->status) }}
                                </span>
                            </td>
                            <td>
                                @if($test->result)
                                    <span class="text-success">Available</span>
                                    <small class="d-block text-muted">{{ Str::limit($test->result, 50) }}</small>
                                @else
                                    <span class="text-muted">Not Available</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    @if($test->status == 'completed')
                                        <a href="{{ route('lab.op.edit', $test) }}" class="btn btn-sm btn-info" style="border-radius: 3px">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="{{ route('lab.op.bill', $test) }}" class="btn btn-sm btn-primary" target="_blank" style="border-radius: 3px">
                                            <i class="fas fa-print"></i> Print Single
                                        </a>
                                    @else
                                        <a href="{{ route('lab.op.edit', $test) }}" class="btn btn-sm btn-primary" style="border-radius: 5px">
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
