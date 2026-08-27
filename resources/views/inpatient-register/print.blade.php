<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inpatient Report - {{ $inpatientRegister->hospital_ip_no }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @page {
            size: A4;
            margin: 10mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 11px;
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
            padding: 10px;
        }

        .header {
            text-align: center;
            padding-bottom: 8px;
            margin-bottom: 10px;
            border-bottom: 2px solid #2c3e50;
        }

        .report-title {
            color: #2c3e50;
            font-size: 16px;
            font-weight: 700;
            margin: 8px 0;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .patient-info {
            background: #f8f9fa;
            border-radius: 3px;
            padding: 10px;
            margin-bottom: 12px;
            border: 1px solid #dee2e6;
        }

        .patient-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
        }

        .patient-item {
            display: flex;
            flex-direction: column;
        }

        .patient-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 2px;
            font-size: 10px;
        }

        .patient-value {
            color: #34495e;
            padding: 3px 0;
        }

        .ip-status-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
            padding-bottom: 8px;
            border-bottom: 1px solid #dee2e6;
        }

        .ip-number {
            color: #2c3e50;
            font-weight: bold;
            font-size: 13px;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .admitted-badge {
            background: #27ae60;
            color: white;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: 600;
        }

        .discharged-badge {
            background: #3498db;
            color: white;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: 600;
        }

        .section-title {
            color: #2c3e50;
            font-size: 12px;
            font-weight: 600;
            margin: 12px 0 6px 0;
            padding-bottom: 4px;
            border-bottom: 1px solid #dee2e6;
        }

        .table-container {
            margin-bottom: 10px;
            overflow: hidden;
            page-break-inside: avoid;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        th {
            background: #2c3e50;
            color: white;
            padding: 6px 4px;
            text-align: left;
            font-weight: 600;
            border: 1px solid #1a252f;
        }

        td {
            padding: 4px;
            border: 1px solid #dee2e6;
            vertical-align: top;
        }

        tr:nth-child(even) {
            background: #f8f9fa;
        }

        .total-section {
            background: #f8f9fa;
            border-radius: 3px;
            padding: 10px;
            margin-bottom: 12px;
            border: 1px solid #dee2e6;
        }

        .total-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 6px;
        }

        .total-item {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
            border-bottom: 1px dashed #ced4da;
        }

        .total-item:last-child {
            border-bottom: none;
        }

        .total-item.full-width {
            grid-column: 1 / -1;
            border-top: 2px solid #2c3e50;
            padding-top: 6px;
            margin-top: 6px;
            font-size: 12px;
        }

        .total-label {
            font-weight: 600;
            color: #2c3e50;
        }

        .total-value {
            font-weight: 600;
            color: #2c3e50;
        }

        .footer {
            text-align: center;
            padding-top: 10px;
            color: #5d6d7e;
            font-size: 9px;
            border-top: 1px solid #dee2e6;
            margin-top: 10px;
        }

        .print-actions {
            text-align: center;
            margin-top: 15px;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 3px;
        }

        .print-btn {
            background: #2c3e50;
            color: white;
            border: none;
            padding: 6px 15px;
            border-radius: 2px;
            font-size: 10px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: background 0.3s;
        }

        .print-btn:hover {
            background: #1a252f;
        }

        .close-btn {
            background: #6c757d;
            color: white;
            border: none;
            padding: 6px 15px;
            border-radius: 2px;
            font-size: 10px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-left: 8px;
        }

        .close-btn:hover {
            background: #5a6268;
        }

        .medical-info {
            background: #e8f4fd;
            border-radius: 3px;
            padding: 8px;
            margin: 8px 0;
            border-left: 3px solid #3498db;
        }

        .signature-box {
            text-align: right;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #000;
            display: inline-block;
            float: right;
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

        .mb-3 {
            margin-bottom: 3px;
        }

        .mt-3 {
            margin-top: 3px;
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

            table, .total-section, .patient-info {
                page-break-inside: avoid;
            }

            .section-break {
                page-break-before: always;
            }
        }

        @media (max-width: 768px) {
            .print-container {
                padding: 8px;
            }

            .patient-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .total-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="print-container">
        <!-- Header -->
        <div class="header">
            <img src="{{ asset('images/rg-banner.png') }}" style="max-width: 100%; height: auto;">
        </div>

        <!-- Report Title -->
        <div class="report-title">
            INPATIENT DETAILED REPORT
        </div>

        <!-- Patient Information -->
        <div class="patient-info">
            <!-- IP Number and Status Row -->
            <div class="ip-status-row">
                <div class="ip-number">IP No: {{ $inpatientRegister->hospital_ip_no }}</div>
                <div class="status-badge">
                    Status:
                    @if($inpatientRegister->date_of_discharge)
                        <span class="discharged-badge">DISCHARGED</span>
                    @else
                        <span class="admitted-badge">ADMITTED</span>
                    @endif
                </div>
            </div>

            <!-- Patient Details -->
            <div class="patient-grid">
                <div class="patient-item">
                    <span class="patient-label">Patient ID</span>
                    <span class="patient-value">{{ $inpatientRegister->patient->patient_id ?? 'N/A' }}</span>
                </div>
                <div class="patient-item">
                    <span class="patient-label">Patient Name</span>
                    <span class="patient-value">{{ $inpatientRegister->patient->name ?? 'N/A' }}</span>
                </div>
                <div class="patient-item">
                    <span class="patient-label">Age / Gender</span>
                    <span class="patient-value">
                        {{ $inpatientRegister->patient->age ?? 'N/A' }} years /
                        {{ ucfirst($inpatientRegister->patient->gender ?? 'N/A') }}
                    </span>
                </div>
                <div class="patient-item">
                    <span class="patient-label">Phone</span>
                    <span class="patient-value">{{ $inpatientRegister->patient->phone ?? 'N/A' }}</span>
                </div>
                <div class="patient-item">
                    <span class="patient-label">Admission Date</span>
                    <span class="patient-value">{{ \Carbon\Carbon::parse($inpatientRegister->date_of_admission)->format('d/m/Y') }}</span>
                </div>
                @if($inpatientRegister->date_of_discharge)
                <div class="patient-item">
                    <span class="patient-label">Discharge Date</span>
                    <span class="patient-value">{{ \Carbon\Carbon::parse($inpatientRegister->date_of_discharge)->format('d/m/Y') }}</span>
                </div>
                @endif
                <div class="patient-item">
                    <span class="patient-label">Admission Days</span>
                    <span class="patient-value">{{ $admissionDays }} days</span>
                </div>
                @if($inpatientRegister->patient->address)
                <div class="patient-item" style="grid-column: span 2;">
                    <span class="patient-label">Address</span>
                    <span class="patient-value">{{ $inpatientRegister->patient->address }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Medical Information -->
        <div class="medical-info">
            <h4 style="color: #2c3e50; font-size: 12px; margin-bottom: 6px;">Medical Information</h4>
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;">
                <div>
                    <div class="mb-3"><strong>Provisional Diagnosis:</strong></div>
                    <div>{{ $inpatientRegister->provisional_diagnosis ?? 'N/A' }}</div>
                </div>
                <div>
                    <div class="mb-3"><strong>Final Diagnosis:</strong></div>
                    <div>{{ $inpatientRegister->final_diagnosis ?? 'N/A' }}</div>
                </div>
                <div>
                    <div class="mb-3"><strong>Investigations:</strong></div>
                    <div>{{ $inpatientRegister->investigations ?? 'N/A' }}</div>
                </div>
                <div>
                    <div class="mb-3"><strong>Treatment:</strong></div>
                    <div>{{ $inpatientRegister->treatment ?? 'N/A' }}</div>
                </div>
            </div>
            @if($inpatientRegister->result)
            <div class="mt-3">
                <div class="mb-3"><strong>Result:</strong></div>
                <div>{{ $inpatientRegister->result }}</div>
            </div>
            @endif
        </div>

        <!-- Medicines Section -->
        @if($inpatientRegister->medicines->count() > 0)
        <div class="table-container">
            <div class="section-title">Medicines Prescribed</div>
            <table>
                <thead>
                    <tr>
                        <th style="width: 3%;">#</th>
                        <th style="width: 25%;">Medicine Name</th>
                        <th style="width: 12%;">Timing</th>
                        <th style="width: 8%;">Days</th>
                        <th style="width: 8%;">Qty</th>
                        <th style="width: 12%;">Price (₹)</th>
                        <th style="width: 12%;">Total (₹)</th>
                        <th style="width: 20%;">Instructions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inpatientRegister->medicines as $index => $medicine)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        @php
                                    // Decode the medicine name for display
                                    $decodedName = \App\Helpers\StringHelper::decodeQuotes($medicine->medicine->name);
                                @endphp
                        <td>{{ $decodedName ?? 'N/A' }}</td>
                        <td>
                            @php
                                $timing = [];
                                if($medicine->morning) $timing[] = 'M';
                                if($medicine->afternoon) $timing[] = 'A';
                                if($medicine->night) $timing[] = 'N';
                            @endphp
                            {{ implode(', ', $timing) }}
                        </td>
                        <td class="text-center">{{ $medicine->no_of_days }}</td>
                        <td class="text-center">{{ $medicine->quantity }}</td>
                        <td class="text-right">{{ number_format($medicine->price, 2) }}</td>
                        <td class="text-right">{{ number_format($medicine->quantity * $medicine->price, 2) }}</td>
                        <td>{{ $medicine->instructions ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background: #e3f2fd;">
                        <td colspan="6" class="text-right"><strong>Medicines Total:</strong></td>
                        <td class="text-right"><strong>₹{{ number_format($medicineTotal, 2) }}</strong></td>
                        <td></td>
                    </tr>
                    <tr style="background: #d4edda;">
                        <td colspan="6" class="text-right"><strong>Paid Amount:</strong></td>
                        <td class="text-right"><strong>₹{{ number_format($medicinePaid, 2) }}</strong></td>
                        <td></td>
                    </tr>
                    <tr style="background: #fff3cd;">
                        <td colspan="6" class="text-right"><strong>Balance:</strong></td>
                        <td class="text-right"><strong>₹{{ number_format($medicineTotal - $medicinePaid, 2) }}</strong></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @endif

        <!-- Lab Tests Section -->
        @if($inpatientRegister->labTests->count() > 0)
        <div class="table-container">
            <div class="section-title">Laboratory Tests</div>
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 50%;">Test Name</th>
                        <th style="width: 15%;">Price (₹)</th>
                        <th style="width: 30%;">Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inpatientRegister->labTests as $index => $test)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $test->labTest->name ?? 'N/A' }}</td>
                        <td class="text-right">{{ number_format($test->price, 2) }}</td>
                        <td>{{ $test->notes ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background: #e3f2fd;">
                        <td colspan="2" class="text-right"><strong>Lab Tests Total:</strong></td>
                        <td class="text-right"><strong>₹{{ number_format($labTotal, 2) }}</strong></td>
                        <td></td>
                    </tr>
                    <tr style="background: #d4edda;">
                        <td colspan="2" class="text-right"><strong>Paid Amount:</strong></td>
                        <td class="text-right"><strong>₹{{ number_format($labPaid, 2) }}</strong></td>
                        <td></td>
                    </tr>
                    <tr style="background: #fff3cd;">
                        <td colspan="2" class="text-right"><strong>Balance:</strong></td>
                        <td class="text-right"><strong>₹{{ number_format($labTotal - $labPaid, 2) }}</strong></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @endif

        <!-- Radiology Tests Section -->
        @if($inpatientRegister->radiologyTests->count() > 0)
        <div class="table-container">
            <div class="section-title">Radiology Tests</div>
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 50%;">Test Name</th>
                        <th style="width: 15%;">Price (₹)</th>
                        <th style="width: 30%;">Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inpatientRegister->radiologyTests as $index => $test)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $test->radiologyTest->name ?? 'N/A' }}</td>
                        <td class="text-right">{{ number_format($test->price, 2) }}</td>
                        <td>{{ $test->notes ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background: #e3f2fd;">
                        <td colspan="2" class="text-right"><strong>Radiology Total:</strong></td>
                        <td class="text-right"><strong>₹{{ number_format($radiologyTotal, 2) }}</strong></td>
                        <td></td>
                    </tr>
                    <tr style="background: #d4edda;">
                        <td colspan="2" class="text-right"><strong>Paid Amount:</strong></td>
                        <td class="text-right"><strong>₹{{ number_format($radiologyPaid, 2) }}</strong></td>
                        <td></td>
                    </tr>
                    <tr style="background: #fff3cd;">
                        <td colspan="2" class="text-right"><strong>Balance:</strong></td>
                        <td class="text-right"><strong>₹{{ number_format($radiologyTotal - $radiologyPaid, 2) }}</strong></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @endif

        <!-- Additional Information -->
        @if($inpatientRegister->additional_info)
        <div class="table-container">
            <div class="section-title">Additional Information</div>
            <div style="padding: 8px; background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 3px;">
                {{ $inpatientRegister->additional_info }}
            </div>
        </div>
        @endif

        <!-- Financial Summary -->
        <div class="total-section">
            <div class="section-title">Financial Summary</div>
            <div class="total-grid">
                @if($medicineTotal > 0)
                <div class="total-item">
                    <span class="total-label">Medicines Total</span>
                    <span class="total-value">₹{{ number_format($medicineTotal, 2) }}</span>
                </div>
                <div class="total-item">
                    <span class="total-label">Medicines Paid</span>
                    <span class="total-value">₹{{ number_format($medicinePaid, 2) }}</span>
                </div>
                @endif

                @if($labTotal > 0)
                <div class="total-item">
                    <span class="total-label">Lab Tests Total</span>
                    <span class="total-value">₹{{ number_format($labTotal, 2) }}</span>
                </div>
                <div class="total-item">
                    <span class="total-label">Lab Tests Paid</span>
                    <span class="total-value">₹{{ number_format($labPaid, 2) }}</span>
                </div>
                @endif

                @if($radiologyTotal > 0)
                <div class="total-item">
                    <span class="total-label">Radiology Total</span>
                    <span class="total-value">₹{{ number_format($radiologyTotal, 2) }}</span>
                </div>
                <div class="total-item">
                    <span class="total-label">Radiology Paid</span>
                    <span class="total-value">₹{{ number_format($radiologyPaid, 2) }}</span>
                </div>
                @endif

                <div class="total-item full-width">
                    <span class="total-label">Grand Total Amount</span>
                    <span class="total-value">₹{{ number_format($totalAmount, 2) }}</span>
                </div>

                <div class="total-item">
                    <span class="total-label">Total Paid Amount</span>
                    <span class="total-value">₹{{ number_format($totalPaid, 2) }}</span>
                </div>

                <div class="total-item">
                    <span class="total-label">Balance Amount</span>
                    <span class="total-value">₹{{ number_format($totalAmount - $totalPaid, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Medical Officer Signature -->
        @if($inpatientRegister->medical_officer_initials)
        <div class="signature-box">
            <div style="margin-bottom: 5px;">Medical Officer Initials:</div>
            <div style="font-size: 14px; font-weight: bold;">{{ $inpatientRegister->medical_officer_initials }}</div>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>Generated on: {{ now()->format('d/m/Y h:i A') }}</p>
        </div>

        <!-- Print Actions (Hidden when printing) -->
        <div class="print-actions">
            <button onclick="window.print()" class="print-btn">
                <i class="fas fa-print"></i> Print Report
            </button>
            <button onclick="window.close()" class="close-btn">
                <i class="fas fa-times"></i> Close Window
            </button>
        </div>
    </div>

    <script>
        // Auto print after page loads
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };

        // Listen for print shortcut (Ctrl+P)
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
        });
    </script>
</body>
</html>
