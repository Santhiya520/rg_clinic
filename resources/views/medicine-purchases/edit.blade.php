@extends('layouts.app')

@section('title', 'Edit Medicine Purchase')
@section('page-title', 'Edit Medicine Purchase')

@section('content')
<div class="nk-block nk-block-lg">
    <form action="{{ route('medicine-purchases.update', $medicinePurchase->id) }}" method="POST" id="purchaseForm">
        @csrf
        @method('PUT')

        <div class="card card-preview">
            <div class="card-inner">
                <!-- Purchase Header -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Invoice Number *</label>
                            <input type="text" class="form-control" name="invoice_number"
                                value="{{ $medicinePurchase->invoice_number }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Purchase Date *</label>
                            <input type="date" class="form-control" name="purchase_date"
                                value="{{ $medicinePurchase->purchase_date instanceof \Carbon\Carbon ? $medicinePurchase->purchase_date->format('Y-m-d') : date('Y-m-d', strtotime($medicinePurchase->purchase_date)) }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Payment Status *</label>
                            <select class="form-control js-select2" name="payment_status" id="payment_status" required
                                    data-placeholder="Select Payment Status">
                                <option value="due" {{ $medicinePurchase->payment_status == 'due' ? 'selected' : '' }}>Due</option>
                                <option value="partial" {{ $medicinePurchase->payment_status == 'partial' ? 'selected' : '' }}>Partial</option>
                                <option value="paid" {{ $medicinePurchase->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Supplier Information -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Supplier *</label>
                            <select class="form-control js-select2" name="supplier_id" id="supplierSelect" required
                                    data-placeholder="Select Supplier">
                                <option value=""></option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}"
                                        {{ $medicinePurchase->supplier_id == $supplier->id ? 'selected' : '' }}>
                                        {{ \App\Helpers\StringHelper::decodeQuotes($supplier->name) }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="supplier_name" id="supplier_name" value="{{ $medicinePurchase->supplier_name_decoded ?? \App\Helpers\StringHelper::decodeQuotes($medicinePurchase->supplier_name) }}">
                            <input type="hidden" name="supplier_phone" id="supplier_phone" value="{{ $medicinePurchase->supplier_phone }}">
                            <input type="hidden" name="supplier_address" id="supplier_address" value="{{ $medicinePurchase->supplier_address }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Supplier Phone</label>
                            <input type="text" class="form-control" id="supplier_phone_display"
                                value="{{ $medicinePurchase->supplier_phone ?? 'N/A' }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Supplier Address</label>
                            <textarea class="form-control" id="supplier_address_display" rows="1" readonly>{{ $medicinePurchase->supplier_address ?? 'N/A' }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Purchase Items -->
                <h6 class="title border-bottom pb-2">Purchase Items</h6>
                <div class="alert alert-info" id="supplierAlert" style="display: {{ $medicinePurchase->supplier_id ? 'none' : 'block' }};">
                    Please select a supplier first to add medicines.
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" id="itemsTable">
                        <thead>
                            <tr>
                                <th width="30%">Medicine</th>
                                <th width="15%">Batch No *</th>
                                <th width="15%">Expiry Date (MM/YYYY) *</th>
                                <th width="10%">Quantity *</th>
                                <th width="15%">Purchase Price *</th>
                                <th width="15%">Total</th>
                                <th width="5%">Action</th>
                            </tr>
                        </thead>
                        <tbody id="itemsBody">
                            <!-- Items will be populated from existing data -->
                        </tbody>
                        <tfoot>
                            <tr class="table-primary">
                                <td colspan="4" class="text-end"></td>
                                <td colspan="3" class="text-center">
                                    <button type="button" class="btn btn-success" id="addItem" style="border-radius: 5px" {{ !$medicinePurchase->supplier_id ? 'disabled' : '' }}>
                                        <em class="icon ni ni-plus"></em> &nbsp; Add Medicine Item
                                    </button>
                                </td>
                            </tr>
                            <tr class="table-primary">
                                <td colspan="5" class="text-end"><strong>Grand Total:</strong></td>
                                <td colspan="2"><strong id="grandTotal">₹ {{ number_format($medicinePurchase->total_amount, 2) }}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-end"><strong>Paid Amount:</strong></td>
                                <td colspan="2">
                                    <input type="number" class="form-control" name="paid_amount" id="paid_amount"
                                        value="{{ $medicinePurchase->paid_amount }}" step="0.01" min="0" required>
                                </td>
                            </tr>
                            <tr class="table-info">
                                <td colspan="5" class="text-end"><strong>Due Amount:</strong></td>
                                <td colspan="2"><strong id="dueAmount">₹ {{ number_format($medicinePurchase->due_amount, 2) }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <input type="hidden" name="total_amount" id="total_amount" value="{{ $medicinePurchase->total_amount }}">
                <input type="hidden" name="due_amount" id="due_amount" value="{{ $medicinePurchase->due_amount }}">

                <div class="form-group">
                    <label class="form-label">Notes</label>
                    <textarea class="form-control" name="notes" rows="3" placeholder="Any additional notes...">{{ $medicinePurchase->notes }}</textarea>
                </div>

                <!-- Action Buttons -->
                <div class="row mt-4">
                    <div class="col-md-6">
                    </div>
                    <div class="col-md-6 text-end">
                        <button type="submit" class="btn btn-primary" style="border-radius: 6px 0 0 6px">
                            <em class="icon ni ni-save"></em> &nbsp; Update Purchase
                        </button>
                        <a href="{{ route('medicine-purchases.index') }}" class="btn btn-secondary">
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
                    data-placeholder="Select Medicine">
                <option value=""></option>
                <!-- Medicines will be populated dynamically -->
            </select>
        </td>
        <td>
            <input type="text" class="form-control batch-number" name="items[0][batch_number]" required
                   placeholder="Batch Number">
        </td>
        <td>
            <input type="text" class="form-control expiry-date" name="items[0][expiry_date]"
                   placeholder="MM/YYYY" required maxlength="7">
        </td>
        <td>
            <input type="number" class="form-control quantity" name="items[0][quantity]" value="1" required>
        </td>
        <td>
            <input type="number" class="form-control purchase-price" name="items[0][purchase_price]" step="0.01" min="0" required>
        </td>
        <td>
            <input type="text" class="form-control item-total" name="items[0][total_amount]" readonly>
            <input type="hidden" class="item-total-hidden" name="items[0][total_amount]" value="0">
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
.select2-container--default .select2-selection--single .select2-selection__placeholder {
    color: #6c757d;
}
.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #6576ff;
    color: white;
}
.table td {
    vertical-align: middle;
}
#supplierAlert {
    border-left: 4px solid #17a2b8;
}
.expiry-date {
    text-align: center;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
let rowCount = 0;
let currentSupplierId = "{{ $medicinePurchase->supplier_id }}";
let medicinesData = [];

// Format expiry date as MM/YYYY
function formatExpiryDate(input) {
    let value = input.value.replace(/\D/g, '');

    if (value.length >= 2) {
        let month = value.substring(0, 2);
        let year = value.substring(2, 6);

        if (month > 12) {
            month = '12';
        }

        if (year) {
            input.value = month + '/' + year;
        } else {
            input.value = month;
        }
    } else {
        input.value = value;
    }
}

// Validate expiry date
function validateExpiryDate(expiryDateStr) {
    if (!expiryDateStr) return false;

    const parts = expiryDateStr.split('/');
    if (parts.length !== 2) return false;

    const month = parseInt(parts[0]);
    const year = parseInt(parts[1]);

    if (isNaN(month) || isNaN(year)) return false;
    if (month < 1 || month > 12) return false;
    if (year < 2000 || year > 2100) return false;

    // Check if expiry date is not in the past
    const currentDate = new Date();
    const currentYear = currentDate.getFullYear();
    const currentMonth = currentDate.getMonth() + 1;

    if (year < currentYear) return false;
    if (year === currentYear && month < currentMonth) return false;

    return true;
}

// Initialize Select2
function initializeSelect2() {
    $('#payment_status').select2({
        placeholder: "Select Payment Status",
        allowClear: false,
        width: '100%'
    });

    $('#supplierSelect').select2({
        placeholder: "Select Supplier",
        allowClear: false,
        width: '100%'
    });
}

// Generate batch number
function generateBatchNumber() {
    const date = new Date();
    const year = date.getFullYear().toString().slice(-2);
    const month = (date.getMonth() + 1).toString().padStart(2, '0');
    const day = date.getDate().toString().padStart(2, '0');
    const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
    return `BATCH${year}${month}${day}${random}`;
}

// Load medicines for selected supplier
function loadMedicinesForSupplier(supplierId) {
    if (!supplierId) {
        $('#addItem').prop('disabled', true);
        $('#supplierAlert').show();
        medicinesData = [];
        return;
    }

    $('#supplierSelect').prop('disabled', true);
    $('#addItem').prop('disabled', true).html('<em class="icon ni ni-loader"></em> Loading...');

    const url = '{{ route("medicine-purchases.get-medicines-by-supplier", ":supplier") }}'.replace(':supplier', supplierId);

    $.ajax({
        url: url,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                medicinesData = response.medicines;

                $('#supplier_name').val(response.supplier.name);
                $('#supplier_phone_display').val(response.supplier.phone || 'N/A');
                $('#supplier_address_display').val(response.supplier.address || 'N/A');
                $('#supplier_phone').val(response.supplier.phone || '');
                $('#supplier_address').val(response.supplier.address || '');

                $('#addItem').prop('disabled', false).html('<em class="icon ni ni-plus"></em> Add Medicine Item');
                $('#supplierAlert').hide();

                updateExistingRowsWithNewMedicines();
            } else {
                alert('Failed to load medicines: ' + response.message);
                resetSupplierSelection();
            }
        },
        error: function(xhr) {
            alert('Error loading medicines. Please try again.');
            console.error(xhr);
            resetSupplierSelection();
        },
        complete: function() {
            $('#supplierSelect').prop('disabled', false);
        }
    });
}

// Reset supplier selection
function resetSupplierSelection() {
    currentSupplierId = null;
    medicinesData = [];
    $('#supplierSelect').val(null).trigger('change');
    $('#addItem').prop('disabled', true);
    $('#supplierAlert').show();
    $('#itemsBody').empty();
    calculateGrandTotal();
    $('#supplier_name').val('');
    $('#supplier_phone_display').val('');
    $('#supplier_address_display').val('');
    $('#supplier_phone').val('');
    $('#supplier_address').val('');
}

// Update existing rows with new medicine options
function updateExistingRowsWithNewMedicines() {
    document.querySelectorAll('.js-medicine-select').forEach(select => {
        const currentValue = select.value;

        $(select).empty();

        const emptyOption = document.createElement('option');
        emptyOption.value = '';
        emptyOption.textContent = 'Select Medicine';
        select.appendChild(emptyOption);

        medicinesData.forEach(medicine => {
            const option = document.createElement('option');
            option.value = medicine.id;
            option.textContent = medicine.decoded_name + ' - ₹' + medicine.price;
            option.setAttribute('data-purchase-price', medicine.price);
            option.setAttribute('data-stock', medicine.stock);
            option.setAttribute('data-category', medicine.category);

            if (medicine.id == currentValue) {
                option.selected = true;
            }

            select.appendChild(option);
        });

        if (!select.classList.contains('select2-hidden-accessible')) {
            $(select).select2({
                placeholder: 'Select Medicine',
                allowClear: false,
                width: '100%'
            });
        } else {
            $(select).trigger('change.select2');
        }
    });
}

// Initialize medicine select for a row
function initializeMedicineSelect(row) {
    const select = row.querySelector('.js-medicine-select');

    $(select).empty();

    const emptyOption = document.createElement('option');
    emptyOption.value = '';
    emptyOption.textContent = 'Select Medicine';
    select.appendChild(emptyOption);

    medicinesData.forEach(medicine => {
        const option = document.createElement('option');
        option.value = medicine.id;
        option.textContent = medicine.decoded_name;
        option.setAttribute('data-purchase-price', medicine.price);
        option.setAttribute('data-stock', medicine.stock);
        option.setAttribute('data-category', medicine.category);
        select.appendChild(option);
    });

    if (!select.classList.contains('select2-hidden-accessible')) {
        $(select).select2({
            placeholder: 'Select Medicine',
            allowClear: false,
            width: '100%'
        });
    } else {
        $(select).trigger('change.select2');
    }
}

// Add new medicine row
document.getElementById('addItem').addEventListener('click', function() {
    if (!currentSupplierId || medicinesData.length === 0) {
        alert('Please select a supplier first.');
        return;
    }

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
    attachRowEvents(newRowElement);
    initializeMedicineSelect(newRowElement);

    // Set expiry date formatting
    const expiryInput = newRowElement.querySelector('.expiry-date');
    expiryInput.addEventListener('input', function() {
        formatExpiryDate(this);
    });

    expiryInput.addEventListener('blur', function() {
        if (!validateExpiryDate(this.value)) {
            alert('Please enter a valid expiry date in MM/YYYY format (future date only)');
            this.value = '';
        }
    });
});

// Attach events to a row
function attachRowEvents(row) {
    row.querySelector('.quantity').addEventListener('input', calculateRowTotal);
    row.querySelector('.purchase-price').addEventListener('input', calculateRowTotal);

    const medicineSelect = row.querySelector('.js-medicine-select');
    $(medicineSelect).on('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            const purchasePrice = selectedOption.getAttribute('data-purchase-price');
            const priceInput = row.querySelector('.purchase-price');
            priceInput.value = purchasePrice || '';
            calculateRowTotal.call(this);
        }
    });

    row.querySelector('.remove-item').addEventListener('click', function() {
        if (document.querySelectorAll('.item-row').length > 1) {
            const select = row.querySelector('.js-medicine-select');
            $(select).select2('destroy');
            row.remove();
            calculateGrandTotal();
            reindexRows();
        } else {
            alert('At least one medicine item is required.');
        }
    });
}

// Calculate row total
function calculateRowTotal() {
    const row = this.closest('tr');
    const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
    const purchasePrice = parseFloat(row.querySelector('.purchase-price').value) || 0;
    const total = quantity * purchasePrice;

    row.querySelector('.item-total').value = '₹' + total.toFixed(2);
    row.querySelector('.item-total-hidden').value = total;
    calculateGrandTotal();
}

// Calculate grand total
function calculateGrandTotal() {
    let grandTotal = 0;
    document.querySelectorAll('.item-total-hidden').forEach(input => {
        grandTotal += parseFloat(input.value) || 0;
    });

    document.getElementById('grandTotal').textContent = '₹' + grandTotal.toFixed(2);
    document.getElementById('total_amount').value = grandTotal;
    updateDueAmount();
}

// Update due amount
function updateDueAmount() {
    const totalAmount = parseFloat(document.getElementById('total_amount').value) || 0;
    const paidAmount = parseFloat(document.getElementById('paid_amount').value) || 0;
    const dueAmount = totalAmount - paidAmount;

    document.getElementById('dueAmount').textContent = '₹' + dueAmount.toFixed(2);
    document.getElementById('due_amount').value = dueAmount;
}

// Paid amount change
document.getElementById('paid_amount').addEventListener('input', updateDueAmount);

// Payment status change
$('#payment_status').on('change', function() {
    const totalAmount = parseFloat(document.getElementById('total_amount').value) || 0;

    if (this.value === 'paid') {
        document.getElementById('paid_amount').value = totalAmount;
    } else if (this.value === 'due') {
        document.getElementById('paid_amount').value = 0;
    }

    updateDueAmount();
});

// Re-index rows after removal
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

// Form validation before submit
document.getElementById('purchaseForm').addEventListener('submit', function(e) {
    const itemRows = document.querySelectorAll('.item-row');
    if (itemRows.length === 0) {
        e.preventDefault();
        alert('Please add at least one medicine item.');
        return;
    }

    let isValid = true;

    itemRows.forEach(row => {
        const medicineSelect = row.querySelector('.js-medicine-select');
        const batchNumber = row.querySelector('.batch-number');
        const expiryDate = row.querySelector('.expiry-date');
        const quantity = row.querySelector('.quantity');
        const purchasePrice = row.querySelector('.purchase-price');

        if (!medicineSelect.value || !batchNumber.value || !expiryDate.value ||
            !quantity.value || !purchasePrice.value) {
            isValid = false;
        }

        if (expiryDate.value && !validateExpiryDate(expiryDate.value)) {
            isValid = false;
            alert('Please enter a valid expiry date in MM/YYYY format (future date only).');
            expiryDate.focus();
            return;
        }
    });

    if (!isValid) {
        e.preventDefault();
        alert('Please fill all required fields in medicine items correctly.');
    }
});

// Supplier change event
$('#supplierSelect').on('change', function() {
    const supplierId = $(this).val();

    if (supplierId) {
        currentSupplierId = supplierId;
        loadMedicinesForSupplier(supplierId);
    } else {
        resetSupplierSelection();
    }
});

// Load existing items on page load
$(document).ready(function() {
    initializeSelect2();

    // Load medicines for existing supplier if any
    if (currentSupplierId) {
        loadMedicinesForSupplier(currentSupplierId);

        // Wait for medicines to load then populate items
        setTimeout(() => {
            const existingItems = @json($medicinePurchase->items);

            if (existingItems && existingItems.length > 0) {
                existingItems.forEach((item, index) => {
                    document.getElementById('addItem').click();

                    setTimeout(() => {
                        const rows = document.querySelectorAll('.item-row');
                        const currentRow = rows[rows.length - 1];

                        if (currentRow) {
                            const medicineSelect = currentRow.querySelector('.js-medicine-select');
                            medicineSelect.value = item.medicine_id;
                            $(medicineSelect).trigger('change');

                            currentRow.querySelector('.batch-number').value = item.batch_number;

                            // Handle expiry date format
                            if (item.expiry_date) {
                                currentRow.querySelector('.expiry-date').value = item.expiry_date;
                            }

                            currentRow.querySelector('.quantity').value = item.quantity;
                            currentRow.querySelector('.purchase-price').value = item.purchase_price;

                            calculateRowTotal.call(currentRow.querySelector('.quantity'));
                        }
                    }, 200);
                });
            }
        }, 1000);
    } else {
        $('#supplierAlert').show();
        $('#addItem').prop('disabled', true);
    }

    updateDueAmount();
});
</script>
@endpush
