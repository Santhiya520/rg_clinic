<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inpatient Admission Record - {{ $inpatients->first()->hospital_ip_no ?? '' }}</title>
    <style>
    @page { size: A4 landscape; margin: 10mm 15mm; }
    body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
    .page { width: 100%; padding: 5px; box-sizing: border-box; margin-top: 10rem; }
    .top-row { display: flex; justify-content: space-between; font-size: 15px; margin-bottom: 10px; }
    h3 { text-align: center; margin: 4px 0; }
    table { width: 100%; border-collapse: collapse; font-size: 11px; }
    th, td { border: 1px solid #000; padding: 4px; vertical-align: top; }
    th { text-align: center; font-weight: bold; }
    .note { margin-top: 10px; font-size: 14px; text-align: start; }
    .note span { margin-left: 40px; }
    </style>
</head>
<body>
<div class="page">
    <div class="top-row">
        <div>System of Medicine: Allopathy _________ Hospital / Nursing Home</div>
        <div><b>PART C</b></div>
        <div>Tamil Nadu Clinical Establishment Regulation Act Registration no. : TN/CLN/2024/IP001</div>
    </div>
    <h3>Admission and Discharge Register of Patients</h3>
    <table>
        <thead>
            <tr>
                <th>S.<br>No</th><th>Name of the Patient and address</th><th>Mobile No.</th><th>Age</th><th>Sex</th>
                <th>Hospital IP No.</th><th>Date of Admission</th><th>Provisional Diagnosis</th>
                <th>Investigations if any</th><th>Final diagnosis</th><th>Treatment</th>
                <th>Date of Discharge</th><th>Result<br>Cured / Same condition / Referred / Expired</th>
                <th>Additional information if any</th><th>Initial of the medical officer</th>
            </tr>
            <tr>
                <th>(1)</th><th>(2)</th><th>(3)</th><th>(4)</th><th>(5)</th><th>(6)</th><th>(7)</th><th>(8)</th>
                <th>(9)</th><th>(10)</th><th>(11)</th><th>(12)</th><th>(13)</th><th>(14)</th><th>(15)</th>
            </tr>
        </thead>
        <tbody>
            @php
                $inpatient = $inpatients->first();
                $patient = $inpatient->patient;
                $doctor = $inpatient->doctor;
            @endphp
            <tr>
                <td>1.</td>
                <td>{{ $patient->name ?? 'N/A' }}@if($patient->address)<br>{{ $patient->address }}@endif</td>
                <td>{{ $patient->mobile ?? 'N/A' }}</td>
                <td>{{ $patient->age ?? 'N/A' }}</td>
                <td>{{ ucfirst($patient->sex ?? 'N/A') }}</td>
                <td>{{ $inpatient->hospital_ip_no ?? 'N/A' }}</td>
                <td>@if($inpatient->date_of_admission){{ \Carbon\Carbon::parse($inpatient->date_of_admission)->format('d/m/Y') }}@else N/A @endif</td>
                <td>{{ $inpatient->provisional_diagnosis ?? 'N/A' }}</td>
                <td>{{ $inpatient->investigations ?? 'N/A' }}</td>
                <td>{{ $inpatient->final_diagnosis ?? 'N/A' }}</td>
                <td>{{ $inpatient->treatment ?? 'N/A' }}</td>
                <td>@if($inpatient->date_of_discharge){{ \Carbon\Carbon::parse($inpatient->date_of_discharge)->format('d/m/Y') }}@else - @endif</td>
                <td>{{ $inpatient->result ?? 'Cured' }}</td>
                <td>{{ $inpatient->additional_info ?? '-' }}</td>
                <td>@if($doctor){{ $doctor->name }}@else N/A @endif</td>
            </tr>
            <tr><td>2.</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
            <tr><td>3.</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
            <tr><td>4.</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
        </tbody>
    </table>
    <p class="note">
        Note: If electronic records are maintained and / or existing registers capture this information,
        a monthly print outs / copy shall be taken and signed by the Hospital authorities.
        <br><br>
        <span>2. The hospital shall maintain individual case sheets for the patients.</span>
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
