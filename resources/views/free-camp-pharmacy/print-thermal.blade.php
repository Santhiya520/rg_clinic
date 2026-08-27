<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Free Camp - Patient Slip</title>
    <style>
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            @page {
                size: 70mm auto;
                margin: 0;
            }
        }

        body {
            width: 70mm;
            font-family: 'Courier New', monospace;
            font-size: 9px;
            line-height: 1.4;
            margin: 0 auto;
            padding: 6px;
            box-sizing: border-box;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .line {
            border-bottom: 1px solid #000;
            margin: 8px 0;
        }

        .dash {
            border-bottom: 1px dashed #000;
            margin: 8px 0;
        }

        .thanks {
            margin-top: 10px;
            font-size: 10px;
        }

        .hospital-name {
            font-size: 12px;
            letter-spacing: 1px;
        }

        .free-badge {
            background: #000;
            color: #fff;
            padding: 2px 8px;
            display: inline-block;
            font-size: 8px;
            letter-spacing: 1px;
            margin: 3px 0;
        }

        .info-row {
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 2px 0;
        }

        .label {
            color: #555;
        }

        .value {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="center bold hospital-name">
        ரோட்டரி உடுமலை கேலக்ஸி மருத்துவ மையம்
    </div>
    <div class="dash"></div>

    <div class="center bold">
        FREE CAMP
    </div>

    <div class="line"></div>

    <!-- Patient Details -->
    <table>
        <tr>
            <td class="label" style="width: 35%;">Token No :</td>
            <td class="value" style="width: 65%;">{{ $record->token_number }}</td>
        </tr>
        <tr>
            <td class="label">Date :</td>
            <td class="value">{{ date('d/m/Y', strtotime($record->created_at)) }}</td>
        </tr>
        <tr>
            <td class="label">Patient Name :</td>
            <td class="value">{{ $record->patient_name }}</td>
        </tr>
        @if($record->mobile_number)
        <tr>
            <td class="label">Mobile :</td>
            <td class="value">{{ $record->mobile_number }}</td>
        </tr>
        @endif
        @if($record->age || $record->gender)
        <tr>
            <td class="label">Age/Gender :</td>
            <td class="value">
                @if($record->age){{ $record->age }} years @endif
                @if($record->gender) / {{ ucfirst($record->gender) }} @endif
            </td>
        </tr>
        @endif
    </table>


    <div class="line"></div>

    <table style="margin-top: 3px;">
        <tr>
            <td style="width: 70%;">• General Checkup</td>
            <td style="width: 30%; text-align: right;">Free</td>
        </tr>
        <tr>
            <td>• Doctor Consultation</td>
            <td style="text-align: right;">Free</td>
        </tr>

    </table>

    <div class="dash"></div>

    <!-- Amount Summary - All Zero/Free -->
    <table>

        <tr class="bold">
            <td>TOTAL AMOUNT</td>
            <td style="text-align: right;">₹ 0</td>
        </tr>
    </table>

    <div class="center bold" style="margin: 8px 0; font-size: 12px; background: #f0f0f0; padding: 3px;">
        ⭐ COMPLETELY FREE ⭐
    </div>

    <div class="line"></div>


    <div class="center thanks bold">
        Thank You for Visiting Us :)
    </div>


    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
