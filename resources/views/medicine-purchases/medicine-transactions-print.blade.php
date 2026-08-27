<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Report - {{ $medicine->name }}</title>
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

        .report-title {
            color: #2c3e50;
            font-size: 16px;
            font-weight: 700;
            margin: 8px 0;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .medicine-info {
            background: #f8f9fa;
            border-radius: 3px;
            padding: 10px;
            margin-bottom: 12px;
            border: 1px solid #dee2e6;
        }

        .medicine-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
        }

        .medicine-item {
            display: flex;
            flex-direction: column;
        }

        .medicine-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 2px;
            font-size: 10px;
        }

        .medicine-value {
            color: #34495e;
            padding: 3px 0;
        }

        .stock-summary {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 15px;
        }

        .summary-card {
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 10px;
            text-align: center;
            background: #f8f9fa;
        }

        .summary-title {
            color: #2c3e50;
            font-size: 11px;
            font-weight: 600;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .summary-value {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 3px;
        }

        .summary-subtitle {
            font-size: 10px;
            color: #6c757d;
        }

        .stock-card { border-top: 3px solid #3498db; }
        .stock-card .summary-value { color: #3498db; }

        .purchase-card { border-top: 3px solid #27ae60; }
        .purchase-card .summary-value { color: #27ae60; }

        .sale-card { border-top: 3px solid #e74c3c; }
        .sale-card .summary-value { color: #e74c3c; }

        .value-card { border-top: 3px solid #8e44ad; }
        .value-card .summary-value { color: #8e44ad; }

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

        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: 600;
        }

        .badge-purchase {
            background: #27ae60;
            color: white;
        }

        .badge-sale {
            background: #e74c3c;
            color: white;
        }

        .total-section {
            background: #f8f9fa;
            border-radius: 3px;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #dee2e6;
        }

        .total-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 6px;
        }

        .total-item {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
            border-bottom: 1px dashed #ced4da;
        }

        .total-item:last-child {
            border-bottom: none;
        }

        .total-item.full-width {
            grid-column: 1 / -1;
            border-top: 2px solid #2c3e50;
            padding-top: 6px;
            margin-top: 6px;
            font-size: 12px;
        }

        .total-label {
            font-weight: 600;
            color: #2c3e50;
        }

        .total-value {
            font-weight: 600;
            color: #2c3e50;
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

        .bold {
            font-weight: bold;
        }

        .stock-indicator {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
        }

        .stock-good { background: #d4edda; color: #155724; }
        .stock-low { background: #fff3cd; color: #856404; }
        .stock-out { background: #f8d7da; color: #721c24; }

        .running-stock {
            font-size: 9px;
            color: #6c757d;
            display: block;
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

            table, .total-section, .medicine-info, .stock-summary {
                page-break-inside: avoid;
            }

            .section-break {
                page-break-before: always;
            }
        }

        @media (max-width: 768px) {
            .print-container {
                padding: 8px;
            }

            .medicine-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .stock-summary {
                grid-template-columns: 1fr;
            }

            .total-grid {
                grid-template-columns: 1fr;
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

        <!-- Report Title -->
        <div class="report-title">
            MEDICINE TRANSACTION REPORT
        </div>

        <!-- Medicine Information -->
        <div class="medicine-info">
            <div class="medicine-grid">
                <div class="medicine-item">
                    <span class="medicine-label">Medicine Name</span>
                    <span class="medicine-value bold">{{ $medicine->name }}</span>
                </div>
                <div class="medicine-item">
                    <span class="medicine-label">Generic Name</span>
                    <span class="medicine-value">{{ $medicine->generic_name ?? 'N/A' }}</span>
                </div>
                <div class="medicine-item">
                    <span class="medicine-label">Category</span>
                    <span class="medicine-value">{{ $medicine->category ?? 'N/A' }}</span>
                </div>
                <div class="medicine-item">
                    <span class="medicine-label">Manufacturer</span>
                    <span class="medicine-value">{{ $medicine->manufacturer ?? 'N/A' }}</span>
                </div>
                <div class="medicine-item">
                    <span class="medicine-label">Current Stock</span>
                    <span class="medicine-value">
                        <span class="stock-indicator stock-{{ $medicine->stock > 20 ? 'good' : ($medicine->stock > 0 ? 'low' : 'out') }}">
                            {{ $medicine->stock }} units
                        </span>
                    </span>
                </div>
                <div class="medicine-item">
                    <span class="medicine-label">Unit Type</span>
                    <span class="medicine-value">{{ $medicine->unit_type ?? 'N/A' }}</span>
                </div>
                <div class="medicine-item">
                    <span class="medicine-label">Purchase Price</span>
                    <span class="medicine-value">₹{{ $medicine->purchase_price > 0 ? number_format($medicine->purchase_price, 2) : 'N/A' }}</span>
                </div>
                <div class="medicine-item">
                    <span class="medicine-label">Selling Price</span>
                    <span class="medicine-value">₹{{ number_format($medicine->selling_price, 2) }}</span>
                </div>
                @if($medicine->description)
                <div class="medicine-item" style="grid-column: span 2;">
                    <span class="medicine-label">Description</span>
                    <span class="medicine-value">{{ $medicine->description }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Stock Summary -->
        @php
            // Calculate totals from transactions
            $purchases = collect($transactions)->where('transaction_type', 'PURCHASE');
            $sales = collect($transactions)->where('transaction_type', 'SALE');

            $totalPurchased = $purchases->sum('quantity');
            $totalSold = $sales->sum('quantity');
            $purchaseAmount = $purchases->sum(function($t) {
                return $t->quantity * $t->purchase_price;
            });
            $saleAmount = $sales->sum(function($t) {
                return $t->quantity * $t->purchase_price;
            });
            $avgPurchasePrice = $purchases->count() > 0 ? $purchases->avg('purchase_price') : 0;
            $avgSalePrice = $sales->count() > 0 ? $sales->avg('purchase_price') : 0;
            $currentStockValue = $medicine->stock * $avgPurchasePrice;
        @endphp

        <!-- Transaction History -->
        <div class="table-container">
            <div class="section-title">Transaction History ({{ count($transactions) }} records)</div>
            <table>
                <thead>
                    <tr>
                        <th style="width: 8%;">#</th>
                        <th style="width: 12%;">Date</th>
                        <th style="width: 10%;">Type</th>
                        <th style="width: 15%;">Reference</th>
                        <th style="width: 12%;">Batch No</th>
                        <th style="width: 10%;">Expiry</th>
                        <th style="width: 10%;">Quantity</th>
                        <th style="width: 11%;">Price (₹)</th>
                        <th style="width: 12%;">Total (₹)</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $runningStock = $medicine->stock;
                        $runningValue = $currentStockValue;
                    @endphp
                    @foreach($transactions as $index => $transaction)
                    @php
                        $isPurchase = $transaction->transaction_type == 'PURCHASE';
                        $quantity = $transaction->quantity;
                        $totalAmount = $quantity * $transaction->purchase_price;

                        // Calculate running stock and value (reverse chronological)
                        if($isPurchase) {
                            $runningStock -= $quantity;
                            $runningValue -= $totalAmount;
                        } else {
                            $runningStock += $quantity;
                            $runningValue += $totalAmount;
                        }
                    @endphp
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            @if($transaction->transaction_date)
                                {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d/m/Y') }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-{{ strtolower($transaction->transaction_type) }}">
                                {{ $transaction->transaction_type }}
                            </span>
                        </td>
                        <td>
                            {{ $transaction->reference ?? '-' }}
                        </td>
                        <td>
                            @if($transaction->batch_number && $transaction->batch_number != 'NULL')
                                {{ $transaction->batch_number }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($transaction->expiry_date && $transaction->expiry_date != 'NULL')
                                {{ \Carbon\Carbon::parse($transaction->expiry_date)->format('M Y') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-center">
                            <span style="padding: 2px 6px" class="{{ $isPurchase ? 'badge-purchase' : 'badge-sale' }}">
                                {{ $isPurchase ? '+' : '-' }}{{ $quantity }}
                            </span>
                            <span class="running-stock">Stock: {{ $runningStock }}</span>
                        </td>
                        <td class="text-right">{{ number_format($transaction->purchase_price, 2) }}</td>
                        <td class="text-right">{{ number_format($totalAmount, 2) }}</td>
                    </tr>
                    @endforeach
                    @if(empty($transactions))
                    <tr>
                        <td colspan="9" class="text-center py-4">
                            <div class="text-muted">No transactions found for this medicine.</div>
                        </td>
                    </tr>
                    @endif
                </tbody>
                @if(!empty($transactions))
                <tfoot>
                    <tr style="background: #e3f2fd;">
                        <td colspan="6" class="text-right"><strong>Summary:</strong></td>
                        <td class="text-center">
                            <strong>+{{ $totalPurchased }} / -{{ $totalSold }}</strong>
                        </td>
                        <td class="text-right">
                            <strong>₹{{ number_format($avgPurchasePrice, 2) }} / ₹{{ number_format($avgSalePrice, 2) }}</strong>
                        </td>
                        <td class="text-right">
                            <strong>₹{{ number_format($purchaseAmount + $saleAmount, 2) }}</strong>
                        </td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>

        <!-- Summary Statistics -->
        @if(!empty($transactions))
        <div class="total-section">
            <div class="section-title">Transaction Summary</div>
            <div class="total-grid">
                <div class="total-item">
                    <span class="total-label">Total Purchase Transactions</span>
                    <span class="total-value">{{ $purchases->count() }}</span>
                </div>
                <div class="total-item">
                    <span class="total-label">Total Sale Transactions</span>
                    <span class="total-value">{{ $sales->count() }}</span>
                </div>
                <div class="total-item">
                    <span class="total-label">Total Purchased Quantity</span>
                    <span class="total-value">{{ $totalPurchased }} units</span>
                </div>
                <div class="total-item">
                    <span class="total-label">Total Sold Quantity</span>
                    <span class="total-value">{{ $totalSold }} units</span>
                </div>
                <div class="total-item">
                    <span class="total-label">Average Purchase Price</span>
                    <span class="total-value">₹{{ number_format($avgPurchasePrice, 2) }}</span>
                </div>
                <div class="total-item">
                    <span class="total-label">Average Sale Price</span>
                    <span class="total-value">₹{{ number_format($avgSalePrice, 2) }}</span>
                </div>
                <div class="total-item">
                    <span class="total-label">Total Purchase Value</span>
                    <span class="total-value">₹{{ number_format($purchaseAmount, 2) }}</span>
                </div>
                <div class="total-item">
                    <span class="total-label">Total Sale Value</span>
                    <span class="total-value">₹{{ number_format($saleAmount, 2) }}</span>
                </div>
                <div class="total-item full-width">
                    <span class="total-label">Current Stock Value (estimated)</span>
                    <span class="total-value">₹{{ number_format($currentStockValue, 2) }}</span>
                </div>
            </div>
        </div>
        @endif


        <!-- Print Actions (Hidden when printing) -->
        <div class="print-actions">
            <button onclick="window.print()" class="print-btn">
                <i class="fas fa-print"></i> Print Report
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
