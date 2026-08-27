@extends('online.layouts.app')

@section('title', 'Lab Reports - ' . $patient->name)

@section('content')
    <div class="nk-block nk-block-lg">
        <div class="card card-preview">
            <div class="card-inner">
                <!-- Header -->
                <div class="nk-block-head">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">My Lab Reports</h3>
                            <div class="nk-block-des text-soft">
                                <p>View all your laboratory test reports</p>
                                @if ($hasFilter && $dateRange)
                                    <p class="text-success"><em class="icon ni ni-calendar"></em> Showing records for:
                                        {{ $dateRange }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="nk-block-head-content">
                            @if ($hasFilter)
                                <button onclick="window.print()" class="btn btn-primary">
                                    <em class="icon ni ni-printer"></em>&nbsp; Print
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Display error message if any -->
                @if (session('error'))
                    <div class="alert alert-danger alert-icon">
                        <em class="icon ni ni-cross-circle"></em> {{ session('error') }}
                    </div>
                @endif

                <!-- Date Filter Form -->
                <div class="card card-bordered mb-4">
                    <div class="card-inner">
                        <h6 class="title mb-3">Filter by Date Range</h6>
                        <form method="GET" action="{{ route('online.lab.reports') }}" class="row g-3" id="dateFilterForm">
                            <div class="col-md-4">
                                <label for="from_date" class="form-label">From Date *</label>
                                <input type="date" class="form-control" id="from_date" name="from_date"
                                    value="{{ $fromDate ?? '' }}" max="{{ date('Y-m-d') }}" required>
                                <div class="invalid-feedback">Please select from date</div>
                            </div>
                            <div class="col-md-4">
                                <label for="to_date" class="form-label">To Date *</label>
                                <input type="date" class="form-control" id="to_date" name="to_date"
                                    value="{{ $toDate ?? '' }}" max="{{ date('Y-m-d') }}" required>
                                <div class="invalid-feedback">Please select to date</div>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <div class="btn-group w-100">
                                    <button type="submit" class="btn btn-primary">
                                        <em class="icon ni ni-filter"></em> Apply Filter
                                    </button>
                                    <a href="{{ route('online.lab.reports') }}" class="btn btn-secondary">
                                        <em class="icon ni ni-reload"></em> Clear
                                    </a>
                                </div>
                            </div>
                        </form>

                        <!-- Quick Date Filters -->
                        <div class="mt-3">
                            <small class="text-muted">Quick Filters:</small>
                            <div class="btn-group btn-group-sm mt-1">
                                <button type="button" class="btn btn-outline-light quick-filter" data-days="7">
                                    Last 7 Days
                                </button>
                                <button type="button" class="btn btn-outline-light quick-filter" data-days="30">
                                    Last 30 Days
                                </button>
                                <button type="button" class="btn btn-outline-light quick-filter" data-days="90">
                                    Last 3 Months
                                </button>
                                <button type="button" class="btn btn-outline-light quick-filter" data-days="365">
                                    Last Year
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Show message when no filter is applied -->
                @if (!$hasFilter)
                    <div class="text-center p-5 border rounded bg-light">
                        <em class="icon ni ni-calendar text-muted" style="font-size: 4rem;"></em>
                        <h4 class="mt-3">Select Date Range to View Reports</h4>
                        <p class="text-muted mb-4">Please select a date range using the filter above to view your lab
                            reports.</p>

                        <div class="alert alert-info text-left">
                            <em class="icon ni ni-info"></em>
                            <strong>Note:</strong>
                            <ul class="mt-2 mb-0 pl-3">
                                <li>Select both "From Date" and "To Date"</li>
                                <li>You can use quick filters for common time periods</li>
                                <li>Only completed reports will be shown</li>
                            </ul>
                        </div>
                    </div>
                @else
                    <!-- Only show the following content when filter is applied -->

                    <!-- Check if any records exist for the filtered period -->
                    @if ($totalTests == 0)
                        <div class="alert alert-warning alert-icon">
                            <em class="icon ni ni-info"></em>
                            <strong>No lab tests found for the selected date range ({{ $dateRange }}).</strong>
                            Please try a different date range.
                        </div>
                    @else
                        <!-- Summary Statistics -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="card card-bordered">
                                    <div class="card-inner text-center">
                                        <h2 class="text-primary">{{ $totalTests }}</h2>
                                        <h6 class="title">Total Tests</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-bordered">
                                    <div class="card-inner text-center">
                                        <h2 class="text-success">{{ $completedTests }}</h2>
                                        <h6 class="title">Completed</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-bordered">
                                    <div class="card-inner text-center">
                                        <h2 class="text-warning">{{ $pendingTests }}</h2>
                                        <h6 class="title">Pending</h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Lab Tests Table -->
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Test Name</th>
                                        <th>Date</th>
                                        <th>Visit Type</th>
                                        <th>Reference</th>
                                        <th>Doctor</th>
                                        <th>Status</th>
                                        <th>Result</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($allLabTests as $index => $test)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <strong>{{ $test->labTest->name ?? 'N/A' }}</strong>
                                                @if ($test->notes)
                                                    <br><small
                                                        class="text-muted">{{ Str::limit($test->notes, 50) }}</small>
                                                @endif
                                            </td>
                                            <td>{{ $test->visit_date->format('d M Y') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $test->type == 'OP' ? 'primary' : 'info' }}">
                                                    {{ $test->type }}
                                                </span>
                                            </td>
                                            <td>{{ $test->token_or_ip }}</td>
                                            <td>{{ $test->doctor }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $test->status == 'completed' ? 'success' : ($test->status == 'cancelled' ? 'danger' : 'warning') }}">
                                                    {{ ucfirst($test->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($test->result)
                                                    <span class="text-success">
                                                        <em class="icon ni ni-check-circle"></em> Available
                                                    </span>
                                                @else
                                                    <span class="text-muted">Not Available</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($test->status == 'completed')
                                                    <a href="{{ route('online.lab.test.view', $test) }}"
                                                        class="btn btn-sm btn-outline-primary">
                                                        <em class="icon ni ni-eye"></em> View
                                                    </a>
                                                    <a href="{{ route('online.lab.test.print', $test) }}" target="_blank"
                                                        class="btn btn-sm btn-outline-primary">
                                                        <em class="icon ni ni-printer mr-1"></em> Print
                                                    </a>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-4">
                                                <em class="icon ni ni-info text-warning" style="font-size: 3rem;"></em>
                                                <p class="mt-2">No lab tests found for the selected date range.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Additional Information -->
                        <div class="alert alert-info alert-icon mt-4">
                            <em class="icon ni ni-info"></em>
                            <strong>Note:</strong>
                            <ul class="mt-2 mb-0 pl-3">
                                <li>Click "View" to see detailed lab report</li>
                                <li>Completed tests show green status with available results</li>
                                <li>Pending tests are still being processed in the lab</li>
                                <li>Results are available only for completed tests</li>
                                <li>Showing {{ $totalTests }} records for period: {{ $dateRange }}</li>
                            </ul>
                        </div>
                    @endif
                @endif
                <!-- End of conditional content (only shown when filter is applied) -->

            </div>
        </div>
    </div>

    <style>
        @media print {

            .btn,
            .alert,
            .card-bordered:first-child {
                display: none;
            }

            .card {
                border: none;
            }

            .table {
                border: 1px solid #000;
            }

            .table-bordered th,
            .table-bordered td {
                border: 1px solid #000;
            }

            .nk-block-head {
                display: none;
            }
        }
    </style>

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Set max date for "to_date" to today
                const today = new Date().toISOString().split('T')[0];
                document.getElementById('to_date').max = today;
                document.getElementById('from_date').max = today;

                // Set "from_date" max to "to_date" if to_date is selected
                document.getElementById('to_date').addEventListener('change', function() {
                    document.getElementById('from_date').max = this.value;
                });

                // Set "to_date" min to "from_date" if from_date is selected
                document.getElementById('from_date').addEventListener('change', function() {
                    document.getElementById('to_date').min = this.value;
                });

                // Quick filter buttons
                document.querySelectorAll('.quick-filter').forEach(button => {
                    button.addEventListener('click', function() {
                        const days = parseInt(this.dataset.days);
                        const toDate = new Date();
                        const fromDate = new Date();
                        fromDate.setDate(fromDate.getDate() - days);

                        document.getElementById('from_date').value = fromDate.toISOString().split('T')[
                            0];
                        document.getElementById('to_date').value = toDate.toISOString().split('T')[0];
                        document.getElementById('dateFilterForm').submit();
                    });
                });

                // Form validation
                document.getElementById('dateFilterForm').addEventListener('submit', function(e) {
                    const fromDate = document.getElementById('from_date').value;
                    const toDate = document.getElementById('to_date').value;

                    if (!fromDate || !toDate) {
                        e.preventDefault();
                        alert('Please select both From Date and To Date.');
                        return false;
                    }

                    if (new Date(fromDate) > new Date(toDate)) {
                        e.preventDefault();
                        alert('From Date cannot be greater than To Date.');
                        return false;
                    }

                    return true;
                });
            });
        </script>
    @endpush
@endsection
