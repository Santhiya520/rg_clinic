<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OP Details Report - {{ $opRegister->token_number }}</title>
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
            padding-bottom: 10px;
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
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #dee2e6;
        }

        .patient-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }

        .patient-item {
            display: flex;
            flex-direction: column;
        }

        .patient-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 3px;
            font-size: 11px;
        }

        .patient-value {
            color: #34495e;
            padding: 4px 0;
        }

        .bill-no-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #dee2e6;
        }

        .bill-number {
            color: #2c3e50;
            font-weight: bold;
            font-size: 14px;
        }

        .medical-info {
            background: #e8f4fd;
            border-radius: 3px;
            padding: 10px;
            margin: 10px 0;
            border-left: 3px solid #3498db;
        }

        .table-container {
            margin-bottom: 15px;
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
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
            padding: 6px;
            border: 1px solid #dee2e6;
            vertical-align: top;
        }

        tr:nth-child(even) {
            background: #f8f9fa;
        }

        .total-section {
            background: #f8f9fa;
            border-radius: 4px;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #dee2e6;
        }

        .total-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
        }

        .total-item {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            border-bottom: 1px dashed #ced4da;
        }

        .total-item:last-child {
            border-bottom: none;
        }

        .total-item.full-width {
            grid-column: 1 / -1;
            border-top: 2px solid #2c3e50;
            padding-top: 8px;
            margin-top: 8px;
            font-size: 13px;
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
            padding-top: 12px;
            color: #5d6d7e;
            font-size: 10px;
            border-top: 1px solid #dee2e6;
            margin-top: 15px;
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
            font-size: 11px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background 0.3s;
        }

        .print-btn:hover {
            background: #1a252f;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
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

            table, .total-section, .patient-info, .medical-info {
                page-break-inside: avoid;
            }

            table {
                page-break-before: auto;
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

        <!-- Bill Title -->
        <div class="bill-title">
            OP REGISTER DETAILS REPORT
        </div>

        <!-- Patient Information -->
        <div class="patient-info">
            <!-- Bill Number and Payment Status Row -->
            <div class="bill-no-row">
                <div class="bill-number">OP Registration No: {{ $opRegister->token_number }}</div>
                <div class="payment-status">
                    Date: {{ $opRegister->created_at->format('d/m/Y') }}
                </div>
            </div>

            <!-- Patient Details in 3-column grid -->
            <div class="patient-grid">
                <div class="patient-item">
                    <span class="patient-label">Patient ID</span>
                    <span class="patient-value">{{ $opRegister->patient->patient_id ?? 'N/A' }}</span>
                </div>
                <div class="patient-item">
                    <span class="patient-label">Patient Name</span>
                    <span class="patient-value">{{ $opRegister->patient->name ?? 'N/A' }}</span>
                </div>
                <div class="patient-item">
                    <span class="patient-label">Age / Gender</span>
                    <span class="patient-value">
                        {{ $opRegister->patient->age ?? 'N/A' }} years /
                        {{ ucfirst($opRegister->patient->gender ?? 'N/A') }}
                    </span>
                </div>
                <div class="patient-item">
                    <span class="patient-label">Phone</span>
                    <span class="patient-value">{{ $opRegister->patient->phone ?? 'N/A' }}</span>
                </div>
                <div class="patient-item">
                    <span class="patient-label">Consulting Doctor</span>
                    <span class="patient-value">{{ $opRegister->medicalOfficer->name ?? 'N/A' }}</span>
                </div>
                @if($opRegister->patient->address)
                <div class="patient-item" style="grid-column: span 2;">
                    <span class="patient-label">Address</span>
                    <span class="patient-value">{{ $opRegister->patient->address }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Medical Information Section - ADDED THIS SECTION -->
        <div class="medical-info">
            <h4 style="color: #2c3e50; font-size: 13px; margin-bottom: 8px;">Medical Information</h4>
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
                <div>
                    <div style="margin-bottom: 4px;"><strong>Provisional Diagnosis:</strong></div>
                    <div>{{ $opRegister->provisional_diagnosis ?? 'N/A' }}</div>
                </div>
                <div>
                    <div style="margin-bottom: 4px;"><strong>Final Diagnosis:</strong></div>
                    <div>{{ $opRegister->final_diagnosis ?? 'N/A' }}</div>
                </div>
                <div>
                    <div style="margin-bottom: 4px;"><strong>Investigations:</strong></div>
                    <div>{{ $opRegister->investigations ?? 'N/A' }}</div>
                </div>
                <div>
                    <div style="margin-bottom: 4px;"><strong>Treatment:</strong></div>
                    <div>{{ $opRegister->treatment ?? 'N/A' }}</div>
                </div>
                @if($opRegister->result)
                <div style="grid-column: 1 / -1;">
                    <div style="margin-bottom: 4px;"><strong>Result:</strong></div>
                    <div>{{ $opRegister->result }}</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Medicines Section -->
        @if($opRegister->medicines->count() > 0)
        <div class="table-container">
            <h4 style="margin-bottom: 8px; color: #2c3e50; font-size: 13px;">Medicines Prescribed</h4>
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 25%;">Medicine Name</th>
                        <th style="width: 15%;">Timing</th>
                        <th style="width: 8%;">Days</th>
                        <th style="width: 8%;">Qty</th>
                        <th style="width: 12%;">Price (₹)</th>
                        <th style="width: 12%;">Total (₹)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($opRegister->medicines as $index => $medicine)
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
                                if($medicine->morning) $timing[] = 'Morning';
                                if($medicine->afternoon) $timing[] = 'Afternoon';
                                if($medicine->night) $timing[] = 'Night';
                            @endphp
                            {{ implode(', ', $timing) }}
                        </td>
                        <td class="text-center">{{ $medicine->no_of_days }}</td>
                        <td class="text-center">{{ $medicine->quantity }}</td>
                        <td class="text-right">{{ number_format($medicine->price, 2) }}</td>
                        <td class="text-right">{{ number_format($medicine->quantity * $medicine->price, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background: #e3f2fd;">
                        <td colspan="6" class="text-right"><strong>Medicines Total:</strong></td>
                        <td class="text-right"><strong>₹{{ number_format($medicineTotal, 2) }}</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @endif

        <!-- Radiology Tests Section -->
        @if($opRegister->radiologyTests->count() > 0)
        <div class="table-container">
            <h4 style="margin-bottom: 8px; color: #2c3e50; font-size: 13px;">Radiology Tests</h4>
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 35%;">Test Name</th>
                        <th style="width: 15%;">Status</th>
                        <th style="width: 15%;">Price (₹)</th>
                        <th style="width: 15%;">Paid (₹)</th>
                        <th style="width: 15%;">Balance (₹)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($opRegister->radiologyTests as $index => $test)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $test->radiologyTest->name ?? 'N/A' }}</td>
                        <td>
                            <span style="padding: 3px 8px" class="badge bg-{{ $test->status == 'completed' ? 'success' : ($test->status == 'cancelled' ? 'danger' : 'warning') }}">
                                {{ ucfirst($test->status) }}
                            </span>
                        </td>
                        <td class="text-right">{{ number_format($test->price, 2) }}</td>
                        <td class="text-right">{{ number_format($test->paid_amount, 2) }}</td>
                        <td class="text-right">{{ number_format($test->price - $test->paid_amount, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background: #e3f2fd;">
                        <td colspan="3" class="text-right"><strong>Radiology Total:</strong></td>
                        <td class="text-right"><strong>₹{{ number_format($radiologyTotal, 2) }}</strong></td>
                        <td class="text-right"><strong>₹{{ number_format($opRegister->radiologyTests->sum('paid_amount'), 2) }}</strong></td>
                        <td class="text-right"><strong>₹{{ number_format($radiologyTotal - $opRegister->radiologyTests->sum('paid_amount'), 2) }}</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @endif

        <!-- Lab Tests Section -->
        @if($opRegister->labTests->count() > 0)
        <div class="table-container">
            <h4 style="margin-bottom: 8px; color: #2c3e50; font-size: 13px;">Laboratory Tests</h4>
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 35%;">Test Name</th>
                        <th style="width: 15%;">Status</th>
                        <th style="width: 15%;">Price (₹)</th>
                        <th style="width: 15%;">Paid (₹)</th>
                        <th style="width: 15%;">Balance (₹)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($opRegister->labTests as $index => $test)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $test->labTest->name ?? 'N/A' }}</td>
                        <td>
                            <span style="padding: 3px 8px" class="badge bg-{{ $test->status == 'completed' ? 'success' : ($test->status == 'cancelled' ? 'danger' : 'warning') }}">
                                {{ ucfirst($test->status) }}
                            </span>
                        </td>
                        <td class="text-right">{{ number_format($test->price, 2) }}</td>
                        <td class="text-right">{{ number_format($test->paid_amount, 2) }}</td>
                        <td class="text-right">{{ number_format($test->price - $test->paid_amount, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background: #e3f2fd;">
                        <td colspan="3" class="text-right"><strong>Lab Tests Total:</strong></td>
                        <td class="text-right"><strong>₹{{ number_format($labTotal, 2) }}</strong></td>
                        <td class="text-right"><strong>₹{{ number_format($opRegister->labTests->sum('paid_amount'), 2) }}</strong></td>
                        <td class="text-right"><strong>₹{{ number_format($labTotal - $opRegister->labTests->sum('paid_amount'), 2) }}</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @endif

        <!-- Summary -->
        <div class="total-section">
            <h4 style="margin-bottom: 8px; color: #2c3e50; font-size: 13px;">Payment Summary</h4>
            <div class="total-grid">
                <div class="total-item">
                    <span class="total-label">Medicines Total</span>
                    <span class="total-value">₹{{ number_format($medicineTotal, 2) }}</span>
                </div>
                <div class="total-item">
                    <span class="total-label">Radiology Total</span>
                    <span class="total-value">₹{{ number_format($radiologyTotal, 2) }}</span>
                </div>
                <div class="total-item">
                    <span class="total-label">Lab Tests Total</span>
                    <span class="total-value">₹{{ number_format($labTotal, 2) }}</span>
                </div>
                <div class="total-item">
                    <span class="total-label">Doctor Fees</span>
                    <span class="total-value">₹{{ number_format($opRegister->doctor_fee ?? 0, 2) }}</span>
                </div>
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

        <!-- Footer -->
        <div class="footer">
            <p>Generated on: {{ now()->format('d/m/Y h:i A') }}</p>
        </div>

        <!-- Print Actions (Hidden when printing) -->
        <div class="print-actions">
            <button onclick="window.print()" class="print-btn">
                <i class="fas fa-print"></i> Print Report
            </button>
            <button onclick="window.close()" class="print-btn" style="background: #6c757d; margin-left: 10px;">
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
