<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Radiology Test Bill - {{ $manualRadiologyTest->reference_no }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            font-size: 12px;
            background: #f5f5f5;
        }

        .print-container {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 20px;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }

        .bill-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin: 10px 0;
        }

        .patient-info {
            margin-bottom: 20px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .info-table td {
            padding: 6px;
            border: 1px solid #ddd;
        }

        .info-table th {
            background-color: #f5f5f5;
            padding: 6px;
            border: 1px solid #ddd;
            text-align: left;
            font-weight: bold;
        }

        .tests-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 11px;
        }

        .tests-table th {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-weight: bold;
        }

        .tests-table td {
            border: 1px solid #ddd;
            padding: 6px;
        }

        .amount-table {
            width: 300px;
            float: right;
            border-collapse: collapse;
            margin-top: 20px;
            border: 1px solid #ddd;
        }

        .amount-table td {
            padding: 6px 10px;
            border-bottom: 1px solid #ddd;
        }

        .amount-table tr.total td {
            font-weight: bold;
            background-color: #f5f5f5;
            font-size: 13px;
        }

        .footer {
            margin-top: 40px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 11px;
            color: #666;
            clear: both;
        }

        .signature-line {
            width: 200px;
            border-top: 1px solid #333;
            margin: 40px auto 5px;
        }

        .signature-text {
            text-align: center;
            font-size: 11px;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            color: rgba(0, 0, 0, 0.1);
            z-index: -1;
            font-weight: bold;
            text-transform: uppercase;
        }

        @media print {
            body {
                margin: 0;
                padding: 10px;
                background: white;
            }

            .print-container {
                border: none;
                padding: 10px;
                box-shadow: none;
            }

            .no-print {
                display: none !important;
            }

            .watermark {
                display: block;
            }

            .page-break {
                page-break-before: always;
            }
        }

        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            z-index: 1000;
            font-size: 14px;
        }

        .print-btn:hover {
            background: #0056b3;
        }

        .bill-details {
            text-align: center;
            margin: 15px 0;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }

        .status-badge {
            padding: 3px 10px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            display: inline-block;
        }

        .status-paid { background-color: #d4edda; color: #155724; }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-partial { background-color: #cce5ff; color: #004085; }
        .status-completed { background-color: #d4edda; color: #155724; }
        .status-cancelled { background-color: #f8d7da; color: #721c24; }

        .amount-in-words {
            margin-top: 15px;
            padding: 8px;
            background-color: #f8f9fa;
            border: 1px dashed #ddd;
            font-style: italic;
            font-size: 11px;
        }

        .terms-conditions {
            margin-top: 10px;
            padding: 10px;
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            font-size: 10px;
            line-height: 1.4;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
    </style>
</head>
<body>
    <button class="print-btn no-print" onclick="window.print()">🖨️ Print Bill</button>

    <div class="watermark">BILL COPY</div>

    <div class="print-container">
        <!-- Header Section -->
        <div class="header">
            <img src="{{ asset('images/rg-banner.png') }}" style="width: 100%; height: auto;">
            <h2 class="bill-title">RADIOLOGY TEST BILL</h2>
        </div>

        <!-- Bill Details -->
        <div class="bill-details">
            <p><strong>Bill No:</strong> {{ $manualRadiologyTest->reference_no }} &nbsp; | &nbsp;
               <strong>Date:</strong> {{ $manualRadiologyTest->created_at->format('d/m/Y h:i A') }}</p>
        </div>

        <!-- Patient Information -->
        <div class="patient-info">
            <table class="info-table">
                <tr>
                    <th width="25%">Patient Name</th>
                    <td>{{ $manualRadiologyTest->patient->name }}</td>
                    <th>Mobile Number</th>
                    <td>{{ $manualRadiologyTest->patient->mobile ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Patient ID</th>
                    <td width="25%">{{ $manualRadiologyTest->patient->patient_id }}</td>
                    <th width="25%">Age/Sex</th>
                    <td>{{ $manualRadiologyTest->patient->age }}y / {{ $manualRadiologyTest->patient->sex }}</td>
                </tr>
                <tr>
                    <th>Payment Status</th>
                    <td>
                        <span class="status-badge status-{{ $manualRadiologyTest->payment_status }}">
                            {{ strtoupper($manualRadiologyTest->payment_status) }}
                        </span>
                    </td>
                    <th>Payment Type</th>
                    <td>
                        <span class="status-badge status-{{ $manualRadiologyTest->payment_type }}">
                            {{ strtoupper($manualRadiologyTest->payment_type) }}
                        </span>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Tests Table -->
        <div style="clear: both; margin-top: 20px;">
            <h3 style="border-bottom: 1px solid #ddd; padding-bottom: 5px; margin-bottom: 10px;">RADIOLOGY TESTS</h3>
            <table class="tests-table">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="40%">Test Name</th>
                        <th width="15%" class="text-right">Price (₹)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($manualRadiologyTest->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->radiologyTest->name ?? 'N/A' }}</td>
                        <td class="text-right">{{ number_format($item->price, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="text-bold">Total</td>
                        <td class="text-right text-bold">₹{{ number_format($manualRadiologyTest->total_amount, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Amount Summary -->
        <div style="clear: both; margin-top: 20px;">
            <table class="amount-table">
                <tr>
                    <td><strong>Total Amount:</strong></td>
                    <td class="text-right">₹{{ number_format($manualRadiologyTest->total_amount, 2) }}</td>
                </tr>
                <tr>
                    <td><strong>Paid Amount:</strong></td>
                    <td class="text-right">₹{{ number_format($manualRadiologyTest->paid_amount, 2) }}</td>
                </tr>
                <tr class="total">
                    <td><strong>Balance Amount:</strong></td>
                    <td class="text-right">₹{{ number_format($manualRadiologyTest->total_amount - $manualRadiologyTest->paid_amount, 2) }}</td>
                </tr>
            </table>


        </div>

        <!-- Notes -->
        @if($manualRadiologyTest->notes)
        <div style="clear: both; margin-top: 20px; padding: 10px; border: 1px solid #ddd; background-color: #f9f9f9; border-radius: 5px;">
            <strong>Notes:</strong> {{ $manualRadiologyTest->notes }}
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <table style="width: 100%; margin-bottom: 20px;">
                <tr>
                    <td width="33%" class="text-center">
                        <div class="signature-line"></div>
                        <div class="signature-text">Patient's Signature</div>
                    </td>
                    <td width="33%" class="text-center">
                        <div class="signature-line"></div>
                        <div class="signature-text">Radiologist's Signature</div>
                    </td>
                    <td width="33%" class="text-center">
                        <div class="signature-line"></div>
                        <div class="signature-text">Authorized Signature</div>
                    </td>
                </tr>
            </table>


        </div>
    </div>

    <script>

        // Set amount in words on page load
        document.addEventListener('DOMContentLoaded', function() {
            const totalAmount = {{ $manualRadiologyTest->total_amount }};
            const amountElement = document.getElementById('amountWords');

            // If amountInWords is not set from server, calculate it client-side
            if (!amountElement.textContent.trim()) {
                amountElement.textContent = numberToWords(totalAmount);
            }

            // Auto print after 1 second (optional)
            // setTimeout(() => {
            //     window.print();
            // }, 1000);
        });
    </script>
</body>
</html>
