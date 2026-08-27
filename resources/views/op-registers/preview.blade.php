@extends('layouts.app')

@section('title', 'OP Details - ' . $opRegister->token_number)
@section('page-title', 'OP Details Preview - #' . $opRegister->token_number)

@section('content')
<div class="nk-block nk-block-lg">
    <div class="card card-preview">
        <div class="card-inner">
            <!-- Print Button -->
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                    </div>
                    <div class="nk-block-head-content">
                        <a href="{{ route('report') }}" class="btn btn-secondary" style="border-radius: 6px 0 0 6px">
                            <em class="icon ni ni-arrow-left"></em>&nbsp; Back to Reports
                        </a>
                        <a href="{{ route('op-registers.print-details', $opRegister) }}" target="_blank" class="btn btn-primary" style="border-radius: 0 6px 6px 0">
                            <em class="icon ni ni-printer"></em> &nbsp; Print Report
                        </a>
                    </div>
                </div>
            </div>

            <!-- Patient Information -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 class="title">Patient Information</h6>
                    <table class="table table-bordered">
                        <tr>
                            <th width="40%">Patient Name</th>
                            <td>{{ $opRegister->patient->name }}</td>
                        </tr>
                        <tr>
                            <th>Patient ID</th>
                            <td>{{ $opRegister->patient->patient_id }}</td>
                        </tr>
                        <tr>
                            <th>Mobile Number</th>
                            <td>{{ $opRegister->patient->phone }}</td>
                        </tr>
                        <tr>
                            <th>Age & Gender</th>
                            <td>{{ $opRegister->patient->age }} years, {{ $opRegister->patient->gender }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6 class="title">OP Details</h6>
                    <table class="table table-bordered">
                        <tr>
                            <th width="40%">Token Number</th>
                            <td>#{{ $opRegister->token_number }}</td>
                        </tr>
                        <tr>
                            <th>Consulting Doctor</th>
                            <td>{{ $opRegister->medicalOfficer->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Registration Date</th>
                            <td>{{ $opRegister->created_at->format('d M Y, h:i A') }}</td>
                        </tr>
                        <tr>
                            <th>Total Amount</th>
                            <td><strong>₹{{ $totalAmount }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Medicines -->
            @if($opRegister->medicines->count() > 0)
            <div class="mb-4">
                <h6 class="title">Medicines Prescribed</h6>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Medicine Name</th>
                            <th>Morning</th>
                            <th>Afternoon</th>
                            <th>Night</th>
                            <th>No. of Days</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Instructions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($opRegister->medicines as $medicine)
                        <tr>
                            @php
                                    // Decode the medicine name for display
                                    $decodedName = \App\Helpers\StringHelper::decodeQuotes($medicine->medicine->name);
                                @endphp
                            <td>{{ $decodedName ?? 'N/A' }}</td>
                            <td>{{ $medicine->morning ? '✓' : '✗' }}</td>
                            <td>{{ $medicine->afternoon ? '✓' : '✗' }}</td>
                            <td>{{ $medicine->night ? '✓' : '✗' }}</td>
                            <td>{{ $medicine->no_of_days }}</td>
                            <td>{{ $medicine->quantity }}</td>
                            <td>₹{{ $medicine->price }}</td>
                            <td>{{ $medicine->instructions ?? 'N/A' }}</td>
                        </tr>
                        @endforeach
                        <tr class="table-warning">
                            <td colspan="6"><strong>Total Medicines</strong></td>
                            <td><strong>₹{{ $medicineTotal }}</strong></td>
                            <td></td>
                        </tr>
                        <tr class="table-success">
                            <td colspan="6"><strong>Paid Amount</strong></td>
                            <td><strong>₹{{ $medicinePaid }}</strong></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            @endif

            <!-- Radiology Tests -->
            @if($opRegister->radiologyTests->count() > 0)
            <div class="mb-4">
                <h6 class="title">Radiology Tests</h6>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Test Name</th>
                            <th>Price</th>
                            <th>Paid Amount</th>
                            <th>Status</th>
                            <th>Result</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($opRegister->radiologyTests as $test)
                        <tr>
                            <td>{{ $test->radiologyTest->name ?? 'N/A' }}</td>
                            <td>₹{{ $test->price }}</td>
                            <td>₹{{ $test->paid_amount }}</td>
                            <td>
                                <span style="padding: 3px 8px" class="badge bg-{{ $test->status == 'completed' ? 'success' : ($test->status == 'cancelled' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($test->status) }}
                                </span>
                            </td>
                            <td>
                                @if($test->result_document)
                                    <a href="{{ asset('uploads/radiology-documents/' . $test->result_document) }}"
                                       target="_blank" class="btn btn-xs btn-outline-primary ml-1" style="border-radius: 5px">
                                        View Doc
                                    </a>
                                @else
                                    <span class="text-muted">Not Available</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        <tr class="table-warning">
                            <td><strong>Total</strong></td>
                            <td><strong>₹{{ $radiologyTotal }}</strong></td>
                            <td><strong>₹{{ $radiologyPaid }}</strong></td>
                            <td colspan="2"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            @endif

            <!-- Lab Tests -->
            @if($opRegister->labTests->count() > 0)
            <div class="mb-4">
                <h6 class="title">Laboratory Tests</h6>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Test Name</th>
                            <th>Price</th>
                            <th>Paid Amount</th>
                            <th>Status</th>
                            <th>Result</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($opRegister->labTests as $test)
                        <tr>
                            <td>{{ $test->labTest->name ?? 'N/A' }}</td>
                            <td>₹{{ $test->price }}</td>
                            <td>₹{{ $test->paid_amount }}</td>
                            <td>
                                <span class="badge bg-{{ $test->status == 'completed' ? 'success' : ($test->status == 'cancelled' ? 'danger' : 'warning') }}" style="padding: 3px 8px">
                                    {{ ucfirst($test->status) }}
                                </span>
                            </td>
                            <td>
                                @if($test->status == 'completed')

                                        <a href="{{ route('lab.op.bill', $test) }}" class="btn btn-sm btn-primary" target="_blank" style="border-radius: 3px">
                                            <i class="fas fa-print"></i> Print
                                        </a>
                                @else
                                    <span class="text-muted">Not Available</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        <tr class="table-warning">
                            <td><strong>Total</strong></td>
                            <td><strong>₹ {{ $labTotal }}</strong></td>
                            <td><strong>₹ {{ $labPaid }}</strong></td>
                            <td colspan="2"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            @endif

            <!-- Summary -->
            <div class="row">
                <div class="col-md-6 offset-md-6">
                    <table class="table table-bordered">
                        <tr class="table-primary">
                            <th>Grand Total Amount</th>
                            <td><strong>₹ {{ $totalAmount }}</strong></td>
                        </tr>
                        <tr class="table-success">
                            <th>Grand Total Paid</th>
                            <td><strong>₹ {{ $totalPaid }}</strong></td>
                        </tr>
                        <tr class="table-info">
                            <th>Balance Amount</th>
                            <td><strong>₹ {{ $totalAmount - $totalPaid }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .btn { display: none; }
    .card { border: none; }
    .table { border: 1px solid #000; }
    .table-bordered th, .table-bordered td { border: 1px solid #000; }
}
</style>
@endsection
