<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Test Report - {{ $opRegister->patient->name }}</title>
    <style>
        @page {
            size: A4;
            margin: 20mm;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .print-container {
            max-width: 210mm;
            margin: 0 auto;
            padding: 15px;
        }
        .header {
            text-align: center;
            padding-bottom: 10px;
            border-bottom: 2px solid #333;
            margin-bottom: 15px;
        }
        .header img {
            max-width: 100%;
            height: auto;
        }
        .title {
            color: #333;
            font-size: 18px;
            font-weight: bold;
            margin: 10px 0;
            text-align: center;
            text-transform: uppercase;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin: 25px 0 10px;
            padding: 10px;
            background: #2c3e50;
            color: white;
            border-radius: 5px;
        }
        .patient-info {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            background: #f8f9fa;
            border-radius: 5px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
        }
        .info-item {
            display: flex;
            flex-direction: column;
        }
        .info-label {
            font-weight: bold;
            color: #333;
            font-size: 11px;
            margin-bottom: 3px;
        }
        .info-value {
            color: #555;
            font-size: 13px;
        }
        .test-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 20px;
        }
        .test-table th {
            background: #34495e;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: 600;
            font-size: 12px;
        }
        .test-table td {
            padding: 8px 10px;
            border: 1px solid #dee2e6;
            vertical-align: top;
        }
        .test-table tr:nth-child(even) {
            background: #f8f9fa;
        }
        .result-cell {
            font-weight: bold;
            color: #2c3e50;
        }
        .normal-range {
            color: #6c757d;
            font-size: 11px;
        }
        .test-summary {
            background: #e8f4fd;
            padding: 12px;
            margin: 15px 0;
            border-left: 3px solid #3498db;
            border-radius: 4px;
        }
        .test-notes {
            margin-top: 10px;
            padding: 10px;
            border-left: 3px solid #28a745;
            background: #f0f9f0;
            border-radius: 4px;
        }
        .test-meta {
            margin-top: 8px;
            text-align: right;
            font-size: 10px;
            color: #888;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #6c757d;
        }
        .no-print {
            text-align: center;
            margin-top: 20px;
        }
        .print-btn {
            padding: 10px 25px;
            background: #2c3e50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin: 0 5px;
        }
        .print-btn:hover {
            background: #1a252f;
        }
        .signature-area {
            margin-top: 50px;
            text-align: center;
        }
        .signature-line {
            width: 250px;
            border-top: 1px solid #333;
            margin: 20px auto 5px;
        }
        .signature-label {
            font-size: 11px;
            color: #666;
        }
        .page-break {
            page-break-before: avoid;
        }
        .test-count {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 11px;
            margin-left: 10px;
        }
        @media print {
            body { background: white; }
            .print-container { padding: 0; }
            .no-print { display: none; }
            .section-title {
                background: #2c3e50;
                color: white;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .test-table th {
                background: #34495e;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <div class="print-container">
        <!-- Hospital Header -->
        <div class="header">
            <img src="{{ asset('images/rg-banner.png') }}" alt="Hospital Logo">
        </div>

        <!-- Patient Information -->
        <div class="patient-info">
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Patient Name:</span>
                    <span class="info-value"><strong>{{ $opRegister->patient->name }}</strong></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Patient ID:</span>
                    <span class="info-value">{{ $opRegister->patient->patient_id }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Age / Gender:</span>
                    <span class="info-value">
                        {{ $opRegister->patient->age ?? 'N/A' }} yrs /
                        {{ ucfirst($opRegister->patient->gender ?? 'N/A') }}
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Token No:</span>
                    <span class="info-value">{{ $opRegister->token_number }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Mobile No:</span>
                    <span class="info-value">{{ $opRegister->patient->mobile ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Report Date:</span>
                    <span class="info-value">{{ now()->format('d/m/Y h:i A') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Total Tests:</span>
                    <span class="info-value">
                        <span class="test-count">{{ $completedTests->count() }} Test(s)</span>
                    </span>
                </div>
            </div>
        </div>

        <!-- Test Results -->
        <div class="title">LABORATORY TEST REPORT</div>

        @forelse($completedTests as $index => $opLabTest)
            <div class="page-break">
                <div class="section-title" style="text-align: center;">
                    {{ $opLabTest->labTest->name }}
                </div>

                @if($opLabTest->subTests->isNotEmpty())
                <table class="test-table">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="35%">Test Parameter</th>
                            <th width="15%">Result</th>
                            <th width="15%">Unit</th>
                            <th width="30%">Normal Range</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($opLabTest->subTests as $subIndex => $subTest)
                        <tr>
                            <td style="text-align: center;">{{ $subIndex + 1 }}</td>
                            <td>{{ $subTest->test_name }}</td>
                            <td class="result-cell">
                                {{ $subTest->result ?? 'Not Entered' }}
                            </td>
                            <td>{{ $subTest->unit ?? 'N/A' }}</td>
                            <td>
                                <span class="normal-range">{{ $subTest->normal_range ?? 'N/A' }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div style="text-align: center; padding: 20px; color: #666; border: 1px solid #ddd; margin: 15px 0; border-radius: 5px;">
                    No test parameters available for this test.
                </div>
                @endif

                @if($opLabTest->result)
                <div class="test-summary">
                    <strong>📊 Overall Result:</strong> {{ $opLabTest->result }}
                </div>
                @endif

                @if($opLabTest->notes)
                <div class="test-notes">
                    <strong>📝 Notes:</strong>
                    <p style="margin: 5px 0 0 0; color: #555;">{{ $opLabTest->notes }}</p>
                </div>
                @endif
            </div>
        @empty
            <div style="text-align: center; padding: 60px; color: #666;">
                <h3>⚠️ No completed tests found</h3>
                <p>There are no completed lab tests available for this patient.</p>
            </div>
        @endforelse

        @if($completedTests->count() > 0)
        <div class="signature-area">
            <div class="signature-line"></div>
            <div class="signature-label">
                Authorized Signature & Stamp
            </div>
        </div>

        @endif

        <!-- Print Buttons -->
        <div class="no-print">
            <button onclick="window.print()" class="print-btn">
                🖨️ Print Report
            </button>
            <button onclick="window.close()" class="print-btn" style="background: #6c757d;">
                ✖ Close
            </button>
        </div>
    </div>

    <script>
        // Auto print on load
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };

        // Handle Ctrl+P
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
        });
    </script>
</body>
</html>
