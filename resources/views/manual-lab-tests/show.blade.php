@extends('layouts.app')

@section('page-title', 'Manual Lab Test - ' . $manualLabTest->reference_no)

@section('content')
    <div class="nk-block nk-block-lg">
        <div class="card card-preview">
            <div class="card-inner">
                <div class="d-flex justify-content-between mb-3">
                    <h6 class="title">Manual Lab Test Details</h6>
                    {{-- In the btn-group section --}}
                    <div class="btn-group">
                        <a href="{{ route('manual-lab-tests.edit', $manualLabTest) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('manual-lab-tests.print', $manualLabTest) }}" class="btn btn-primary btn-sm"
                            target="_blank">
                            <i class="fas fa-print"></i> Print Bill
                        </a>

                        {{-- Add Print All Results button if there are completed items --}}
                        @php
                            $completedCount = $manualLabTest->items->where('status', 'completed')->count();
                        @endphp
                        @if ($completedCount > 0)
                            <a href="{{ route('manual-lab-tests.print-all-results', $manualLabTest) }}"
                                class="btn btn-success btn-sm" target="_blank">
                                <i class="fas fa-file-pdf"></i> Print All Results ({{ $completedCount }})
                            </a>
                        @endif

                        <a href="{{ route('manual-lab-tests.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="border p-3">
                            <h6>Patient Information</h6>
                            <p><strong>Name:</strong> {{ $manualLabTest->patient->name }}</p>
                            <p><strong>Patient ID:</strong> {{ $manualLabTest->patient->patient_id }}</p>
                            <p><strong>Age/Gender:</strong> {{ $manualLabTest->patient->age ?? 'N/A' }} /
                                {{ ucfirst($manualLabTest->patient->gender ?? 'N/A') }}</p>
                            <p><strong>Phone:</strong> {{ $manualLabTest->patient->mobile ?? 'N/A' }}</p>
                            <p><strong>Address:</strong> {{ $manualLabTest->patient->address ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border p-3">
                            <h6>Test Information</h6>
                            <p><strong>Reference No:</strong> {{ $manualLabTest->reference_no }}</p>
                            <p><strong>Date:</strong> {{ $manualLabTest->created_at->format('d/m/Y h:i A') }}</p>
                            <p><strong>Created By:</strong> {{ $manualLabTest->user->name }}</p>
                            <p><strong>Status:</strong>
                                <span
                                    class="badge bg-{{ $manualLabTest->test_status == 'completed' ? 'success' : ($manualLabTest->test_status == 'cancelled' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($manualLabTest->test_status) }}
                                </span>
                            </p>
                            @if ($manualLabTest->completed_at)
                                <p><strong>Completed On:</strong> {{ $manualLabTest->completed_at->format('d/m/Y h:i A') }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="border p-3">
                            <h6>Payment Information</h6>
                            <div class="row">
                                <div class="col-md-3">
                                    <p><strong>Total Amount:</strong><br>
                                        <span class="h5">₹{{ number_format($manualLabTest->total_amount, 2) }}</span>
                                    </p>
                                </div>
                                <div class="col-md-3">
                                    <p><strong>Paid Amount:</strong><br>
                                        <span class="h5">₹{{ number_format($manualLabTest->paid_amount, 2) }}</span>
                                    </p>
                                </div>
                                <div class="col-md-2">
                                    <p><strong>Due Amount:</strong><br>
                                        <span class="h5">₹{{ number_format($manualLabTest->due_amount, 2) }}</span>
                                    </p>
                                </div>
                                <div class="col-md-2">
                                    <p><strong>Payment Status:</strong><br>
                                        <span
                                            class="badge bg-{{ $manualLabTest->payment_status == 'paid' ? 'success' : ($manualLabTest->payment_status == 'partial' ? 'warning' : 'danger') }} h5">
                                            {{ ucfirst($manualLabTest->payment_status) }}
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-2">
                                    <p><strong>Payment Type:</strong><br>
                                        <span>
                                            {{ ucfirst($manualLabTest->payment_type) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Update Payment Form -->
                @if ($manualLabTest->payment_status != 'paid')
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="border p-3">
                                <h6>Update Payment</h6>
                                <form action="{{ route('manual-lab-tests.update-payment', $manualLabTest) }}"
                                    method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">Paid Amount</label>
                                                <input type="number" name="paid_amount" class="form-control"
                                                    value="{{ $manualLabTest->paid_amount }}" step="0.01" min="0"
                                                    max="{{ $manualLabTest->total_amount }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">Payment Status</label>
                                                <select name="payment_status" class="form-control" required>
                                                    <option value="pending"
                                                        {{ $manualLabTest->payment_status == 'pending' ? 'selected' : '' }}>
                                                        Pending</option>
                                                    <option value="partial"
                                                        {{ $manualLabTest->payment_status == 'partial' ? 'selected' : '' }}>
                                                        Partial</option>
                                                    <option value="paid"
                                                        {{ $manualLabTest->payment_status == 'paid' ? 'selected' : '' }}>
                                                        Paid</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 d-flex align-items-end">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-money-bill"></i> Update Payment
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Test Items -->
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Test Name</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Notes</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($manualLabTest->items as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->labTest->name }}</td>
                                    <td>₹{{ number_format($item->price, 2) }}</td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $item->status == 'completed' ? 'success' : ($item->status == 'cancelled' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($item->notes)
                                            <div class="text-muted">{{ Str::limit($item->notes, 30) }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('manual-lab-tests.edit-result', $item) }}"
                                                class="btn btn-info">
                                                <i class="fas fa-edit"></i> Update Result
                                            </a>
                                            @if ($item->status == 'completed')
                                                <a href="{{ route('manual-lab-tests.print-item-result', $item) }}"
                                                    class="btn btn-primary btn-sm" target="_blank">
                                                    <i class="fas fa-print"></i> Print Result
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Notes -->
                @if ($manualLabTest->notes)
                    <div class="mt-3">
                        <h6>Notes</h6>
                        <div class="border p-3">
                            {{ $manualLabTest->notes }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
