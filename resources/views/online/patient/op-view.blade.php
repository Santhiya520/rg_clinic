@extends('online.layouts.app')

@section('title', 'OP Visit Details - Token #' . $opRegister->token_number)

@section('content')
<div class="nk-block nk-block-lg">
    <div class="card card-preview">
        <div class="card-inner">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">OP Visit Details</h3>
                        <div class="nk-block-des text-soft">
                            <p>Token #{{ $opRegister->token_number }} - {{ $opRegister->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                    <div class="nk-block-head-content">
                        <a href="{{ route('online.patient.report') }}" class="btn btn-secondary">
                            <em class="icon ni ni-arrow-left"></em> Back to Report
                        </a>
                    </div>
                </div>
            </div>

            <!-- OP Visit Details -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-bordered">
                        <div class="card-inner">
                            <h6 class="title">Visit Information</h6>
                            <table class="table">
                                <tr>
                                    <th width="40%">Token Number</th>
                                    <td><strong>#{{ $opRegister->token_number }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Date</th>
                                    <td>{{ $opRegister->created_at->format('d M Y, h:i A') }}</td>
                                </tr>
                                <tr>
                                    <th>Doctor</th>
                                    <td>{{ $opRegister->medicalOfficer->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge bg-{{ $opRegister->status == 'completed' ? 'success' : 'warning' }}">
                                            {{ ucfirst($opRegister->status) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card card-bordered">
                        <div class="card-inner">
                            <h6 class="title">Medical Information</h6>
                            <table class="table">
                                <tr>
                                    <th width="40%">Provisional Diagnosis</th>
                                    <td>{{ $opRegister->provisional_diagnosis ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Final Diagnosis</th>
                                    <td>{{ $opRegister->final_diagnosis ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Treatment</th>
                                    <td>{{ $opRegister->treatment ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Medicines Prescribed -->
            @if($opRegister->medicines->count() > 0)
            <div class="card card-bordered mt-3">
                <div class="card-inner">
                    <h5 class="title mb-3">Medicines Prescribed</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Medicine</th>
                                    <th>Timing</th>
                                    <th>Days</th>
                                    <th>Qty</th>
                                    <th>Instructions</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($opRegister->medicines as $medicine)
                                <tr>
                                    <td>{{ $medicine->medicine->name ?? 'N/A' }}</td>
                                    <td>
                                        @php
                                            $timings = [];
                                            if ($medicine->morning) $timings[] = 'Morning';
                                            if ($medicine->afternoon) $timings[] = 'Afternoon';
                                            if ($medicine->night) $timings[] = 'Night';
                                            if ($medicine->sos) $timings[] = 'SOS';
                                            if ($medicine->ml) $timings[] = 'ML';
                                        @endphp
                                        {{ implode(', ', $timings) }}
                                    </td>
                                    <td>{{ $medicine->no_of_days ?? '-' }}</td>
                                    <td>{{ $medicine->quantity ?? '-' }}</td>
                                    <td>{{ $medicine->instructions ?? '-' }}</td>
                                    <td>₹{{ number_format($medicine->price, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="text-right"><strong>Total</strong></td>
                                    <td><strong>₹{{ number_format($opRegister->medicines->sum('price'), 2) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Radiology Tests -->
            @if($opRegister->radiologyTests->count() > 0)
            <div class="card card-bordered mt-3">
                <div class="card-inner">
                    <h5 class="title mb-3">Radiology Tests</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Test Name</th>
                                    <th>Notes</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($opRegister->radiologyTests as $test)
                                <tr>
                                    <td>{{ $test->radiologyTest->name ?? 'N/A' }}</td>
                                    <td>{{ $test->notes ?? '-' }}</td>
                                    <td>₹{{ number_format($test->price, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" class="text-right"><strong>Total</strong></td>
                                    <td><strong>₹{{ number_format($opRegister->radiologyTests->sum('price'), 2) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Lab Tests -->
            @if($opRegister->labTests->count() > 0)
            <div class="card card-bordered mt-3">
                <div class="card-inner">
                    <h5 class="title mb-3">Laboratory Tests</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Test Name</th>
                                    <th>Notes</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($opRegister->labTests as $test)
                                <tr>
                                    <td>{{ $test->labTest->name ?? 'N/A' }}</td>
                                    <td>{{ $test->notes ?? '-' }}</td>
                                    <td>₹{{ number_format($test->price, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" class="text-right"><strong>Total</strong></td>
                                    <td><strong>₹{{ number_format($opRegister->labTests->sum('price'), 2) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Total Amount -->
            <div class="card card-bordered mt-3">
                <div class="card-inner">
                    <h5 class="title mb-3">Visit Summary</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded">
                                <div class="text-center">
                                    <h6>Medicines</h6>
                                    <h3 class="text-primary">₹{{ number_format($opRegister->medicines->sum('price'), 2) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded">
                                <div class="text-center">
                                    <h6>Radiology</h6>
                                    <h3 class="text-info">₹{{ number_format($opRegister->radiologyTests->sum('price'), 2) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded">
                                <div class="text-center">
                                    <h6>Lab Tests</h6>
                                    <h3 class="text-success">₹{{ number_format($opRegister->labTests->sum('price'), 2) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <h4>Total Visit Amount: <span class="text-danger">₹{{ number_format(
                            $opRegister->medicines->sum('price') +
                            $opRegister->radiologyTests->sum('price') +
                            $opRegister->labTests->sum('price'), 2) }}</span></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
