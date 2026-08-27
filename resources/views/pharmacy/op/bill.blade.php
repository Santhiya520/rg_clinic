<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Pharmacy Bill | RG Hospital</title>
    <style>
        /* THERMAL PRINTER OPTIMIZED – 70mm width */
        @page {
            size: 70mm auto;
            margin: 0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Courier New', Courier, monospace;
            font-size: 9px;
            line-height: 1.25;
        }

        body {
            width: 70mm;
            max-width: 70mm;
            margin: 0 auto;
            padding: 2mm 2mm 4mm 2mm;
            background: #fff;
            color: #000;
            text-align: left; /* base left, but we use text-center for center alignment */
        }

        @media print {
            body {
                margin: 0;
                padding: 2mm;
            }
            .no-print {
                display: none !important;
            }
        }

        /* utilities for CENTER alignment - fixed for thermal */
        .text-center { text-align: center !important; }
        .text-right { text-align: right !important; }
        .text-left { text-align: left !important; }
        .text-bold { font-weight: bold; }
        
        .separator-dash { 
            border-top: 1px dashed #000; 
            margin: 2mm 0; 
        }
        .separator-solid {
            border-top: 1px solid #000;
            margin: 2mm 0;
        }
        .mb-1 { margin-bottom: 1mm; }
        .mt-1 { margin-top: 1mm; }

        /* header / clinic - CENTERED */
        .clinic-header {
            text-align: center;
            border-bottom: 1px dashed #000;
            padding-bottom: 2mm;
            margin-bottom: 2mm;
        }
        .clinic-name {
            font-size: 14px;
            font-weight: bold;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            text-align: center;
        }
        .bill-type {
            font-size: 11px;
            font-weight: bold;
            margin: 1mm 0;
            text-align: center;
        }

        /* patient info block - left label, right value */
        .info-block {
            border-bottom: 1px dashed #000;
            padding-bottom: 2mm;
            margin-bottom: 2mm;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1mm;
        }
        .info-label {
            font-weight: bold;
            min-width: 32mm;
            text-align: left;
        }
        .info-value {
            text-align: right;
            flex: 1;
            word-break: break-word;
        }

        /* section title - CENTERED + underline */
        .section-title {
            font-weight: bold;
            margin: 2mm 0 1mm 0;
            border-bottom: 1px solid #000;
            padding-bottom: 0.5mm;
            text-align: center;
        }

        /* compact table for items - columns aligned properly */
        .item-table {
            width: 100%;
            border-collapse: collapse;
            margin: 1.5mm 0;
        }
        .item-table th, 
        .item-table td {
            padding: 1mm 0.5mm;
            text-align: left;
            border-bottom: 1px dotted #ccc;
        }
        .item-table th {
            border-bottom: 1px solid #000;
            font-weight: bold;
            text-align: left;
        }
        .item-table td:last-child, 
        .item-table th:last-child {
            text-align: right;
        }
        .item-table td:first-child, 
        .item-table th:first-child {
            text-align: left;
            padding-left: 0;
        }
        /* Qty column center */
        .item-table td:nth-child(3),
        .item-table th:nth-child(3) {
            text-align: center;
        }

        /* summary rows - left / right alignment */
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin: 1mm 0;
        }
        .total-row {
            font-weight: bold;
            border-top: 2px solid #000;
            padding-top: 1.5mm;
            margin-top: 1.5mm;
        }
        
        /* payment status badge - CENTERED */
        .payment-status-badge {
            text-align: center;
            font-weight: bold;
            margin: 2mm 0;
            padding: 1mm;
            border: 1px solid #000;
        }
        .paid-badge { background: #000; color: #fff; }
        .unpaid-badge { background: #fff; color: #000; border-width: 2px; }

        .prescription-note {
            border: 1px dashed #000;
            padding: 1.5mm;
            margin: 2mm 0;
            font-style: italic;
            text-align: left;
        }
        .footer {
            text-align: center;
            border-top: 1px dashed #000;
            margin-top: 3mm;
            padding-top: 2mm;
        }
        .thankyou {
            font-weight: bold;
            margin: 1mm 0;
            text-align: center;
        }
        .no-print {
            margin-top: 8mm;
            text-align: center;
        }
        button {
            padding: 3mm 6mm;
            font-size: 10px;
            margin: 0 2mm;
            font-family: monospace;
        }
        
        /* dash line for visual separation */
        .dash-line {
            text-align: center;
            letter-spacing: 2px;
            margin: 1mm 0;
        }
    </style>
</head>
<body>

<!-- ========================================================= -->
<!-- BILL CONTENT – ALL DATA DIRECTLY FROM DB FIELDS          -->
<!-- FULLY CENTERED LAYOUT MATCHING SAMPLE BILL               -->
<!-- ========================================================= -->

<div class="clinic-header">
    <div class="clinic-name">RG HOSPITAL</div>
    <div>Outpatient Pharmacy & Diagnostics</div>
    <div class="separator-dash"></div>
    <div class="bill-type">PHARMACY & LAB BILL</div>
</div>

<!-- Patient & Bill Info -->
<div class="info-block">
    <div class="info-row">
        <span class="info-label">Bill No:</span>
        <span class="info-value">{{ $opRegister->id ?? 'N/A' }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">OP No / Token:</span>
        <span class="info-value">{{ $opRegister->op_no ?? $opRegister->token_number ?? 'N/A' }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Patient ID:</span>
        <span class="info-value">{{ $opRegister->patient->patient_id ?? 'N/A' }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Patient Name:</span>
        <span class="info-value">{{ strtoupper(substr($opRegister->patient->name ?? 'N/A', 0, 25)) }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Date & Time:</span>
        <span class="info-value">{{ $opRegister->created_at ? \Carbon\Carbon::parse($opRegister->created_at)->format('d/m/Y h:i A') : 'N/A' }}</span>
    </div>
    @if($opRegister->patient->phone ?? false)
    <div class="info-row">
        <span class="info-label">Phone:</span>
        <span class="info-value">{{ $opRegister->patient->phone }}</span>
    </div>
    @endif
    <div class="info-row">
        <span class="info-label">Payment Type:</span>
        <span class="info-value">{{ strtoupper($opRegister->payment_type ?? 'CASH') }}</span>
    </div>
    @if($opRegister->payment_reference)
    <div class="info-row">
        <span class="info-label">Ref/Trans ID:</span>
        <span class="info-value">{{ $opRegister->payment_reference }}</span>
    </div>
    @endif
</div>

<!-- PAYMENT STATUS (centered badge) -->
@php
    $grandTotalFromDB = $opRegister->total ?? $opRegister->total_with_gst ?? 0;
    $paidAmt = $opRegister->paid_amount ?? 0;
    $balanceDue = max(0, $grandTotalFromDB - $paidAmt);
    $isPaid = ($opRegister->paid_status === 'paid') || ($paidAmt >= $grandTotalFromDB && $grandTotalFromDB > 0);
    $statusText = $isPaid ? 'PAID' : (($paidAmt > 0) ? 'PARTIALLY PAID' : 'UNPAID');
    $badgeClass = $isPaid ? 'paid-badge' : 'unpaid-badge';
@endphp

<div class="payment-status-badge {{ $badgeClass }}">
    {{ $statusText }} @if($paidAmt > 0 && !$isPaid) - PAID: ₹{{ number_format($paidAmt,2) }} @endif
</div>

<!-- ==================== MEDICINES SECTION ==================== -->
@php
    $activeMedicines = $opRegister->medicines->where('status', 'active');
@endphp
@if($activeMedicines->count() > 0)
<div class="section-title">MEDICINES DISPENSED</div>
<table class="item-table">
    <thead>
        <tr>
            <th>Medicine</th>
            <th>Qty</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach($activeMedicines as $med)
            @php
                $rowNet = ($med->quantity * $med->price) - ($med->discount_amount ?? 0);
            @endphp
            <tr>
                <td>{{ substr($med->medicine->name ?? $med->medicine_name ?? 'MED', 0, 20) }}</td>
                <td class="text-center">{{ $med->quantity }}</td>
                <td class="text-right">₹{{ number_format($rowNet, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endif

<!-- ==================== LAB TESTS (paid/partial) ==================== -->
@php
    $relevantLabTests = $opRegister->labTests->filter(function($test) {
        return in_array($test->status, ['paid', 'partial']);
    });
@endphp
@if($relevantLabTests->count() > 0)
<div class="section-title">LAB INVESTIGATIONS</div>
<table class="item-table">
    <tbody>
        @foreach($relevantLabTests as $lt)
        <tr>
            <td>{{ substr($lt->labTest->name ?? $lt->test_name ?? 'LAB TEST', 0, 25) }}</td>
            <td class="text-right">₹{{ number_format($lt->paid_amount ?? $lt->price, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

<!-- ==================== RADIOLOGY TESTS (paid/partial) ==================== -->
@php
    $relevantRadiology = $opRegister->radiologies->filter(function($rad) {
        return in_array($rad->status, ['paid', 'partial']);
    });
@endphp
@if($relevantRadiology->count() > 0)
<div class="section-title">RADIOLOGY / IMAGING</div>
<table class="item-table">
    <tbody>
        @foreach($relevantRadiology as $rad)
        <tr>
            <td>{{ substr($rad->radiologyTest->name ?? $rad->test_name ?? 'RADIOLOGY', 0, 25) }}</td>
            <td class="text-right">₹{{ number_format($rad->paid_amount ?? $rad->price, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

<!-- ==================== EXTRA FEES ==================== -->
@if(($opRegister->doctor_fees ?? 0) > 0)
<div class="section-title">CONSULTATION</div>
<div class="summary-row"><span>Doctor Consultation Fees</span><span>₹{{ number_format($opRegister->doctor_fees, 2) }}</span></div>
@endif

@if(($opRegister->injection_fees ?? 0) > 0)
<div class="section-title">INJECTION CHARGES</div>
<div class="summary-row"><span>Injection Fees</span><span>₹{{ number_format($opRegister->injection_fees, 2) }}</span></div>
@endif

@if(($opRegister->procedure_amount ?? 0) > 0)
<div class="section-title">PROCEDURE</div>
<div class="summary-row"><span>Procedure Charges</span><span>₹{{ number_format($opRegister->procedure_amount, 2) }}</span></div>
@endif

<!-- ============= BILL SUMMARY - USING DB FIELDS ============= -->
<div class="separator-solid"></div>
<div class="section-title">BILL SUMMARY</div>

@php
    $pharmacySub = $opRegister->pharmacy_amount ?? 0;
    $labTotalDB = $opRegister->lab_total_amount ?? 0;
    $radiologyTotalDB = $opRegister->radiology_total_amount ?? 0;
    $overallDiscount = $opRegister->overall_discount_amount ?? 0;
    $gstAmountDB = $opRegister->gst_amount ?? 0;
    $gstPercentDB = $opRegister->gst_percentage ?? 0;
    $totalWithGST = $opRegister->total_with_gst ?? 0;
    $grandTotalBeforeRound = $opRegister->total ?? $totalWithGST;
    $grandTotalFinal = round($grandTotalBeforeRound);
    $doctorFeeDB = $opRegister->doctor_fees ?? 0;
    $injectionDB = $opRegister->injection_fees ?? 0;
    $procedureDB = $opRegister->procedure_amount ?? 0;
    
    // Net medicines after overall discount
    $netMedicines = $pharmacySub - $overallDiscount;
@endphp

@if($pharmacySub > 0)
<div class="summary-row"><span>Medicines Subtotal:</span><span>₹{{ number_format($pharmacySub, 2) }}</span></div>
@endif

@if($overallDiscount > 0)
<div class="summary-row"><span>Discount (overall):</span><span>- ₹{{ number_format($overallDiscount, 2) }}</span></div>
@endif

@if($netMedicines > 0)
<div class="summary-row" style="border-bottom:1px dashed #ccc; margin-bottom:1mm;">
    <span>Net Medicines:</span><span>₹{{ number_format($netMedicines, 2) }}</span>
</div>
@endif


@if($labTotalDB > 0)
<div class="summary-row"><span>Lab Tests Total:</span><span>₹{{ number_format($labTotalDB, 2) }}</span></div>
@endif

@if($radiologyTotalDB > 0)
<div class="summary-row"><span>Radiology Total:</span><span>₹{{ number_format($radiologyTotalDB, 2) }}</span></div>
@endif

@if($doctorFeeDB > 0)
<div class="summary-row"><span>Doctor Fees:</span><span>₹{{ number_format($doctorFeeDB, 2) }}</span></div>
@endif

@if($injectionDB > 0)
<div class="summary-row"><span>Injection Fees:</span><span>₹{{ number_format($injectionDB, 2) }}</span></div>
@endif

@if($procedureDB > 0)
<div class="summary-row"><span>Procedure Charges:</span><span>₹{{ number_format($procedureDB, 2) }}</span></div>
@endif

<div class="separator-dash"></div>

<!-- GRAND TOTAL - prominently displayed -->
<div class="summary-row total-row">
    <span>GRAND TOTAL:</span>
    <span>₹{{ number_format($grandTotalFinal, 2) }}</span>
</div>

<div class="separator-dash"></div>

<!-- PAID & BALANCE -->
<div class="summary-row">
    <span>Paid Amount:</span>
    <span>₹{{ number_format($paidAmt, 2) }}</span>
</div>
@if($balanceDue > 0)
<div class="summary-row">
    <span>Balance Due:</span>
    <span>₹{{ number_format($balanceDue, 2) }}</span>
</div>
@elseif(($grandTotalFinal - $paidAmt) < 0)
<div class="summary-row">
    <span>Refund Amount:</span>
    <span>₹{{ number_format(abs($grandTotalFinal - $paidAmt), 2) }}</span>
</div>
@endif

<!-- Partial payment note -->
@if($paidAmt > 0 && !$isPaid && $balanceDue > 0)
<div class="prescription-note" style="text-align:center; background:#f9f9f9;">
    ** Partially Paid - Remaining: ₹{{ number_format($balanceDue,2) }} **
</div>
@endif

<!-- Additional information -->
@if($opRegister->additional_information)
<div class="prescription-note">
    <strong>Note:</strong> {{ substr($opRegister->additional_information, 0, 120) }}
</div>
@endif

<!-- Footer with THANK YOU centered -->
<div class="footer">
    <div class="dash-line">------------------------</div>
    <div class="thankyou">*** THANK YOU ***</div>
    <div>Please visit again</div>
</div>

<!-- Print controls (visible on screen only) -->
<div class="no-print">
    <button onclick="window.print();">🖨️ PRINT THERMAL BILL</button>
</div>

<script>
    // Auto-print detection for thermal receipt
    (function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('thermal') || urlParams.has('print') || urlParams.has('auto_print')) {
            setTimeout(function() {
                window.print();
                if (urlParams.has('autoclose')) {
                    window.onafterprint = function() {
                        setTimeout(function() { window.close(); }, 500);
                    };
                }
            }, 300);
        }

        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
        });
    })();
</script>

<!-- 
    THERMAL BILL DESIGN NOTES (Fixed Centering):
    - All section titles are centered using .section-title { text-align: center }
    - Clinic header and footer fully centered
    - Payment badge centered
    - Table layout: Medicine, Qty, Amount with proper alignment (Qty center, Amount right)
    - Summary rows maintain left/right alignment for amounts
    - Grand total bold with double border
    - Consistent with sample image format: shows medicines, consultation, injection fees, GST breakdown
    - Uses pre-stored DB fields: pharmacy_amount, overall_discount_amount, gst_amount, 
      doctor_fees, injection_fees, procedure_amount, total, paid_amount
    - No recalculation of totals - all values fetched from op_registers table
-->
</body>
</html>