@extends('layouts.app')

@section('title', 'Update Lab Test Result')
@section('page-title', 'Update Result - ' . ($manualLabTestItem->labTest->name ?? 'Unknown Test'))

@section('content')
<div class="nk-block nk-block-lg">
    {{-- Check if labTest exists --}}
    @if(!$manualLabTestItem->labTest)
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>Error:</strong> The associated lab test could not be found. It may have been deleted.
        </div>
        <div class="text-center mt-4">

        </div>
        @return
    @endif

    <form action="{{ route('manual-lab-test-items.update-result', ['item' => $manualLabTestItem]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- MESSAGES --}}
        @if (session('success'))
            <div class="alert alert-success mb-3">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger mb-3">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card card-preview">
            <div class="card-inner">

                {{-- HEADER --}}
                <div class="nk-block-head mb-4">
                    <div class="nk-block-between">
                        <div>
                            <h6 class="title mb-1">Test Result Entry</h6>
                            <p class="text-soft mb-0">
                                <strong>Patient:</strong> {{ $manualLabTestItem->manualLabTest->patient->name }}
                                ({{ $manualLabTestItem->manualLabTest->patient->patient_id }})
                            </p>
                            <p class="text-soft mb-0">
                                <strong>Bill No:</strong> {{ $manualLabTestItem->manualLabTest->reference_no }}
                            </p>
                        </div>

                        <div class="btn-group">

                            <button type="submit" class="btn btn-primary">
                                <em class="icon ni ni-save"></em> &nbsp; Save Result
                            </button>
                        </div>
                    </div>
                </div>

                {{-- TEST INFO CARD --}}
                <div class="card border mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Test Name</label>
                                    <input type="text" class="form-control bg-light"
                                           value="{{ $manualLabTestItem->labTest->name ?? 'Test Deleted' }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Test Status</label>
                                    <select name="status" class="form-control" required>
                                        <option value="pending" {{ $manualLabTestItem->status == 'pending' ? 'selected' : '' }}>
                                            ⏳ Pending
                                        </option>
                                        <option value="completed" {{ $manualLabTestItem->status == 'completed' ? 'selected' : '' }}>
                                            ✅ Completed
                                        </option>
                                        <option value="cancelled" {{ $manualLabTestItem->status == 'cancelled' ? 'selected' : '' }}>
                                            ❌ Cancelled
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        @if($manualLabTestItem->completed_at)
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info mb-0">
                                    <em class="icon ni ni-info"></em>
                                    Test was completed on: {{ $manualLabTestItem->completed_at->format('d/m/Y h:i A') }}
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- SUB TESTS SECTION --}}
                @if($manualLabTestItem->subTests->isNotEmpty())
                <div class="card border mb-4">
                    <div class="card-body">
                        <h6 class="card-title mb-3">
                            <i class="fas fa-flask text-primary mr-2"></i>
                            Test Parameters
                        </h6>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="30%">Parameter Name</th>
                                        <th width="15%">Unit</th>
                                        <th width="20%">Normal Range</th>
                                        <th width="30%">Result</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($manualLabTestItem->subTests as $index => $subTest)
                                    @php
                                        $isAbnormal = $subTest->isAbnormal();
                                    @endphp
                                    <tr class="{{ $isAbnormal ? 'table-danger' : '' }}">
                                        <td class="text-center align-middle">{{ $index + 1 }}</td>
                                        <td class="align-middle">
                                            <strong>{{ $subTest->test_name }}</strong>
                                        </td>
                                        <td class="align-middle">{{ $subTest->unit ?? '-' }}</td>
                                        <td class="align-middle">
                                            <span class="badge bg-info">{{ $subTest->normal_range ?? '-' }}</span>
                                        </td>
                                        <td>
                                            <input type="text"
                                                   name="sub_tests[{{ $subTest->id }}][result]"
                                                   class="form-control {{ $isAbnormal ? 'border-danger' : '' }}"
                                                   value="{{ old('sub_tests.' . $subTest->id . '.result', $subTest->result) }}"
                                                   placeholder="Enter result"
                                                   {{ $manualLabTestItem->status == 'cancelled' ? 'readonly' : '' }}>
                                            @if($isAbnormal)
                                                <small class="text-danger">
                                                    <i class="fas fa-exclamation-triangle"></i> Abnormal
                                                </small>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                {{-- NOTES & DOCUMENT --}}
                <div class="row">
                    <div class="col-md-6">
                        {{-- OVERALL RESULT --}}
                <div class="card border mb-4">
                    <div class="card-body">
                        <h6 class="card-title mb-3">
                            <i class="fas fa-file-medical-alt text-primary mr-2"></i>
                            Overall Result Summary
                        </h6>
                        <div class="form-group">
                            <textarea class="form-control" name="result" rows="4"
                                      placeholder="Enter overall test result summary..."
                                      {{ $manualLabTestItem->status == 'cancelled' ? 'readonly' : '' }}>
                                {{ old('result', $manualLabTestItem->result) }}
                            </textarea>
                        </div>
                    </div>
                </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border mb-4">
                            <div class="card-body">
                                <h6 class="card-title mb-3">
                                    <i class="fas fa-sticky-note text-primary mr-2"></i>
                                    Notes
                                </h6>
                                <div class="form-group">
                                    <textarea class="form-control" name="notes" rows="3"
                                              placeholder="Enter any notes..."
                                              {{ $manualLabTestItem->status == 'cancelled' ? 'readonly' : '' }}>
                                        {{ old('notes', $manualLabTestItem->notes) }}
                                    </textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- TECHNICIAN INFO --}}
                @if(auth()->user()->role == 'lab')
                <div class="card border mb-4">
                    <div class="card-body">
                        <h6 class="card-title mb-3">
                            <i class="fas fa-user-md text-primary mr-2"></i>
                            Technician Information
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Technician</label>
                                    <input type="text" class="form-control bg-light"
                                           value="{{ auth()->user()->name }}" readonly>
                                    <input type="hidden" name="technician_id" value="{{ auth()->user()->id }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Completion Date</label>
                                    <input type="text" class="form-control bg-light"
                                           value="{{ $manualLabTestItem->completed_at ? $manualLabTestItem->completed_at->format('d/m/Y h:i A') : 'Not completed yet' }}"
                                           readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- ACTION BUTTONS --}}
                <div class="mt-4 pt-3 border-top">
    <div class="d-flex justify-content-between align-items-center">


        <div class="btn-group">
            <button type="submit" class="btn btn-primary"
                    {{ $manualLabTestItem->status == 'cancelled' ? 'disabled' : '' }}>
                <i class="fas fa-save"></i> Save Result
            </button>

            <a href="{{ route('manual-lab-tests.show', $manualLabTestItem->manual_lab_test_id) }}"
           class="btn btn-secondary">
            <i class="fas fa-times"></i> Cancel
        </a>
        </div>
    </div>
</div>

            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Mark Complete button functionality
    $('#markCompleteBtn').on('click', function() {
        // Set status to completed
        $('select[name="status"]').val('completed').trigger('change');

        // Auto-fill empty result fields
        $('input[name^="sub_tests"]').each(function() {
            if (!$(this).val().trim()) {
                $(this).val('Normal');
            }
        });

        // Auto-fill overall result if empty
        if (!$('textarea[name="result"]').val().trim()) {
            $('textarea[name="result"]').val('All parameters within normal range. Test completed successfully.');
        }

        // Submit form
        $('form').submit();
    });

    // Disable fields if status is cancelled
    $('select[name="status"]').on('change', function() {
        const status = $(this).val();
        const isCancelled = status === 'cancelled';

        $('input[name^="sub_tests"], textarea[name="result"], textarea[name="notes"]')
            .prop('readonly', isCancelled);
    });

    // Auto-format numeric inputs
    $('input[name^="sub_tests"]').on('blur', function() {
        const val = $(this).val().trim();
        if (val && !isNaN(val) && val !== '') {
            // Round to 2 decimal places for numeric values
            $(this).val(parseFloat(val).toFixed(2));
        }
    });
});
</script>
@endpush
