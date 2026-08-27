<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Complete Report - {{ $patient->name }}</title>
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

        .summary-cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 15px;
        }

        .summary-card {
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 10px;
            text-align: center;
            background: #f8f9fa;
        }

        .summary-title {
            color: #2c3e50;
            font-size: 11px;
            font-weight: 600;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .summary-count {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 3px;
        }

        .summary-amount {
            font-size: 14px;
            font-weight: 600;
            color: #27ae60;
        }

        .op-summary .summary-count { color: #3498db; }
        .ip-summary .summary-count { color: #9b59b6; }
        .op-summary { border-top: 3px solid #3498db; }
        .ip-summary { border-top: 3px solid #9b59b6; }
        .operation-summary { border-top: 3px solid #e74c3c; }
        .operation-summary .summary-count { color: #e74c3c; }

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

        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: 600;
        }

        .badge-info { background: #3498db; color: white; }
        .badge-warning { background: #f39c12; color: white; }
        .badge-success { background: #27ae60; color: white; }
        .badge-secondary { background: #95a5a6; color: white; }

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

            table, .total-section, .patient-info, .summary-cards {
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
                grid-template-columns: repeat(4, 1fr);
            }

            .summary-cards {
                grid-template-columns: 1fr;
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
            PATIENT COMPLETE MEDICAL REPORT
        </div>

        <!-- Patient Information -->
        <div class="patient-info">
            <div class="patient-grid">
                <div class="patient-item">
                    <span class="patient-label">Patient ID</span>
                    <span class="patient-value">{{ $patient->patient_id ?? 'N/A' }}</span>
                </div>
                <div class="patient-item">
                    <span class="patient-label">Patient Name</span>
                    <span class="patient-value">{{ $patient->name ?? 'N/A' }}</span>
                </div>
                <div class="patient-item">
                    <span class="patient-label">Age / Gender</span>
                    <span class="patient-value">
                        {{ $patient->age ?? 'N/A' }} years /
                        {{ ucfirst($patient->gender ?? 'N/A') }}
                    </span>
                </div>
                <div class="patient-item">
                    <span class="patient-label">Phone</span>
                    <span class="patient-value">{{ $patient->phone ?? 'N/A' }}</span>
                </div>
                <div class="patient-item">
                    <span class="patient-label">Registration Date</span>
                    <span class="patient-value">{{ $patient->created_at->format('d/m/Y') }}</span>
                </div>
                @if($patient->address)
                <div class="patient-item" style="grid-column: span 2;">
                    <span class="patient-label">Address</span>
                    <span class="patient-value">{{ $patient->address }}</span>
                </div>
                @endif
            </div>
        </div>


        <!-- OP Records Section -->
        @if($patient->opRegisters->count() > 0)
        <div class="table-container">
            <div class="section-title">Outpatient Records ({{ $totalOpVisits }})</div>
            <table>
                <thead>
                    <tr>
                        <th style="width: 8%;">#</th>
                        <th style="width: 12%;">Token No</th>
                        <th style="width: 12%;">Date</th>
                        <th style="width: 15%;">Doctor</th>
                        <th style="width: 8%;">Radiology</th>
                        <th style="width: 8%;">Lab</th>
                        <th style="width: 8%;">Medicines</th>
                        <th style="width: 12%;">Amount (₹)</th>
                        <th style="width: 8%;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($patient->opRegisters as $index => $register)
                    @php
                        $radiologyCount = $register->radiologyTests->count();
                        $labCount = $register->labTests->count();
                        $medicineCount = $register->medicines->count();
                        $registerTotal = $register->radiologyTests->sum('price') +
                                        $register->labTests->sum('price') +
                                        $register->medicines->sum('price');
                    @endphp
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>#{{ $register->token_number }}</td>
                        <td>{{ $register->created_at->format('d/m/Y') }}</td>
                        <td>{{ $register->medicalOfficer->name ?? 'N/A' }}</td>
                        <td class="text-center">
                            @if($radiologyCount > 0)
                                <span class="badge badge-info">{{ $radiologyCount }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-center">
                            @if($labCount > 0)
                                <span class="badge badge-warning">{{ $labCount }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-center">
                            @if($medicineCount > 0)
                                <span class="badge badge-success">{{ $medicineCount }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-right">{{ number_format($registerTotal, 2) }}</td>
                        <td class="text-center">
                            <span class="badge badge-{{ $register->status == 'completed' ? 'success' : 'warning' }}">
                                {{ ucfirst($register->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background: #e3f2fd;">
                        <td colspan="7" class="text-right"><strong>OP Total:</strong></td>
                        <td class="text-right"><strong>₹{{ number_format($totalOpAmount, 2) }}</strong></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @endif

        <!-- IP Records Section -->
        @if($patient->inpatientRegisters->count() > 0)
        <div class="table-container">
            <div class="section-title">Inpatient Records ({{ $totalIpAdmissions }})</div>
            <table>
                <thead>
                    <tr>
                        <th style="width: 8%;">#</th>
                        <th style="width: 12%;">IP No</th>
                        <th style="width: 12%;">Admission</th>
                        <th style="width: 12%;">Discharge</th>
                        <th style="width: 15%;">Diagnosis</th>
                        <th style="width: 8%;">Radiology</th>
                        <th style="width: 8%;">Lab</th>
                        <th style="width: 8%;">Medicines</th>
                        <th style="width: 12%;">Amount (₹)</th>
                        <th style="width: 8%;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($patient->inpatientRegisters as $index => $register)
                    @php
                        $radiologyCount = $register->radiologyTests->count();
                        $labCount = $register->labTests->count();
                        $medicineCount = $register->medicines->count();
                        $registerTotal = $register->radiologyTests->sum('price') +
                                        $register->labTests->sum('price') +
                                        $register->medicines->sum('price');
                        $isDischarged = !empty($register->date_of_discharge);
                    @endphp
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $register->hospital_ip_no }}</td>
                        <td>{{ $register->date_of_admission->format('d/m/Y') }}</td>
                        <td>
                            @if($isDischarged)
                                {{ $register->date_of_discharge->format('d/m/Y') }}
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ Str::limit($register->provisional_diagnosis, 25) }}</td>
                        <td class="text-center">
                            @if($radiologyCount > 0)
                                <span class="badge badge-info">{{ $radiologyCount }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-center">
                            @if($labCount > 0)
                                <span class="badge badge-warning">{{ $labCount }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-center">
                            @if($medicineCount > 0)
                                <span class="badge badge-success">{{ $medicineCount }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-right">{{ number_format($registerTotal, 2) }}</td>
                        <td class="text-center">
                            @if($isDischarged)
                                <span class="badge badge-secondary">Discharged</span>
                            @else
                                <span class="badge badge-success">Active</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background: #e3f2fd;">
                        <td colspan="8" class="text-right"><strong>IP Total:</strong></td>
                        <td class="text-right"><strong>₹{{ number_format($totalIpAmount, 2) }}</strong></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @endif

        <!-- Operation Records Section -->
        @if($patient->operationRegisters->count() > 0)
        <div class="table-container">
            <div class="section-title">Operation Records ({{ $totalOperations }})</div>
            <table>
                <thead>
                    <tr>
                        <th style="width: 8%;">#</th>
                        <th style="width: 12%;">OP ID</th>
                        <th style="width: 12%;">Date</th>
                        <th style="width: 20%;">Operation</th>
                        <th style="width: 15%;">Surgeon</th>
                        <th style="width: 12%;">Theatre</th>
                        <th style="width: 12%;">Ward</th>
                        <th style="width: 12%;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($patient->operationRegisters as $index => $operation)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $operation->hospital_ip_no }}</td>
                        <td>{{ $operation->date_of_admission->format('d/m/Y') }}</td>
                        <td>{{ $operation->operation_performed }}</td>
                        <td>{{ $operation->operatingSurgeon->name ?? 'N/A' }}</td>
                        <td>{{ $operation->operation_theatre_type }}</td>
                        <td>{{ $operation->transferred_to_ward }}</td>
                        <td class="text-center">
                            <span class="badge bg-{{
                                $operation->status == 'scheduled' ? 'warning' :
                                ($operation->status == 'in_progress' ? 'info' :
                                ($operation->status == 'completed' ? 'success' : 'danger'))
                            }}">
                                {{ ucfirst(str_replace('_', ' ', $operation->status)) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Financial Summary -->
        <div class="total-section">
            <div class="section-title">Financial Summary</div>
            <div class="total-grid">
                <div class="total-item">
                    <span class="total-label">OP Visits Total</span>
                    <span class="total-value">₹{{ number_format($totalOpAmount, 2) }}</span>
                </div>
                <div class="total-item">
                    <span class="total-label">IP Admissions Total</span>
                    <span class="total-value">₹{{ number_format($totalIpAmount, 2) }}</span>
                </div>
                @if($totalOperationAmount > 0)
                <div class="total-item">
                    <span class="total-label">Operations Total</span>
                    <span class="total-value">₹{{ number_format($totalOperationAmount, 2) }}</span>
                </div>
                <div class="total-item">
                    <span class="total-label">Other Charges</span>
                    <span class="total-value">₹{{ number_format(0, 2) }}</span>
                </div>
                @endif
                <div class="total-item full-width">
                    <span class="total-label">Grand Total Amount</span>
                    <span class="total-value">₹{{ number_format($totalAmount, 2) }}</span>
                </div>
            </div>
        </div>


        <!-- Print Actions (Hidden when printing) -->
        <div class="print-actions">
            <button onclick="window.print()" class="print-btn">
                <i class="fas fa-print"></i> Print Report
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
