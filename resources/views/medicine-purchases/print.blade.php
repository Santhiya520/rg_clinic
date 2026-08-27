<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Invoice - {{ $medicinePurchase->invoice_number }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @page {
            size: A4;
            margin: 10mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 11px;
            line-height: 1.4;
        }

        body {
            background-color: #ffffff;
            padding: 0;
            color: #333;
        }

        .print-container {
            max-width: 210mm;
            margin: 0 auto;
            background: white;
            padding: 10px;
        }

        .header {
            text-align: center;
            padding-bottom: 8px;
            margin-bottom: 10px;
            border-bottom: 2px solid #2c3e50;
        }

        .invoice-title {
            color: #2c3e50;
            font-size: 16px;
            font-weight: 700;
            margin: 8px 0;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .invoice-info {
            background: #f8f9fa;
            border-radius: 3px;
            padding: 10px;
            margin-bottom: 12px;
            border: 1px solid #dee2e6;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
            border-bottom: 1px dashed #ced4da;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #2c3e50;
        }

        .info-value {
            font-weight: 500;
            color: #34495e;
        }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-paid {
            background: #27ae60;
            color: white;
        }

        .badge-partial {
            background: #f39c12;
            color: white;
        }

        .badge-pending {
            background: #e74c3c;
            color: white;
        }

        .section-title {
            color: #2c3e50;
            font-size: 12px;
            font-weight: 600;
            margin: 12px 0 6px 0;
            padding-bottom: 4px;
            border-bottom: 1px solid #dee2e6;
        }

        .table-container {
            margin-bottom: 10px;
            overflow: hidden;
            page-break-inside: avoid;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        th {
            background: #2c3e50;
            color: white;
            padding: 6px 4px;
            text-align: left;
            font-weight: 600;
            border: 1px solid #1a252f;
        }

        td {
            padding: 4px;
            border: 1px solid #dee2e6;
            vertical-align: top;
        }

        tr:nth-child(even) {
            background: #f8f9fa;
        }

        .amount-section {
            background: #f8f9fa;
            border-radius: 3px;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #dee2e6;
        }

        .amount-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
        }

        .amount-item {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
        }

        .amount-label {
            font-weight: 600;
            color: #2c3e50;
        }

        .amount-value {
            font-weight: 600;
            color: #2c3e50;
        }

        .amount-total {
            grid-column: 1 / -1;
            border-top: 2px solid #2c3e50;
            padding-top: 8px;
            margin-top: 8px;
            font-size: 12px;
        }

        .footer {
            text-align: center;
            padding-top: 10px;
            color: #5d6d7e;
            font-size: 9px;
            border-top: 1px solid #dee2e6;
            margin-top: 10px;
        }

        .print-actions {
            text-align: center;
            margin-top: 15px;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 3px;
        }

        .print-btn {
            background: #2c3e50;
            color: white;
            border: none;
            padding: 6px 15px;
            border-radius: 2px;
            font-size: 10px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: background 0.3s;
        }

        .print-btn:hover {
            background: #1a252f;
        }

        .close-btn {
            background: #6c757d;
            color: white;
            border: none;
            padding: 6px 15px;
            border-radius: 2px;
            font-size: 10px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-left: 8px;
        }

        .close-btn:hover {
            background: #5a6268;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .bold {
            font-weight: bold;
        }

        .mb-2 {
            margin-bottom: 2px;
        }

        .mt-2 {
            margin-top: 2px;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .print-container {
                box-shadow: none;
                padding: 0;
                margin: 0;
                max-width: 100%;
            }

            .print-actions {
                display: none;
            }

            table, .amount-section, .invoice-info {
                page-break-inside: avoid;
            }
        }

        @media (max-width: 768px) {
            .print-container {
                padding: 8px;
            }

        }
    </style>
</head>
<body>
    <div class="print-container">
        <!-- Header -->
        <div class="header">
            <img src="{{ asset('images/rg-banner.png') }}" style="max-width: 100%; height: auto;">
        </div>

        <!-- Invoice Title -->
        <div class="invoice-title">
            MEDICINE PURCHASE INVOICE
        </div>

        <!-- Invoice Information -->
        <div class="invoice-info">
            <div class="info-grid">
                <div class="info-row">
                    <span class="info-label">Invoice Number:</span>
                    <span class="info-value">{{ $medicinePurchase->invoice_number }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Purchase Date:</span>
                    <span class="info-value">{{ $medicinePurchase->purchase_date->format('d/m/Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Supplier Name:</span>
                    <span class="info-value">{{ $medicinePurchase->supplier_name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Supplier Phone:</span>
                    <span class="info-value">{{ $medicinePurchase->supplier_phone ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Payment Status:</span>
                    <span class="info-value">
                        <span class="badge badge-{{ $medicinePurchase->payment_status }}">
                            {{ ucfirst($medicinePurchase->payment_status) }}
                        </span>
                    </span>
                </div>
            </div>
        </div>

        <!-- Purchase Items -->
        <div class="table-container">
            <div class="section-title">Purchase Items</div>
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 30%;">Medicine Details</th>
                        <th style="width: 12%;">Batch No</th>
                        <th style="width: 12%;">Expiry Date</th>
                        <th style="width: 10%;">Quantity</th>
                        <th style="width: 15%;">Purchase Price</th>
                        <th style="width: 16%;">Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($medicinePurchase->items as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            <div class="bold mb-2">{{ $item->medicine->name }}</div>
                            <div class="text-muted" style="font-size: 9px;">
                                {{ $item->medicine->generic_name }}
                                @if($item->medicine->category)
                                | {{ $item->medicine->category }}
                                @endif
                            </div>
                        </td>
                        <td>{{ $item->batch_number }}</td>
                        <td>{{ $item->expiry_date->format('d/m/Y') }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">₹{{ number_format($item->purchase_price, 2) }}</td>
                        <td class="text-right">₹{{ number_format($item->total_amount, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Amount Summary -->
        <div class="amount-section">
            <div class="amount-grid">
                <div class="amount-item">
                    <span class="amount-label">Total Items:</span>
                    <span class="amount-value">{{ $medicinePurchase->items->count() }}</span>
                </div>
                <div class="amount-item">
                    <span class="amount-label">Total Quantity:</span>
                    <span class="amount-value">{{ $medicinePurchase->items->sum('quantity') }}</span>
                </div>
                <div class="amount-item amount-total">
                    <span class="amount-label">Sub Total:</span>
                    <span class="amount-value">₹{{ number_format($medicinePurchase->total_amount, 2) }}</span>
                </div>
                <div class="amount-item">
                    <span class="amount-label">Tax/GST:</span>
                    <span class="amount-value">₹0.00</span>
                </div>
                <div class="amount-item">
                    <span class="amount-label">Discount:</span>
                    <span class="amount-value">₹0.00</span>
                </div>
                <div class="amount-item">
                    <span class="amount-label">Other Charges:</span>
                    <span class="amount-value">₹0.00</span>
                </div>
                <div class="amount-item amount-total">
                    <span class="amount-label">Grand Total:</span>
                    <span class="amount-value">₹{{ number_format($medicinePurchase->total_amount, 2) }}</span>
                </div>
                <div class="amount-item">
                    <span class="amount-label">Paid Amount:</span>
                    <span class="amount-value">₹{{ number_format($medicinePurchase->paid_amount, 2) }}</span>
                </div>
                <div class="amount-item">
                    <span class="amount-label">Due Amount:</span>
                    <span class="amount-value">₹{{ number_format($medicinePurchase->due_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Notes Section -->
        @if($medicinePurchase->notes)
        <div style="margin-top: 10px; padding: 8px; background: #e8f4fd; border-radius: 3px; border-left: 3px solid #3498db;">
            <div class="bold mb-2" style="color: #2c3e50;">Notes:</div>
            <div style="color: #5d6d7e; font-size: 10px;">{{ $medicinePurchase->notes }}</div>
        </div>
        @endif


        <!-- Print Actions (Hidden when printing) -->
        <div class="print-actions">
            <button onclick="window.print()" class="print-btn">
                <i class="fas fa-print"></i> Print Invoice
            </button>
        </div>
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
