@extends('online.layouts.app')

@section('title', 'Online Appointments')
@section('page-title', 'My Online Appointments')

@section('content')
<div class="nk-block">
    <div class="card card-full">
        <div class="card-inner">
            <div class="card-title-group mb-3">
                <div class="card-title">
                    <h6 class="title">Online Appointment List</h6>
                </div>
                <div class="card-tools">
                    <a href="{{ route('patient.appointments.create') }}" class="btn btn-primary">
                        <em class="icon ni ni-plus"></em> Book New Appointment
                    </a>
                </div>
            </div>

            @if(session('success'))
            <div class="alert alert-success alert-icon">
                <em class="icon ni ni-check-circle"></em> {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-icon">
                <em class="icon ni ni-cross-circle"></em> {{ session('error') }}
            </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>OP No</th>
                            <th>Token No</th>
                            <th>Date</th>
                            <th>Doctor</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appointments as $appointment)
                        <tr>
                            <td>{{ $appointment->op_no }}</td>
                            <td><span class="badge bg-primary">#{{ $appointment->token_number }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($appointment->date)->format('d M Y') }}</td>
                            <td>{{ $appointment->doctor_name ?? 'Not Assigned' }}</td>
                            <td>
                                <span class="badge badge-dot bg-{{
                                    $appointment->status == 'confirmed' ? 'success' :
                                    ($appointment->status == 'pending' ? 'warning' :
                                    ($appointment->status == 'cancelled' ? 'danger' : 'primary'))
                                }}">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('patient.appointments.show', $appointment->id) }}"
                                       class="btn btn-sm btn-outline-primary" title="View">
                                        <em class="icon ni ni-eye"></em>
                                    </a>

                                    @if($appointment->status != 'completed')

                                    <form action="{{ route('patient.appointments.destroy', $appointment->id) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to cancel this appointment?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Cancel">
                                            <em class="icon ni ni-trash"></em>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <em class="icon ni ni-calendar text-muted" style="font-size: 2rem;"></em>
                                <p class="mt-2 text-muted">No online appointments found</p>
                                <a href="{{ route('patient.appointments.create') }}" class="btn btn-primary mt-2">
                                    Book Your First Appointment
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($appointments->hasPages())
            <div class="mt-3">
                {{ $appointments->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
