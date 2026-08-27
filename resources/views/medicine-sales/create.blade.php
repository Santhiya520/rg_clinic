@extends('layouts.app')

@section('title', 'Add Medicine Sale/Use')
@section('page-title', 'Add Medicine Sale/Use')

@section('content')
<div class="nk-block nk-block-lg">
    <form action="{{ route('medicine-sales.store') }}" method="POST" id="saleForm">
        @csrf

        <div class="card card-preview">
            <div class="card-inner">
                <!-- Sale Header -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Invoice Number *</label>
                            <input type="text" class="form-control" name="invoice_number"
                                value="{{ $invoiceNumber }}" readonly style="border-radius: 5px">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Sale Date *</label>
                            <input type="date" class="form-control" name="sale_date" value="{{ date('Y-m-d') }}"
                                required style="border-radius: 5px">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Type *</label>
                            <select style="border-radius: 5px" class="form-control js-select2" name="type" id="type" required
                                    data-placeholder="Select Type" onchange="toggleFields()">
                                <option value="customer">Customer Sale</option>
                                <option value="radiology-use">Radiology Use</option>
                                <option value="lab-use">Lab Use</option>
                                <option value="other">Other Internal Use</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3" id="departmentField" style="display: none;">
                        <div class="form-group">
                            <label class="form-label">Department Name</label>
                            <input style="border-radius: 5px" type="text" class="form-control" name="department"
                                placeholder="Enter department name">
                        </div>
                    </div>
                </div>

                <!-- Customer Information (visible only for customer type) -->
                <div class="row" id="customerFields">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Customer Name *</label>
                            <input style="border-radius: 5px" type="text" class="form-control" name="customer_name"
                                id="customer_name" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Customer Phone</label>
                            <input style="border-radius: 5px" type="text" class="form-control" name="customer_phone"
                                id="customer_phone">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Customer Address</label>
                            <textarea class="form-control" name="customer_address" id="customer_address" rows="1"></textarea>
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
                                <option value="cash">Cash</option>
                                <option value="card">Card</option>
                                <option value="upi">UPI</option>
                                <option value="cheque">Cheque</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Payment Status *</label>
                            <select style="border-radius: 5px" class="form-control js-select2" name="payment_status" id="payment_status" required
                                    data-placeholder="Select Payment Status" onchange="updatePaidAmount()">
                                <option value="due">Due</option>
                                <option value="partial">Partial</option>
                                <option value="paid">Paid</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Paid Amount *</label>
                            <input style="border-radius: 5px" type="number" class="form-control" name="paid_amount" id="paid_amount"
                                value="0" step="0.01" min="0" required onchange="updateDueAmount()">
                        </div>
                    </div>
                </div>

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
                            <!-- No default row - items will be added manually by user -->
                        </tbody>
                        <tfoot>
                            <tr class="table-primary">
                                <td colspan="7" class="text-end">
                                    <button type="button" class="btn btn-success" id="addItem" style="border-radius: 5px">
                                        <em class="icon ni ni-plus"></em> Add Medicine Item
                                    </button>
                                    <span class="text-muted ms-2 small">(Optional - can save without medicines)</span>
                                  </td>
                                  <td></td>
                              </tr>
                            <tr class="table-info">
                                <td colspan="4" class="text-end"><strong>Sub Total:</strong></td>
                                <td colspan="1" class="text-right"><strong id="subTotalDisplay">₹ 0.00</strong></td>
                                <td colspan="1"><strong>Total Discount:</strong></td>
                                <td colspan="2"><strong id="totalDiscountDisplay">₹ 0.00</strong></td>
                              </tr>
                              <tr>
                                <td colspan="6" class="text-end"><strong>Overall Discount (%):</strong></td>
                                <td colspan="2">
                                    <input type="number" id="overallDiscountPercent" class="form-control"
                                           name="overall_discount_percent" value="0" step="0.01" min="0" max="100"
                                           oninput="calculateOverallDiscount()" style="width: 120px;">
                                  </td>
                              </tr>
                              <tr>
                                <td colspan="6" class="text-end"><strong>Overall Discount Amount:</strong></td>
                                <td colspan="2">
                                    <input type="text" id="overallDiscountAmount" class="form-control"
                                           name="overall_discount_amount" value="0" readonly>
                                  </td>
                              </tr>
                              <tr>
                                <td colspan="6" class="text-end"><strong>Amount After Discount:</strong></td>
                                <td colspan="2"><strong id="amountAfterDiscount">₹ 0.00</strong></td>
                              </tr>
                              <tr>
                                <td colspan="6" class="text-end"><strong>Injection Fees:</strong></td>
                                <td colspan="2">
                                    <input type="number" id="injectionFees" class="form-control"
                                           name="injection_fees" value="0" step="0.01" min="0"
                                           oninput="calculateGrandTotal()" style="width: 120px;">
                                  </td>
                              </tr>
                              <tr>
                                <td colspan="6" class="text-end"><strong>Procedure Fees:</strong></td>
                                <td colspan="2">
                                    <input type="number" id="procedureFees" class="form-control"
                                           name="procedure_fees" value="0" step="0.01" min="0"
                                           oninput="calculateGrandTotal()" style="width: 120px;">
                                  </td>
                              </tr>
                            <tr class="table-success">
                                <td colspan="6" class="text-end"><strong>Grand Total (Rounded):</strong></td>
                                <td colspan="2"><strong id="grandTotalDisplay">₹ 0.00</strong></td>
                              </tr>
                            <tr id="dueRow">
                                <td colspan="6" class="text-end"><strong>Due Amount:</strong></td>
                                <td colspan="2"><strong id="dueAmountDisplay">₹ 0.00</strong></td>
                              </tr>
                        </tfoot>
                      </table>
                </div>

                <input type="hidden" name="sub_total" id="sub_total" value="0">
                <input type="hidden" name="total_discount" id="total_discount" value="0">
                <input type="hidden" name="grand_total" id="grand_total" value="0">
                <input type="hidden" name="due_amount" id="due_amount" value="0">

                <div class="form-group">
                    <label class="form-label">Notes</label>
                    <textarea class="form-control" name="notes" rows="3" placeholder="Any additional notes..."></textarea>
                </div>

                <!-- Action Buttons -->
                <div class="row mt-4">
                    <div class="col-md-6">
                    </div>
                    <div class="col-md-6 text-end">
                        <button type="submit" class="btn btn-primary" style="border-radius: 6px 0 0 6px">
                            <em class="icon ni ni-save"></em> &nbsp; Save Record
                        </button>
                        <a href="{{ route('medicine-sales.index') }}" class="btn btn-secondary">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Medicine Row Template -->
<template id="medicineRowTemplate">
    <tr class="item-row">
          <td>
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
let rowCount = 0;

// Round off function (always round to nearest integer)
function roundToNearest(value) {
    return Math.round(value);
}

// Toggle fields based on type
function toggleFields() {
    const type = document.getElementById('type').value;
    const isInternal = type !== 'customer';

    // Show/hide customer fields
    const customerFields = document.getElementById('customerFields');
    const customerName = document.getElementById('customer_name');

    if (isInternal) {
        customerFields.style.display = 'none';
        customerName.required = false;

        // Show department field for "other" type
        if (type === 'other') {
            document.getElementById('departmentField').style.display = 'block';
        } else {
            document.getElementById('departmentField').style.display = 'none';
        }
    } else {
        customerFields.style.display = 'flex';
        customerName.required = true;
        document.getElementById('departmentField').style.display = 'none';
    }

    // Show/hide payment fields
    const paymentFields = document.getElementById('paymentFields');
    const dueRow = document.getElementById('dueRow');

    if (isInternal) {
        paymentFields.style.display = 'none';
        dueRow.style.display = 'none';
        document.getElementById('paid_amount').value = 0;
        document.getElementById('due_amount').value = 0;
    } else {
        paymentFields.style.display = 'flex';
        dueRow.style.display = '';
        updateDueAmount();
    }
}

// Update medicine details when selected
function updateMedicineDetails(selectElement) {
    const row = selectElement.closest('tr');
    const selectedOption = selectElement.options[selectElement.selectedIndex];

    if (selectedOption.value) {
        const sellingPrice = selectedOption.getAttribute('data-selling-price');
        const stock = selectedOption.getAttribute('data-stock');

        // Auto-fill unit price and stock
        row.querySelector('.unit-price').value = sellingPrice || 0;
        row.querySelector('.stock-info').textContent = stock || 0;

        // Set max quantity
        const quantityInput = row.querySelector('.quantity');
        quantityInput.max = stock;
        if (quantityInput.value > stock) {
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
    let totalDiscount = 0;

    document.querySelectorAll('.item-row').forEach(row => {
        const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
        const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
        const discountPercent = parseFloat(row.querySelector('.discount-percent').value) || 0;

        const originalAmount = quantity * unitPrice;
        const discountAmount = (originalAmount * discountPercent) / 100;

        subTotal += originalAmount;
        totalDiscount += discountAmount;
    });

    // Update displays
    document.getElementById('subTotalDisplay').textContent = '₹' + subTotal.toFixed(2);
    document.getElementById('totalDiscountDisplay').textContent = '₹' + totalDiscount.toFixed(2);

    // Update hidden inputs
    document.getElementById('sub_total').value = subTotal;
    document.getElementById('total_discount').value = totalDiscount;

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

    // Calculate amount after all discounts (medicine amount only)
    const amountAfterDiscount = subTotal - totalItemDiscount - overallDiscountAmount;

    // Calculate grand total before roundoff (no GST)
    const grandTotalBeforeRound = amountAfterDiscount + injectionFees + procedureFees;

    // Round to nearest integer
    const roundedGrandTotal = roundToNearest(grandTotalBeforeRound);

    // Update display
    document.getElementById('grandTotalDisplay').textContent = '₹' + roundedGrandTotal.toFixed(2);

    // Update hidden inputs
    document.getElementById('grand_total').value = roundedGrandTotal;

    updateDueAmount();
}

// Update due amount
function updateDueAmount() {
    const type = document.getElementById('type').value;

    if (type === 'customer') {
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

    // Initialize Select2 for the new select
    const select = newRowElement.querySelector('.js-medicine-select');
    $(select).select2({
        placeholder: 'Select Medicine',
        allowClear: false,
        width: '100%'
    });

    // Attach remove event
    newRowElement.querySelector('.remove-item').addEventListener('click', function() {
        const select = newRowElement.querySelector('.js-medicine-select');
        $(select).select2('destroy');
        newRowElement.remove();
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
    const type = document.getElementById('type').value;
    const isInternal = type !== 'customer';

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

    // Validate medicine rows only if they exist
    const itemRows = document.querySelectorAll('.item-row');
    let isValid = true;
    let medicineIds = new Set();

    itemRows.forEach(row => {
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

// Initialize on page load - NO DEFAULT ROW
$(document).ready(function() {
    initializeSelect2();
    toggleFields();
    // Do NOT add default row - items are optional
});
</script>
@endpush