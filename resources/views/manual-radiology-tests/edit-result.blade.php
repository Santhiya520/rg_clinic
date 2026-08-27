@extends('layouts.app')

@section('title', 'Update Radiology Result')
@section('page-title', 'Update Result - ' . ($manualRadiologyTestItem->radiologyTest->name ?? 'Unknown Test'))

@section('content')
<div class="nk-block nk-block-lg">
    <form action="{{ route('manual-radiology-tests.update-result', ['item' => $manualRadiologyTestItem]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card card-preview">
            <div class="card-inner">
                <div class="nk-block-head mb-4">
                    <div class="nk-block-between">
                        <div>
                            <h6 class="title mb-1">Radiology Result Entry</h6>
                            <p class="text-soft mb-0">
                                <strong>Patient:</strong> {{ $manualRadiologyTestItem->manualRadiologyTest->patient->name }}
                                ({{ $manualRadiologyTestItem->manualRadiologyTest->patient->patient_id }})
                            </p>
                            <p class="text-soft mb-0">
                                <strong>Bill No:</strong> {{ $manualRadiologyTestItem->manualRadiologyTest->reference_no }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="card border mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Test Name</label>
                                    <input type="text" class="form-control bg-light"
                                           value="{{ $manualRadiologyTestItem->radiologyTest->name ?? 'Test Deleted' }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Test Status</label>
                                    <select name="status" class="form-control" required>
                                        <option value="pending" {{ $manualRadiologyTestItem->status == 'pending' ? 'selected' : '' }}>
                                            ⏳ Pending
                                        </option>
                                        <option value="completed" {{ $manualRadiologyTestItem->status == 'completed' ? 'selected' : '' }}>
                                            ✅ Completed
                                        </option>
                                        <option value="cancelled" {{ $manualRadiologyTestItem->status == 'cancelled' ? 'selected' : '' }}>
                                            ❌ Cancelled
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border mb-4">
                    <div class="card-body">
                        <h6 class="card-title mb-3">
                            <i class="fas fa-file-medical-alt text-primary mr-2"></i>
                            Result
                        </h6>
                        <div class="form-group">
                            <textarea class="form-control" name="result" rows="4"
                                      placeholder="Enter radiology test result..."
                                      {{ $manualRadiologyTestItem->status == 'cancelled' ? 'readonly' : '' }}>
                                {{ old('result', $manualRadiologyTestItem->result) }}
                            </textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
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
                                              {{ $manualRadiologyTestItem->status == 'cancelled' ? 'readonly' : '' }}>
                                        {{ old('notes', $manualRadiologyTestItem->notes) }}
                                    </textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border mb-4">
                            <div class="card-body">
                                <h6 class="card-title mb-3">
                                    <i class="fas fa-file-pdf text-primary mr-2"></i>
                                    Result Document
                                </h6>

                                @if($manualRadiologyTestItem->result_document)
                                <div class="mb-3">
                                    <p class="mb-2">Current Document:</p>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-file-pdf text-danger fa-2x mr-3"></i>
                                        <div>
                                            <a href="{{ Storage::url($manualRadiologyTestItem->result_document) }}"
                                               target="_blank" class="d-block">
                                               View Document
                                            </a>
                                            <small class="text-muted">
                                                Uploaded: {{ $manualRadiologyTestItem->updated_at->format('d/m/Y') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <div class="form-group">
                                    <label class="form-label">Upload New Document (PDF/Image)</label>
                                    <input type="file" name="result_document" class="form-control-file"
                                           accept=".pdf,.jpg,.jpeg,.png">
                                    <small class="text-muted">Max file size: 5MB</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if(auth()->user()->role == 'radiology')
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
                        </div>
                    </div>
                </div>
                @endif

                <div class="mt-4 pt-3 border-top">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{ route('manual-radiology-tests.show', $manualRadiologyTestItem->manual_radiology_test_id) }}"
                               class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="btn-group">
                                <button type="submit" class="btn btn-primary"
                                        {{ $manualRadiologyTestItem->status == 'cancelled' ? 'disabled' : '' }}>
                                    <i class="fas fa-save"></i> Save Result
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
