<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>{{ $medicineSale->is_internal ? 'Internal Use Record' : 'Sale Invoice' }} - {{ $medicineSale->invoice_number }}</title>
    <style>
        /* THERMAL PRINTER OPTIMIZED - 58mm / 70mm compatible */
        @page {
            size: 70mm auto;
            margin: 0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Courier New', 'Lucida Console', monospace;
            font-size: 10px;
            line-height: 1.25;
        }

        body {
            width: 70mm;
            max-width: 70mm;
            margin: 0 auto;
            padding: 2mm 2mm 4mm 2mm;
            background: #fff;
            color: #000;
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

        /* utility classes */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
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

        /* header section */
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
        }
        .invoice-title {
            font-size: 12px;
            font-weight: bold;
            margin: 1mm 0;
        }

        /* info block */
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
            min-width: 35mm;
        }
        .info-value {
            text-align: right;
            flex: 1;
            word-break: break-word;
        }

        /* item table */
        .item-table {
            width: 100%;
            border-collapse: collapse;
            margin: 2mm 0;
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
        }
        .item-table td:last-child, 
        .item-table th:last-child {
            text-align: right;
        }
        .item-table td:first-child, 
        .item-table th:first-child {
            padding-left: 0;
        }

        /* summary rows */
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

        .payment-status-badge {
            text-align: center;
            font-weight: bold;
            margin: 2mm 0;
            padding: 1mm;
            border: 1px solid #000;
        }
        .paid-badge { background: #000; color: #fff; }
        .unpaid-badge { background: #fff; color: #000; border-width: 2px; }
        .partial-badge { background: #fff; color: #000; border: 2px solid #000; }

        .footer {
            text-align: center;
            border-top: 1px dashed #000;
            margin-top: 3mm;
            padding-top: 2mm;
        }
        .thankyou {
            font-weight: bold;
            margin: 1mm 0;
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
    </style>
</head>
<body>

<div class="clinic-header">
    <div class="clinic-name">RG HOSPITAL</div>
    <div>Pharmacy & Medical Store</div>
    <div class="separator-dash"></div>
    <div class="invoice-title">
        {{ $medicineSale->is_internal ? 'INTERNAL USE RECORD' : 'SALE INVOICE' }}
    </div>
</div>

<!-- Invoice Information -->
<div class="info-block">
    <div class="info-row">
        <span class="info-label">Invoice No:</span>
        <span class="info-value">{{ $medicineSale->invoice_number }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Date:</span>
        <span class="info-value">{{ \Carbon\Carbon::parse($medicineSale->sale_date)->format('d/m/Y h:i A') }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Type:</span>
        <span class="info-value">{{ ucfirst(str_replace('-', ' ', $medicineSale->type)) }}</span>
    </div>
    @if($medicineSale->is_internal)
        <div class="info-row">
            <span class="info-label">Department:</span>
            <span class="info-value">{{ $medicineSale->department ?? $medicineSale->department_name ?? 'N/A' }}</span>
        </div>
    @else
        <div class="info-row">
            <span class="info-label">Customer:</span>
            <span class="info-value">{{ $medicineSale->customer_name }}</span>
        </div>
        @if($medicineSale->customer_phone)
        <div class="info-row">
            <span class="info-label">Phone:</span>
            <span class="info-value">{{ $medicineSale->customer_phone }}</span>
        </div>
        @endif
        @if($medicineSale->customer_address)
        <div class="info-row">
            <span class="info-label">Address:</span>
            <span class="info-value">{{ substr($medicineSale->customer_address, 0, 30) }}</span>
        </div>
        @endif
    @endif
</div>

<!-- Payment Status (only for external sales) -->
@if(!$medicineSale->is_internal)
    @php
        $paidAmt = $medicineSale->paid_amount ?? 0;
        $grandTotalVal = $medicineSale->grand_total ?? 0;
        $dueAmt = $medicineSale->due_amount ?? max(0, $grandTotalVal - $paidAmt);
        $status = $medicineSale->payment_status ?? 'pending';
        $badgeClass = 'unpaid-badge';
        if ($status == 'paid' || ($paidAmt >= $grandTotalVal && $grandTotalVal > 0)) {
            $badgeClass = 'paid-badge';
            $status = 'PAID';
        } elseif ($status == 'partial' || ($paidAmt > 0 && $paidAmt < $grandTotalVal)) {
            $badgeClass = 'partial-badge';
            $status = 'PARTIAL';
        } else {
            $status = 'UNPAID';
        }
    @endphp
    <div class="payment-status-badge {{ $badgeClass }}">
        PAYMENT: {{ $status }}
        @if($paidAmt > 0 && $status != 'PAID')
            (Paid: ₹{{ number_format($paidAmt,2) }})
        @endif
    </div>
@endif

<!-- Items Table -->
<table class="item-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Medicine</th>
            <th>Qty</th>
            <th class="text-right">Price</th>
            <th class="text-right">Disc%</th>
            <th class="text-right">Final</th>
        </tr>
    </thead>
    <tbody>
        @foreach($medicineSale->items as $index => $item)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ substr($item->medicine->name ?? $item->medicine_name ?? 'MED', 0, 20) }}</td>
            <td class="text-center">{{ $item->quantity }}</td>
            <td class="text-right">₹{{ number_format($item->unit_price, 2) }}</td>
            <td class="text-right">{{ number_format($item->discount_percent ?? 0, 1) }}%</td>
            <td class="text-right">₹{{ number_format($item->final_amount, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- Financial Summary Section -->
<div class="separator-dash"></div>
<div class="summary-row">
    <span>Sub Total:</span>
    <span>₹{{ number_format($medicineSale->sub_total ?? 0, 2) }}</span>
</div>

@if(($medicineSale->overall_discount_amount ?? 0) > 0)
<div class="summary-row">
    <span>Overall Discount:</span>
    <span>- ₹{{ number_format($medicineSale->overall_discount_amount, 2) }}</span>
</div>
@endif

@if(($medicineSale->injection_fees ?? 0) > 0)
<div class="summary-row">
    <span>Injection Fees:</span>
    <span>₹{{ number_format($medicineSale->injection_fees, 2) }}</span>
</div>
@endif

@if(($medicineSale->procedure_fees ?? 0) > 0)
<div class="summary-row">
    <span>Procedure Fees:</span>
    <span>₹{{ number_format($medicineSale->procedure_fees, 2) }}</span>
</div>
@endif

<div class="separator-dash"></div>

<!-- Grand Total -->
<div class="summary-row total-row">
    <span>{{ $medicineSale->is_internal ? 'TOTAL COST' : 'GRAND TOTAL' }}:</span>
    <span>₹{{ number_format($medicineSale->grand_total ?? 0, 2) }}</span>
</div>

@if(!$medicineSale->is_internal)
    <div class="separator-dash"></div>
    <div class="summary-row">
        <span>Paid Amount:</span>
        <span>₹{{ number_format($medicineSale->paid_amount ?? 0, 2) }}</span>
    </div>
    <div class="summary-row">
        <span>Due Amount:</span>
        <span>₹{{ number_format($medicineSale->due_amount ?? 0, 2) }}</span>
    </div>
@endif

<!-- Notes Section -->
@if($medicineSale->notes)
    <div class="prescription-note" style="border:1px dashed #000; padding:1.5mm; margin:2mm 0;">
        <strong>Note:</strong> {{ substr($medicineSale->notes, 0, 100) }}
    </div>
@endif

<!-- Footer -->
<div class="footer">
    <div class="separator-dash"></div>
    <div class="thankyou">*** THANK YOU ***</div>
</div>
<!-- Print Controls -->
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

        // Keyboard shortcut for print
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
        });
    })();
</script>

<!-- 
    THERMAL BILL NOTES:
    - Designed for 70mm thermal paper (compatible with 58mm with scaling)
    - Uses only database fields from medicine_sales table:
      invoice_number, sale_date, type, customer_name, department, sub_total, 
      total_discount, overall_discount_amount, total_tax, tax_percentage,
      injection_fees, procedure_fees, grand_total, paid_amount, due_amount,
      payment_status, notes
    - GST amount is displayed from total_tax field (no recalculation)
    - All calculations are pre-stored, no runtime math on invoice
-->
</body>
</html>