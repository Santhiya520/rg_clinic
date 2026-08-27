@extends('layouts.app')

@section('title', 'IP Radiology Tests')
@section('page-title', 'IP Radiology Tests - ' . $inpatientRegister->patient->name)

@section('content')
<div class="nk-block nk-block-lg">
    <div class="card card-preview">
        <div class="card-inner">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <p class="text-soft">Patient: <strong>{{ $inpatientRegister->patient->name }}</strong>
                            ({{ $inpatientRegister->patient->patient_id }}) | IP No: {{ $inpatientRegister->hospital_ip_no }}</p>
                        <p class="text-soft">Admission: {{ $inpatientRegister->date_of_admission->format('d/m/Y') }}</p>
                    </div>
                    <div class="nk-block-head-content">
                        <a href="{{ route('radiology.index') }}" class="btn btn-secondary">
                            <em class="icon ni ni-arrow-left"></em>&nbsp; Back to List
                        </a>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Test Name</th>
                            <th>Status</th>
                            <th>Result</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($inpatientRegister->radiologyTests as $test)
                            <tr>
                                <td>{{ $test->created_at->format('d/m/Y h:i A') }}</td>
                                <td>{{ $test->radiologyTest->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $test->status == 'completed' ? 'success' : ($test->status == 'cancelled' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($test->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if ($test->result_document)
                                        <a href="{{ asset('uploads/radiology-documents/' . $test->result_document) }}"
                                            target="_blank" class="btn btn-sm btn-outline-primary">
                                            View Document
                                        </a>
                                    @else
                                        <span class="text-muted">Not Available</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('radiology.ip.edit', $test) }}"
                                        class="btn btn-sm btn-primary" style="border-radius: 5px">
                                        Update Result
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
// Store active tab when clicking back button
document.addEventListener('DOMContentLoaded', function() {
    const backButton = document.querySelector('a[href="{{ route("radiology.index") }}"]');
    if (backButton) {
        backButton.addEventListener('click', function(e) {
            // Store in localStorage
            localStorage.setItem('radiology_active_tab', 'ip');
        });
    }
});
</script>
@endpush
