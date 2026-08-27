@extends('layouts.app')

@section('title', 'Free Camp Pharmacy')
@section('page-title', 'Free Camp Pharmacy Management')

@section('content')
<div class="nk-block nk-block-lg">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="close" data-bs-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="close" data-bs-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Validation Error!</strong> Please check the form and try again.
            <button type="button" class="close" data-bs-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <div class="card card-preview">
        <div class="card-inner">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="card-title mb-0">Free Camp Pharmacy Records</h5>
                <a href="{{ route('free-camp-pharmacy.create') }}" class="btn btn-primary">
                    <em class="icon ni ni-plus"></em> Add New Free Record
                </a>
            </div>

            <div class="table-responsive">
                <table id="freeCampPharmacyTable" class="table table-hover">
                    <thead>
                        <tr>
                            <th width="80">Token No</th>
                            <th>Patient Name</th>
                            <th width="120">Mobile</th>
                            <th width="100">Age/Gender</th>
                            <th>Remarks</th>
                            <th width="100">Date</th>
                            <th width="120" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($freeCampPharmacies as $record)
                        <tr>
                            <td>
                                <strong>{{ $record->token_number }}</strong>
                            </td>
                            <td>
                                <strong>{{ $record->patient_name }}</strong>
                                @if($record->address)
                                <br><small class="text-muted">{{ Str::limit($record->address, 30) }}</small>
                                @endif
                            </td>
                            <td>{{ $record->mobile_number ?? 'N/A' }}</td>
                            <td>
                                @if($record->age)
                                    {{ $record->age }} / {{ ucfirst($record->gender ?? 'N/A') }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                @if($record->remarks)
                                    <span class="text-muted">{{ Str::limit($record->remarks, 30) }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                {{ date('d/m/Y', strtotime($record->created_at)) }}
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('free-camp-pharmacy.show', $record->id) }}"
                                       class="btn btn-outline-primary" title="View">
                                        <em class="icon ni ni-eye"></em>
                                    </a>
                                    <a href="{{ route('free-camp-pharmacy.edit', $record->id) }}"
                                       class="btn btn-outline-info" title="Edit">
                                        <em class="icon ni ni-edit"></em>
                                    </a>
                                    <a href="{{ route('free-camp-pharmacy.print-thermal', $record->id) }}"
                                       target="_blank" class="btn btn-outline-success" title="Print">
                                        <em class="icon ni ni-printer"></em>
                                    </a>
                                    <form action="{{ route('free-camp-pharmacy.destroy', $record->id) }}"
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger"
                                                onclick="return confirm('Are you sure you want to delete this record?')"
                                                title="Delete">
                                            <em class="icon ni ni-trash"></em>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-muted">
                                    <em class="icon ni ni-file-text text-lg" style="font-size: 3rem;"></em>
                                    <p class="mt-3 mb-2">No free camp pharmacy records found</p>
                                    <a href="{{ route('free-camp-pharmacy.create') }}" class="btn btn-primary mt-2">
                                        <em class="icon ni ni-plus"></em> Create First Free Record
                                    </a>
                                </div>
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

@push('styles')
<style>
    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,.02);
    }
    .btn-group-sm .btn {
        padding: 0.25rem 0.4rem;
    }
    .badge-dim {
        opacity: 0.8;
    }
    .popover {
        max-width: 300px;
    }
    .nk-block .card {
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Check if table has data rows (excluding the empty message row)
    var hasData = $('#freeCampPharmacyTable tbody tr').length > 0 &&
                  !$('#freeCampPharmacyTable tbody tr td[colspan]').length;

    if (hasData) {
        // Initialize DataTable only if there's actual data
        $('#freeCampPharmacyTable').DataTable({
            responsive: true,
            order: [[5, 'desc']], // Sort by date column (now index 5)
            pageLength: 25,
            language: {
                search: "Search records:",
                lengthMenu: "Show _MENU_ records",
                info: "Showing _START_ to _END_ of _TOTAL_ records",
                infoEmpty: "Showing 0 to 0 of 0 records",
                emptyTable: "No data available",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous"
                },
                zeroRecords: "No matching records found"
            },
            columnDefs: [
                {
                    targets: 4, // Remarks column (now index 4) - FIXED
                    orderable: false
                },
                {
                    targets: 6, // Actions column (now index 6) - FIXED
                    orderable: false,
                    searchable: false
                }
            ]
        });
    } else {
        console.log('Table is empty, DataTable not initialized');
    }
});
</script>
@endpush
