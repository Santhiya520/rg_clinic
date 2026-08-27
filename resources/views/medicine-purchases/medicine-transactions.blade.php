@extends('layouts.app')

@section('title', $medicine->name . ' - Transactions')
@section('page-title', $medicine->name . ' - Transaction History')

@section('content')
<div class="nk-block nk-block-lg">
    <div class="card card-preview">
        <div class="card-inner">
            <!-- Header Section -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5 class="title">{{ $medicine->name }} - Transaction History</h5>
                    <p class="text-soft">
                        Category: {{ $medicine->category }} |
                        Purchase Price: ₹{{ $medicine->purchase_price > 0 ? number_format($medicine->purchase_price, 2) : 'N/A' }} |
                        Current Stock: <span style="padding: 3px 8px"
                            class="badge bg-{{ $medicine->stock > 20 ? 'success' : ($medicine->stock > 0 ? 'warning' : 'danger') }}">
                            {{ $medicine->stock }} units
                        </span>
                    </p>
                    @if ($medicine->description)
                        <p class="text-muted">{{ $medicine->description }}</p>
                    @endif
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('medicine-purchases.medicine-transactions.print', $medicine->id) }}"
                        target="_blank" class="btn btn-primary" style="border-radius: 5px">
                        <em class="icon ni ni-printer"></em> &nbsp; Print Report
                    </a>
                </div>
            </div>

            <!-- Transaction Summary Cards -->
            @php
                $purchases = collect($transactions)->where('transaction_type', 'PURCHASE');
                $sales = collect($transactions)->where('transaction_type', 'SALE');

                $totalPurchased = $purchases->sum('quantity');
                $totalSold = $sales->sum('quantity');
                $purchaseValue = $purchases->sum(function($t) {
                    return $t->quantity * $t->purchase_price;
                });
                $saleValue = $sales->sum(function($t) {
                    return $t->quantity * $t->purchase_price;
                });
                $avgPurchasePrice = $purchases->count() > 0 ? $purchases->avg('purchase_price') : 0;
                $avgSalePrice = $sales->count() > 0 ? $sales->avg('purchase_price') : 0;
            @endphp

            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-light-success">
                        <div class="card-body text-center">
                            <h5 class="text-success">{{ $totalPurchased }}</h5>
                            <p class="mb-0">Total Purchased</p>
                            <small>{{ $purchases->count() }} transactions</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-light-warning">
                        <div class="card-body text-center">
                            <h5 class="text-warning">{{ $totalSold }}</h5>
                            <p class="mb-0">Total Sold</p>
                            <small>{{ $sales->count() }} transactions</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-light-info">
                        <div class="card-body text-center">
                            <h5 class="text-info">₹ {{ number_format($purchaseValue, 2) }}</h5>
                            <p class="mb-0">Purchase Value</p>
                            <small>Avg: ₹{{ number_format($avgPurchasePrice, 2) }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-light-primary">
                        <div class="card-body text-center">
                            <h5 class="text-primary">₹ {{ number_format($saleValue, 2) }}</h5>
                            <p class="mb-0">Sale Value</p>
                            <small>Avg: ₹{{ number_format($avgSalePrice, 2) }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaction History -->
            <table class="datatable-init table" id="transactionTable">
                <thead>
                    <tr>
                        <th width="12%">Date</th>
                        <th width="10%">Type</th>
                        <th width="18%">Reference</th>
                        <th width="8%">Quantity</th>
                        <th width="12%">Price (₹)</th>
                        <th width="13%">Total (₹)</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $runningStock = $medicine->stock;
                    @endphp
                    @forelse($transactions as $transaction)
                        @php
                            $isPurchase = $transaction->transaction_type == 'PURCHASE';
                            $quantity = $transaction->quantity;
                            $total = $quantity * $transaction->purchase_price;

                            // Calculate running stock (reverse chronological - starting from current stock)
                            if ($isPurchase) {
                                $runningStock -= $quantity; // Remove purchase to get previous stock
                            } else {
                                $runningStock += $quantity; // Add sale to get previous stock
                            }
                        @endphp
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d M, Y') }}</td>
                            <td>
                                <span style="padding: 3px 8px"
                                    class="badge bg-{{ $transaction->transaction_type == 'PURCHASE' ? 'success' : 'warning' }}">
                                    {{ $transaction->transaction_type }}
                                </span>
                            </td>
                            <td>
                                <small>{{ $transaction->reference }}</small>
                            </td>
                            <td class="text-center">
                                <span style="padding: 3px 8px"
                                    class="badge bg-{{ $transaction->transaction_type == 'PURCHASE' ? 'success' : 'danger' }}">
                                    {{ $isPurchase ? '+' : '-' }}{{ $quantity }}
                                </span>
                                <br>
                                <small class="text-muted">Balance: {{ $runningStock }}</small>
                            </td>
                            <td class="text-end">₹ {{ number_format($transaction->purchase_price, 2) }}</td>
                            <td class="text-end">
                                <strong>₹{{ number_format($total, 2) }}</strong>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <em class="icon ni ni-file-text fs-2"></em>
                                <p class="mt-2">No transactions found for this medicine.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($transactions->count() > 0)
                <tfoot>
                    <tr class="table-primary">
                        <td colspan="5" class="text-end"><strong>Totals:</strong></td>
                        <td class="text-end">
                            <strong>₹{{ number_format($purchaseValue + $saleValue, 2) }}</strong>
                        </td>
                    </tr>
                </tfoot>
                @endif
            </table>

            <!-- Summary Statistics -->
            @if($transactions->count() > 0)
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Transaction Summary</h6>
                            <div class="row">
                                <div class="col-md-3">
                                    <p><strong>Total Transactions:</strong> {{ $transactions->count() }}</p>
                                    <p><strong>Purchase Transactions:</strong> {{ $purchases->count() }}</p>
                                    <p><strong>Sale Transactions:</strong> {{ $sales->count() }}</p>
                                </div>
                                <div class="col-md-3">
                                    <p><strong>Total Purchased Quantity:</strong> {{ $totalPurchased }}</p>
                                    <p><strong>Total Sold Quantity:</strong> {{ $totalSold }}</p>
                                    <p><strong>Current Stock:</strong> {{ $medicine->stock }}</p>
                                </div>
                                <div class="col-md-3">
                                    <p><strong>Avg. Purchase Price:</strong> ₹{{ number_format($avgPurchasePrice, 2) }}</p>
                                    <p><strong>Avg. Sale Price:</strong> ₹{{ number_format($avgSalePrice, 2) }}</p>
                                    <p><strong>Profit Margin:</strong>
                                        @php
                                            $profitMargin = $avgSalePrice > 0 ? (($avgSalePrice - $avgPurchasePrice) / $avgPurchasePrice * 100) : 0;
                                        @endphp
                                        <span class="{{ $profitMargin >= 0 ? 'text-success' : 'text-danger' }}">
                                            {{ number_format($profitMargin, 2) }}%
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-3">
                                    <p><strong>Total Purchase Value:</strong> ₹{{ number_format($purchaseValue, 2) }}</p>
                                    <p><strong>Total Sale Value:</strong> ₹{{ number_format($saleValue, 2) }}</p>
                                    <p><strong>Current Stock Value:</strong> ₹{{ number_format($medicine->stock * $avgPurchasePrice, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
    <style>
        @media print {
            .btn, .card.bg-light-*, .card .card-body small {
                display: none !important;
            }
            .card {
                border: none !important;
                box-shadow: none !important;
            }
        }

        .datatable-init table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .badge {
            font-size: 0.75em;
        }
    </style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#transactionTable').DataTable({
            responsive: true,
            order: [[0, 'desc']], // Sort by date descending
            pageLength: 25,
            language: {
                search: "Search transactions:",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ transactions",
                paginate: {
                    next: '<i class="ni ni-chevron-right"></i>',
                    previous: '<i class="ni ni-chevron-left"></i>'
                }
            },
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                 '<"row"<"col-sm-12"tr>>' +
                 '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            columnDefs: [
                { orderable: false, targets: [1, 2, 3, 4] } // Disable sorting on Type, Reference, Batch, Expiry columns
            ]
        });
    });
</script>
@endpush
