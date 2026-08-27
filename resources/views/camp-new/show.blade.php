@extends('layouts.app')

@section('title', 'View Camp New Record')
@section('page-title', 'Camp New Record Details')

@section('content')
<div class="nk-block nk-block-lg">
    <div class="card card-preview">
        <div class="card-inner">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5 class="card-title">Patient Details</h5>
                </div>
                <div class="col-md-6 text-right">
                    <div class="btn-group">
                        <a href="{{ route('camp-new.edit', $record->id) }}" class="btn btn-outline-primary">
                            <em class="icon ni ni-edit"></em> Edit
                        </a>
                        <a href="{{ route('camp-new.print-thermal', $record->id) }}" target="_blank" class="btn btn-outline-success">
                            <em class="icon ni ni-printer"></em> Print Bill
                        </a>
                        <a href="{{ route('camp-new.index') }}" class="btn btn-outline-light">
                            <em class="icon ni ni-arrow-left"></em> Back
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Patient Information -->
                <div class="col-md-6">
                    <div class="card border">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Patient Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-4"><strong>Token No:</strong></div>
                                <div class="col-8">{{ $record->token_number }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4"><strong>Patient Name:</strong></div>
                                <div class="col-8">{{ $record->patient_name }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4"><strong>Mobile:</strong></div>
                                <div class="col-8">{{ $record->mobile_number ?? 'N/A' }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4"><strong>Age/Gender:</strong></div>
                                <div class="col-8">
                                    @if($record->age)
                                        {{ $record->age }} years / {{ ucfirst($record->gender ?? 'N/A') }}
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4"><strong>Address:</strong></div>
                                <div class="col-8">{{ $record->address ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="col-md-6">
                    <div class="card border">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Payment Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-4"><strong>Payment Type:</strong></div>
                                <div class="col-8">
                                    <span class="badge badge-dim bg-outline-{{ $record->payment_type == 'cash' ? 'success' : ($record->payment_type == 'card' ? 'primary' : 'info') }}">
                                        {{ ucfirst($record->payment_type) }}
                                    </span>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4"><strong>Payment Status:</strong></div>
                                <div class="col-8">
                                    @php
                                        $statusColor = $record->payment_status == 'paid' ? 'success' :
                                                      ($record->payment_status == 'partial' ? 'warning' : 'secondary');
                                    @endphp
                                    <span class="badge bg-{{ $statusColor }}">
                                        {{ ucfirst($record->payment_status) }}
                                    </span>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4"><strong>Total Amount:</strong></div>
                                <div class="col-8">₹{{ number_format($record->total_amount, 2) }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4"><strong>Paid Amount:</strong></div>
                                <div class="col-8">₹{{ number_format($record->paid_amount, 2) }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4"><strong>Balance:</strong></div>
                                <div class="col-8">
                                    <span class="{{ $record->balance_amount > 0 ? 'text-danger' : 'text-success' }}">
                                        ₹{{ number_format($record->balance_amount, 2) }}
                                    </span>
                                </div>
                            </div>
                            @if($record->bill_number)
                            <div class="row mb-2">
                                <div class="col-4"><strong>Bill No:</strong></div>
                                <div class="col-8">{{ $record->bill_number }}</div>
                            </div>
                            <div class="row">
                                <div class="col-4"><strong>Bill Date:</strong></div>
                                <div class="col-8">{{ date('d/m/Y', strtotime($record->bill_date)) }}</div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Medicines Section -->
            @if($record->medicines && !empty($record->medicines))
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card border">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Medicines Prescribed</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Medicine Name</th>
                                            <th>Type</th>
                                            <th>Quantity</th>
                                            <th>Price (₹)</th>
                                            <th>Total (₹)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $total = 0;
                                            $medicines = is_array($record->medicines) ? $record->medicines :
                                                        (is_string($record->medicines) ? json_decode($record->medicines, true) : []);
                                        @endphp

                                        @foreach($medicines as $index => $medicine)
                                            @php
                                                $itemTotal = ($medicine['price'] ?? 0) * ($medicine['qty'] ?? 1);
                                                $total += $itemTotal;
                                            @endphp
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $medicine['name'] ?? 'N/A' }}</td>
                                                <td>{{ ucfirst($medicine['type'] ?? 'N/A') }}</td>
                                                <td>{{ $medicine['qty'] ?? 1 }}</td>
                                                <td>₹{{ number_format($medicine['price'] ?? 0, 2) }}</td>
                                                <td>₹{{ number_format($itemTotal, 2) }}</td>
                                            </tr>
                                        @endforeach
                                        @if($total > 0)
                                        <tr>
                                            <td colspan="5" class="text-right"><strong>Sub Total:</strong></td>
                                            <td><strong>₹{{ number_format($total, 2) }}</strong></td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Remarks -->
            @if($record->remarks)
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card border">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Remarks</h6>
                        </div>
                        <div class="card-body">
                            {{ $record->remarks }}
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Record Info -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <small class="text-muted">
                        <em class="icon ni ni-calendar"></em>
                        Created: {{ date('d/m/Y H:i', strtotime($record->created_at)) }}
                    </small>
                </div>
                <div class="col-md-6 text-right">
                    <small class="text-muted">
                        <em class="icon ni ni-calendar"></em>
                        Updated: {{ date('d/m/Y H:i', strtotime($record->updated_at)) }}
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
