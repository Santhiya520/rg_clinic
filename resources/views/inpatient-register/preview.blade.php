@extends('layouts.app')

@section('title', 'IP Details - ' . $inpatientRegister->hospital_ip_no)
@section('page-title', 'IP Details - #' . $inpatientRegister->hospital_ip_no)

@section('content')
    <div class="nk-block nk-block-lg">
        <div class="card card-preview">
            <div class="card-inner">
                <!-- Header with buttons -->
                <div class="nk-block-head">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h6 class="title">
                                IP Number: <strong>{{ $inpatientRegister->hospital_ip_no }}</strong>
                                @php
                                    $status = $inpatientRegister->date_of_discharge ? 'discharged' : 'admitted';
                                    $statusClass = $inpatientRegister->date_of_discharge ? 'primary' : 'success';
                                @endphp
                                <span class="badge ml-2 bg-{{ $statusClass }}" style="padding: 3px 8px">
                                    {{ ucfirst($status) }}
                                </span>
                            </h6>
                        </div>
                        <div class="nk-block-head-content">
                            <a href="{{ route('ip-report') }}" class="btn btn-secondary"
                                style="border-radius: 6px 0 0 6px">
                                <em class="icon ni ni-arrow-left"></em> &nbsp; Back to Reports
                            </a>
                            <!-- Change from onclick to route -->
                            <a href="{{ route('inpatient-registers.print', $inpatientRegister) }}" target="_blank"
                                class="btn btn-primary" style="border-radius: 0 6px 6px 0">
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
                                <td>{{ $inpatientRegister->patient->name }}</td>
                            </tr>
                            <tr>
                                <th>Patient ID</th>
                                <td>{{ $inpatientRegister->patient->patient_id }}</td>
                            </tr>
                            <tr>
                                <th>Mobile Number</th>
                                <td>{{ $inpatientRegister->patient->phone }}</td>
                            </tr>
                            <tr>
                                <th>Age & Gender</th>
                                <td>{{ $inpatientRegister->patient->age }} years, {{ $inpatientRegister->patient->gender }}
                                </td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td>{{ $inpatientRegister->patient->address ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="title">IP Details</h6>
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">IP Number</th>
                                <td>{{ $inpatientRegister->hospital_ip_no }}</td>
                            </tr>
                            <tr>
                                <th>Admission Date</th>
                                <td>{{ \Carbon\Carbon::parse($inpatientRegister->date_of_admission)->format('d M Y') }}
                                </td>
                            </tr>
                            @if ($inpatientRegister->date_of_discharge)
                                <tr>
                                    <th>Discharge Date</th>
                                    <td>{{ \Carbon\Carbon::parse($inpatientRegister->date_of_discharge)->format('d M Y') }}
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <th>Admission Days</th>
                                <td>{{ $admissionDays }} days</td>
                            </tr>
                            <tr>
                                <th>Result</th>
                                <td>{{ $inpatientRegister->result ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Medical Information -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h6 class="title">Medical Information</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="40%">Provisional Diagnosis</th>
                                        <td>{{ $inpatientRegister->provisional_diagnosis ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Investigations</th>
                                        <td>{{ $inpatientRegister->investigations ?? 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="40%">Final Diagnosis</th>
                                        <td>{{ $inpatientRegister->final_diagnosis ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Treatment</th>
                                        <td>{{ $inpatientRegister->treatment ?? 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Medicines -->
                @if ($inpatientRegister->medicines->count() > 0)
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
                                @foreach ($inpatientRegister->medicines as $medicine)
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

                <!-- Lab Tests -->
                @if ($inpatientRegister->labTests->count() > 0)
                    <div class="mb-4">
                        <h6 class="title">Laboratory Tests</h6>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Test Name</th>
                                    <th>Price</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($inpatientRegister->labTests as $test)
                                    <tr>
                                        <td>{{ $test->labTest->name }}</td>
                                        <td>₹{{ $test->price }}</td>
                                        <td>{{ $test->notes ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                                <tr class="table-warning">
                                    <td><strong>Total Lab Tests</strong></td>
                                    <td><strong>₹{{ $labTotal }}</strong></td>
                                    <td></td>
                                </tr>
                                <tr class="table-success">
                                    <td><strong>Paid Amount</strong></td>
                                    <td><strong>₹{{ $labPaid }}</strong></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @endif

                <!-- Radiology Tests -->
                @if ($inpatientRegister->radiologyTests->count() > 0)
                    <div class="mb-4">
                        <h6 class="title">Radiology Tests</h6>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Test Name</th>
                                    <th>Price</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($inpatientRegister->radiologyTests as $test)
                                    <tr>
                                        <td>{{ $test->radiologyTest->name }}</td>
                                        <td>₹{{ $test->price }}</td>
                                        <td>{{ $test->notes ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                                <tr class="table-warning">
                                    <td><strong>Total Radiology Tests</strong></td>
                                    <td><strong>₹{{ $radiologyTotal }}</strong></td>
                                    <td></td>
                                </tr>
                                <tr class="table-success">
                                    <td><strong>Paid Amount</strong></td>
                                    <td><strong>₹{{ $radiologyPaid }}</strong></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @endif

                <!-- Additional Information -->
                @if ($inpatientRegister->additional_info)
                    <div class="mb-4">
                        <h6 class="title">Additional Information</h6>
                        <div class="card">
                            <div class="card-body">
                                {{ $inpatientRegister->additional_info }}
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Financial Summary -->
                <div class="row">
                    <div class="col-md-8 offset-md-4">
                        <h6 class="title">Financial Summary</h6>
                        <table class="table table-bordered">
                            <tr class="table-light">
                                <th width="70%">Category</th>
                                <th>Total Amount</th>
                                <th>Paid Amount</th>
                                <th>Balance</th>
                            </tr>
                            @if ($medicineTotal > 0)
                                <tr>
                                    <td>Medicines</td>
                                    <td>₹{{ $medicineTotal }}</td>
                                    <td>₹{{ $medicinePaid }}</td>
                                    <td>₹{{ $medicineTotal - $medicinePaid }}</td>
                                </tr>
                            @endif
                            @if ($labTotal > 0)
                                <tr>
                                    <td>Lab Tests</td>
                                    <td>₹{{ $labTotal }}</td>
                                    <td>₹{{ $labPaid }}</td>
                                    <td>₹{{ $labTotal - $labPaid }}</td>
                                </tr>
                            @endif
                            @if ($radiologyTotal > 0)
                                <tr>
                                    <td>Radiology Tests</td>
                                    <td>₹{{ $radiologyTotal }}</td>
                                    <td>₹{{ $radiologyPaid }}</td>
                                    <td>₹{{ $radiologyTotal - $radiologyPaid }}</td>
                                </tr>
                            @endif
                            <tr class="table-primary">
                                <th>Grand Total</th>
                                <th>₹ {{ $totalAmount }}</th>
                                <th>₹ {{ $totalPaid }}</th>
                                <th>₹ {{ $totalAmount - $totalPaid }}</th>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Medical Officer Signature -->
                @if ($inpatientRegister->medical_officer_initials)
                    <div class="row mt-4">
                        <div class="col-md-12 text-right">
                            <div class="signature-box">
                                <p>Medical Officer Initials:</p>
                                <h4>{{ $inpatientRegister->medical_officer_initials }}</h4>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        @media print {

            .btn,
            .nk-block-head {
                display: none;
            }

            .card {
                border: none;
            }

            .table {
                border: 1px solid #000;
            }

            .table-bordered th,
            .table-bordered td {
                border: 1px solid #000;
            }

            body {
                font-size: 12px;
            }

            .signature-box {
                border-top: 1px solid #000;
                display: inline-block;
                padding-top: 10px;
                margin-top: 50px;
            }
        }
    </style>
@endsection
