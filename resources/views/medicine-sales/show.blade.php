@extends('layouts.app')

@section('title', 'Sale Details')
@section('page-title', 'Sale Details')

@section('content')
<div class="nk-block nk-block-lg">
    <div class="card card-preview">
        <div class="card-inner">
            <!-- Sale Header -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>{{ $medicineSale->is_internal ? 'Internal Use' : 'Sale' }} Information</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th width="40%">Invoice Number:</th>
                            <td>{{ $medicineSale->invoice_number }}</td>
                        </tr>
                        <tr>
                            <th>Date:</th>
                            <td>{{ $medicineSale->sale_date->format('d M, Y') }}</td>
                        </tr>
                        <tr>
                            <th>Type:</th>
                            <td>
                                <span class="badge bg-{{ $medicineSale->type == 'customer' ? 'primary' : ($medicineSale->type == 'radiology-use' ? 'warning' : 'info') }}">
                                    {{ ucfirst(str_replace('-', ' ', $medicineSale->type)) }}
                                </span>
                            </td>
                        </tr>
                        @if($medicineSale->is_internal)
                        <tr>
                            <th>Department:</th>
                            <td>{{ $medicineSale->department ?? 'N/A' }}</td>
                        </tr>
                        @else
                        <tr>
                            <th>Customer Name:</th>
                            <td>{{ $medicineSale->customer_name }}</td>
                        </tr>
                        <tr>
                            <th>Customer Phone:</th>
                            <td>{{ $medicineSale->customer_phone ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Customer Address:</th>
                            <td>{{ $medicineSale->customer_address ?? 'N/A' }}</td>
                        </tr>
                        @endif
                        @if(!$medicineSale->is_internal)
                        <tr>
                            <th>Payment Status:</th>
                            <td>
                                <span class="badge bg-{{ $medicineSale->payment_status == 'paid' ? 'success' : ($medicineSale->payment_status == 'partial' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($medicineSale->payment_status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Payment Method:</th>
                            <td>{{ ucfirst($medicineSale->payment_method) }}</td>
                        </tr>
                        @endif
                        <tr>
                            <th>Notes:</th>
                            <td>{{ $medicineSale->notes ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5>Financial Information</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th width="45%">Sub Total:</th>
                            <td>₹{{ number_format($medicineSale->sub_total, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Item Discounts:</th>
                            <td class="text-danger">- ₹{{ number_format($medicineSale->total_discount - ($medicineSale->overall_discount_amount ?? 0), 2) }}</td>
                        </tr>
                        @if(($medicineSale->overall_discount_percent ?? 0) > 0 || ($medicineSale->overall_discount_amount ?? 0) > 0)
                        <tr>
                            <th>Overall Discount ({{ $medicineSale->overall_discount_percent ?? 0 }}%):</th>
                            <td class="text-danger">- ₹{{ number_format($medicineSale->overall_discount_amount ?? 0, 2) }}</td>
                        </tr>
                        @endif
                        <tr class="table-secondary">
                            <th>Amount After Discount:</th>
                            <td>₹{{ number_format(($medicineSale->sub_total - $medicineSale->total_discount), 2) }}</td>
                        </tr>
                        @if(($medicineSale->injection_fees ?? 0) > 0)
                        <tr>
                            <th>Injection Fees:</th>
                            <td class="text-success">+ ₹{{ number_format($medicineSale->injection_fees ?? 0, 2) }}</td>
                        </tr>
                        @endif
                        @if(($medicineSale->procedure_fees ?? 0) > 0)
                        <tr>
                            <th>Procedure Fees:</th>
                            <td class="text-success">+ ₹{{ number_format($medicineSale->procedure_fees ?? 0, 2) }}</td>
                        </tr>
                        @endif
                        <tr class="table-primary">
                            <th>Grand Total (Rounded):</th>
                            <td class="fw-bold">₹{{ number_format($medicineSale->grand_total, 2) }}</td>
                        </tr>
                        @if(!$medicineSale->is_internal)
                        <tr>
                            <th>Paid Amount:</th>
                            <td class="text-success">₹{{ number_format($medicineSale->paid_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Due Amount:</th>
                            <td class="{{ $medicineSale->due_amount > 0 ? 'text-danger fw-bold' : 'text-success' }}">
                                ₹{{ number_format($medicineSale->due_amount, 2) }}
                            </td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            <!-- Sale Items -->
            <h6 class="title border-bottom pb-2">{{ $medicineSale->is_internal ? 'Medicine Items Used' : 'Sale Items' }}</h6>
            @if($medicineSale->items->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Medicine</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Discount %</th>
                            <th>Discount Amount</th>
                            <th>Final Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($medicineSale->items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $item->medicine->name }}</strong>
                                <br><small class="text-muted">{{ $item->medicine->generic_name ?? 'N/A' }} | {{ $item->medicine->category ?? 'N/A' }}</small>
                            </td>
                            <td>{{ $item->quantity }}</td>
                            <td>₹{{ number_format($item->unit_price, 2) }}</td>
                            <td>{{ number_format($item->discount_percent, 2) }}%</td>
                            <td class="text-danger">₹{{ number_format($item->discount_amount, 2) }}</td>
                            <td class="fw-bold">₹{{ number_format($item->final_amount, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="table-secondary">
                            <td colspan="5" class="text-end"><strong>Total Item Discount:</strong></td>
                            <td class="text-danger"><strong>₹{{ number_format($medicineSale->total_discount - ($medicineSale->overall_discount_amount ?? 0), 2) }}</strong></td>
                            <td></td>
                        </tr>
                        <tr class="table-secondary">
                            <td colspan="6" class="text-end"><strong>Sub Total:</strong></td>
                            <td><strong>₹{{ number_format($medicineSale->sub_total, 2) }}</strong></td>
                        </tr>
                        @if(($medicineSale->overall_discount_amount ?? 0) > 0)
                        <tr class="table-secondary">
                            <td colspan="6" class="text-end"><strong>Overall Discount:</strong></td>
                            <td class="text-danger"><strong>₹{{ number_format($medicineSale->overall_discount_amount ?? 0, 2) }}</strong></td>
                        </tr>
                        @endif
                        <tr class="table-secondary">
                            <td colspan="6" class="text-end"><strong>Amount After All Discounts:</strong></td>
                            <td><strong>₹{{ number_format(($medicineSale->sub_total - $medicineSale->total_discount), 2) }}</strong></td>
                        </tr>
                        @if(($medicineSale->injection_fees ?? 0) > 0)
                        <tr class="table-secondary">
                            <td colspan="6" class="text-end"><strong>Injection Fees:</strong></td>
                            <td><strong>₹{{ number_format($medicineSale->injection_fees ?? 0, 2) }}</strong></td>
                        </tr>
                        @endif
                        @if(($medicineSale->procedure_fees ?? 0) > 0)
                        <tr class="table-secondary">
                            <td colspan="6" class="text-end"><strong>Procedure Fees:</strong></td>
                            <td><strong>₹{{ number_format($medicineSale->procedure_fees ?? 0, 2) }}</strong></td>
                        </tr>
                        @endif
                        <tr class="table-primary">
                            <td colspan="6" class="text-end"><strong>Grand Total (Rounded):</strong></td>
                            <td><strong>₹{{ number_format($medicineSale->grand_total, 2) }}</strong></td>
                        </tr>
                        @if(!$medicineSale->is_internal)
                        <tr class="table-info">
                            <td colspan="6" class="text-end"><strong>Paid Amount:</strong></td>
                            <td><strong>₹{{ number_format($medicineSale->paid_amount, 2) }}</strong></td>
                        </tr>
                        <tr class="table-{{ $medicineSale->due_amount > 0 ? 'danger' : 'success' }}">
                            <td colspan="6" class="text-end"><strong>Due Amount:</strong></td>
                            <td><strong>₹{{ number_format($medicineSale->due_amount, 2) }}</strong></td>
                        </tr>
                        @endif
                    </tfoot>
                </table>
            </div>
            @else
            <div class="alert alert-info mt-3">
                <em class="icon ni ni-info"></em> No medicines in this transaction. Only fees were applied.
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <a href="{{ route('medicine-sales.index') }}" class="btn btn-secondary" style="border-radius: 5px">
                        <em class="icon ni ni-arrow-left"></em> &nbsp; Back to List
                    </a>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('medicine-sales.print', $medicineSale->id) }}" target="_blank" class="btn btn-info" style="border-radius: 6px 0 0 6px">
                        <em class="icon ni ni-printer"></em> &nbsp; Print Record
                    </a>
                    <a href="{{ route('medicine-sales.edit', $medicineSale->id) }}" class="btn btn-primary">
                        <em class="icon ni ni-edit"></em> &nbsp; Edit
                    </a>
                    <form action="{{ route('medicine-sales.destroy', $medicineSale->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure? This will restore stock.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <em class="icon ni ni-trash"></em> &nbsp; Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection