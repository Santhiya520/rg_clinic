@extends('layouts.app')

@section('page-title', 'Add Manual Lab Test')

@section('content')
<div class="nk-block nk-block-lg">
    <div class="card card-preview">
        <div class="card-inner">
            <div class="preview-block">
                <form id="manualLabTestForm" action="{{ route('manual-lab-tests.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Select Patient <span class="text-danger">*</span></label>
                                <select name="patient_id" id="patient_id" class="form-control searchable-select" required>
                                    <option value="">Search and select patient</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}">
                                            {{ $patient->name }} ({{ $patient->patient_id }}) - {{ $patient->mobile ?? 'No Phone' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Payment Type</label>
                                <select name="payment_type" class="form-control">
                                    <option value="">Select Type</option>
                                    <option value="cash">Cash</option>
                                    <option value="card">Card</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <hr class="mt-4 mb-4">

                    <h6 class="title mb-3">Add Lab Tests</h6>

                    <div id="testItemsContainer">
                        <!-- Test items will be added here dynamically -->
                        <div class="test-item-card card mb-3" data-index="0">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col-md-10">
                                        <strong>Test Item #1</strong>
                                    </div>
                                    <div class="col-md-2 text-end">
                                        <button type="button" class="btn btn-danger btn-sm remove-test-item" onclick="removeTestItem(this)" disabled>
                                            <em class="icon ni ni-trash"></em> Remove
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Select Test <span class="text-danger">*</span></label>
                                            <select name="items[0][lab_test_id]" class="form-control test-select" data-test-index="0" required>
                                                <option value="">Select test</option>
                                                @foreach($labTests as $labTest)
                                                    <option value="{{ $labTest->id }}"
                                                            data-price="{{ $labTest->price }}"
                                                            data-sub-tests='@json($labTest->subTests)'>
                                                        {{ $labTest->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Sub Tests Container (Initially Hidden) -->
                                <div class="sub-tests-container mt-3" id="sub-tests-container-0" style="display: none;">
                                    <div class="card">
                                        <div class="card-header">
                                            <strong>Select Sub Tests</strong>
                                        </div>
                                        <div class="card-body">
                                            <div class="sub-tests-list" id="sub-tests-list-0">
                                                <!-- Sub tests will be loaded here dynamically -->
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Price <span class="text-danger">*</span></label>
                                            <input type="number" name="items[0][price]" class="form-control price-input"
                                                   step="0.01" min="0" required>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label class="form-label">Notes</label>
                                            <textarea name="items[0][notes]" class="form-control" rows="2" placeholder="Enter test notes..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-success btn-sm" onclick="addTestItem()">
                                <em class="icon ni ni-plus"></em> Add Test
                            </button>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Overall Notes</label>
                                <textarea name="notes" class="form-control" rows="3" placeholder="Enter overall notes..."></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Summary</label>
                                <div class="border p-3 bg-light">
                                    <p><strong>Total Tests:</strong> <span id="totalTestsCount">1</span></p>
                                    <p><strong>Total Amount:</strong> ₹<span id="totalAmount">0.00</span></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="mt-4 mb-4">

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <em class="icon ni ni-save"></em> Save Manual Lab Test
                        </button>
                        <a href="{{ route('manual-lab-tests.index') }}" class="btn btn-secondary">
                            <em class="icon ni ni-arrow-left"></em> Back
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let testItemCount = 1;

function addTestItem() {
    const container = document.getElementById('testItemsContainer');
    const testItemCard = document.createElement('div');
    testItemCard.className = 'test-item-card card mb-3';
    testItemCard.setAttribute('data-index', testItemCount);

    testItemCard.innerHTML = `
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-md-10">
                    <strong>Test Item #${testItemCount + 1}</strong>
                </div>
                <div class="col-md-2 text-end">
                    <button type="button" class="btn btn-danger btn-sm remove-test-item" onclick="removeTestItem(this)">
                        <em class="icon ni ni-trash"></em> Remove
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-label">Select Test <span class="text-danger">*</span></label>
                        <select name="items[${testItemCount}][lab_test_id]" class="form-control test-select" data-test-index="${testItemCount}" required>
                            <option value="">Select test</option>
                            @foreach($labTests as $labTest)
                                <option value="{{ $labTest->id }}"
                                        data-price="{{ $labTest->price }}"
                                        data-sub-tests='@json($labTest->subTests)'>
                                    {{ $labTest->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Sub Tests Container (Initially Hidden) -->
            <div class="sub-tests-container mt-3" id="sub-tests-container-${testItemCount}" style="display: none;">
                <div class="card">
                    <div class="card-header">
                        <strong>Select Sub Tests</strong>
                    </div>
                    <div class="card-body">
                        <div class="sub-tests-list" id="sub-tests-list-${testItemCount}">
                            <!-- Sub tests will be loaded here dynamically -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Price <span class="text-danger">*</span></label>
                        <input type="number" name="items[${testItemCount}][price]" class="form-control price-input"
                               step="0.01" min="0" required>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="form-label">Notes</label>
                        <textarea name="items[${testItemCount}][notes]" class="form-control" rows="2" placeholder="Enter test notes..."></textarea>
                    </div>
                </div>
            </div>
        </div>
    `;

    container.appendChild(testItemCard);

    // Initialize Select2 for the new test select
    $(testItemCard).find('.test-select').select2({
        placeholder: 'Search and select lab test',
        allowClear: true,
        width: '100%'
    });

    testItemCount++;
    updateSummary();

    // Enable remove button on first row if we have more than 1
    if (document.querySelectorAll('.test-item-card').length > 1) {
        const firstRemoveBtn = document.querySelector('.test-item-card:first-child .remove-test-item');
        if (firstRemoveBtn) firstRemoveBtn.disabled = false;
    }
}

function removeTestItem(button) {
    const testCard = button.closest('.test-item-card');
    if (document.querySelectorAll('.test-item-card').length <= 1) {
        alert('At least one test is required!');
        return;
    }

    testCard.remove();

    // Reindex all test cards
    const cards = document.querySelectorAll('.test-item-card');
    cards.forEach((card, index) => {
        card.setAttribute('data-index', index);

        // Update header text
        const headerStrong = card.querySelector('.card-header strong');
        if (headerStrong) {
            headerStrong.textContent = `Test Item #${index + 1}`;
        }

        // Update select name and data attributes
        const select = card.querySelector('.test-select');
        if (select) {
            select.name = `items[${index}][lab_test_id]`;
            select.setAttribute('data-test-index', index);
        }

        // Update sub-tests container ID
        const subTestContainer = card.querySelector('.sub-tests-container');
        if (subTestContainer) {
            subTestContainer.id = `sub-tests-container-${index}`;
        }

        // Update sub-tests list ID
        const subTestList = card.querySelector('.sub-tests-list');
        if (subTestList) {
            subTestList.id = `sub-tests-list-${index}`;
        }

        // Update price input name
        const priceInput = card.querySelector('.price-input');
        if (priceInput) {
            priceInput.name = `items[${index}][price]`;
        }

        // Update notes textarea name
        const notesTextarea = card.querySelector('textarea[name^="items"]');
        if (notesTextarea) {
            notesTextarea.name = `items[${index}][notes]`;
        }
    });

    testItemCount = cards.length;
    updateSummary();

    // Disable remove button on first row if only one remains
    if (cards.length === 1) {
        const firstRemoveBtn = cards[0].querySelector('.remove-test-item');
        if (firstRemoveBtn) firstRemoveBtn.disabled = true;
    }
}

// Handle test selection change (including sub-tests loading)
$(document).on('change', '.test-select', function() {
    const testCard = $(this).closest('.test-item-card');
    const testIndex = $(this).data('test-index');
    const selectedOption = $(this).find('option:selected');
    const price = selectedOption.data('price');
    const subTestsData = selectedOption.data('sub-tests');

    // Set price
    const priceInput = testCard.find('.price-input');
    if (price && priceInput.length) {
        priceInput.val(price);
    }

    // Load sub tests
    const subTestsContainer = $(`#sub-tests-container-${testIndex}`);
    const subTestsList = $(`#sub-tests-list-${testIndex}`);

    if (subTestsData && subTestsData.length > 0) {
        subTestsList.empty();

        subTestsData.forEach(function(subTest, subIndex) {
            const subTestHtml = `
                <div class="sub-test-item mb-3 p-2 border rounded">
                    <div class="form-check">
                        <input type="checkbox"
                               class="form-check-input sub-test-checkbox"
                               name="items[${testIndex}][sub_tests][${subTest.id}][checked]"
                               value="1"
                               id="sub-test-${testIndex}-${subTest.id}"
                               data-sub-test-id="${subTest.id}"
                               data-test-index="${testIndex}">
                        <label class="form-check-label fw-bold" for="sub-test-${testIndex}-${subTest.id}">
                            ${subTest.name}
                        </label>
                        <div class="sub-test-info mt-1 small text-muted">
                            <span>Unit: ${subTest.unit || 'N/A'}</span> |
                            <span>Normal Range: ${subTest.normal_range || 'N/A'}</span>
                        </div>
                        <input type="hidden" name="items[${testIndex}][sub_tests][${subTest.id}][test_name]" value="${subTest.name}">
                        <input type="hidden" name="items[${testIndex}][sub_tests][${subTest.id}][unit]" value="${subTest.unit || ''}">
                        <input type="hidden" name="items[${testIndex}][sub_tests][${subTest.id}][normal_range]" value="${subTest.normal_range || ''}">
                    </div>
                </div>
            `;
            subTestsList.append(subTestHtml);
        });

        subTestsContainer.show();
    } else {
        subTestsContainer.hide();
    }

    updateSummary();
});

// Update summary when price changes
$(document).on('change keyup', '.price-input', function() {
    updateSummary();
});

// Update summary function
function updateSummary() {
    const totalTests = document.querySelectorAll('.test-item-card').length;
    document.getElementById('totalTestsCount').textContent = totalTests;

    let totalAmount = 0;
    document.querySelectorAll('.price-input').forEach(input => {
        const price = parseFloat(input.value) || 0;
        totalAmount += price;
    });

    document.getElementById('totalAmount').textContent = totalAmount.toFixed(2);
}

// Initialize
$(document).ready(function() {
    // Initialize select2 for patient search
    $('.searchable-select').select2({
        placeholder: 'Search patient by name, ID or phone',
        allowClear: true,
        width: '100%'
    });

    // Initialize select2 for test selects
    $('.test-select').select2({
        placeholder: 'Search and select lab test',
        allowClear: true,
        width: '100%'
    });

    // Update summary on page load
    updateSummary();
});
</script>

<style>
.test-item-card {
    border: 1px solid #e5e9f2;
    border-radius: 8px;
    overflow: hidden;
}

.test-item-card .card-header {
    background-color: #f8f9fa;
    padding: 12px 15px;
    border-bottom: 1px solid #e5e9f2;
}

.test-item-card .card-body {
    padding: 20px;
}

.sub-test-item {
    background-color: #ffffff;
    transition: all 0.2s;
}

.sub-test-item:hover {
    background-color: #f8f9fa;
}

.sub-test-info {
    margin-left: 25px;
    padding-left: 5px;
    border-left: 2px solid #e5e9f2;
}

.select2-container {
    width: 100% !important;
}

.remove-test-item {
    margin-bottom: 0;
}
</style>
@endpush
