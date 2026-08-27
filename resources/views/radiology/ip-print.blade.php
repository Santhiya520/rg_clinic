<!DOCTYPE html>
<html>
<head>
    <title>FORM III (PART B) - Register of Imaging Techniques Conducted - IP</title>
    <style>
        /* Your existing print styles */
        @page {
            size: A4 landscape;
            margin: 10mm 15mm;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .page {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            margin-top: 10rem;
        }
        h3, h4, p {
            margin: 4px 0;
            text-align: center;
            text-decoration: underline;
        }
        h3 {
            text-decoration: none;
        }
        .top-row {
            display: flex;
            justify-content: space-between;
            margin: 8px 0;
            font-size: 14px;
        }
        .date-label {
            margin: 5px 0;
            font-size: 14px;
            text-align: start;
            font-weight: bold;
            text-decoration: none;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        th, td {
            border: 1px solid #000;
            padding: 4px;
            vertical-align: top;
        }
        th {
            text-align: center;
            font-weight: bold;
        }
        .note {
            margin-top: 10px;
            font-size: 14px;
            text-decoration: none;
            text-align: start;
        }
    </style>
</head>
<body>
<div class="page">
    <h4>PART B</h4>
    <div class="top-row">
        <div><b>System of Medicine</b>: Allopathy</div>
        <div><b>Clinical Laboratory</b>: Radiology Department</div>
        <div style="margin-right: 20px;">
            <b>Tamil Nadu Clinical Establishment Regulation <br> Act Registration no. :</b> TN/CLN/2024/RAD001
        </div>
    </div>
    <h3>Register for Imaging techniques USG/X Ray/ CT/MRI/PET etc. Laboratory Test Conducted - IP</h3>
    <p class="date-label">Date : {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
    <table>
        <thead>
            <tr style="height: 30px;">
                <th>S.No</th>
                <th>IP No.</th>
                <th>Name of the Patient and Address</th>
                <th>Mobile No.</th>
                <th>Age</th>
                <th>Sex</th>
                <th>Patient ID</th>
                <th>Admission Date</th>
                <th>Provisional Diagnosis</th>
                <th>Investigation Performed</th>
                <th>Method of Investigation</th>
                <th>Result</th>
                <th>Additional Information</th>
                <th>Initial of the Medical Officer</th>
            </tr>
        </thead>
        <tbody>
            @php
                $inpatientRegister = $inpatientRegisters->first();
                $patient = $inpatientRegister->patient;
                $radiologyTests = $inpatientRegister->radiologyTests;
                $totalRows = max(count($radiologyTests), 5);
            @endphp
            @for($i = 0; $i < $totalRows; $i++)
            <tr>
                <td>{{ $i + 1 }}.</td>
                @if(isset($radiologyTests[$i]))
                    @php $radiologyTest = $radiologyTests[$i]; @endphp
                    <td>{{ $inpatientRegister->hospital_ip_no ?? 'N/A' }}</td>
                    <td>{{ $patient->name ?? 'N/A' }}@if($patient->address)<br>{{ $patient->address }}@endif</td>
                    <td>{{ $patient->mobile ?? 'N/A' }}</td>
                    <td>{{ $patient->age ?? 'N/A' }}</td>
                    <td>{{ ucfirst($patient->sex ?? 'N/A') }}</td>
                    <td>{{ $patient->patient_id ?? 'N/A' }}</td>
                    <td>{{ $inpatientRegister->date_of_admission->format('d/m/Y') ?? 'N/A' }}</td>
                    <td>{{ $inpatientRegister->provisional_diagnosis ?? 'N/A' }}</td>
                    <td>{{ $radiologyTest->radiologyTest->name ?? $radiologyTest->test_name }}</td>
                    <td>{{ $radiologyTest->method ?? 'Standard Radiology Method' }}</td>
                    <td>{{ $radiologyTest->result ?? 'Pending' }}</td>
                    <td>{{ $radiologyTest->notes ?? '-' }}</td>
                    <td>@if($inpatientRegister->doctor){{ strtoupper(substr($inpatientRegister->doctor->name, 0, 3)) }}@else N/A @endif</td>
                @else
                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                @endif
            </tr>
            @endfor
        </tbody>
    </table>
    <p class="note">
        Note: If electronic records are maintained, and / or existing registers capture this information, a monthly print outs/ copy shall be taken and signed by the Medical Officer.
    </p>
</div>
<script>
    window.onload = function() {
        setTimeout(function() {
            window.print();
        }, 500);
    };
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
            e.preventDefault();
            window.print();
        }
    });
</script>
</body>
</html>
