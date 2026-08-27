@extends('online.layouts.app')

@section('title', 'Lab Report - ' . ($foundTest->labTest->name ?? 'Test'))

@section('content')
    <div class="nk-block nk-block-lg">
        <div class="card card-preview">
            <div class="card-inner">
                <!-- Header -->
                <div class="nk-block-head">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Laboratory Report</h3>
                            <div class="nk-block-des text-soft">
                                <p>{{ $foundTest->labTest->name ?? 'Test' }}</p>
                            </div>
                        </div>
                        <div class="nk-block-head-content">
                            <a href="{{ route('online.lab.reports') }}" class="btn btn-secondary">
                                <em class="icon ni ni-arrow-left"></em>&nbsp; Back to Reports
                            </a>
                            <a href="{{ route('online.lab.test.print', $foundTest) }}" target="_blank"
                                class="btn btn-primary">
                                <em class="icon ni ni-printer"></em>&nbsp; Print Report
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Print View Container -->
                <div id="printable-report">
                    <!-- Test Details -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card card-bordered">
                                <div class="card-inner">
                                    <h6 class="title">Patient Information</h6>
                                    <table class="table">
                                        <tr>
                                            <th width="40%">Patient Name</th>
                                            <td>{{ $patient->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Patient ID</th>
                                            <td>{{ $patient->patient_id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Age & Gender</th>
                                            <td>{{ $patient->age }} years, {{ ucfirst($patient->sex) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Visit Type</th>
                                            <td>
                                                <span class="badge bg-{{ $source == 'op' ? 'primary' : 'info' }}">
                                                    {{ strtoupper($source) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Reference</th>
                                            <td>
                                                @if ($source == 'op')
                                                    Token: {{ $register->token_number }}
                                                @else
                                                    IP No: {{ $register->ip_no }}
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card card-bordered">
                                <div class="card-inner">
                                    <h6 class="title">Test Information</h6>
                                    <table class="table">
                                        <tr>
                                            <th width="40%">Test Name</th>
                                            <td>{{ $foundTest->labTest->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Test Date</th>
                                            <td>{{ $register->created_at->format('d M Y') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Report Date</th>
                                            <td>{{ $foundTest->completed_at ? $foundTest->completed_at->format('d M Y') : 'N/A' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Doctor</th>
                                            <td>{{ $register->medicalOfficer->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>
                                                <span class="badge bg-success">
                                                    Completed
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Test Results -->
                    <div class="card card-bordered mt-4">
                        <div class="card-inner">
                            <h5 class="title mb-3">Test Results</h5>

                            @if ($foundTest->subTests->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Test Parameter</th>
                                                <th>Result</th>
                                                <th>Unit</th>
                                                <th>Normal Range</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($foundTest->subTests as $index => $subTest)
                                                <tr>
                                                    <td class="text-center">{{ $index + 1 }}</td>
                                                    <td>{{ $subTest->test_name }}</td>
                                                    <td class="text-primary font-weight-bold">
                                                        {{ $subTest->result ?? 'Not Available' }}
                                                    </td>
                                                    <td>{{ $subTest->unit ?? 'N/A' }}</td>
                                                    <td class="text-muted">
                                                        {{ $subTest->normal_range ?? 'N/A' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center p-4">
                                    <em class="icon ni ni-info text-warning" style="font-size: 3rem;"></em>
                                    <p class="mt-2">No detailed test parameters available.</p>
                                </div>
                            @endif

                            <!-- Overall Result Summary -->
                            @if ($foundTest->result)
                                <div class="mt-4 p-3 bg-light rounded">
                                    <h6 class="title mb-2">Overall Result Summary</h6>
                                    <p class="mb-0">{{ $foundTest->result }}</p>
                                </div>
                            @endif

                            <!-- Notes -->
                            @if ($foundTest->notes)
                                <div class="mt-3 p-3 bg-light rounded">
                                    <h6 class="title mb-2">Notes</h6>
                                    <p class="mb-0">{{ $foundTest->notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Interpretation (if available) -->
                    @if ($foundTest->interpretation)
                        <div class="card card-bordered mt-4">
                            <div class="card-inner">
                                <h5 class="title mb-3">Interpretation</h5>
                                <div class="p-3 bg-light rounded">
                                    <p class="mb-0">{{ $foundTest->interpretation }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Signature Area -->
                    <div class="text-center mt-5 pt-4 border-top">
                        <div style="margin: 40px auto 10px; width: 250px; border-top: 1px solid #000;"></div>
                        <p class="text-muted mb-0">Authorized Lab Technician / Doctor</p>
                        <p class="text-muted small">RG Maruthuvamaiyam</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            #printable-report {
                font-size: 12px;
            }

            .card {
                border: none !important;
            }

            .card-bordered {
                border: 1px solid #000 !important;
                margin-bottom: 15px;
            }

            .table {
                border: 1px solid #000;
            }

            .table-bordered th,
            .table-bordered td {
                border: 1px solid #000;
            }

            .btn,
            .nk-block-head,
            .alert {
                display: none;
            }

            body {
                background: white !important;
            }

            .container-fluid {
                padding: 0 !important;
            }

            .nk-content-body {
                padding: 0 !important;
            }
        }
    </style>

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Auto-print after 2 seconds when print button is clicked
                $('button[onclick="window.print()"]').click(function() {
                    setTimeout(function() {
                        window.print();
                    }, 2000);
                });
            });
        </script>
    @endpush
@endsection
