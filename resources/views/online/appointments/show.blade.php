@extends('online.layouts.app')

@section('title', 'Appointment Details')
@section('page-title', 'Appointment Details')

@section('content')
<div class="nk-block">
    <div class="card card-full">
        <div class="card-inner">
            <div class="card-title-group mb-3">
                <div class="card-title">
                    <h6 class="title">Appointment #{{ $appointment->token_number }}</h6>
                </div>
                <div class="card-tools">
                    <a href="{{ route('patient.appointments') }}" class="btn btn-outline-primary">
                        <em class="icon ni ni-arrow-left"></em> Back to List
                    </a>
                </div>
            </div>

            <div class="row g-3">
                <!-- Appointment Details Card -->
                <div class="col-md-12">
                    <div class="card border border-primary">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <strong><em class="icon ni ni-user"></em> Patient Name:</strong>
                                    <div class="text-muted">{{ $patient->name }}</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <strong><em class="icon ni ni-id"></em> Patient ID:</strong>
                                    <div class="text-muted">{{ $patient->patient_id }}</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <strong><em class="icon ni ni-hash"></em> OP Number:</strong>
                                    <div class="text-muted">{{ $appointment->op_no }}</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <strong><em class="icon ni ni-hash"></em> Token Number:</strong>
                                    <div class="badge bg-primary fs-6">#{{ $appointment->token_number }}</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <strong><em class="icon ni ni-user-check"></em> Doctor:</strong>
                                    <div class="text-muted">{{ $appointment->doctor_name ?? 'Not Assigned' }}</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <strong><em class="icon ni ni-calendar"></em> Appointment Date:</strong>
                                    <div class="text-muted">{{ \Carbon\Carbon::parse($appointment->date)->format('d M Y, l') }}</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <strong><em class="icon ni ni-activity"></em> Status:</strong>
                                    <div>
                                        <span class="badge badge-dot bg-{{
                                            $appointment->status == 'confirmed' ? 'success' :
                                            ($appointment->status == 'pending' ? 'warning' :
                                            ($appointment->status == 'cancelled' ? 'danger' : 'primary'))
                                        }}">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <strong><em class="icon ni ni-calendar-date"></em> Booked On:</strong>
                                    <div class="text-muted">{{ \Carbon\Carbon::parse($appointment->created_at)->format('d M Y, h:i A') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>


            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('shareAppointment').addEventListener('click', function() {
    if (navigator.share) {
        navigator.share({
            title: 'My Medical Appointment - RG Maruthuvamaiyam',
            text: `Appointment Details:
OP No: {{ $appointment->op_no }}
Token: #{{ $appointment->token_number }}
Date: {{ \Carbon\Carbon::parse($appointment->date)->format('d M Y') }}
Doctor: {{ $appointment->doctor_name ?? 'Not Assigned' }}
Status: {{ ucfirst($appointment->status) }}`,
            url: window.location.href
        });
    } else {
        // Fallback for browsers that don't support Web Share API
        alert('Appointment details copied to clipboard!');
        navigator.clipboard.writeText(
            `Appointment Details:\nOP No: {{ $appointment->op_no }}\nToken: #{{ $appointment->token_number }}\nDate: {{ \Carbon\Carbon::parse($appointment->date)->format('d M Y') }}\nDoctor: {{ $appointment->doctor_name ?? 'Not Assigned' }}\nStatus: {{ ucfirst($appointment->status) }}\n\nBooked via RG Maruthuvamaiyam Online Portal`
        );
    }
});
</script>
@endpush
