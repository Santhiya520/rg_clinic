@extends('layouts.app')

@section('title', 'Inpatient Register')
@section('page-title', 'Inpatient Register')

@section('content')
<div class="nk-block nk-block-lg">
    <div class="card card-preview">
        <div class="card-inner">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h5 class="nk-block-title">Inpatient Records</h5>
                        <p class="text-soft">Total Records: {{ $inpatients->count() }}</p>
                    </div>
                    <div class="nk-block-head-content">
                        <a href="{{ route('inpatient-register.create') }}" class="btn btn-primary" style="border-radius: 5px">
                            <em class="icon ni ni-plus"></em> &nbsp; Add New Record
                        </a>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert">
                        <em class="icon ni ni-cross"></em>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert">
                        <em class="icon ni ni-cross"></em>
                    </button>
                </div>
            @endif

            <div class="table-responsive" data-ordering="false">
                <table class="table table-bordered table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th width="50">S. No.</th>
                            <th>Patient Name & Address</th>
                            <th>Mobile No.</th>
                            <th width="80">Age</th>
                            <th width="80">Sex</th>
                            <th>Hospital IP No.</th>
                            <th>Admission Date</th>
                            <th>Doctor</th>
                            <th>Discharge Date</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($inpatients as $key => $inpatient)
                        <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td>
                                <strong class="text-primary">{{ $inpatient->patient->name }}</strong><br>
                                <small class="text-muted">{{ Str::limit($inpatient->patient->address, 40) }}</small>
                            </td>
                            <td>
                                <a href="tel:{{ $inpatient->patient->mobile }}" class="text-dark">
                                    {{ $inpatient->patient->mobile }}
                                </a>
                            </td>
                            <td class="text-center">{{ $inpatient->patient->age }}</td>
                            <td class="text-center">
                                <span  style="padding:2px 8px" class="badge bg-{{ $inpatient->patient->sex == 'Male' ? 'primary' : 'pink' }}">
                                    {{ $inpatient->patient->sex }}
                                </span>
                            </td>
                            <td>
                                <strong>{{ $inpatient->hospital_ip_no }}</strong>
                            </td>
                            <td>{{ $inpatient->date_of_admission->format('d/m/Y') }}</td>
                            <td>
                                {{ $inpatient->doctor->name ?? 'N/A' }}
                            </td>
                            <td>
                                @if($inpatient->date_of_discharge)
                                    {{ $inpatient->date_of_discharge->format('d/m/Y') }}
                                @else
                                    <span class="badge badge-dim bg-warning">Still Admitted</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('inpatient-register.show', $inpatient) }}"
                                       class="btn btn-sm btn-info" title="View Details" data-toggle="tooltip">
                                        <em class="icon ni ni-eye"></em>
                                    </a>
                                    <a href="{{ route('inpatient-register.edit', $inpatient) }}"
                                       class="btn btn-sm btn-primary" title="Edit Record" data-toggle="tooltip" style="border-radius: 0px">
                                        <em class="icon ni ni-edit"></em>
                                    </a>
                                    <form action="{{ route('inpatient-register.destroy', $inpatient) }}"
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this inpatient record?')"
                                                title="Delete Record" data-toggle="tooltip">
                                            <em class="icon ni ni-trash"></em>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="12" class="text-center py-4">
                                <p class="mt-3 text-muted">No inpatient records found.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        $('.alert').alert('close');
    }, 5000);
});
</script>
@endsection
