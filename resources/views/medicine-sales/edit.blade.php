@extends('layouts.app')

@section('title', 'Edit Sale/Use')
@section('page-title', 'Edit Sale/Use')

@section('content')
<div class="nk-block nk-block-lg">
    <form action="{{ route('medicine-sales.update', $medicineSale) }}" method="POST" id="saleForm">
        @csrf
        @method('PUT')

        <div class="card card-preview">
            <div class="card-inner">
                <!-- Sale Header -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Invoice Number</label>
                            <input type="text" class="form-control" value="{{ $medicineSale->invoice_number }}" readonly style="border-radius: 5px">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Sale Date *</label>
                            <input type="date" class="form-control" name="sale_date" value="{{ $medicineSale->sale_date->format('Y-m-d') }}"
                                required style="border-radius: 5px">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Type</label>
                            <input type="text" class="form-control" value="{{ ucfirst(str_replace('-', ' ', $medicineSale->type)) }}" readonly style="border-radius: 5px">
                        </div>
                    </div>
                    @if($medicineSale->is_internal && $medicineSale->type == 'other')
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Department Name</label>
                            <input style="border-radius: 5px" type="text" class="form-control" name="department"
                                value="{{ $medicineSale->department }}" placeholder="Enter department name">
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Customer Information (visible only for customer type) -->
                @if(!$medicineSale->is_internal)
                <div class="row" id="customerFields">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Customer Name *</label>
                            <input style="border-radius: 5px" type="text" class="form-control" name="customer_name"
                                id="customer_name" value="{{ $medicineSale->customer_name }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Customer Phone</label>
                            <input style="border-radius: 5px" type="text" class="form-control" name="customer_phone"
                                id="customer_phone" value="{{ $medicineSale->customer_phone }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Customer Address</label>
                            <textarea class="form-control" name="customer_address" id="customer_address" rows="1">{{ $medicineSale->customer_address }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Payment Information (visible only for customer type) -->
                <div class="row" id="paymentFields">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Payment Method *</label>
                            <select style="border-radius: 5px" class="form-control js-select2" name="payment_method"
                                id="payment_method" required data-placeholder="Select Payment Method">
                                <option value="cash" {{ $medicineSale->payment_method == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="card" {{ $medicineSale->payment_method == 'card' ? 'selected' : '' }}>Card</option>
                                <option value="upi" {{ $medicineSale->payment_method == 'upi' ? 'selected' : '' }}>UPI</option>
                                <option value="cheque" {{ $medicineSale->payment_method == 'cheque' ? 'selected' : '' }}>Cheque</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Payment Status *</label>
                            <select style="border-radius: 5px" class="form-control js-select2" name="payment_status" id="payment_status" required
                                    data-placeholder="Select Payment Status" onchange="updatePaidAmount()">
                                <option value="due" {{ $medicineSale->payment_status == 'due' ? 'selected' : '' }}>Due</option>
                                <option value="partial" {{ $medicineSale->payment_status == 'partial' ? 'selected' : '' }}>Partial</option>
                                <option value="paid" {{ $medicineSale->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Paid Amount *</label>
                            <input style="border-radius: 5px" type="number" class="form-control" name="paid_amount" id="paid_amount"
                                value="{{ $medicineSale->paid_amount }}" step="0.01" min="0" required onchange="updateDueAmount()">
                        </div>
                    </div>
                </div>
                @endif

                <!-- Medicine Items -->
                <h6 class="title border-bottom pb-2">Medicine Items</h6>
                <div class="table-responsive">
                    <table class="table table-bordered" id="itemsTable">
                        <thead>
                            <tr>
                                <th width="25%">Medicine *</th>
                                <th width="10%">Stock</th>
                                <th width="10%">Qty *</th>
                                <th width="15%">Price (per unit) *</th>
                                <th width="10%">Discount %</th>
                                <th width="15%">Discount Amount</th>
                                <th width="15%">Final Amount</th>
                                <th width="5%">Action</th>
                            </tr>
                        </thead>
                        <tbody id="itemsBody">
                            @foreach($medicineSale->items as $index => $item)
                            <tr class="item-row">
                                <td>
                                    <select class="form-control js-medicine-select" name="items[{{ $index }}][medicine_id]" required
                                            data-placeholder="Select Medicine" onchange="updateMedicineDetails(this)">
                                        <option value=""></option>
                                        @foreach ($medicines as $medicine)
                                            <option value="{{ $medicine->id }}"
                                                data-selling-price="{{ $medicine->price }}"
                                                data-purchase-price="{{ $medicine->purchase_price }}"
                                                data-stock="{{ $medicine->stock }}"
                                                {{ $item->medicine_id == $medicine->id ? 'selected' : '' }}>
                                                {{ $medicine->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <span class="stock-info">{{ $item->medicine->stock + $item->quantity }}</span>
                                </td>
                                <td>
                                    <input type="number" class="form-control quantity" name="items[{{ $index }}][quantity]"
                                        value="{{ $item->quantity }}" min="1" required oninput="calculateRowTotal(this)">
                                </td>
                                <td>
                                    <input type="number" class="form-control unit-price" name="items[{{ $index }}][unit_price]"
                                        value="{{ $item->unit_price }}" step="0.01" min="0" required oninput="calculateRowTotal(this)">
                                </td>
                                <td>
                                    <input type="number" class="form-control discount-percent" name="items[{{ $index }}][discount_percent]"
                                        value="{{ $item->discount_percent }}" step="0.01" min="0" max="100" oninput="calculateRowTotal(this)">
                                </td>
                                <td>
                                    <input type="text" class="form-control discount-amount" value="{{ number_format($item->discount_amount, 2) }}" readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control final-amount" value="₹ {{ number_format($item->final_amount, 2) }}" readonly>
                                    <input type="hidden" class="final-amount-hidden" name="items[{{ $index }}][final_amount]" value="{{ $item->final_amount }}">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-danger remove-item" style="border-radius: 5px">
                                        <em class="icon ni ni-trash"></em>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-primary">
                                <td colspan="7" class="text-end">
                                    <button type="button" class="btn btn-success" id="addItem" style="border-radius: 5px">
                                        <em class="icon ni ni-plus"></em> Add Medicine Item
                                    </button>
                                    <span class="text-muted ms-2 small">(Optional - can save without medicines)</span>
                                 </td>
                                 <td>

                            </tr>
                            <tr class="table-info">
                                <td colspan="4" class="text-end"><strong>Sub Total:</strong></td>
                                <td colspan="1" class="text-right"><strong id="subTotalDisplay">₹ {{ number_format($medicineSale->sub_total, 2) }}</strong></td>
                                <td colspan="1"><strong>Total Discount:</strong></td>
                                <td colspan="2"><strong id="totalDiscountDisplay">₹ {{ number_format($medicineSale->total_discount, 2) }}</strong></td>
                              </tr>
                              <tr>
                                <td colspan="6" class="text-end"><strong>Overall Discount (%):</strong></td>
                                <td colspan="2">
                                    <input type="number" id="overallDiscountPercent" class="form-control"
                                           name="overall_discount_percent" value="{{ $medicineSale->overall_discount_percent ?? 0 }}"
                                           step="0.01" min="0" max="100" oninput="calculateOverallDiscount()" style="width: 120px;">
                                  </td>
                              </tr>
                              <tr>
                                <td colspan="6" class="text-end"><strong>Overall Discount Amount:</strong></td>
                                <td colspan="2">
                                    <input type="text" id="overallDiscountAmount" class="form-control"
                                           name="overall_discount_amount" value="{{ number_format($medicineSale->overall_discount_amount ?? 0, 2) }}" readonly>
                                  </td>
                              </tr>
                              <tr>
                                <td colspan="6" class="text-end"><strong>Amount After Discount:</strong></td>
                                <td colspan="2"><strong id="amountAfterDiscount">₹ {{ number_format(($medicineSale->sub_total - $medicineSale->total_discount - ($medicineSale->overall_discount_amount ?? 0)), 2) }}</strong></td>
                              </tr>
                              <tr>
                                <td colspan="6" class="text-end"><strong>Injection Fees:</strong></td>
                                <td colspan="2">
                                    <input type="number" id="injectionFees" class="form-control"
                                           name="injection_fees" value="{{ $medicineSale->injection_fees ?? 0 }}"
                                           step="0.01" min="0" oninput="calculateGrandTotal()" style="width: 120px;">
                                  </td>
                              </tr>
                              <tr>
                                <td colspan="6" class="text-end"><strong>Procedure Fees:</strong></td>
                                <td colspan="2">
                                    <input type="number" id="procedureFees" class="form-control"
                                           name="procedure_fees" value="{{ $medicineSale->procedure_fees ?? 0 }}"
                                           step="0.01" min="0" oninput="calculateGrandTotal()" style="width: 120px;">
                                  </td>
                              </tr>
                            <tr class="table-success">
                                <td colspan="6" class="text-end"><strong>Grand Total (Rounded):</strong></td>
                                <td colspan="2"><strong id="grandTotalDisplay">₹ {{ number_format($medicineSale->grand_total, 2) }}</strong></td>
                              </tr>
                            @if(!$medicineSale->is_internal)
                            <tr id="dueRow">
                                <td colspan="6" class="text-end"><strong>Due Amount:</strong></td>
                                <td colspan="2"><strong id="dueAmountDisplay">₹ {{ number_format($medicineSale->due_amount, 2) }}</strong></td>
                              </tr>
                            @endif
                        </tfoot>
                      </table>
                </div>

                <input type="hidden" name="sub_total" id="sub_total" value="{{ $medicineSale->sub_total }}">
                <input type="hidden" name="total_discount" id="total_discount" value="{{ $medicineSale->total_discount }}">
                <input type="hidden" name="grand_total" id="grand_total" value="{{ $medicineSale->grand_total }}">
                <input type="hidden" name="due_amount" id="due_amount" value="{{ $medicineSale->due_amount }}">

                <div class="form-group">
                    <label class="form-label">Notes</label>
                    <textarea class="form-control" name="notes" rows="3" placeholder="Any additional notes...">{{ $medicineSale->notes }}</textarea>
                </div>

                <!-- Action Buttons -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <a href="{{ route('medicine-sales.show', $medicineSale) }}" class="btn btn-secondary" style="border-radius: 5px">
                            <em class="icon ni ni-arrow-left"></em> &nbsp; Cancel
                        </a>
                    </div>
                    <div class="col-md-6 text-end">
                        <button type="submit" class="btn btn-primary" style="border-radius: 6px 0 0 6px">
                            <em class="icon ni ni-save"></em> &nbsp; Update Record
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Medicine Row Template -->
<template id="medicineRowTemplate">
    <tr class="item-row">
        <tr>
            <select class="form-control js-medicine-select" name="items[0][medicine_id]" required
                    data-placeholder="Select Medicine" onchange="updateMedicineDetails(this)">
                <option value=""></option>
                @foreach ($medicines as $medicine)
                    <option value="{{ $medicine->id }}"
                        data-selling-price="{{ $medicine->price }}"
                        data-purchase-price="{{ $medicine->purchase_price }}"
                        data-stock="{{ $medicine->stock }}">
                        {{ $medicine->name }}
                    </option>
                @endforeach
            </select>
          </td>
          <td>
            <span class="stock-info">0</span>
          </td>
          <td>
            <input type="number" class="form-control quantity" name="items[0][quantity]" value="1"
                min="1" required oninput="calculateRowTotal(this)">
          </td>
          <td>
            <input type="number" class="form-control unit-price" name="items[0][unit_price]" step="0.01"
                min="0" required oninput="calculateRowTotal(this)">
          </td>
          <td>
            <input type="number" class="form-control discount-percent" name="items[0][discount_percent]"
                value="0" step="0.01" min="0" max="100" oninput="calculateRowTotal(this)">
          </td>
          <td>
            <input type="text" class="form-control discount-amount" readonly>
          </td>
          <td>
            <input type="text" class="form-control final-amount" readonly>
            <input type="hidden" class="final-amount-hidden" name="items[0][final_amount]" value="0">
          </td>
          <td>
            <button type="button" class="btn btn-sm btn-danger remove-item" style="border-radius: 5px">
                <em class="icon ni ni-trash"></em>
            </button>
          </td>
      </tr>
</template>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
.select2-container--default .select2-selection--single {
    height: 38px;
    border: 1px solid #dbdfea;
    border-radius: 4px;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 36px;
    padding-left: 12px;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 36px;
}
.table td {
    vertical-align: middle;
}
.stock-info {
    display: inline-block;
    padding: 5px 10px;
    background: #f8f9fa;
    border-radius: 3px;
    font-weight: 600;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
let rowCount = {{ $medicineSale->items->count() }};

// Round off function
function roundToNearest(value) {
    return Math.round(value);
}

// Update medicine details when selected
function updateMedicineDetails(selectElement) {
    const row = selectElement.closest('tr');
    const selectedOption = selectElement.options[selectElement.selectedIndex];

    if (selectedOption.value) {
        const sellingPrice = selectedOption.getAttribute('data-selling-price');
        const stock = selectedOption.getAttribute('data-stock');

        // Auto-fill unit price only if it's 0 or empty
        const currentPrice = row.querySelector('.unit-price').value;
        if (!currentPrice || parseFloat(currentPrice) === 0) {
            row.querySelector('.unit-price').value = sellingPrice || 0;
        }

        // Update stock information
        row.querySelector('.stock-info').textContent = stock || 0;

        // Set max quantity
        const quantityInput = row.querySelector('.quantity');
        quantityInput.max = stock;
        if (parseInt(quantityInput.value) > parseInt(stock)) {
            quantityInput.value = stock;
        }

        calculateRowTotal(selectElement);
    }
}

// Calculate row total
function calculateRowTotal(inputElement) {
    const row = inputElement.closest('tr');
    const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
    const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
    const discountPercent = parseFloat(row.querySelector('.discount-percent').value) || 0;

    // Calculate amounts
    const originalAmount = quantity * unitPrice;
    const discountAmount = (originalAmount * discountPercent) / 100;
    const finalAmount = originalAmount - discountAmount;

    // Update row displays
    row.querySelector('.discount-amount').value = discountAmount.toFixed(2);
    row.querySelector('.final-amount').value = '₹' + finalAmount.toFixed(2);
    row.querySelector('.final-amount-hidden').value = finalAmount;

    calculateTotals();
}

// Calculate medicine totals
function calculateTotals() {
    let subTotal = 0;
    let totalItemDiscount = 0;

    document.querySelectorAll('.item-row').forEach(row => {
        const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
        const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
        const discountPercent = parseFloat(row.querySelector('.discount-percent').value) || 0;

        const originalAmount = quantity * unitPrice;
        const discountAmount = (originalAmount * discountPercent) / 100;

        subTotal += originalAmount;
        totalItemDiscount += discountAmount;
    });

    // Update displays
    document.getElementById('subTotalDisplay').textContent = '₹' + subTotal.toFixed(2);
    document.getElementById('totalDiscountDisplay').textContent = '₹' + totalItemDiscount.toFixed(2);

    // Update hidden inputs
    document.getElementById('sub_total').value = subTotal;
    document.getElementById('total_discount').value = totalItemDiscount;

    calculateOverallDiscount();
}

// Calculate overall discount
function calculateOverallDiscount() {
    const subTotal = parseFloat(document.getElementById('sub_total').value) || 0;
    const totalItemDiscount = parseFloat(document.getElementById('total_discount').value) || 0;
    const overallDiscountPercent = parseFloat(document.getElementById('overallDiscountPercent').value) || 0;

    const amountAfterItemDiscount = subTotal - totalItemDiscount;
    const overallDiscountAmount = (amountAfterItemDiscount * overallDiscountPercent) / 100;

    document.getElementById('overallDiscountAmount').value = overallDiscountAmount.toFixed(2);

    const amountAfterOverallDiscount = amountAfterItemDiscount - overallDiscountAmount;
    document.getElementById('amountAfterDiscount').textContent = '₹' + amountAfterOverallDiscount.toFixed(2);

    calculateGrandTotal();
}

// Calculate grand total (without GST)
function calculateGrandTotal() {
    const subTotal = parseFloat(document.getElementById('sub_total').value) || 0;
    const totalItemDiscount = parseFloat(document.getElementById('total_discount').value) || 0;
    const overallDiscountAmount = parseFloat(document.getElementById('overallDiscountAmount').value) || 0;
    const injectionFees = parseFloat(document.getElementById('injectionFees').value) || 0;
    const procedureFees = parseFloat(document.getElementById('procedureFees').value) || 0;

    const amountAfterDiscount = subTotal - totalItemDiscount - overallDiscountAmount;

    // Calculate grand total before roundoff (no GST)
    const grandTotalBeforeRound = amountAfterDiscount + injectionFees + procedureFees;
    const roundedGrandTotal = roundToNearest(grandTotalBeforeRound);

    // Update display
    document.getElementById('grandTotalDisplay').textContent = '₹' + roundedGrandTotal.toFixed(2);

    // Update hidden inputs
    document.getElementById('grand_total').value = roundedGrandTotal;

    updateDueAmount();
}

// Update due amount
function updateDueAmount() {
    const isInternal = {{ $medicineSale->is_internal ? 'true' : 'false' }};

    if (!isInternal) {
        const grandTotal = parseFloat(document.getElementById('grandTotalDisplay').textContent.replace('₹', '')) || 0;
        const paidAmount = parseFloat(document.getElementById('paid_amount').value) || 0;
        const dueAmount = grandTotal - paidAmount;

        document.getElementById('dueAmountDisplay').textContent = '₹' + dueAmount.toFixed(2);
        document.getElementById('due_amount').value = dueAmount;
    }
}

// Update paid amount based on payment status
function updatePaidAmount() {
    const paymentStatus = document.getElementById('payment_status').value;
    const grandTotal = parseFloat(document.getElementById('grandTotalDisplay').textContent.replace('₹', '')) || 0;

    if (paymentStatus === 'paid') {
        document.getElementById('paid_amount').value = grandTotal;
    } else if (paymentStatus === 'due') {
        document.getElementById('paid_amount').value = 0;
    }

    updateDueAmount();
}

// Initialize Select2
function initializeSelect2() {
    $('.js-select2').select2({
        placeholder: "Select Option",
        allowClear: false,
        width: '100%'
    });

    $('.js-medicine-select').each(function() {
        if (!$(this).hasClass('select2-hidden-accessible')) {
            $(this).select2({
                placeholder: 'Select Medicine',
                allowClear: false,
                width: '100%'
            });
        }
    });
}

// Add new medicine row
document.getElementById('addItem').addEventListener('click', function() {
    const template = document.getElementById('medicineRowTemplate');
    const newRow = template.content.cloneNode(true);
    const newRowElement = newRow.querySelector('tr');

    const newIndex = rowCount++;
    const inputs = newRowElement.querySelectorAll('[name]');
    inputs.forEach(input => {
        const name = input.getAttribute('name');
        input.setAttribute('name', name.replace('[0]', `[${newIndex}]`));
    });

    document.getElementById('itemsBody').appendChild(newRowElement);

    const select = newRowElement.querySelector('.js-medicine-select');
    $(select).select2({
        placeholder: 'Select Medicine',
        allowClear: false,
        width: '100%'
    });

    newRowElement.querySelector('.remove-item').addEventListener('click', function() {
        const select = newRowElement.querySelector('.js-medicine-select');
        $(select).select2('destroy');
        newRowElement.remove();
        calculateTotals();
        reindexRows();
    });
});

// Attach remove events to existing rows
document.querySelectorAll('.remove-item').forEach(button => {
    button.addEventListener('click', function() {
        const row = this.closest('tr');
        const select = row.querySelector('.js-medicine-select');
        $(select).select2('destroy');
        row.remove();
        calculateTotals();
        reindexRows();
    });
});

// Re-index rows
function reindexRows() {
    const rows = document.querySelectorAll('.item-row');
    rowCount = 0;
    rows.forEach((row, index) => {
        const inputs = row.querySelectorAll('[name]');
        inputs.forEach(input => {
            const name = input.getAttribute('name');
            const newName = name.replace(/items\[\d+\]/, `items[${index}]`);
            input.setAttribute('name', newName);
        });
        rowCount = index + 1;
    });
}

// Form validation
document.getElementById('saleForm').addEventListener('submit', function(e) {
    const isInternal = {{ $medicineSale->is_internal ? 'true' : 'false' }};

    if (!isInternal) {
        const customerName = document.getElementById('customer_name').value;
        if (!customerName.trim()) {
            e.preventDefault();
            alert('Customer Name is required for customer sales.');
            return;
        }

        const grandTotal = parseFloat(document.getElementById('grandTotalDisplay').textContent.replace('₹', '')) || 0;
        const paidAmount = parseFloat(document.getElementById('paid_amount').value) || 0;

        if (paidAmount > grandTotal) {
            e.preventDefault();
            alert('Paid amount cannot be greater than grand total.');
            return;
        }
    }

    // Medicines are optional - no validation for empty items
    let isValid = true;
    let medicineIds = new Set();

    document.querySelectorAll('.item-row').forEach(row => {
        const medicineSelect = row.querySelector('.js-medicine-select');
        const quantity = row.querySelector('.quantity');
        const unitPrice = row.querySelector('.unit-price');
        const stock = parseInt(row.querySelector('.stock-info').textContent);

        if (medicineSelect.value) {
            if (medicineIds.has(medicineSelect.value)) {
                isValid = false;
                alert('Duplicate medicine found. Please remove duplicate entries.');
                return;
            }
            medicineIds.add(medicineSelect.value);
        }

        if (medicineSelect.value && (!quantity.value || !unitPrice.value)) {
            isValid = false;
        }

        if (medicineSelect.value && parseInt(quantity.value) > stock) {
            isValid = false;
            alert(`Quantity exceeds available stock for ${medicineSelect.options[medicineSelect.selectedIndex].text}`);
            quantity.focus();
            return;
        }

        if (medicineSelect.value && parseFloat(unitPrice.value) <= 0) {
            isValid = false;
            alert('Unit price must be greater than 0.');
            unitPrice.focus();
            return;
        }
    });

    if (!isValid) {
        e.preventDefault();
        alert('Please fill all required fields correctly.');
    }
});

// Initialize on page load
$(document).ready(function() {
    initializeSelect2();

    document.querySelectorAll('.item-row').forEach(row => {
        const medicineSelect = row.querySelector('.js-medicine-select');
        const quantityInput = row.querySelector('.quantity');

        if (medicineSelect.value) {
            const selectedOption = medicineSelect.options[medicineSelect.selectedIndex];
            const stock = selectedOption ? selectedOption.getAttribute('data-stock') : 0;
            row.querySelector('.stock-info').textContent = stock || 0;
            quantityInput.max = stock;
            calculateRowTotal(medicineSelect);
        }
    });

    calculateTotals();

    if (!{{ $medicineSale->is_internal ? 'true' : 'false' }}) {
        updateDueAmount();
    }
});
</script>
@endpush