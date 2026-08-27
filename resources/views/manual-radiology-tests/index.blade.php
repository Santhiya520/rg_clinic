@extends('layouts.app')

@section('title', 'Manual Radiology Tests')
@section('page-title', 'Manual Radiology Tests')

@section('content')
<div class="nk-block nk-block-lg">
    <div class="card card-preview">
        <div class="card-inner">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h6 class="title">Manual Radiology Tests</h6>
                        <p class="text-soft">All manually created radiology tests</p>
                    </div>
                    <div class="nk-block-head-content">
                        <a href="{{ route('manual-radiology-tests.create') }}" class="btn btn-primary">
                            <em class="icon ni ni-plus"></em> Add New Test
                        </a>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="datatable-init table">
                    <thead>
                        <tr>
                            <th>Bill No</th>
                            <th>Patient</th>
                            <th>Created By</th>
                            <th>Date</th>
                            <th>Tests</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tests as $test)
                            <tr>
                                <td><strong>{{ $test->reference_no }}</strong></td>
                                <td>
                                    <strong>{{ $test->patient->name }}</strong><br>
                                    <small class="text-muted">{{ $test->patient->patient_id }}</small>
                                </td>
                                <td>{{ $test->user->name }}</td>
                                <td>{{ $test->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge badge-dim bg-primary">{{ $test->items_count }}</span>
                                </td>
                                <td>₹{{ number_format($test->total_amount, 2) }}</td>
                                <td>
                                    @if($test->test_status == 'completed')
                                        <span class="badge badge-success">Completed</span>
                                    @elseif($test->test_status == 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @else
                                        <span class="badge badge-danger">Cancelled</span>
                                    @endif
                                    <br>
                                    <small class="text-muted">
                                        @if($test->payment_status == 'paid')
                                            <span class="text-success">Paid</span>
                                        @elseif($test->payment_status == 'partial')
                                            <span class="text-warning">Partial</span>
                                        @else
                                            <span class="text-danger">Pending</span>
                                        @endif
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('manual-radiology-tests.show', $test) }}"
                                           class="btn btn-sm btn-primary" title="View">
                                            <em class="icon ni ni-eye"></em>
                                        </a>
                                        <a href="{{ route('manual-radiology-tests.edit', $test) }}"
                                           class="btn btn-sm btn-info" title="Edit">
                                            <em class="icon ni ni-edit"></em>
                                        </a>
                                        <a href="{{ route('manual-radiology-tests.print', $test) }}"
                                           target="_blank" class="btn btn-sm btn-secondary" title="Print">
                                            <em class="icon ni ni-printer"></em>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $tests->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
