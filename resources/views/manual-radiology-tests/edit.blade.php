@extends('layouts.app')

@section('title', 'Edit Manual Radiology Test')
@section('page-title', 'Edit Test - ' . $manualRadiologyTest->reference_no)

@section('content')
    <div class="nk-block nk-block-lg">
        <form action="{{ route('manual-radiology-tests.update', $manualRadiologyTest) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card card-preview">
                <div class="card-inner">
                    <div class="nk-block-head">
                        <h6 class="title">Edit Radiology Test</h6>
                        <p class="text-soft">Bill No: {{ $manualRadiologyTest->reference_no }}</p>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Patient *</label>
                                <select name="patient_id" class="form-control js-select2" required>
                                    <option value="">Select Patient</option>
                                    @foreach ($patients as $patient)
                                        <option value="{{ $patient->id }}"
                                            {{ $manualRadiologyTest->patient_id == $patient->id ? 'selected' : '' }}>
                                            {{ $patient->name }} ({{ $patient->patient_id }}) - {{ $patient->mobile }}
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
                                    <option value="cash"
                                        {{ old('payment_type', $manualRadiologyTest->payment_type) == 'cash' ? 'selected' : '' }}>
                                        Cash</option>
                                    <option value="card"
                                        {{ old('payment_type', $manualRadiologyTest->payment_type) == 'card' ? 'selected' : '' }}>
                                        Card</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <h6 class="title mb-3">Radiology Tests</h6>
                            <div id="items-container">
                                @foreach ($manualRadiologyTest->items as $index => $item)
                                    <div class="item-row row mb-3">
                                        <div class="col-md-6">
                                            <select name="items[{{ $index }}][radiology_test_id]"
                                                class="form-control radiology-test-select" required>
                                                <option value="">Select Test</option>
                                                @foreach ($radiologyTests as $test)
                                                    <option value="{{ $test->id }}" data-price="{{ $test->price }}"
                                                        {{ $item->radiology_test_id == $test->id ? 'selected' : '' }}>
                                                        {{ $test->name }} - ₹{{ number_format($test->price, 2) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="number" name="items[{{ $index }}][price]"
                                                class="form-control price-input"
                                                value="{{ old('items.' . $index . '.price', $item->price) }}"
                                                step="0.01" min="0" placeholder="Price" required>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger remove-item">Remove</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <button type="button" class="btn btn-primary" id="add-item">
                                <em class="icon ni ni-plus"></em> Add Test
                            </button>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" class="form-control" rows="3" placeholder="Any notes...">{{ old('notes', $manualRadiologyTest->notes) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <em class="icon ni ni-save"></em> Update Test
                        </button>
                        <a href="{{ route('manual-radiology-tests.index') }}" class="btn btn-secondary">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .item-row {
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize select2
            $('.js-select2').select2();

            let itemCount = {{ $manualRadiologyTest->items->count() }};

            // Add item
            $('#add-item').click(function() {
                const newRow = `
            <div class="item-row row mb-3">
                <div class="col-md-6">
                    <select name="items[${itemCount}][radiology_test_id]" class="form-control radiology-test-select" required>
                        <option value="">Select Test</option>
                        @foreach ($radiologyTests as $test)
                            <option value="{{ $test->id }}" data-price="{{ $test->price }}">
                                {{ $test->name }} - ₹{{ number_format($test->price, 2) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="number" name="items[${itemCount}][price]" class="form-control price-input" step="0.01" min="0" placeholder="Price" required>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger remove-item">Remove</button>
                </div>
            </div>
        `;
                $('#items-container').append(newRow);
                itemCount++;
            });

            // Remove item
            $(document).on('click', '.remove-item', function() {
                if ($('.item-row').length > 1) {
                    $(this).closest('.item-row').remove();
                }
            });

            // Auto-fill price when test is selected
            $(document).on('change', '.radiology-test-select', function() {
                const price = $(this).find(':selected').data('price');
                if (price) {
                    $(this).closest('.item-row').find('.price-input').val(price);
                }
            });
        });
    </script>
@endpush
