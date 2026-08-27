@extends('layouts.app')

@section('page-title', 'Edit Lab Sub Tests')

@section('content')
<div class="nk-block nk-block-lg">
    <div class="card card-preview">
        <div class="card-inner">
            <div class="preview-block">
                <form id="subTestForm" action="{{ route('lab-sub-tests.update', $labTest) }}" method="POST">
                    @method('PUT')
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="lab_test_id">Lab Test <span class="text-danger">*</span></label>
                                <select name="lab_test_id" id="lab_test_id" class="form-control" disabled>
                                    <option value="{{ $labTest->id }}" selected>{{ $labTest->name }}</option>
                                </select>
                                <input type="hidden" name="lab_test_id" value="{{ $labTest->id }}">
                                <small class="text-muted">You cannot change the lab test in edit mode. Delete and recreate if needed.</small>
                            </div>
                        </div>
                    </div>

                    <hr class="mt-4 mb-4">

                    <h6 class="title mb-3">Edit Sub Tests</h6>

                    <div id="subTestsContainer">
                        <!-- Sub tests will be added here dynamically -->
                        @foreach($labTest->subTests as $index => $subTest)
                        <div class="row sub-test-row mb-3" data-index="{{ $index }}">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Sub Test Name <span class="text-danger">*</span></label>
                                    <input type="text" name="sub_tests[{{ $index }}][name]"
                                           class="form-control" value="{{ old('sub_tests.' . $index . '.name', $subTest->name) }}" required>
                                    @error('sub_tests.' . $index . '.name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Unit</label>
                                    <input type="text" name="sub_tests[{{ $index }}][unit]"
                                           class="form-control" value="{{ old('sub_tests.' . $index . '.unit', $subTest->unit) }}">
                                    @error('sub_tests.' . $index . '.unit')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Normal Range</label>
                                    <input type="text" name="sub_tests[{{ $index }}][normal_range]"
                                           class="form-control" value="{{ old('sub_tests.' . $index . '.normal_range', $subTest->normal_range) }}">
                                    @error('sub_tests.' . $index . '.normal_range')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-danger btn-sm remove-sub-test" onclick="removeSubTest(this)">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-success btn-sm" onclick="addSubTest()">
                                <i class="fas fa-plus"></i> Add More Sub Test
                            </button>
                        </div>
                    </div>

                    <hr class="mt-4 mb-4">

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Sub Tests
                        </button>
                        <a href="{{ route('lab-sub-tests.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
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
    let subTestCount = {{ $labTest->subTests->count() }};

    function addSubTest() {
        const container = document.getElementById('subTestsContainer');
        const row = document.createElement('div');
        row.className = 'row sub-test-row mb-3';
        row.setAttribute('data-index', subTestCount);

        row.innerHTML = `
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label">Sub Test Name <span class="text-danger">*</span></label>
                    <input type="text" name="sub_tests[${subTestCount}][name]"
                           class="form-control" required>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-label">Unit</label>
                    <input type="text" name="sub_tests[${subTestCount}][unit]"
                           class="form-control">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-label">Normal Range</label>
                    <input type="text" name="sub_tests[${subTestCount}][normal_range]"
                           class="form-control">
                </div>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-danger btn-sm remove-sub-test" onclick="removeSubTest(this)">
                    <i class="fas fa-trash"></i> Remove
                </button>
            </div>
        `;

        container.appendChild(row);
        subTestCount++;
    }

    function removeSubTest(button) {
        const row = button.closest('.sub-test-row');
        if (document.querySelectorAll('.sub-test-row').length <= 1) {
            alert('At least one sub test is required!');
            return;
        }
        row.remove();

        // Reindex all remaining rows
        const rows = document.querySelectorAll('.sub-test-row');
        rows.forEach((row, index) => {
            row.setAttribute('data-index', index);
            const inputs = row.querySelectorAll('input[name^="sub_tests"]');
            inputs.forEach(input => {
                const name = input.name;
                const newName = name.replace(/sub_tests\[\d+\]/, `sub_tests[${index}]`);
                input.name = newName;
            });
        });

        subTestCount = rows.length;
    }

    // Initialize Select2 for searchable dropdown (disabled in edit mode)
    $(document).ready(function() {
        $('#lab_test_id').select2({
            placeholder: 'Lab Test',
            disabled: true
        });

        // Ensure at least one sub test exists
        if (subTestCount === 0) {
            addSubTest();
        }
    });
</script>

<style>
    .select2-container {
        width: 100% !important;
    }
    .sub-test-row {
        border-bottom: 1px solid #eee;
        padding-bottom: 15px;
    }
    .remove-sub-test {
        margin-bottom: 8px;
    }
    .select2-container--disabled .select2-selection {
        background-color: #f8f9fa;
        cursor: not-allowed;
    }
</style>
@endpush
