<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IP Pharmacy Bill - {{ $inpatientRegister->patient->name ?? 'Patient' }}</title>
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
            font-size: 9px;
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
            INPATIENT PHARMACY BILL
        </div>

        <!-- Patient Information -->
        <div class="patient-info">
            <!-- Bill Number and Payment Status Row -->
            <div class="bill-no-row">
                <div class="bill-number">Bill No: IP-{{ str_pad($inpatientRegister->id, 6, '0', STR_PAD_LEFT) }}</div>
                <div class="payment-status">
                    Payment Status:
                    @php
                        $pharmacyStatus = $inpatientRegister->paid_status ?? 'pending';
                    @endphp
                    @if($pharmacyStatus == 'paid')
                        <span class="paid-badge">Paid</span>
                    @elseif($pharmacyStatus == 'partial')
                        <span class="partial-badge">Partial</span>
                    @else
                        <span class="unpaid-badge">Unpaid</span>
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
                    <span class="patient-label">IP Number</span>
                    <span class="patient-value">{{ $inpatientRegister->hospital_ip_no }}</span>
                </div>
                <div class="patient-item">
                    <span class="patient-label">Admission Date</span>
                    <span class="patient-value">{{ $inpatientRegister->date_of_admission->format('d/m/Y') }}</span>
                </div>
                <div class="patient-item">
                    <span class="patient-label">Bill Date</span>
                    <span class="patient-value">{{ $inpatientRegister->paid_at?->format('d/m/Y') ?? now()->format('d/m/Y') }}</span>
                </div>
                @if($inpatientRegister->patient->phone)
                <div class="patient-item">
                    <span class="patient-label">Phone</span>
                    <span class="patient-value">{{ $inpatientRegister->patient->phone }}</span>
                </div>
                @endif
                @if($inpatientRegister->patient->address)
                <div class="patient-item" style="grid-column: span 2;">
                    <span class="patient-label">Address</span>
                    <span class="patient-value">{{ $inpatientRegister->patient->address }}</span>
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
                        <th style="width: 30%;">Medicine Name</th>
                        <th style="width: 15%;">Timing</th>
                        <th style="width: 8%;">Days</th>
                        <th style="width: 8%;">Qty</th>
                        <th style="width: 12%;">Unit Price (₹)</th>
                        <th style="width: 12%;">Total (₹)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inpatientRegister->medicines as $index => $medicine)
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
                    @if($inpatientRegister->medicines->isEmpty())
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
                <div class="total-item full-width">
                    <span class="total-label">Medicines Total</span>
                    <span class="total-value">₹{{ number_format($medicineTotal, 2) }}</span>
                </div>
                @if($labTotal > 0)
                <div class="total-item full-width">
                    <span class="total-label">Lab Tests Total</span>
                    <span class="total-value">₹{{ number_format($labTotal, 2) }}</span>
                </div>
                @endif
                @if($radiologyTotal > 0)
                <div class="total-item full-width">
                    <span class="total-label">Radiology Tests Total</span>
                    <span class="total-value">₹{{ number_format($radiologyTotal, 2) }}</span>
                </div>
                @endif
                @if($overallDiscount > 0)
                <div class="total-item full-width">
                    <span class="total-label">Overall Discount</span>
                    <span class="total-value">- ₹{{ number_format($overallDiscount, 2) }}</span>
                </div>
                @endif
                <div class="total-item full-width" style="border-top: 3px solid #2c3e50; font-size: 14px;">
                    <span class="total-label">GRAND TOTAL</span>
                    <span class="total-value">₹{{ number_format($grandTotal, 2) }}</span>
                </div>
                <div class="total-item">
                    <span class="total-label">Paid Amount</span>
                    <span class="total-value">₹{{ number_format($inpatientRegister->paid_amount ?? 0, 2) }}</span>
                </div>
                <div class="total-item">
                    <span class="total-label">Balance Amount</span>
                    <span class="total-value">
                        ₹{{ number_format($grandTotal - ($inpatientRegister->paid_amount ?? 0), 2) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Payment Details -->
        @if($inpatientRegister->payment_type)
        <div class="total-section">
            <h4 style="margin-bottom: 8px; color: #2c3e50; font-size: 13px;">Payment Details</h4>
            <div class="total-grid">
                <div class="total-item">
                    <span class="total-label">Payment Type</span>
                    <span class="total-value">{{ ucfirst($inpatientRegister->payment_type) }}</span>
                </div>
                @if($inpatientRegister->payment_reference)
                <div class="total-item">
                    <span class="total-label">Payment Reference</span>
                    <span class="total-value">{{ $inpatientRegister->payment_reference }}</span>
                </div>
                @endif
                <div class="total-item">
                    <span class="total-label">Payment Date</span>
                    <span class="total-value">{{ $inpatientRegister->paid_at?->format('d/m/Y H:i') ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
        @endif

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
    </script>
</body>
</html>
