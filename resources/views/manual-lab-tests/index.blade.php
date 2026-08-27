@extends('layouts.app')

@section('page-title', 'Manual Lab Tests')

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
            <div class="d-flex justify-content-between mb-3">
                <h6 class="title">Manual Lab Tests</h6>
                <a href="{{ route('manual-lab-tests.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Add Manual Test
                </a>
            </div>

            <table class="datatable-init nowrap table">
                <thead>
                    <tr>
                        <th>Reference No</th>
                        <th>Patient</th>
                        <th>Total</th>
                        <th>Paid </th>
                        <th>Payment</th>
                        <th>Test</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tests as $test)
                    <tr>
                        <td>{{ $test->reference_no }}</td>
                        <td>
                            <strong>{{ $test->patient->name }}</strong><br>
                            <small>{{ $test->patient->patient_id }}</small>
                        </td>
                        <td>₹{{ number_format($test->total_amount, 2) }}</td>
                        <td>₹{{ number_format($test->paid_amount, 2) }}</td>
                        <td>
                            <span class="badge bg-{{ $test->payment_status == 'paid' ? 'success' : ($test->payment_status == 'partial' ? 'warning' : 'danger') }}">
                                {{ ucfirst($test->payment_status) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $test->test_status == 'completed' ? 'success' : ($test->test_status == 'cancelled' ? 'danger' : 'warning') }}">
                                {{ ucfirst($test->test_status) }}
                            </span>
                        </td>
                        <td>{{ $test->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('manual-lab-tests.show', $test) }}" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="{{ route('manual-lab-tests.edit', $test) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="{{ route('manual-lab-tests.print', $test) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                    <i class="fas fa-print"></i> Print
                                </a>
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
