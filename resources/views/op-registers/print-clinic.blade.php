<!DOCTYPE html>
<html>
<head>
    <title>Clinic Register - {{ $opRegister->token_number ?? 'OP Register' }}</title>
<style>
@page { size: A4 landscape; margin: 10mm 15mm; }
body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
.page { width: 100%; padding: 5px; box-sizing: border-box; margin-top: 10rem; }
.top-row { display: flex; justify-content: space-between; font-size: 15px; margin-bottom: 10px; }
h3, h4, p { text-align: left; margin: 4px 0; }
table { width: 100%; border-collapse: collapse; font-size: 11px; }
th, td { border: 1px solid #000; padding: 4px; vertical-align: top; }
th { text-align: center; font-weight: bold; }
.note { margin-top: 10px; font-size: 14px; }
</style>
</head>
<body>

<div class="page">
    <div class="top-row">
        <div>System of Medicine: Allopathy</div>
        <div><b>CLINIC / CONSULTING ROOM</b></div>
        <div>Tamil Nadu Clinical Establishment Regulation Act Registration no. : TN/CLN/2024/OP001</div>
    </div>

    @php
        $opRegister = $opRegisters->first();
        $patient = $opRegister->patient;
        $medicalOfficer = $opRegister->medicalOfficer;
    @endphp

    <p><b>Name of the Doctor</b> : &nbsp; &nbsp; {{ $medicalOfficer->name ?? 'N/A' }}</p>
    <p><b>Register of Patients (Outpatient)</b></p>
    <p><b>Date</b> : {{ \Carbon\Carbon::parse($opRegister->created_at)->format('d/m/Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>Serial No</th>
                <th>Name of the Patient and address</th>
                <th>Mobile No. / Contact No. if available</th>
                <th>Age</th>
                <th>Sex</th>
                <th>Token No.</th>
                <th>Provisional Diagnosis</th>
                <th>Investigations if any</th>
                <th>Final diagnosis</th>
                <th>Treatment</th>
                <th>Result<br>Cured / Same condition / Referred</th>
                <th>Additional information if any</th>
                <th>Initial of the Medical officer</th>
            </tr>
            <tr>
                <th>(1)</th><th>(2)</th><th>(3)</th><th>(4)</th><th>(5)</th><th>(6)</th>
                <th>(7)</th><th>(8)</th><th>(9)</th><th>(10)</th><th>(11)</th><th>(12)</th><th>(13)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1.</td>
                <td>
                    {{ $patient->name ?? 'N/A' }}
                    @if($patient->address)<br>{{ $patient->address }}@endif
                </td>
                <td>{{ $patient->mobile ?? 'N/A' }}</td>
                <td>{{ $patient->age ?? 'N/A' }}</td>
                <td>{{ ucfirst($patient->sex ?? 'N/A') }}</td>
                <td>{{ $opRegister->token_number ?? 'N/A' }}</td>
                <td>{{ $opRegister->provisional_diagnosis ?? 'N/A' }}</td>
                <td>
                    {{ $opRegister->investigations ?? 'N/A' }}
                    @if($opRegister->labTests->count() > 0)<br>Lab Tests: {{ $opRegister->labTests->count() }}@endif
                    @if($opRegister->radiologyTests->count() > 0)<br>Radiology: {{ $opRegister->radiologyTests->count() }}@endif
                </td>
                <td>{{ $opRegister->final_diagnosis ?? 'N/A' }}</td>
                <td>
                    {{ $opRegister->treatment ?? 'N/A' }}
                    @if($opRegister->medicines->count() > 0)<br>Medicines: {{ $opRegister->medicines->count() }}@endif
                </td>
                <td>{{ $opRegister->result ?? 'Cured' }}</td>
                <td>{{ $opRegister->additional_info ?? '-' }}</td>
                <td>
                    @if($medicalOfficer)
                        {{ strtoupper(substr($medicalOfficer->name, 0, 3)) }}
                    @else
                        N/A
                    @endif
                </td>
            </tr>
            <tr><td>2.</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
            <tr><td>3.</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
            <tr><td>4.</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
        </tbody>
    </table>

    <p class="note">
        Note: If electronic records are maintained and / or existing registers capture this information,
        a monthly print outs / copy shall be taken authenticated by the Hospital authorities.
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
