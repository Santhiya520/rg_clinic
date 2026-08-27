@extends('layouts.app')

@section('title', 'Manual Radiology Test Details')
@section('page-title', 'Test Details - ' . $manualRadiologyTest->reference_no)

@section('content')
<div class="nk-block nk-block-lg">
    <div class="card card-preview">
        <div class="card-inner">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div>
                        <h6 class="title">Radiology Test Details</h6>
                        <p class="text-soft">
                            Patient: <strong>{{ $manualRadiologyTest->patient->name }}</strong>
                            ({{ $manualRadiologyTest->patient->patient_id }}) |
                            Bill No: {{ $manualRadiologyTest->reference_no }}
                        </p>
                    </div>
                    <div class="btn-group">
                        <a href="{{ route('manual-radiology-tests.edit', $manualRadiologyTest) }}" class="btn btn-primary">
                            <em class="icon ni ni-edit"></em> Edit
                        </a>
                        <a href="{{ route('manual-radiology-tests.print', $manualRadiologyTest) }}" target="_blank" class="btn btn-info">
                            <em class="icon ni ni-printer"></em> Print
                        </a>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card border">
                        <div class="card-body">
                            <h6 class="card-title">Test Information</h6>
                            <table class="table table-sm">
                                <tr>
                                    <th width="40%">Patient Name:</th>
                                    <td>{{ $manualRadiologyTest->patient->name }}</td>
                                </tr>
                                <tr>
                                    <th>Patient ID:</th>
                                    <td>{{ $manualRadiologyTest->patient->patient_id }}</td>
                                </tr>
                                <tr>
                                    <th>Created By:</th>
                                    <td>{{ $manualRadiologyTest->user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Date:</th>
                                    <td>{{ $manualRadiologyTest->created_at->format('d/m/Y h:i A') }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="badge bg-{{ $manualRadiologyTest->test_status == 'completed' ? 'success' : ($manualRadiologyTest->test_status == 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($manualRadiologyTest->test_status) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card border">
                        <div class="card-body">
                            <h6 class="card-title">Payment Information</h6>
                            <table class="table table-sm">
                                <tr>
                                    <th width="40%">Payment Type:</th>
                                    <td>{{ ucfirst($manualRadiologyTest->payment_type) }}</td>
                                </tr>
                                <tr>
                                    <th width="40%">Total Amount:</th>
                                    <td>₹{{ number_format($manualRadiologyTest->total_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Paid Amount:</th>
                                    <td>₹{{ number_format($manualRadiologyTest->paid_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Balance:</th>
                                    <td>₹{{ number_format($manualRadiologyTest->total_amount - $manualRadiologyTest->paid_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Payment Status:</th>
                                    <td>
                                        <span class="badge bg-{{ $manualRadiologyTest->payment_status == 'paid' ? 'success' : ($manualRadiologyTest->payment_status == 'partial' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($manualRadiologyTest->payment_status) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>

                            @if($manualRadiologyTest->payment_status != 'paid')
                            <form action="{{ route('manual-radiology-tests.update-payment', $manualRadiologyTest) }}" method="POST" class="mt-3">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="number" name="paid_amount" class="form-control"
                                               value="{{ $manualRadiologyTest->paid_amount }}"
                                               step="0.01" min="0" max="{{ $manualRadiologyTest->total_amount }}">
                                    </div>
                                    <div class="col-md-4">
                                        <select name="payment_status" class="form-control">
                                            <option value="pending" {{ $manualRadiologyTest->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="partial" {{ $manualRadiologyTest->payment_status == 'partial' ? 'selected' : '' }}>Partial</option>
                                            <option value="paid" {{ $manualRadiologyTest->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                    </div>
                                </div>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border mt-4">
                <div class="card-body">
                    <h6 class="card-title">Tests List</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Test Name</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Result</th>
                                    <th>Technician</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($manualRadiologyTest->items as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->radiologyTest->name ?? 'N/A' }}</td>
                                        <td>₹{{ number_format($item->price, 2) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $item->status == 'completed' ? 'success' : ($item->status == 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($item->result_document)
                                                <a href="{{ Storage::url($item->result_document) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    View Document
                                                </a>
                                            @else
                                                <span class="text-muted">Not Available</span>
                                            @endif
                                        </td>
                                        <td>{{ $item->technician->name ?? 'N/A' }}</td>
                                        <td>
                                            <a href="{{ route('manual-radiology-tests.edit-result', $item) }}"
                                               class="btn btn-sm btn-primary">
                                                <em class="icon ni ni-edit"></em> Update Result
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @if($manualRadiologyTest->notes)
            <div class="card border mt-4">
                <div class="card-body">
                    <h6 class="card-title">Notes</h6>
                    <p>{{ $manualRadiologyTest->notes }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
