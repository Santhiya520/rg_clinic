<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>FORM III - Register of Laboratory Test Conducted</title>
<style>
    /* Print Page Setup */
    @page {
        size: A4 landscape;
        margin: 10mm 15mm; /* top/bottom – 10mm, left/right – 15mm */
    }
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
        margin-top: 6rem;
    }

    .title {
        text-align: center;
        font-weight: bold;
        text-transform: uppercase;
    }

    .subtitle {
        text-align: center;
        margin-top: -8px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    table, th, td {
        border: 1px solid black;
    }

    th, td {
        padding: 6px 4px;
        font-size: 14px;
        text-align: center;
    }

    .note {
        margin-top: 10px;
        font-size: 13px;
    }
</style>
</head>

<body>

<p class="title">FORM III</p>
<p class="subtitle">(see rule 12)</p>
<p class="title">PART A</p>

<table border="0" style="border:0; margin-top:20px;">
<tr>
    <td style="border:0;"><b>System of Medicine</b>: Allopathy</td>
    <td style="border:0;"><b>Clinical Laboratory</b>: Hospital Laboratory</td>
    <td style="border:0;"><b>Tamil Nadu Clinical Establishment Regulation Act Registration no.</b>: __________________</td>
</tr>
</table>

<h3 style="text-align:center; margin-top:10px;">Register of Laboratory Test Conducted</h3>
<p><b>Date :</b> {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>

<table>
    <tr>
        <th>S. No</th>
        <th>Name of the Patient and Address</th>
        <th>Mobile No.</th>
        <th>Age</th>
        <th>Sex</th>
        <th>ID No.</th>
        <th>Referring Doctor</th>
        <th>Provisional Diagnosis</th>
        <th>Investigations Specimen</th>
        <th>Investigation Performed</th>
        <th>Method of Investigation and Equipment</th>
        <th>Result</th>
        <th>Additional Information (if any)</th>
        <th>Initial of the Medical Officer</th>
    </tr>

    @php
        // Get the OP Register data
        $opRegister = $opRegisters->first();
        $patient = $opRegister->patient;
        $medicalOfficer = $opRegister->medicalOfficer;
        $labTests = $opRegister->labTests;

        // Fill the form rows
        $rows = [];

        if($labTests->count() > 0) {
            foreach($labTests as $index => $labTest) {
                $rows[] = [
                    'sno' => $index + 1,
                    'name_address' => $patient->name . ($patient->address ? '<br>' . $patient->address : ''),
                    'mobile' => $patient->phone ?? 'N/A',
                    'age' => $patient->age ?? 'N/A',
                    'sex' => ucfirst($patient->gender ?? 'N/A'),
                    'id_no' => $patient->patient_id ?? 'N/A',
                    'doctor' => $medicalOfficer->name ?? 'N/A',
                    'diagnosis' => $opRegister->provisional_diagnosis ?? 'N/A',
                    'specimen' => $labTest->specimen_type ?? 'Blood',
                    'investigation' => $labTest->labTest->name ?? $labTest->test_name,
                    'method' => $labTest->method ?? 'Standard Lab Method',
                    'result' => $labTest->result ?? 'Pending',
                    'additional_info' => $labTest->notes ?? '-',
                    'initial' => $medicalOfficer ? substr($medicalOfficer->name, 0, 3) : 'N/A'
                ];
            }
        }

        // Ensure we have at least 5 rows (as per the form template)
        $totalRows = max(count($rows), 5);
    @endphp

    @for($i = 1; $i <= $totalRows; $i++)
    <tr>
        <td>{{ $i }}.</td>

        @if(isset($rows[$i-1]))
            @php $row = $rows[$i-1]; @endphp
            <td>{!! $row['name_address'] !!}</td>
            <td>{{ $row['mobile'] }}</td>
            <td>{{ $row['age'] }}</td>
            <td>{{ $row['sex'] }}</td>
            <td>{{ $row['id_no'] }}</td>
            <td>{{ $row['doctor'] }}</td>
            <td>{{ $row['diagnosis'] }}</td>
            <td>{{ $row['specimen'] }}</td>
            <td>{{ $row['investigation'] }}</td>
            <td>{{ $row['method'] }}</td>
            <td>{{ $row['result'] }}</td>
            <td>{{ $row['additional_info'] }}</td>
            <td>{{ $row['initial'] }}</td>
        @else
            {{-- Empty rows for the form template --}}
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        @endif
    </tr>
    @endfor
</table>

<p class="note"><b>Note:</b> If electronic records are maintained and/or existing registers capture this information, a monthly printout/copy shall be taken and signed by the authorities concerned.</p>

<script>
    // Auto print after page loads
    window.onload = function() {
        setTimeout(function() {
            window.print();
        }, 500);
    };
</script>

</body>
</html>
