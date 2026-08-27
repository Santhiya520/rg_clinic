<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacy Bill - {{ $opRegister->patient->name ?? 'Patient' }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @page {
            size: A4;
            margin: 15mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }

        body {
            background-color: #ffffff;
            padding: 0;
            color: #333;
        }

        .print-container {
            max-width: 210mm;
            margin: 0 auto;
            background: white;
            padding: 15px;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #2c3e50;
        }

        .bill-title {
            color: #2c3e50;
            font-size: 16px;
            font-weight: 600;
            margin: 10px 0;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .patient-info {
            background: #f8f9fa;
            border-radius: 4px;
            padding: 5px;
            margin-bottom: 5px;
            border: 1px solid #000000;
        }

        .patient-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }

        .patient-item {
            display: flex;
            align-items: center
        }

        .patient-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 3px;
            font-size:14px;
        }

        .patient-value {
            color: #34495e;
            padding: 4px;
        }

        .bill-no-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #000000;
        }

        .bill-number {
            color: #2c3e50;
            font-weight: bold;
            font-size: 14px;
        }

        .table-container {
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size:14px;
        }

        th {
            background: #2c3e50;
            color: white;
            padding: 8px 6px;
            text-align: left;
            font-weight: 600;
            border: 1px solid #1a252f;
        }

        td {
            padding: 3px;
            border: 1px solid #000000;
            vertical-align: top;
        }

        tr:nth-child(even) {
            background: #f8f9fa;
        }

        .footer {
            text-align: center;
            padding-top: 12px;
            color: #5d6d7e;
            font-size: 10px;
            border-top: 1px solid #000000;
        }

        .print-actions {
            text-align: center;
            margin-top: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 4px;
        }

        .print-btn {
            background: #2c3e50;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 3px;
            font-size:14px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background 0.3s;
        }

        .print-btn:hover {
            background: #1a252f;
        }

        .back-btn {
            background: #6c757d;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 3px;
            font-size:14px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-left: 8px;
            text-decoration: none;
        }

        .back-btn:hover {
            background: #5a6268;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .print-container {
                box-shadow: none;
                padding: 0;
                margin: 0;
                max-width: 100%;
            }

            .print-actions {
                display: none;
            }

            .back-btn {
                display: none;
            }

            .print-btn {
                display: none;
            }

            table, .patient-info {
                page-break-inside: avoid;
            }

            table {
                page-break-before: auto;
            }
        }

        @media (max-width: 768px) {
            .print-container {
                padding: 10px;
            }

            .patient-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media (max-width: 480px) {
            .patient-grid {
                grid-template-columns: 1fr;
            }
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .mt-10 {
            margin-top: 10px;
        }

        .mb-5 {
            margin-bottom: 5px;
        }

        /* Doctor Info */
        .doctor-info {
            background: #e8f4fd;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 15px;
            border-left: 3px solid #3498db;
        }

        .doctor-info h4 {
            color: #2c3e50;
            margin-bottom: 6px;
            font-size: 12px;
        }

        /* Timing badges */
        .timing-badge {
            display: inline-block;
            background: #e9ecef;
            border: 1px solid #dee2e6;
            border-radius: 3px;
            padding: 1px 4px;
            margin: 1px;
            font-size: 11px;
        }

        .route-info {
            font-size: 11px;
            color: #6c757d;
            margin-top: 2px;
        }
    </style>
</head>
<body>
    <div class="print-container">
        <!-- Header -->
        <div class="header">
            <img src="{{ asset('images/rg-banner.png') }}" style="max-width: 100%; height: auto;">
        </div>

        <!-- Bill Title -->
        <div class="bill-title" style="  margin: -10PX 0px 5PX 0PX">
            PATIENT PRESCRIPTION
        </div>

        <!-- Patient Information -->
        <div class="patient-info">
            <!-- Bill Number Row -->
            <div class="bill-no-row">
                <div class="bill-number">Prescription No: {{ $opRegister->op_no ?? 'N/A' }}</div>
                <div class="patient-item">
                    <span class="patient-label">Token No : </span>
                    <span class="patient-value"> &nbsp;{{ $opRegister->token_number }}</span>
                </div>
            </div>

            <!-- Patient Details in 4-column grid -->
            <div class="patient-grid">
                <div class="patient-item">
                    <span class="patient-label">Patient ID : </span>
                    <span class="patient-value">{{ $opRegister->patient->patient_id ?? 'N/A' }}</span>
                </div>
                <div class="patient-item">
                    <span class="patient-label">Patient Name : </span>
                    <span class="patient-value">{{ $opRegister->patient->name ?? 'N/A' }}</span>
                </div>
                <div class="patient-item">
                    <span class="patient-label">Age / Gender : </span>
                    <span class="patient-value">
                        {{ $opRegister->patient->age ?? 'N/A' }} years /
                        {{ ucfirst($opRegister->patient->gender ?? 'N/A') }}
                    </span>
                </div>
                <div class="patient-item">
                    <span class="patient-label">Date : </span>
                    <span class="patient-value">{{ $opRegister->created_at }}</span>
                </div>
                @if($opRegister->patient && $opRegister->patient->mobile)
                <div class="patient-item">
                    <span class="patient-label">Phone : </span>
                    <span class="patient-value">{{ $opRegister->patient->mobile ?? 'N/A' }}</span>
                </div>
                @endif
                @if($opRegister->patient && $opRegister->patient->address)
                <div class="patient-item" style="grid-column: span 2;">
                    <span class="patient-label">Address : </span>
                    <span class="patient-value">{{ $opRegister->patient->address ?? 'N/A' }}</span>
                </div>
                @endif
                <!-- Patient Vitals -->
                @if($opRegister->weight)
                <div class="patient-item">
                    <span class="patient-label">Weight : </span>
                    <span class="patient-value">{{ $opRegister->weight }} kg</span>
                </div>
                @endif
                @if($opRegister->height)
                <div class="patient-item">
                    <span class="patient-label">Height : </span>
                    <span class="patient-value">{{ $opRegister->height }} cm</span>
                </div>
                @endif
                @if($opRegister->bp)
                <div class="patient-item">
                    <span class="patient-label">BP : </span>
                    <span class="patient-value">{{ $opRegister->bp }}</span>
                </div>
                @endif
                @if($opRegister->temparature)
                <div class="patient-item">
                    <span class="patient-label">Temperature : </span>
                    <span class="patient-value">{{ $opRegister->temparature }} °C</span>
                </div>
                @endif
                @if($opRegister->pluse)
                <div class="patient-item">
                    <span class="patient-label">Pulse : </span>
                    <span class="patient-value">{{ $opRegister->pluse }} bpm</span>
                </div>
                @endif
                @if($opRegister->spo2)
                <div class="patient-item">
                    <span class="patient-label">SpO₂ : </span>
                    <span class="patient-value">{{ $opRegister->spo2 }}%</span>
                </div>
                @endif
                @if($opRegister->medicalOfficer)
                <div class="patient-item">
                    <span class="patient-label">Doctor Name : </span>
                    <span class="patient-value">{{ $opRegister->medicalOfficer->name }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Medicines Table -->
        <div class="table-container">
            <h4 style="margin-bottom: 5px; color: #2c3e50; font-size: 13px;">Medicines Prescribed</h4>
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 30%;">Medicine Name</th>
                        <th style="width: 25%;">Timing & Route</th>
                        <th style="width: 10%;">Days</th>
                        <th style="width: 10%;">Qty</th>
                        <th style="width: 20%;">Instructions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($opRegister->medicines as $index => $medicine)
                    @if($medicine->medicine->category != 'INJECTION')
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            @php
                                $decodedName = \App\Helpers\StringHelper::decodeQuotes($medicine->medicine->name ?? 'N/A');
                            @endphp
                            <strong>{{ $decodedName }}</strong>
                            @if($medicine->medicine->category ?? false)
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
                                    <span class="timing-badge">{{ $time }}</span>
                                @endforeach
                            @else
                                <span class="text-muted">Not specified</span>
                            @endif

                            @if(!empty($injectionRoutes))
                                <div class="route-info">
                                    <strong>Route:</strong> {{ implode(', ', $injectionRoutes) }}
                                </div>
                            @endif
                        </td>
                        <td class="text-center">{{ $medicine->no_of_days ?? 'N/A' }}</td>
                        <td class="text-center">{{ $medicine->quantity ?? 'N/A' }}</td>
                        <td>{{ $medicine->instructions ?? 'No instructions' }}</td>
                    </tr>
                    @endif
                    @endforeach
                    @if($opRegister->medicines->isEmpty())
                    <tr>
                        <td colspan="6" class="text-center">No medicines prescribed</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Radiology Tests -->
        @if($opRegister->radiologyTests->count() > 0)
        <div class="table-container my-5">
            <h4 style="margin-bottom: 5px; color: #2c3e50; font-size: 13px;">Radiology Tests</h4>
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 75%;">Test Name</th>
                        <th style="width: 20%;">Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($opRegister->radiologyTests as $index => $radiology)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $radiology->radiologyTest->name ?? 'N/A' }}</td>
                        <td>{{ $radiology->notes ?? 'No notes' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Lab Tests -->
        @if($opRegister->labTests->count() > 0)
        <div class="table-container my-5">
            <h4 style="margin-bottom: 5px; color: #2c3e50; font-size: 13px;">Laboratory Tests</h4>
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 75%;">Test Name</th>
                        <th style="width: 20%;">Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($opRegister->labTests as $index => $labTest)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $labTest->labTest->name ?? 'N/A' }}</td>
                        <td>{{ $labTest->notes ?? 'No notes' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif



        <!-- Additional Information -->
        @if($opRegister->additional_information)
        <div class="patient-info mt-10">
            <h4 style="margin-bottom: 8px; color: #2c3e50; font-size: 13px;">Additional Information</h4>
            <div class="patient-value">
                {{ $opRegister->additional_information }}
            </div>
        </div>
        @endif

        <div class="footer">
            <p style="text-align: right;margin-right:10px" class="mt-2">
                <strong>Signature:</strong> _________________________
                <br>
                <small>Date: {{ date('d/m/Y') }}</small>
            </p>

        </div>

        <!-- Print Actions (Only visible on screen, hidden when printing) -->
        <div class="print-actions">
            <button onclick="window.print()" class="print-btn">
                <i class="fas fa-print"></i> Print Prescription
            </button>
            <a href="{{ route('op-registers.doctor-op') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <script>
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
        });

        // Optional: Auto-print after load (uncomment if needed)
        // window.onload = function() {
        //     setTimeout(function() {
        //         window.print();
        //     }, 500);
        // };
    </script>
</body>
</html>
