<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Camp Bill</title>

<style>
@media print {
    body { margin:0; }
    @page { size: 70mm auto; margin: 0; }
}

body{
    width:70mm;
    font-family: 'Courier New', monospace;
    font-size:9px;
    line-height:1.4;
    margin:0 auto;
    padding:6px;
}

.center{text-align:center;}
.right{text-align:right;}
.bold{font-weight:bold;}
.line{border-bottom:1px solid #000;margin:6px 0;}
.dash{border-bottom:1px dashed #000;margin:6px 0;}
.thanks{margin-top:10px;}
</style>
</head>

<body>

<div class="center bold">
    ரோட்டரி உடுமலை கேலக்ஸி மருத்துவ மையம்
</div>

<div class="center">
    CAMP
</div>

<div class="dash"></div>

<div class="center bold">
    PHARMACY & LAB BILL
</div>

<div class="line"></div>

<div>
    <table style="width:100%; border-collapse:collapse;">
        <tr>
            <td style="width:50%; text-align:left;">
                Token No : {{ $record->token_number }}
            </td>
            <td style="width:50%; text-align:right;">
                Date : {{ date('d/m/Y', strtotime($record->created_at)) }}
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align:left;">
                Name : {{ $record->patient_name }}
            </td>
        </tr>
    </table>
</div>


<div class="line"></div>

<table style="width:100%; border-collapse:collapse;">
    <tr>
        <td style="text-align:left;">
            முழு உடல் பரிசோதனை
        </td>
        <td style="text-align:right; font-weight:bold;">
            ₹ {{ number_format($record->total_amount,0) }}
        </td>
    </tr>
</table>


<div class="line"></div>

<div class="right bold">
    Total : ₹ {{ number_format($record->total_amount,0) }}
</div>

<div class="line"></div>

<div class="center thanks">
    Thank You :)
</div>

</body>
</html>
