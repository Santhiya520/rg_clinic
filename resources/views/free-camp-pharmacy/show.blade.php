@extends('layouts.app')

@section('title', 'View Free Camp Pharmacy')
@section('page-title', 'View Free Camp Pharmacy Record')

@section('content')
<div class="nk-block nk-block-lg">
    <div class="card card-preview">
        <div class="card-inner">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="card-title mb-0">Free Camp Pharmacy Record Details</h5>
                <div>
                    <a href="{{ route('free-camp-pharmacy.edit', $record->id) }}" class="btn btn-info">
                        <em class="icon ni ni-edit"></em> Edit
                    </a>
                    <a href="{{ route('free-camp-pharmacy.print-thermal', $record->id) }}" target="_blank" class="btn btn-success">
                        <em class="icon ni ni-printer"></em> Print
                    </a>
                    <a href="{{ route('free-camp-pharmacy.index') }}" class="btn btn-outline-primary">
                        <em class="icon ni ni-arrow-left"></em> Back to List
                    </a>
                </div>
            </div>

            <div class="row g-4">
                <!-- Token Number -->
                <div class="col-md-6">
                    <div class="info-card p-3 border rounded">
                        <label class="text-muted small">Token Number</label>
                        <h5 class="mb-0 fw-bold">{{ $record->token_number }}</h5>
                    </div>
                </div>

                <!-- Patient Name -->
                <div class="col-md-6">
                    <div class="info-card p-3 border rounded">
                        <label class="text-muted small">Patient Name</label>
                        <h5 class="mb-0 fw-bold">{{ $record->patient_name }}</h5>
                    </div>
                </div>

                <!-- Mobile Number -->
                <div class="col-md-6">
                    <div class="info-card p-3 border rounded">
                        <label class="text-muted small">Mobile Number</label>
                        <h5 class="mb-0">{{ $record->mobile_number ?? 'N/A' }}</h5>
                    </div>
                </div>

                <!-- Age & Gender -->
                <div class="col-md-6">
                    <div class="info-card p-3 border rounded">
                        <label class="text-muted small">Age / Gender</label>
                        <h5 class="mb-0">
                            @if($record->age)
                                {{ $record->age }} years
                                @if($record->gender)
                                    / {{ ucfirst($record->gender) }}
                                @endif
                            @else
                                N/A
                            @endif
                        </h5>
                    </div>
                </div>

                <!-- Address (Full Width) -->
                <div class="col-12">
                    <div class="info-card p-3 border rounded">
                        <label class="text-muted small">Address</label>
                        <p class="mb-0">{{ $record->address ?? 'No address provided' }}</p>
                    </div>
                </div>

                <!-- Created Date -->
                <div class="col-md-6">
                    <div class="info-card p-3 border rounded">
                        <label class="text-muted small">Created Date</label>
                        <h5 class="mb-0">{{ date('d/m/Y h:i A', strtotime($record->created_at)) }}</h5>
                    </div>
                </div>

                <!-- Last Updated -->
                <div class="col-md-6">
                    <div class="info-card p-3 border rounded">
                        <label class="text-muted small">Last Updated</label>
                        <h5 class="mb-0">{{ date('d/m/Y h:i A', strtotime($record->updated_at)) }}</h5>
                    </div>
                </div>

                <!-- Remarks (Full Width) -->
                @if($record->remarks)
                <div class="col-12">
                    <div class="info-card p-3 border rounded">
                        <label class="text-muted small">Remarks</label>
                        <p class="mb-0">{{ $record->remarks }}</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Action Buttons at Bottom -->
            <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                <form action="{{ route('free-camp-pharmacy.destroy', $record->id) }}"
                      method="POST"
                      onsubmit="return confirm('Are you sure you want to delete this record?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <em class="icon ni ni-trash"></em> Delete Record
                    </button>
                </form>

                <div>
                    <a href="{{ route('free-camp-pharmacy.edit', $record->id) }}" class="btn btn-info me-2">
                        <em class="icon ni ni-edit"></em> Edit
                    </a>
                    <a href="{{ route('free-camp-pharmacy.index') }}" class="btn btn-light">
                        <em class="icon ni ni-arrow-left"></em> Back
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .info-card {
        background-color: #f8f9fa;
        transition: all 0.2s;
        height: 100%;
    }
    .info-card:hover {
        background-color: #fff;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        border-color: #0d6efd !important;
    }
    .info-card label {
        font-size: 0.85rem;
        margin-bottom: 5px;
        display: block;
    }
    .info-card h5 {
        font-size: 1.1rem;
        color: #333;
    }
    .info-card p {
        font-size: 1rem;
        color: #555;
        line-height: 1.6;
    }
    .border-top {
        border-top: 1px solid #dee2e6 !important;
    }
</style>
@endpush
