@extends('layouts.app')

@section('page-title', 'View Prescription')

@section('content')
    <div class="nk-block nk-block-lg">

        <!-- Patient Information Section -->
        <div class="card card-preview mb-3">
            <div class="card-inner">
                <div class="nk-block-head">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h5 class="card-title">Patient Information</h5>
                            <p class="text-soft">Date: {{ $opRegister->created_at->format('d/m/Y h:i A') }}</p>
                        </div>
                        <div class="nk-block-head-content">
                            <span class="badge bg-{{ $opRegister->status == 'active' ? 'success' : ($opRegister->status == 'completed' ? 'primary' : 'danger') }}">
                                {{ ucfirst($opRegister->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label"><strong>Name</strong></label>
                            <div class="form-control-plaintext">
                                {{ $opRegister->patient?->name ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label"><strong>Age</strong></label>
                            <div class="form-control-plaintext">
                                {{ $opRegister->patient?->age?? 'N/A' }} years
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label"><strong>Sex</strong></label>
                            <div class="form-control-plaintext">
                                {{ $opRegister->patient?->gender ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label"><strong>OP No</strong></label>
                            <div class="form-control-plaintext">
                                {{ $opRegister->op_no ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label"><strong>Token</strong></label>
                            <div class="form-control-plaintext">
                                {{ $opRegister->token_number }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label"><strong>Weight (kg)</strong></label>
                            <div class="form-control-plaintext">
                                {{ $opRegister->weight ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label"><strong>Height (cm)</strong></label>
                            <div class="form-control-plaintext">
                                {{ $opRegister->height ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label"><strong>Pulse</strong></label>
                            <div class="form-control-plaintext">
                                {{ $opRegister->pluse ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label"><strong>SpO₂</strong></label>
                            <div class="form-control-plaintext">
                                {{ $opRegister->spo2 ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label"><strong>BP</strong></label>
                            <div class="form-control-plaintext">
                                {{ $opRegister->bp ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label"><strong>Temperature</strong></label>
                            <div class="form-control-plaintext">
                                {{ $opRegister->temparature ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Comorbidities and History -->
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label"><strong>Comorbidities</strong></label>
                            <div class="form-control-plaintext">
                                {!! nl2br(e($opRegister->patient?->comorbidities ?? 'Not specified')) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label"><strong>Medical History</strong></label>
                            <div class="form-control-plaintext">
                                {!! nl2br(e($opRegister->patient?->history ?? 'Not specified')) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Diagnosis & Treatment Section -->
        <div class="card card-preview">
            <div class="card-inner">
                <div class="nk-block-head">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h5 class="card-title">Diagnosis & Treatment</h5>
                            <p class="text-soft">Patient ID: <strong>{{ $opRegister->patient?->patient_id ?? 'N/A' }}</strong></p>
                        </div>
                        <div class="nk-block-head-content">
                            <div class="btn-group">
                                <a href="{{ route('op-registers.prescription.edit', $opRegister) }}"
                                    class="btn btn-primary">
                                    <em class="icon ni ni-edit"></em> Edit Prescription
                                </a>
                                <a href="{{ route('op-registers.doctor-op') }}" class="btn btn-secondary">
                                    <em class="icon ni ni-arrow-left"></em> Back to List
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <h5 class="card-title">Diagnosis & Treatment</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label"><strong>Provisional Diagnosis</strong></label>
                            <div class="form-control-plaintext">
                                {!! nl2br(e($opRegister->provisional_diagnosis ?? 'Not specified')) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label"><strong>Investigations</strong></label>
                            <div class="form-control-plaintext">
                                {!! nl2br(e($opRegister->investigations ?? 'Not specified')) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label"><strong>Final Diagnosis</strong></label>
                            <div class="form-control-plaintext">
                                {!! nl2br(e($opRegister->final_diagnosis ?? 'Not specified')) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label"><strong>Treatment</strong></label>
                            <div class="form-control-plaintext">
                                {!! nl2br(e($opRegister->treatment ?? 'Not specified')) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label"><strong>Result</strong></label>
                            <div class="form-control-plaintext">
                                @if ($opRegister->result)
                                    <span
                                        class="badge bg-{{ $opRegister->result == 'cured' ? 'success' : ($opRegister->result == 'improved' ? 'primary' : ($opRegister->result == 'not_improved' ? 'warning' : 'info')) }}">
                                        {{ ucfirst(str_replace('_', ' ', $opRegister->result)) }}
                                    </span>
                                @else
                                    Not specified
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label"><strong>Status</strong></label>
                            <div class="form-control-plaintext">
                                <span
                                    class="badge bg-{{ $opRegister->status == 'active' ? 'success' : ($opRegister->status == 'completed' ? 'primary' : 'danger') }}">
                                    {{ ucfirst($opRegister->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label"><strong>Additional Information</strong></label>
                            <div class="form-control-plaintext">
                                {!! nl2br(e($opRegister->additional_information ?? 'Not specified')) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Medicines Section -->
        @if ($opRegister->medicines->count() > 0)
            <div class="card card-preview mt-3">
                <div class="card-inner">
                    <h5 class="card-title">Medicines</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Medicine</th>
                                    <th>Timing & Route</th>
                                    <th>Days</th>
                                    <th>Qty</th>
                                    <th>Instructions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($opRegister->medicines as $medicine)
                                    <tr>
                                        <td>
                                            @php
                                                $decodedName = \App\Helpers\StringHelper::decodeQuotes($medicine->medicine->name);
                                            @endphp
                                            <strong>{{ $decodedName ?? 'N/A' }}</strong>
                                            @if ($medicine->medicine->category ?? false)
                                                <br><small class="text-muted">({{ $medicine->medicine->category }})</small>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $timing = [];
                                                if($medicine->morning) $timing[] = 'Morning';
                                                if($medicine->afternoon) $timing[] = 'Afternoon';
                                                if($medicine->night) $timing[] = 'Night';
                                                if($medicine->sos) $timing[] = 'SOS';
                                                if($medicine->ml) $timing[] = 'ML';

                                                $injectionRoutes = [];
                                                if($medicine->im_route) $injectionRoutes[] = 'IM';
                                                if($medicine->iv_route) $injectionRoutes[] = 'IV';
                                                if($medicine->id_route) $injectionRoutes[] = 'ID';
                                                if($medicine->sub_q_route) $injectionRoutes[] = 'SUB-Q';
                                            @endphp

                                            @if(!empty($timing))
                                                @foreach($timing as $time)
                                                    <span class="badge bg-light me-1 mb-1">{{ $time }}</span>
                                                @endforeach
                                            @else
                                                <span class="text-muted">Not specified</span>
                                            @endif

                                            @if(!empty($injectionRoutes))
                                                <div class="mt-1">
                                                    <small class="text-muted">Route: {{ implode(', ', $injectionRoutes) }}</small>
                                                </div>
                                            @endif
                                        </td>
                                        <td>{{ $medicine->no_of_days ?? 'N/A' }} {{ $medicine->no_of_days ? 'days' : '' }}</td>
                                        <td>{{ $medicine->quantity ?? 'N/A' }}</td>
                                        <td>{!! nl2br(e($medicine->instructions ?? 'No instructions')) !!}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <!-- Radiology Tests Section -->
        @if ($opRegister->radiologyTests->count() > 0)
            <div class="card card-preview mt-3">
                <div class="card-inner">
                    <h5 class="card-title">Radiology Tests</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Test Name</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($opRegister->radiologyTests as $radiology)
                                    <tr>
                                        <td><strong>{{ $radiology->radiologyTest->name ?? 'N/A' }}</strong></td>
                                        <td>{!! nl2br(e($radiology->notes ?? 'No notes')) !!}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <!-- Lab Tests Section with Sub Tests -->
        @if ($opRegister->labTests->count() > 0)
            <div class="card card-preview mt-3">
                <div class="card-inner">
                    <h5 class="card-title">Laboratory Tests</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Test Name</th>
                                    <th>Sub Tests</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($opRegister->labTests as $labTest)
                                    <tr>
                                        <td>
                                            <strong>{{ $labTest->labTest->name ?? 'N/A' }}</strong>
                                        </td>
                                        <td>
                                            @if ($labTest->subTests->count() > 0)
                                                <div class="sub-tests-list">
                                                    @foreach ($labTest->subTests as $subTest)
                                                        <div class="sub-test-item mb-2 p-2 bg-light rounded">
                                                            <div class="fw-bold">{{ $subTest->test_name }}</div>
                                                            <div class="small text-muted">
                                                                @if($subTest->unit)
                                                                    <span class="me-2">Unit: {{ $subTest->unit }}</span>
                                                                @endif
                                                                @if($subTest->normal_range)
                                                                    <span>Normal Range: {{ $subTest->normal_range }}</span>
                                                                @endif
                                                            </div>
                                                            @if($subTest->result)
                                                                <div class="mt-1">
                                                                    <span class="badge bg-info">Result: {{ $subTest->result }}</span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-muted">No sub tests</span>
                                            @endif
                                        </td>
                                        <td>{!! nl2br(e($labTest->notes ?? 'No notes')) !!}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <!-- Summary Section -->
        <div class="card card-preview mt-3">
            <div class="card-inner">
                <h5 class="card-title">Prescription Summary</h5>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label"><strong>Total Medicines</strong></label>
                            <div class="form-control-plaintext">
                                {{ $opRegister->medicines->count() }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label"><strong>Total Radiology Tests</strong></label>
                            <div class="form-control-plaintext">
                                {{ $opRegister->radiologyTests->count() }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label"><strong>Total Lab Tests</strong></label>
                            <div class="form-control-plaintext">
                                {{ $opRegister->labTests->count() }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label"><strong>Total Sub Tests</strong></label>
                            <div class="form-control-plaintext">
                                @php
                                    $totalSubTests = 0;
                                    foreach($opRegister->labTests as $labTest) {
                                        $totalSubTests += $labTest->subTests->count();
                                    }
                                @endphp
                                {{ $totalSubTests }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Medicine Statistics -->
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label"><strong>Medicine Types</strong></label>
                            <div class="form-control-plaintext">
                                @php
                                    $injectionCount = 0;
                                    $regularCount = 0;
                                    foreach($opRegister->medicines as $medicine) {
                                        $category = strtolower($medicine->medicine->category ?? '');
                                        if (str_contains($category, 'inject') || str_contains($category, 'injection')) {
                                            $injectionCount++;
                                        } else {
                                            $regularCount++;
                                        }
                                    }
                                @endphp
                                Regular: {{ $regularCount }} | Injections: {{ $injectionCount }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label"><strong>SOS Medicines</strong></label>
                            <div class="form-control-plaintext">
                                {{ $opRegister->medicines->where('sos', true)->count() }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lab Tests with Results -->
                @php
                    $labTestsWithResults = 0;
                    foreach($opRegister->labTests as $labTest) {
                        foreach($labTest->subTests as $subTest) {
                            if($subTest->result) {
                                $labTestsWithResults++;
                                break;
                            }
                        }
                    }
                @endphp
                @if($labTestsWithResults > 0)
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label"><strong>Lab Tests with Results</strong></label>
                                <div class="form-control-plaintext">
                                    {{ $labTestsWithResults }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="mt-3">
            <a href="{{ route('op-registers.prescription.edit', $opRegister) }}" class="btn btn-primary">
                <em class="icon ni ni-edit"></em> Edit Prescription
            </a>
            <a href="{{ route('op-registers.doctor-op') }}" class="btn btn-secondary">Back to List</a>
            <button onclick="window.print()" class="btn btn-info">
                <em class="icon ni ni-printer"></em> Print Prescription
            </button>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        @media print {
            .btn,
            .nk-block-head-content .btn-group,
            .form-label {
                display: none !important;
            }

            .card {
                border: 1px solid #ddd !important;
                box-shadow: none !important;
            }

            .card-title {
                color: #000 !important;
                border-bottom: 2px solid #000;
                padding-bottom: 10px;
                font-size: 16px;
                font-weight: bold;
            }

            .form-control-plaintext {
                padding: 0 !important;
                margin-bottom: 10px;
            }

            .badge {
                border: 1px solid #000;
                background-color: #fff !important;
                color: #000 !important;
                padding: 2px 6px;
                margin: 1px;
            }

            .table {
                border-collapse: collapse;
                width: 100%;
            }

            .table th {
                background-color: #f0f0f0 !important;
                font-weight: bold;
            }

            .sub-test-item {
                border: 1px solid #ddd;
                margin-bottom: 5px;
                padding: 5px;
            }
        }

        .badge {
            font-size: 0.75em;
        }

        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .form-control-plaintext {
            min-height: auto;
            padding: 0;
            background-color: transparent;
        }

        .badge.bg-light {
            background-color: #f8f9fa !important;
            color: #212529 !important;
            border: 1px solid #dee2e6;
        }

        .sub-tests-list {
            max-height: 300px;
            overflow-y: auto;
        }

        .sub-test-item {
            border-left: 3px solid #0d6efd;
        }

        .sub-test-item:last-child {
            margin-bottom: 0 !important;
        }
    </style>
@endpush
