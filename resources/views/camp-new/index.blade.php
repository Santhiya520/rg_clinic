@extends('layouts.app')

@section('title', 'Camp New')
@section('page-title', 'Camp New Management')

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

    <div class="card card-preview">
        <div class="card-inner">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="card-title mb-0">Camp New Records</h5>
                <a href="{{ route('camp-new.create') }}" class="btn btn-primary">
                    <em class="icon ni ni-plus"></em> Add New Record
                </a>
            </div>

            <div class="table-responsive">
                <table id="campNewTable" class="table table-hover">
                    <thead>
                        <tr>
                            <th width="80">Token No</th>
                            <th>Patient Name</th>
                            <th width="100">Mobile</th>
                            <th width="80">Age/Gender</th>
                            <th width="100">Amount</th>
                            <th width="100">Payment Type</th>
                            <th width="100">Status</th>
                            <th width="120">Date</th>
                            <th width="150" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($campNew as $record)
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
                                @if($record->age || $record->gender)
                                    {{ $record->age ? $record->age : 'N/A' }} / {{ ucfirst($record->gender ?? 'N/A') }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                <strong>₹{{ number_format($record->total_amount, 2) }}</strong>
                                @if($record->payment_status == 'partial')
                                <br><small class="text-warning">Paid: ₹{{ number_format($record->paid_amount, 2) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-dim bg-outline-{{ $record->payment_type == 'cash' ? 'success' : ($record->payment_type == 'card' ? 'primary' : 'info') }}">
                                    {{ ucfirst($record->payment_type) }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $statusColor = $record->payment_status == 'paid' ? 'success' :
                                                  ($record->payment_status == 'partial' ? 'warning' : 'secondary');
                                @endphp
                                <span class="badge bg-{{ $statusColor }}">
                                    {{ ucfirst($record->payment_status) }}
                                </span>
                            </td>
                            <td>
                                {{ date('d/m/Y', strtotime($record->created_at)) }}
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('camp-new.show', $record->id) }}"
                                       class="btn btn-outline-primary" title="View">
                                        <em class="icon ni ni-eye"></em>
                                    </a>
                                    <a href="{{ route('camp-new.edit', $record->id) }}"
                                       class="btn btn-outline-info" title="Edit">
                                        <em class="icon ni ni-edit"></em>
                                    </a>
                                    @if($record->total_amount > 0)
                                    <a href="{{ route('camp-new.print-thermal', $record->id) }}"
                                       target="_blank" class="btn btn-outline-success" title="Print Bill">
                                        <em class="icon ni ni-printer"></em>
                                    </a>
                                    @endif
                                    <form action="{{ route('camp-new.destroy', $record->id) }}"
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger"
                                                onclick="return confirm('Are you sure?')" title="Delete">
                                            <em class="icon ni ni-trash"></em>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    @if(empty($campPharmacies))
                    <tfoot>
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <div class="text-muted">
                                    <em class="icon ni ni-info text-lg"></em>
                                    <p class="mt-2">No camp new records found</p>
                                    <a href="{{ route('camp-new.create') }}" class="btn btn-sm btn-primary mt-2">
                                        <em class="icon ni ni-plus"></em> Create First Record
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                    @endif
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
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    $('#campNewTable').DataTable({
        responsive: true,
        order: [[7, 'desc']], // Sort by date column (7th column)
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
            }
        }
    });
});
</script>
@endpush
