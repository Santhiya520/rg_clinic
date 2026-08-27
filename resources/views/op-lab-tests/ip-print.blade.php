<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>FORM III - Register of Laboratory Test Conducted - IP</title>
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

<h3 style="text-align:center; margin-top:10px;">Register of Laboratory Test Conducted - IP</h3>
<p><b>Date :</b> {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>

<table>
    <tr>
        <th>S. No</th>
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

    @php
        // Get the IP Register data
        $inpatientRegister = $inpatientRegisters->first();
        $patient = $inpatientRegister->patient;
        $labTests = $inpatientRegister->labTests;

        // Fill the form rows
        $rows = [];

        if($labTests->count() > 0) {
            foreach($labTests as $index => $labTest) {
                $rows[] = [
                    'sno' => $index + 1,
                    'ip_no' => $inpatientRegister->hospital_ip_no ?? 'N/A',
                    'name_address' => $patient->name . ($patient->address ? '<br>' . $patient->address : ''),
                    'mobile' => $patient->phone ?? 'N/A',
                    'age' => $patient->age ?? 'N/A',
                    'sex' => ucfirst($patient->gender ?? 'N/A'),
                    'id_no' => $patient->patient_id ?? 'N/A',
                    'admission_date' => $inpatientRegister->date_of_admission->format('d/m/Y') ?? 'N/A',
                    'diagnosis' => $inpatientRegister->provisional_diagnosis ?? 'N/A',
                    'investigation' => $labTest->labTest->name ?? $labTest->test_name,
                    'method' => $labTest->method ?? 'Standard Lab Method',
                    'result' => $labTest->result ?? 'Pending',
                    'additional_info' => $labTest->notes ?? '-',
                    'initial' => $inpatientRegister->doctor ? substr($inpatientRegister->doctor->name, 0, 3) : 'N/A'
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
            <td>{{ $row['ip_no'] }}</td>
            <td>{!! $row['name_address'] !!}</td>
            <td>{{ $row['mobile'] }}</td>
            <td>{{ $row['age'] }}</td>
            <td>{{ $row['sex'] }}</td>
            <td>{{ $row['id_no'] }}</td>
            <td>{{ $row['admission_date'] }}</td>
            <td>{{ $row['diagnosis'] }}</td>
            <td>{{ $row['investigation'] }}</td>
            <td>{{ $row['method'] }}</td>
            <td>{{ $row['result'] }}</td>
            <td>{{ $row['additional_info'] }}</td>
            <td>{{ $row['initial'] }}</td>
        @else
            {{-- Empty rows for the form template --}}
            <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
            <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
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
