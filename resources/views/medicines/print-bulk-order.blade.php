<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulk Order: {{ $bulkOrder->invoice_number }}</title>
    <style>
        @page { size: A4; margin: 20mm; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 14px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 28px;
        }

        .header .subtitle {
            color: #666;
            font-size: 16px;
            margin-top: 5px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .info-box {
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .info-box h3 {
            margin-top: 0;
            color: #007bff;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        .info-row {
            display: flex;
            margin-bottom: 8px;
        }

        .info-label {
            font-weight: 600;
            min-width: 120px;
            color: #555;
        }

        .info-value {
            flex: 1;
        }

        .table-container {
            margin: 30px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 12px 8px;
            text-align: left;
            font-weight: 600;
            color: #495057;
        }

        td {
            border: 1px solid #dee2e6;
            padding: 10px 8px;
        }

        .total-row {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-draft { background-color: #ffc107; color: #212529; }
        .badge-billed { background-color: #28a745; color: white; }
        .badge-paid { background-color: #28a745; color: white; }
        .badge-due { background-color: #dc3545; color: white; }
        .badge-partial { background-color: #fd7e14; color: white; }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 12px;
        }

        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            z-index: 1000;
        }

        @media print {
            .print-btn {
                display: none;
            }

            body {
                padding: 0;
            }
        }

        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .hospital-banner {
            width: 100%;
            height: auto;
            margin-bottom: 10px;
        }

        .document-title {
            text-align: center;
            margin: 15px 0;
        }

        .document-title h2 {
            color: #2c3e50;
            margin: 0;
            text-transform: uppercase;
            font-size: 24px;
        }

        .document-subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">🖨️ Print</button>

    <div class="container">
        <!-- Hospital Banner -->
        <div class="logo-container">
            <img src="{{ asset('images/rg-banner.png') }}" alt="Hospital Banner" class="hospital-banner">
        </div>

        <!-- Document Title -->
        <div class="document-title">
            <h2>BULK ORDER DOCUMENT</h2>
        </div>

        <!-- Document Details -->
        <div class="document-subtitle">
            Order No: <strong>{{ $bulkOrder->invoice_number }}</strong>
            | Date: <strong>{{ $bulkOrder->purchase_date->format('d M, Y') }}</strong>
            | Generated: <strong>{{ now()->format('d/m/Y h:i A') }}</strong>
        </div>

        <!-- Information Grid -->
        <div class="info-grid">
            <div class="info-box">
                <h3>Supplier Details</h3>
                <div class="info-row">
                    <div class="info-label">Name:</div>
                    <div class="info-value">{{ $bulkOrder->supplier_name_decoded }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Contact Person:</div>
                    <div class="info-value">{{ \App\Helpers\StringHelper::decodeQuotes($bulkOrder->supplier->contact_person ?? 'N/A') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Phone:</div>
                    <div class="info-value">{{ $bulkOrder->supplier_phone ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Address:</div>
                    <div class="info-value">{{ \App\Helpers\StringHelper::decodeQuotes($bulkOrder->supplier_address ?? 'N/A') }}</div>
                </div>
            </div>

            <div class="info-box">
                <h3>Order Details</h3>
                @if($bulkOrder->total_amount > 0)
                <div class="info-row">
                    <div class="info-label">Payment Status:</div>
                    <div class="info-value">
                        <span class="status-badge badge-{{ $bulkOrder->payment_status }}">
                            {{ ucfirst($bulkOrder->payment_status) }}
                        </span>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Total Amount:</div>
                    <div class="info-value">₹ {{ number_format($bulkOrder->total_amount, 2) }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Paid Amount:</div>
                    <div class="info-value">₹ {{ number_format($bulkOrder->paid_amount, 2) }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Due Amount:</div>
                    <div class="info-value">₹ {{ number_format($bulkOrder->due_amount, 2) }}</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Order Items -->
        <div class="table-container">
            <h3>Order Items ({{ $bulkOrder->items->count() }})</h3>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Medicine Name</th>
                        <th>Category</th>
                        <th>Quantity</th>
                            <th>Batch No</th>
                            <th>Expiry Date</th>
                        @if($bulkOrder->total_amount > 0)
                            <th>Unit Price</th>
                            <th>Total</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($bulkOrder->items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->medicine->decoded_name ?? 'N/A' }}</td>
                            <td>{{ $item->medicine->category ?? 'N/A' }}</td>
                            <td>{{ $item->quantity }}</td>
                                <td>{{ $item->batch_number }}</td>
                                <td>{{ $item->expiry_date ? \Carbon\Carbon::parse($item->expiry_date)->format('d M, Y') : 'N/A' }}</td>
                            @if($bulkOrder->total_amount > 0)
                                <td>₹ {{ number_format($item->purchase_price, 2) }}</td>
                                <td>₹ {{ number_format($item->total_amount, 2) }}</td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
                @if($bulkOrder->total_amount > 0)
                    <tfoot>
                        <tr class="total-row">
                            <td colspan="6"></td>
                            <td><strong>Grand Total:</strong></td>
                            <td><strong>₹ {{ number_format($bulkOrder->total_amount, 2) }}</strong></td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>

        <!-- Notes -->
        @if($bulkOrder->notes)
        <div class="info-box">
            <h3>Additional Notes</h3>
            <p>{{ $bulkOrder->notes }}</p>
        </div>
        @endif

        <!-- Signature Area -->
        <div style="margin-top: 50px; padding-top: 20px; border-top: 1px solid #ddd;">
            <div style="display: flex; justify-content: space-between;">
                <div style="text-align: center;">
                    <div style="border-top: 1px solid #333; width: 200px; margin: 20px auto 5px;"></div>
                    <div style="font-size: 12px; color: #666;"> Signature</div>
                </div>
            </div>
        </div>

    </div>

    <script>
        // Auto-print option (optional)
        @if(request()->get('auto_print'))
        window.onload = function() {
            window.print();
        }
        @endif

        // Ctrl+P shortcut
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
        });

        // Auto-close after printing
        window.onafterprint = function() {
            setTimeout(function() {
                // Uncomment to auto-close
                // window.close();
            }, 1000);
        };
    </script>
</body>
</html>
