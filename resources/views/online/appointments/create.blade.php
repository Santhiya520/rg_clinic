@extends('online.layouts.app')

@section('title', 'Book Online Appointment')
@section('page-title', 'Book Now')

@section('content')
<div class="nk-block">
    <div class="card card-full">
        <div class="card-inner">
            <div class="card-title-group mb-3">
                <div class="card-title">
                    <h6 class="title">Book Now</h6>
                </div>
                <div class="card-tools">
                    <a href="{{ route('patient.appointments') }}" class="btn btn-outline-primary">
                        <em class="icon ni ni-arrow-left"></em> Back to List
                    </a>
                </div>
            </div>

            <form action="{{ route('patient.appointments.store') }}" method="POST" id="appointmentForm">
                @csrf

                <div class="row g-3">
                    <!-- OP Number (Readonly) -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="op_no">OP Number</label>
                            <div class="form-control-wrap">
                                <input type="text"
                                       class="form-control bg-light"
                                       id="op_no"
                                       name="op_no"
                                       value="{{ App\Models\OpRegister::generateOpNo() }}"
                                       readonly>
                                <small class="form-text text-muted">Auto-generated OP number</small>
                            </div>
                        </div>
                    </div>

                    <!-- Token Number (Readonly) -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="token_number">Token Number</label>
                            <div class="form-control-wrap">
                                <input type="text"
                                       class="form-control bg-light"
                                       id="token_number"
                                       name="token_number"
                                       value="{{ App\Models\OpRegister::generateTokenNumber() }}"
                                       readonly>
                                <small class="form-text text-muted">Auto-generated token number</small>
                            </div>
                        </div>
                    </div>

                    <!-- Select Doctor -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="medical_officer_id">Select Doctor *</label>
                            <div class="form-control-wrap">
                                <select class="form-select" id="medical_officer_id" name="medical_officer_id" required>
                                    <option value="">-- Select Doctor --</option>
                                    @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" >
                                        Dr. {{ $doctor->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Select Date -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="date">Select Date *</label>
                            <div class="form-control-wrap">
                                <input type="date"
                                       class="form-control"
                                       id="date"
                                       name="date"
                                       min="{{ $minDate }}"
                                       max="{{ $maxDate }}"
                                       required>
                                <small class="form-text text-muted">Select date between {{ $minDate }} and {{ $maxDate }}</small>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="col-12">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <em class="icon ni ni-calendar-plus"></em> Book Appointment
                            </button>
                            <a href="{{ route('patient.appointments') }}" class="btn btn-outline-light btn-lg">Cancel</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>



<style>
.summary-item {
    padding: 10px 0;
    border-bottom: 1px solid #e5e5e5;
}
.summary-item:last-child {
    border-bottom: none;
}
.summary-item strong {
    display: block;
    color: #666;
    font-size: 0.875rem;
    margin-bottom: 5px;
}
.summary-item div {
    font-size: 1rem;
    font-weight: 500;
}
</style>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let selectedDoctor = $('#medical_officer_id');
    let selectedDate = $('#date');

    // Update summary when doctor is selected
    selectedDoctor.on('change', function() {
        updateSummary();
    });

    // Update summary when date is selected
    selectedDate.on('change', function() {
        updateSummary();
    });

    // Update appointment summary
    function updateSummary() {
        let doctorOption = selectedDoctor.find('option:selected');
        let doctorText = doctorOption.text() || 'Not selected';
        let date = selectedDate.val() ? new Date(selectedDate.val()).toLocaleDateString('en-US', {
            weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
        }) : '-';

        $('#summary_doctor').text(doctorText);
        $('#summary_date').text(date);
    }

    // Form validation
    $('#appointmentForm').on('submit', function(e) {
        let doctorId = selectedDoctor.val();
        let date = selectedDate.val();

        if (!doctorId) {
            e.preventDefault();
            alert('Please select a doctor.');
            return false;
        }

        if (!date) {
            e.preventDefault();
            alert('Please select a date.');
            return false;
        }

        // Confirm booking
        if (!confirm('Are you sure you want to book this appointment?')) {
            return false;
        }

        return true;
    });
});
</script>
@endpush
