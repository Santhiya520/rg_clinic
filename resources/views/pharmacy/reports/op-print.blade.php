<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OP Pharmacy Report - {{ \Carbon\Carbon::parse($fromDate)->format('d/m/Y') }} to {{ \Carbon\Carbon::parse($toDate)->format('d/m/Y') }}</title>
    <style>
        /* Page Setup */
        @page {
            size: A4 portrait;
            margin: 0.7cm;
        }

        @page :first {
            margin-top: 0.5cm;
        }

        /* Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10.5pt;
            line-height: 1.4;
            color: #000;
            background: #fff;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .print-container {
            width: 100%;
            margin: 0 auto;
        }

        /* Header Section - More Professional */
        .report-header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 12px;
            border-bottom: 2.5px solid #1a237e;
            position: relative;
        }

        .hospital-banner {
            text-align: center;
            border-bottom: 1px solid #e0e0e0;
        }

        .hospital-banner img {
            height: auto;
            filter: grayscale(30%);
        }

        .report-title {
            font-size: 18pt;
            font-weight: 700;
            margin: 8px 0 5px 0;
            color: #1a237e;
            letter-spacing: 0.5px;
        }

        .report-period {
            font-size: 11pt;
            margin: 5px 0;
            color: #333;
            font-weight: 500;
        }

        .report-generated {
            font-size: 9.5pt;
            color: #666;
            margin-top: 3px;
        }

        /* Summary Section - Compact & Clean */
        .summary-section {
            margin: 15px 0 20px 0;
            padding: 12px 10px;
            border: 1px solid #b0bec5;
            background: linear-gradient(to bottom, #f8f9fa 0%, #f1f3f5 100%);
            border-radius: 3px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
        }

        .summary-item {
            text-align: center;
            padding: 6px 3px;
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 2px;
            transition: all 0.2s ease;
        }

        .summary-item:hover {
            border-color: #1a237e;
            background: #f8f9ff;
        }

        .summary-label {
            font-size: 8.5pt;
            font-weight: 600;
            margin-bottom: 4px;
            color: #455a64;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .summary-value {
            font-size: 13pt;
            font-weight: 700;
            color: #1a237e;
        }

        .summary-value.currency {
            font-family: 'Courier New', monospace;
            font-weight: 600;
        }

        /* Table Styles - Professional Look */
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin: 20px 0 25px 0;
            font-size: 9.5pt;
            border: 1px solid #000;
            page-break-inside: auto;
        }

        thead {
            display: table-header-group;
        }

        tfoot {
            display: table-footer-group;
        }

        th {
            background: linear-gradient(to bottom, #2c3e50 0%, #1a252f 100%);
            color: #fff;
            font-weight: 700;
            padding: 8px 6px;
            text-align: center;
            border-right: 1px solid #444;
            border-bottom: 2px solid #000;
            position: relative;
            text-transform: uppercase;
            font-size: 9pt;
            letter-spacing: 0.3px;
        }

        th:last-child {
            border-right: none;
        }

        th::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 1px;
            background: rgba(255,255,255,0.1);
        }

        td {
            padding: 6px 5px;
            border-right: 1px solid #e0e0e0;
            border-bottom: 1px solid #e0e0e0;
            vertical-align: middle;
            transition: background 0.2s ease;
        }

        td:last-child {
            border-right: none;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:nth-child(even) {
            background: #f8f9fa;
        }

        tr:hover td {
            background: #e3f2fd;
        }

        /* Column Alignment */
        .text-left { text-align: left; }
        .text-center { text-align: center; }
        .text-right {
            text-align: right;
            font-family: 'Courier New', monospace;
            font-weight: 500;
        }

        /* Patient Name Styling */
        .patient-name {
            font-weight: 600;
            color: #1a237e;
            margin-bottom: 2px;
            font-size: 10pt;
        }

        .patient-id {
            font-size: 8.5pt;
            color: #666;
            font-style: italic;
        }

        /* Amount Styling */
        .amount-positive {
            color: #2e7d32;
            font-weight: 600;
        }

        .amount-negative {
            color: #c62828;
            font-weight: 600;
        }

        .amount-total {
            color: #1a237e;
            font-weight: 700;
        }

        /* Footer */
        .table-footer {
            background: linear-gradient(to bottom, #e9ecef 0%, #dee2e6 100%);
            border-top: 2px solid #000;
        }

        .table-footer th {
            background: linear-gradient(to bottom, #495057 0%, #343a40 100%);
            border-bottom: none;
            font-size: 10pt;
            padding: 9px 6px;
        }

        .table-footer th:first-child {
            border-right: 1px solid #666;
        }

        /* No Data Message */
        .no-data {
            padding: 30px 20px;
            text-align: center;
            background: #f8f9fa;
            border-radius: 4px;
            margin: 20px 0;
        }

        .no-data-icon {
            font-size: 32pt;
            color: #b0bec5;
            margin-bottom: 10px;
        }

        .no-data-text {
            color: #78909c;
            font-size: 11pt;
            font-weight: 500;
        }

        /* Control Buttons */
        .print-controls {
            position: fixed;
            top: 15px;
            right: 15px;
            z-index: 1000;
            background: rgba(255,255,255,0.95);
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.15);
            backdrop-filter: blur(5px);
        }

        .print-btn, .close-btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 11pt;
            font-weight: 500;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 8px;
            width: 100%;
        }

        .print-btn {
            background: linear-gradient(to bottom, #1976d2 0%, #1565c0 100%);
            color: white;
        }

        .print-btn:hover {
            background: linear-gradient(to bottom, #1565c0 0%, #0d47a1 100%);
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(21, 101, 192, 0.3);
        }

        .close-btn {
            background: linear-gradient(to bottom, #78909c 0%, #607d8b 100%);
            color: white;
        }

        .close-btn:hover {
            background: linear-gradient(to bottom, #607d8b 0%, #546e7a 100%);
            transform: translateY(-1px);
        }

        /* Page Break Handling */
        .page-break {
            page-break-before: always;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px dashed #ccc;
        }

        /* Print-specific Styles */
        @media print {
            body {
                padding: 0;
                margin: 0;
                font-size: 9.5pt;
            }

            .print-container {
                padding: 0;
                margin: 0;
            }

            .no-print {
                display: none !important;
            }

            .summary-section {
                border: 1px solid #ccc;
                background: #f8f9fa;
                box-shadow: none;
                margin: 10px 0 15px 0;
            }

            .summary-item {
                border: 1px solid #ddd;
                background: #fff;
            }

            .summary-item:hover {
                border-color: #ddd;
                background: #fff;
            }

            table {
                border: 1px solid #000;
                box-shadow: none;
            }

            tr:hover td {
                background: inherit !important;
            }

            /* Ensure proper page breaks */
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            thead {
                display: table-header-group;
            }

            tfoot {
                display: table-footer-group;
            }

            /* Remove transitions for print */
            * {
                transition: none !important;
            }
        }

        /* Screen-only Enhancements */
        @media screen {
            body {
                padding: 20px;
                background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
                min-height: 100vh;
            }

            .print-container {
                background: white;
                padding: 25px;
                border-radius: 8px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.1);
                border: 1px solid #e0e0e0;
            }

            table {
                box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                border-radius: 4px;
                overflow: hidden;
            }
        }

        /* Signature Section */
        .signature-section {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ccc;
            text-align: center;
            font-size: 9pt;
            color: #666;
        }

        .signature-line {
            width: 200px;
            height: 1px;
            background: #000;
            margin: 20px auto 5px;
        }

        .signature-label {
            font-size: 8.5pt;
            color: #555;
            margin-top: 3px;
        }

        /* Footer Note */
        .footer-note {
            text-align: center;
            font-size: 8pt;
            color: #777;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #eee;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="print-controls no-print">
        <button class="print-btn" onclick="window.print()">
            <span>🖨️</span> Print Report
        </button>
    </div>

    <div class="print-container">
        <!-- Header Section -->
        <div class="report-header">
            <div class="hospital-banner">
                <img src="{{ asset('images/rg-banner.png') }}" alt="Hospital Logo">
            </div>
            <h1 class="report-title">OUT PATIENT PHARMACY REPORT</h1>
            <div class="report-period">
                Period: {{ \Carbon\Carbon::parse($fromDate)->format('d/m/Y') }} to {{ \Carbon\Carbon::parse($toDate)->format('d/m/Y') }}
            </div>
            <div class="report-generated">
                Generated on: {{ now()->format('d/m/Y h:i A') }}
            </div>
        </div>

        <!-- Summary Section -->
        <div class="summary-section no-print">
            <div class="summary-grid">
                <div class="summary-item">
                    <div class="summary-label">Total Patients</div>
                    <div class="summary-value">{{ $opRegisters->count() }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Medicines</div>
                    <div class="summary-value currency">₹{{ number_format($totalMedicineAmount, 2) }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Lab Tests</div>
                    <div class="summary-value currency">₹{{ number_format($totalLabAmount, 2) }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Radiology</div>
                    <div class="summary-value currency">₹{{ number_format($totalRadiologyAmount, 2) }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Discount</div>
                    <div class="summary-value currency amount-negative">₹{{ number_format($totalDiscount, 2) }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Grand Total</div>
                    <div class="summary-value currency amount-total">₹{{ number_format($grandTotalAmount, 2) }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Paid Amount</div>
                    <div class="summary-value currency amount-positive">₹{{ number_format($totalPaid, 2) }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Balance Due</div>
                    <div class="summary-value currency amount-negative">₹{{ number_format($totalBalance, 2) }}</div>
                </div>
            </div>
        </div>

        <!-- Report Table -->
        <table>
            <thead>
                <tr>
                    <th width="4%">#</th>
                    <th width="8%">Date</th>
                    <th width="8%">Token No</th>
                    <th width="22%" class="text-left">Patient Details</th>
                    <th width="9%">Medicines</th>
                    <th width="9%">Lab</th>
                    <th width="9%">Radiology</th>
                    <th width="9%">Discount</th>
                    <th width="10%">Total</th>
                    <th width="9%">Paid</th>
                    <th width="9%">Balance</th>
                </tr>
            </thead>
            <tbody>
                @forelse($opRegisters as $index => $op)
                    @php
                        $medicineTotal = $op->medicines->sum(function($m) {
                            return ($m->quantity * $m->price) - ($m->discount_amount ?? 0);
                        });
                        $labTotal = $op->labTests->sum('paid_amount');
                        $radiologyTotal = $op->radiologies->sum('paid_amount');
                        $doctorFees = $op->medicalOfficer->consulting_fee ?? 0;
                        $discount = $op->overall_discount_amount ?? 0;
                        $grandTotal = $medicineTotal + $labTotal + $radiologyTotal + $doctorFees - $discount;
                        $paid = $op->paid_amount ?? 0;
                        $balance = $grandTotal - $paid;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center">{{ $op->date->format('d/m/Y') }}</td>
                        <td class="text-center">{{ $op->token_number ?? 'N/A' }}</td>
                        <td class="text-left">
                            <div class="patient-name">{{ $op->patient->name ?? 'N/A' }}</div>
                            <div class="patient-id">{{ $op->patient->patient_id ?? 'N/A' }}</div>
                        </td>
                        <td class="text-right">₹{{ number_format($medicineTotal, 2) }}</td>
                        <td class="text-right">₹{{ number_format($labTotal, 2) }}</td>
                        <td class="text-right">₹{{ number_format($radiologyTotal, 2) }}</td>
                        <td class="text-right amount-negative">₹{{ number_format($discount, 2) }}</td>
                        <td class="text-right amount-total"><strong>₹{{ number_format($grandTotal, 2) }}</strong></td>
                        <td class="text-right amount-positive">₹{{ number_format($paid, 2) }}</td>
                        <td class="text-right amount-negative">₹{{ number_format($balance, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center" style="padding: 30px;">
                            <div class="no-data">
                                <div class="no-data-icon">📊</div>
                                <div class="no-data-text">No OP records found for the selected period</div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
            @if($opRegisters->count() > 0)
                <tfoot class="table-footer">
                    <tr>
                        <th colspan="4" class="text-right">GRAND TOTAL:</th>
                        <th class="text-right">₹{{ number_format($totalMedicineAmount, 2) }}</th>
                        <th class="text-right">₹{{ number_format($totalLabAmount, 2) }}</th>
                        <th class="text-right">₹{{ number_format($totalRadiologyAmount, 2) }}</th>
                        <th class="text-right">₹{{ number_format($totalDiscount, 2) }}</th>
                        <th class="text-right">₹{{ number_format($grandTotalAmount, 2) }}</th>
                        <th class="text-right">₹{{ number_format($totalPaid, 2) }}</th>
                        <th class="text-right">₹{{ number_format($totalBalance, 2) }}</th>
                    </tr>
                </tfoot>
            @endif
        </table>

        <!-- Signature Section -->
        <div class="signature-section no-print">
            <div class="signature-line"></div>
            <div class="signature-label">Authorized Signature</div>
        </div>
    </div>

    <script>
        // Auto-print if specified in URL
        if (window.location.search.includes('autoprint=true')) {
            setTimeout(() => window.print(), 500);
        }

        // Close window after printing (optional)
        window.onafterprint = function() {
            setTimeout(function() {
                if (window.location.search.includes('autoprint=true')) {
                    window.close();
                }
            }, 500);
        };

        // Ctrl+P shortcut
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
            if (e.key === 'Escape') {
                window.close();
            }
        });

        // Add zebra striping for better readability
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach((row, index) => {
                if (index % 2 === 0) {
                    row.style.backgroundColor = '#f8f9fa';
                }
            });
        });
    </script>
</body>
</html>
