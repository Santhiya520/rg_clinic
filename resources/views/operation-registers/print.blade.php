<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Operation Register – {{ $operationRegister->hospital_ip_no ?? 'PART E' }}</title>

    <style>
      @page {
        size: A4 landscape;
        margin: 20mm 10mm; /* top-bottom / left-right */
      }

      body {
        font-family: Arial, sans-serif;
        padding: 0;
        margin: 0;
        margin-top: 5rem;
      }

      .header {
        text-align: center;
        font-weight: bold;
        margin-bottom: 10px;
        text-decoration: underline;
      }

      .top-line {
        display: flex;
        justify-content: space-between;
        font-weight: bold;
        margin-bottom: 20px;
      }

      table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
      }

      th,
      td {
        border: 1px solid #000;
        padding: 2px 4px;
        text-align: center;
      }

      .note {
        margin-top: 10px;
        font-size: 12px;
      }
    </style>
  </head>

  <body>
    <div class="header">PART E</div>
    <div class="top-line">
      <div>System of Medicine: Allopathy</div>
      <div>Hospital / Nursing Home</div>
      <div>
        Tamil Nadu Clinical Establishment Regulation Act Registration no. : TN/CLN/2024/OR001
      </div>
    </div>

    <div class="header" style="margin-top: 5px; text-decoration: none">
      Operation Register
    </div>
    <div style="text-align: center; margin-bottom: 15px">
      <span style="font-weight: bold"> Operation Theatre: {{ $operationRegister->operation_theatre_type ?? 'General' }} </span>
      <i>(Please specify the OT either Maternity / General / Ortho etc., as the case may be)</i>
    </div>

    <table>
      <tr>
        <th>S. No</th>
        <th>Name of the Patient and address</th>
        <th>Mobile No.</th>
        <th>Age</th>
        <th>Sex</th>
        <th>Date of Admission</th>
        <th>Hospital IP No.</th>
        <th>Provisional Diagnosis</th>
        <th>Investigations if any</th>
        <th>Operation performed</th>
        <th>Operating Surgeon and Assistant</th>
        <th>Anaesthetist</th>
        <th>Staff reception Assisted</th>
        <th>Operation Time (From to)</th>
        <th>Operation Notes</th>
        <th>Transferred to which ward</th>
        <th>Additional information if any</th>
        <th>Initial of the Medical Officer</th>
      </tr>

      <!-- Column Numbers -->
      <tr style="font-weight: bold;">
        <td height="35">(1)</td>
        <td>(2)</td>
        <td>(3)</td>
        <td>(4)</td>
        <td>(5)</td>
        <td>(6)</td>
        <td>(7)</td>
        <td>(8)</td>
        <td>(9)</td>
        <td>(10)</td>
        <td>(11)</td>
        <td>(12)</td>
        <td>(13)</td>
        <td>(14)</td>
        <td>(15)</td>
        <td>(16)</td>
        <td>(17)</td>
        <td>(18)</td>
      </tr>

      @php
        $patient = $operationRegister->patient;
        $operatingSurgeon = $operationRegister->operatingSurgeon;
        $assistantSurgeon = $operationRegister->assistantSurgeon;
        $anaesthetist = $operationRegister->anaesthetist;
        $staffreception = $operationRegister->staffreception;
        $medicalOfficer = $operationRegister->medicalOfficer;

        // Format operation time
        $operationTime = '';
        if ($operationRegister->operation_start_time && $operationRegister->operation_end_time) {
            $start = \Carbon\Carbon::parse($operationRegister->operation_start_time)->format('h:i A');
            $end = \Carbon\Carbon::parse($operationRegister->operation_end_time)->format('h:i A');
            $operationTime = $start . ' to ' . $end;
        }
      @endphp

      <!-- First row with actual data -->
      <tr>
        <td height="35">1</td>
        <td>
          {{ $patient->name ?? 'N/A' }}
          @if($patient->address)<br>{{ $patient->address }}@endif
        </td>
        <td>{{ $patient->mobile ?? 'N/A' }}</td>
        <td>{{ $patient->age ?? 'N/A' }}</td>
        <td>{{ ucfirst($patient->sex ?? 'N/A') }}</td>
        <td>
          @if($operationRegister->date_of_admission)
            {{ \Carbon\Carbon::parse($operationRegister->date_of_admission)->format('d/m/Y') }}
          @else
            N/A
          @endif
        </td>
        <td>{{ $operationRegister->hospital_ip_no ?? 'N/A' }}</td>
        <td>{{ $operationRegister->provisional_diagnosis ?? 'N/A' }}</td>
        <td>{{ $operationRegister->investigations ?? 'N/A' }}</td>
        <td>{{ $operationRegister->operation_performed ?? 'N/A' }}</td>
        <td>
          {{ $operatingSurgeon->name ?? 'N/A' }}
          @if($assistantSurgeon)
            <br>Asst: {{ $assistantSurgeon->name }}
          @endif
        </td>
        <td>{{ $anaesthetist->name ?? 'N/A' }}</td>
        <td>{{ $staffreception->name ?? 'N/A' }}</td>
        <td>{{ $operationTime }}</td>
        <td>{{ $operationRegister->operation_notes ?? 'N/A' }}</td>
        <td>{{ $operationRegister->transferred_to_ward ?? 'N/A' }}</td>
        <td>{{ $operationRegister->additional_information ?? '-' }}</td>
        <td>
          @if($medicalOfficer)
            {{ $medicalOfficer->name }}
          @else
            N/A
          @endif
        </td>
      </tr>

      <!-- Empty rows (2-9) -->
      @for($i = 2; $i <= 9; $i++)
      <tr>
        <td height="35">{{ $i }}</td>
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
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      @endfor
    </table>

    <div class="note">
      Note: If electronic records are maintained, and / or existing registers
      capture this information, a monthly print out / copy shall be taken and it
      shall be authenticated by the Hospital authorities.
    </div>

    <script>
      // Auto print after page loads
      window.onload = function() {
        setTimeout(function() {
          window.print();
        }, 500);
      };

      // Listen for print shortcut (Ctrl+P)
      document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
          e.preventDefault();
          window.print();
        }
      });
    </script>
  </body>
</html>
