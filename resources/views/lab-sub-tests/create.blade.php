@extends('layouts.app')

@section('page-title', 'Add Lab Sub Tests')

@section('content')
<div class="nk-block nk-block-lg">
    <div class="card card-preview">
        <div class="card-inner">
            <div class="preview-block">
                <form id="subTestForm" action="{{ isset($labTest) ? route('lab-sub-tests.update', $labTest) : route('lab-sub-tests.store') }}" method="POST">
                    @if(isset($labTest))
                        @method('PUT')
                    @endif
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="lab_test_id">Select Lab Test <span class="text-danger">*</span></label>
                                <select name="lab_test_id" id="lab_test_id" class="form-control searchable-select" required {{ isset($labTest) ? 'disabled' : '' }}>
                                    <option value="">Select Lab Test</option>
                                    @foreach($labTests as $test)
                                        <option value="{{ $test->id }}" {{ (isset($labTest) && $labTest->id == $test->id) ? 'selected' : '' }}>
                                            {{ $test->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @if(isset($labTest))
                                    <input type="hidden" name="lab_test_id" value="{{ $labTest->id }}">
                                @endif
                            </div>
                        </div>
                    </div>

                    <hr class="mt-4 mb-4">

                    <h6 class="title mb-3">Add Sub Tests</h6>

                    <div id="subTestsContainer">
                        <!-- Sub tests will be added here dynamically -->
                        @if(isset($labTest) && $labTest->subTests->count() > 0)
                            @foreach($labTest->subTests as $index => $subTest)
                            <div class="row sub-test-row mb-3" data-index="{{ $index }}">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Sub Test Name <span class="text-danger">*</span></label>
                                        <input type="text" name="sub_tests[{{ $index }}][name]"
                                               class="form-control" value="{{ $subTest->name }}" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Unit</label>
                                        <input type="text" name="sub_tests[{{ $index }}][unit]"
                                               class="form-control" value="{{ $subTest->unit }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Normal Range</label>
                                        <input type="text" name="sub_tests[{{ $index }}][normal_range]"
                                               class="form-control" value="{{ $subTest->normal_range }}">
                                    </div>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger btn-sm remove-sub-test" onclick="removeSubTest(this)">
                                        <i class="fas fa-trash"></i> Remove
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-success btn-sm" onclick="addSubTest()">
                                <i class="fas fa-plus"></i> Add Sub Test
                            </button>
                        </div>
                    </div>

                    <hr class="mt-4 mb-4">

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> {{ isset($labTest) ? 'Update' : 'Save' }}
                        </button>
                        <a href="{{ route('lab-sub-tests.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
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
    let subTestCount = {{ isset($labTest) ? $labTest->subTests->count() : 0 }};

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

    // Initialize Select2 for searchable dropdown
    $(document).ready(function() {
        $('.searchable-select').select2({
            placeholder: 'Search and select lab test',
            allowClear: true
        });

        // Add at least one sub test if none exist
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
</style>
@endpush
