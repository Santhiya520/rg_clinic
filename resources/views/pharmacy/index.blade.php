@extends('layouts.app')

@section('title', 'Pharmacy Dashboard')
@section('page-title', 'Pharmacy - OP & IP List')

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

    <!-- Tabs for OP and IP -->
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#op">
                <em class="icon ni ni-user"></em>
                Out Patients (OP)
                <span class="badge bg-primary">{{ $opRegisters->count() }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#ip">
                <em class="icon ni ni-bed"></em>
                In Patients (IP)
                <span class="badge bg-success">{{ $ipRegisters->count() }}</span>
            </a>
        </li>
    </ul>

    <div class="tab-content">
        <!-- OP Tab -->
        <div class="tab-pane fade show active" id="op">
            <div class="card card-preview mt-3">
                <div class="card-inner">
                    <h5 class="card-title">Out Patients List</h5>
                    <div class="table-responsive">
                        <table id="opTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="100">Token No</th>
                                    <th>Patient ID & Name</th>
                                    <th width="120">Date</th>
                                    <th width="120">Medicines</th>
                                    <th width="100">Status</th>
                                    <th width="120">Paid Status</th>
                                    <th width="150">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($opRegisters as $op)
                                <tr>
                                    <td>{{ $op->token_number ?? 'N/A' }}</td>
                                    <td>
                                        @if($op->patient && $op->patient->patient_id)
                                            <strong>{{ $op->patient->patient_id }}</strong><br>
                                            <small>{{ $op->patient->name ?? 'N/A' }}</small>
                                        @else
                                            <strong>N/A</strong><br>
                                            <small>Patient data missing</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($op->created_at)
                                            {{ $op->created_at->format('d/m/Y') }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge" style="background-color: #8e44ad; padding:3px 8px">
                                            {{ $op->medicines ? $op->medicines->count() : 0 }} medicines
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $status = $op->status ?? 'pending';
                                            $statusColor = $status == 'completed' ? 'success' :
                                                          ($status == 'in_progress' ? 'warning' : 'secondary');
                                        @endphp
                                        <span class="badge bg-{{ $statusColor }}" style="padding:3px 8px">
                                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $paidStatus = $op->paid_status ?? 'pending';
                                            $paidColor = $paidStatus == 'paid' ? 'success' :
                                                        ($paidStatus == 'partial' ? 'warning' : 'danger');
                                        @endphp
                                        <span class="badge bg-{{ $paidColor }}" style="padding:3px 8px">
                                            {{ ucfirst($paidStatus) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('pharmacy.op.show', $op) }}" class="btn btn-outline-primary">
                                                <em class="icon ni ni-eye"></em> View
                                            </a>
                                            @if(in_array($op->paid_status ?? '', ['paid', 'partial']))
                                                <a href="{{ route('pharmacy.op.bill', $op) }}" target="_blank" class="btn btn-outline-info">
                                                    <em class="icon ni ni-printer"></em> Bill
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            @if($opRegisters->isEmpty())
                            <tfoot>
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <em class="icon ni ni-info text-lg"></em>
                                            <p class="mt-2">No OP records found for today</p>
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

        <!-- IP Tab -->
        <div class="tab-pane fade" id="ip">
            <div class="card card-preview mt-3">
                <div class="card-inner">
                    <h5 class="card-title">In Patients List</h5>
                    <div class="table-responsive">
                        <table id="ipTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="100">IP No</th>
                                    <th>Patient ID & Name</th>
                                    <th width="120">Admission Date</th>
                                    <th width="120">Medicines</th>
                                    <th width="100">Status</th>
                                    <th width="120">Pharmacy Status</th>
                                    <th width="150">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ipRegisters as $ip)
                                <tr>
                                    <td>{{ $ip->hospital_ip_no ?? 'N/A' }}</td>
                                    <td>
                                        @if($ip->patient && $ip->patient->patient_id)
                                            <strong>{{ $ip->patient->patient_id }}</strong><br>
                                            <small>{{ $ip->patient->name ?? 'N/A' }}</small>
                                        @else
                                            <strong>N/A</strong><br>
                                            <small>Patient data missing</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($ip->date_of_admission)
                                            {{ $ip->date_of_admission->format('d/m/Y') }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge" style="background-color: #2c3e50; padding:3px 8px">
                                            {{ $ip->medicines ? $ip->medicines->count() : 0 }} medicines
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $ipStatus = $ip->date_of_discharge ? 'Discharged' : 'Admitted';
                                            $ipStatusColor = $ip->date_of_discharge ? 'secondary' : 'info';
                                        @endphp
                                        <span class="badge bg-{{ $ipStatusColor }}" style="padding:3px 8px">
                                            {{ $ipStatus }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $pharmacyStatus = $ip->pharmacy_paid_status ?? $ip->paid_status ?? 'pending';
                                            $statusColor = $pharmacyStatus == 'paid' ? 'success' :
                                                          ($pharmacyStatus == 'partial' ? 'warning' : 'danger');
                                        @endphp
                                        <span class="badge bg-{{ $statusColor }}" style="padding:3px 8px">
                                            {{ ucfirst($pharmacyStatus) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('pharmacy.ip.show', $ip) }}" class="btn btn-outline-primary">
                                                <em class="icon ni ni-eye"></em> View
                                            </a>
                                            @if(in_array($pharmacyStatus, ['paid', 'partial']))
                                                <a href="{{ route('pharmacy.ip.bill', $ip) }}" target="_blank" class="btn btn-outline-info">
                                                    <em class="icon ni ni-printer"></em> Bill
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            @if($ipRegisters->isEmpty())
                            <tfoot>
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <em class="icon ni ni-info text-lg"></em>
                                            <p class="mt-2">No admitted patients found</p>
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
    </div>
</div>
@endsection

@push('styles')
<style>
    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,.02);
    }
    .btn-group-sm > .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.765625rem;
        border-radius: 0.2rem;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize OP DataTable with explicit columns
    $('#opTable').DataTable({
        responsive: true,
        searching: true,
        ordering: true,
        info: true,
        paging: {{ $opRegisters->count() > 10 ? 'true' : 'false' }},
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        pageLength: 10,
        columnDefs: [
            { orderable: false, targets: [3, 4, 5, 6] } // Make medicines, status, paid status, and actions non-orderable
        ],
        language: {
            search: "Search:",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "Showing 0 to 0 of 0 entries",
            infoFiltered: "(filtered from _MAX_ total entries)",
            zeroRecords: "No matching records found",
            emptyTable: "No data available in table",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        },
        drawCallback: function(settings) {
            // Check if table is empty
            if (this.api().data().count() === 0) {
                $(this).find('tbody').html(
                    '<tr><td colspan="7" class="text-center py-4">No OP records found for today</td></tr>'
                );
            }
        }
    });

    // Initialize IP DataTable with explicit columns
    $('#ipTable').DataTable({
        responsive: true,
        searching: true,
        ordering: true,
        info: true,
        paging: {{ $ipRegisters->count() > 10 ? 'true' : 'false' }},
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        pageLength: 10,
        columnDefs: [
            { orderable: false, targets: [3, 4, 5, 6] } // Make medicines, status, pharmacy status, and actions non-orderable
        ],
        language: {
            search: "Search:",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "Showing 0 to 0 of 0 entries",
            infoFiltered: "(filtered from _MAX_ total entries)",
            zeroRecords: "No matching records found",
            emptyTable: "No data available in table",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        },
        drawCallback: function(settings) {
            // Check if table is empty
            if (this.api().data().count() === 0) {
                $(this).find('tbody').html(
                    '<tr><td colspan="7" class="text-center py-4">No admitted patients found</td></tr>'
                );
            }
        }
    });

    // Handle tab switching to redraw DataTables
    $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
    });
});
</script>
@endpush
