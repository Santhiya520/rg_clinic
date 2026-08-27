<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Test Report - {{ $foundTest->labTest->name }}</title>
    <style>
        @page { size: A4; margin: 20mm; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; line-height: 1.4; }
        .print-container { max-width: 210mm; margin: 0 auto; padding: 15px; }
        .header { text-align: center; padding-bottom: 10px; border-bottom: 2px solid #333; margin-bottom: 15px; }
        .title { color: #333; font-size: 18px; font-weight: bold; margin: 10px 0; text-align: center; text-transform: uppercase; }
        .patient-info { margin-bottom: 15px; padding: 12px; border: 1px solid #ddd; background: #f8f9fa; }
        .info-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; }
        .info-item { display: flex; flex-direction: column; }
        .info-label { font-weight: bold; color: #333; font-size: 11px; }
        .info-value { color: #555; }
        .test-table { width: 100%; border-collapse: collapse; margin-top: 15px; margin-bottom: 20px; }
        .test-table th { background: #2c3e50; color: white; padding: 10px; text-align: left; font-weight: 600; }
        .test-table td { padding: 8px 10px; border: 1px solid #dee2e6; vertical-align: top; }
        .test-table tr:nth-child(even) { background: #f8f9fa; }
        .result-cell { font-weight: bold; color: #2c3e50; }
        .normal-range { color: #6c757d; font-size: 11px; }
        .footer { text-align: center; margin-top: 25px; padding-top: 10px; border-top: 1px solid #ddd; font-size: 10px; color: #6c757d; }
        .no-print { text-align: center; margin-top: 20px; }
        .print-btn { padding: 10px 25px; background: #2c3e50; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; }
        .print-btn:hover { background: #1a252f; }
        .signature-area { margin-top: 40px; text-align: center; }
        .signature-line { width: 250px; border-top: 1px solid #333; margin: 20px auto 5px; }
        .signature-label { font-size: 11px; color: #666; }
        @media print {
            body { background: white; }
            .print-container { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="print-container">
        <!-- Hospital/Clinic Header -->
        <div class="header">
            <img src="{{ asset('images/rg-banner.png') }}" style="max-width: 100%; height: auto;">
        </div>

        <!-- Patient Information -->
        <div class="patient-info">
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Patient Name:</span>
                    <span class="info-value">{{ $patient->name }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Patient ID:</span>
                    <span class="info-value">{{ $patient->patient_id }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Age / Gender:</span>
                    <span class="info-value">
                        {{ $patient->age ?? 'N/A' }} years /
                        {{ ucfirst($patient->sex ?? 'N/A') }}
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Reference No:</span>
                    <span class="info-value">
                        @if($source == 'op')
                            Token: {{ $register->token_number }}
                        @else
                            IP No: {{ $register->ip_no }}
                        @endif
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Test Name:</span>
                    <span class="info-value"><strong>{{ $foundTest->labTest->name ?? 'N/A' }}</strong></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Report Date:</span>
                    <span class="info-value">{{ $foundTest->completed_at ? $foundTest->completed_at->format('d/m/Y h:i A') : now()->format('d/m/Y h:i A') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Visit Date:</span>
                    <span class="info-value">{{ $register->created_at->format('d/m/Y') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Doctor:</span>
                    <span class="info-value">{{ $register->medicalOfficer->name ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Test Results Table -->
        @if($foundTest->subTests->isNotEmpty())
        <div class="title">TEST RESULTS</div>
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
                @foreach($foundTest->subTests as $index => $subTest)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
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
        <div style="text-align: center; padding: 20px; color: #666;">
            No test parameters available for this test.
        </div>
        @endif

        <!-- Overall Result Summary -->
        @if($foundTest->result)
        <div style="margin-top: 20px; padding: 15px; border: 1px solid #dee2e6; background: #f8f9fa;">
            <h4 style="margin-bottom: 8px; color: #2c3e50;">Overall Result Summary</h4>
            <p style="margin: 0; color: #555;">{{ $foundTest->result }}</p>
        </div>
        @endif

        <!-- Notes -->
        @if($foundTest->notes)
        <div style="margin-top: 15px; padding: 12px; border-left: 3px solid #3498db; background: #e8f4fd;">
            <h4 style="margin-bottom: 5px; color: #2c3e50; font-size: 13px;">Notes</h4>
            <p style="margin: 0; color: #555;">{{ $foundTest->notes }}</p>
        </div>
        @endif

        <!-- Interpretation -->
        @if($foundTest->interpretation)
        <div style="margin-top: 15px; padding: 12px; border-left: 3px solid #2ecc71; background: #e8f8f1;">
            <h4 style="margin-bottom: 5px; color: #2c3e50; font-size: 13px;">Interpretation</h4>
            <p style="margin: 0; color: #555;">{{ $foundTest->interpretation }}</p>
        </div>
        @endif

        <!-- Status Information -->
        <div style="margin-top: 15px; padding: 10px; text-align: center;">
            <span style="padding: 5px 15px; background: #28a745; color: white; border-radius: 20px;">
                STATUS: COMPLETED
            </span>
            @if($foundTest->completed_at)
            <span style="margin-left: 20px; color: #666;">
                Completed on: {{ $foundTest->completed_at->format('d/m/Y h:i A') }}
            </span>
            @endif
        </div>


        <!-- Print Button (Hidden when printing) -->
        <div class="no-print">
            <button onclick="window.print()" class="print-btn">
                🖨️ Print Lab Report
            </button>
            <button onclick="window.close()" class="print-btn" style="background: #6c757d; margin-left: 10px;">
                ✕ Close Window
            </button>
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
            // Auto-print after 1 second
            setTimeout(function() {
                window.print();
            }, 1000);
        };
    </script>
</body>
</html>
