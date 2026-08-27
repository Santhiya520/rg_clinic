<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Test Bill - {{ $manualLabTest->reference_no }}</title>
    <style>
        /* Thermal Print Optimized Styles */
        @page {
            size: 80mm 297mm;
            /* Standard thermal receipt size */
            margin: 0mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 11px;
            line-height: 1.3;
            background: white;
            width: 80mm;
            margin: 0 auto;
        }

        .print-container {
            width: 100%;
            padding: 3mm 4mm;
            background: white;
        }

        /* Header Styles */
        .header {
            text-align: center;
            padding-bottom: 5px;
            border-bottom: 1px dashed #000;
            margin-bottom: 8px;
        }

        .header img {
            max-width: 60mm;
            height: auto;
            margin-bottom: 5px;
        }

        .header h3 {
            font-size: 14px;
            font-weight: bold;
            margin: 5px 0;
            letter-spacing: 1px;
        }

        /* Section Titles */
        .section-title {
            font-size: 12px;
            font-weight: bold;
            margin: 8px 0 5px 0;
            padding-bottom: 2px;
            border-bottom: 1px dotted #000;
        }

        /* Info Grid */
        .info-grid {
            width: 100%;
            margin-bottom: 10px;
            border-collapse: collapse;
        }

        .info-grid td {
            padding: 2px 0;
            vertical-align: top;
        }

        .info-grid td:first-child {
            width: 35%;
            font-weight: bold;
        }

        .info-grid td:last-child {
            width: 65%;
        }

        /* Bill Table */
        .bill-table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0;
        }

        .bill-table th {
            background: #000;
            color: white;
            padding: 5px 3px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
            border: 1px solid #000;
        }

        .bill-table td {
            padding: 4px 3px;
            border: 1px solid #ccc;
            vertical-align: top;
        }

        .bill-table tr:nth-child(even) {
            background: #f5f5f5;
        }

        .total-row {
            font-weight: bold;
            background: #e0e0e0 !important;
        }

        .total-row td {
            border-top: 2px solid #000;
        }

        /* Amount Alignment */
        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* Notes Section */
        .notes-box {
            margin: 10px 0;
            padding: 5px;
            border: 1px dotted #999;
            background: #f9f9f9;
        }

        .notes-box h4 {
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        /* Footer */
        .footer {
            text-align: center;
            margin-top: 12px;
            padding-top: 8px;
            border-top: 1px dashed #000;
            font-size: 8px;
        }

        .footer p {
            margin: 2px 0;
        }

        /* Barcode / Reference */
        .reference-bar {
            text-align: center;
            margin: 8px 0;
            padding: 5px;
            font-family: 'Courier New', monospace;
            letter-spacing: 1px;
        }

        /* Divider */
        .divider {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }

        /* Print Button (Hidden when printing) */
        .no-print {
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            background: #f0f0f0;
        }

        .print-btn {
            padding: 8px 20px;
            background: #2c3e50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            margin: 0 5px;
        }

        .print-btn:hover {
            background: #1a252f;
        }

        /* Thermal Printer Specific */
        @media print {
            @page {
                size: 80mm auto;
                margin: 0mm;
            }

            body {
                margin: 0;
                padding: 0;
                width: 80mm;
            }

            .print-container {
                padding: 2mm;
            }

            .no-print {
                display: none;
            }

            .bill-table th {
                background: #000 !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .total-row {
                background: #e0e0e0 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }

        /* Small adjustments for better thermal print */
        @media (max-width: 80mm) {
            body {
                width: 100%;
            }

            .bill-table th,
            .bill-table td {
                font-size: 9px;
            }
        }
    </style>
</head>

<body>
    <div class="print-container">
        <!-- Header -->
        <div class="header">

            <h2>RG Hospital</h2>
            <div class="divider"></div>
            <h3>LAB TEST BILL</h3>
        </div>

        <!-- Bill Information -->
        <table class="info-grid">
            <tr>
                <td>Bill No:</td>
                <td><strong>{{ $manualLabTest->reference_no }}</strong></td>
            </tr>
            <tr>
                <td>Date:</td>
                <td>{{ $manualLabTest->created_at->format('d/m/Y h:i A') }}</td>
            </tr>
            <tr>
                <td>Status:</td>
                <td>
                    @if ($manualLabTest->status == 'pending')
                        <strong style="color: #e67e22;">PENDING</strong>
                    @elseif($manualLabTest->status == 'completed')
                        <strong style="color: #27ae60;">COMPLETED</strong>
                    @elseif($manualLabTest->status == 'cancelled')
                        <strong style="color: #e74c3c;">CANCELLED</strong>
                    @endif
                </td>
            </tr>
            <tr>
                <td>Payment:</td>
                <td>{{ ucfirst($manualLabTest->payment_type ?? 'N/A') }}</td>
            </tr>
        </table>

        <div class="divider"></div>

        <!-- Patient Information -->
        <div class="section-title">PATIENT DETAILS</div>
        <table class="info-grid">
            <tr>
                <td>Name:</td>
                <td><strong>{{ $manualLabTest->patient->name ?? 'N/A' }}</strong></td>
            </tr>
            <tr>
                <td>Patient ID:</td>
                <td>{{ $manualLabTest->patient->patient_id ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td>Age/Gender:</td>
                <td>{{ $manualLabTest->patient->age ?? 'N/A' }} /
                    {{ ucfirst($manualLabTest->patient->gender ?? 'N/A') }}</td>
            </tr>
            <tr>
                <td>Phone:</td>
                <td>{{ $manualLabTest->patient->mobile ?? 'N/A' }}</td>
            </tr>
        </table>

        <div class="divider"></div>

        <!-- Lab Tests Table -->
        <div class="section-title">TEST DETAILS</div>
        <table class="bill-table">
            <thead>
                <tr>
                    <th style="width: 8%;">#</th>
                    <th style="width: 62%;">Test Name</th>
                    <th style="width: 30%;" class="text-right">Amount (₹)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($manualLabTest->items as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $item->labTest->name ?? 'Test Deleted' }}</td>
                        <td class="text-right">{{ number_format($item->price, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">No tests found</td>
                    </tr>
                @endforelse

                <!-- Total Row -->
                <tr class="total-row">
                    <td colspan="2" class="text-right"><strong>TOTAL:</strong></td>
                    <td class="text-right"><strong>₹{{ number_format($manualLabTest->total_amount, 2) }}</strong></td>
                </tr>
            </tbody>
        </table>
        <div class="divider"></div>

        <!-- Notes -->
        @if ($manualLabTest->notes)
            <div class="notes-box">
                <h4>📝 NOTES:</h4>
                <p>{{ $manualLabTest->notes }}</p>
            </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p><strong>Thank you for choosing us :)</strong></p>
        </div>
    </div>

    <!-- Print Button -->
    <div class="no-print">
        <button class="print-btn" onclick="window.print()">
            🖨️ Thermal Print
        </button>
        <button class="print-btn" onclick="window.close()" style="background: #95a5a6;">
            ✕ Close
        </button>
    </div>

    <script>
        // Auto-print if parameter exists
        @if (request()->has('autoprint'))
            window.onload = function() {
                setTimeout(function() {
                    window.print();
                }, 500);
            }
        @endif

        // Handle print dialog close
        window.onafterprint = function() {
            // Optional: Auto-close after 3 seconds
            // setTimeout(function() {
            //     window.close();
            // }, 3000);
        };
        // Function to convert number to words (add this helper
    </script>
</body>

</html>
