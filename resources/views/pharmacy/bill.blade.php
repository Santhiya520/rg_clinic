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

        .payment-status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .paid-badge {
            background: #27ae60;
            color: white;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
        }

        .partial-badge {
            background: #f39c12;
            color: white;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
        }

        .unpaid-badge {
            background: #e74c3c;
            color: white;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
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

        .back-btn {
            background: #6c757d;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 3px;
            font-size: 11px;
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

        .instructions-box {
            margin-bottom: 15px;
            padding: 10px;
            background: #e8f4fd;
            border-radius: 4px;
            border-left: 3px solid #3498db;
        }

        .instructions-box h4 {
            color: #2c3e50;
            margin-bottom: 6px;
            font-size: 12px;
        }

        .instructions-box ul {
            padding-left: 18px;
            color: #5d6d7e;
        }

        .instructions-box li {
            margin-bottom: 3px;
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

            table, .total-section, .patient-info {
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

            .total-grid {
                grid-template-columns: 1fr;
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
            PHARMACY BILL
        </div>

        <!-- Patient Information -->
        <div class="patient-info">
            <!-- Bill Number and Payment Status Row -->
            <div class="bill-no-row">
                <div class="bill-number">Bill No: PH-{{ str_pad($opRegister->id, 6, '0', STR_PAD_LEFT) }}</div>
                <div class="payment-status">
                    Payment Status:
                    @if($opRegister->paid_status == 'paid')
                        <span class="paid-badge">Paid</span>
                    @elseif($opRegister->paid_status == 'partial')
                        <span class="partial-badge">Partial</span>
                    @else
                        <span class="unpaid-badge">Unpaid</span>
                    @endif
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
                    <span class="patient-label">Token Number</span>
                    <span class="patient-value">{{ $opRegister->token_number }}</span>
                </div>
                <div class="patient-item">
                    <span class="patient-label">Date</span>
                    <span class="patient-value">{{ $opRegister->created_at->format('d/m/Y') }}</span>
                </div>
                <div class="patient-item">
                    <span class="patient-label">Time</span>
                    <span class="patient-value">{{ $opRegister->created_at->format('h:i A') }}</span>
                </div>
                @if($opRegister->patient->phone)
                <div class="patient-item">
                    <span class="patient-label">Phone</span>
                    <span class="patient-value">{{ $opRegister->patient->phone }}</span>
                </div>
                @endif
                @if($opRegister->patient->address)
                <div class="patient-item" style="grid-column: span 2;">
                    <span class="patient-label">Address</span>
                    <span class="patient-value">{{ $opRegister->patient->address }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Medicines Table -->
        <div class="table-container">
            <h4 style="margin-bottom: 8px; color: #2c3e50; font-size: 13px;">Medicines Issued</h4>
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 25%;">Medicine Name</th>
                        <th style="width: 15%;">Timing</th>
                        <th style="width: 8%;">Days</th>
                        <th style="width: 8%;">Qty</th>
                        <th style="width: 12%;">Unit Price (₹)</th>
                        <th style="width: 12%;">Total (₹)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($opRegister->medicines as $index => $medicine)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $medicine->medicine->name ?? 'N/A' }}</td>
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
                    @if($opRegister->medicines->isEmpty())
                    <tr>
                        <td colspan="7" class="text-center">No medicines prescribed</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Totals -->
        <div class="total-section">
            <h4 style="margin-bottom: 8px; color: #2c3e50; font-size: 13px;">Payment Summary</h4>
            <div class="total-grid">
                <div class="total-item">
                    <span class="total-label">Medicines Total</span>
                    <span class="total-value">₹{{ number_format($medicineTotal, 2) }}</span>
                </div>
                <div class="total-item">
                    <span class="total-label">Doctor Fees</span>
                    <span class="total-value">₹{{ number_format($doctorFees, 2) }}</span>
                </div>
                <div class="total-item full-width">
                    <span class="total-label">Grand Total</span>
                    <span class="total-value">₹{{ number_format($grandTotal, 2) }}</span>
                </div>
                <div class="total-item">
                    <span class="total-label">Paid Amount</span>
                    <span class="total-value">₹{{ number_format($opRegister->total ?: 0, 2) }}</span>
                </div>
                <div class="total-item">
                    <span class="total-label">Balance Amount</span>
                    <span class="total-value">
                        ₹{{ number_format($grandTotal - ($opRegister->total ?: 0), 2) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Print Actions -->
        <div class="print-actions">
            <button onclick="window.print()" class="print-btn">
                <i class="fas fa-print"></i> Print Bill
            </button>
            <a href="{{ route('pharmacy.index') }}" class="back-btn">
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
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
