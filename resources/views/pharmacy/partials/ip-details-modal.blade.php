{{-- resources/views/pharmacy/partials/ip-details-modal.blade.php --}}
<div class="patient-details">
    <h6 class="mb-3">Patient Information</h6>
    <div class="row">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="detail-item">
                <label>Patient Name:</label>
                <span>{{ $ip->patient->name ?? 'N/A' }}</span>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="detail-item">
                <label>Patient ID:</label>
                <span>{{ $ip->patient->patient_id ?? 'N/A' }}</span>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="detail-item">
                <label>IP Number:</label>
                <span>{{ $ip->hospital_ip_no ?? 'N/A' }}</span>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="detail-item">
                <label>Admission Date:</label>
                <span>{{ $ip->date_of_admission->format('d/m/Y') }}</span>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="detail-item">
                <label>Discharge Date:</label>
                <span>{{ $ip->date_of_discharge ? $ip->date_of_discharge->format('d/m/Y') : 'Still Admitted' }}</span>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="detail-item">
                <label>Doctor:</label>
                <span>{{ $ip->medicalOfficer->name ?? 'N/A' }}</span>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="detail-item">
                <label>Payment Status:</label>
                <span class="badge bg-{{ $ip->paid_status == 'paid' ? 'success' : ($ip->paid_status == 'partial' ? 'warning' : 'danger') }}">
                    {{ ucfirst($ip->paid_status ?? 'pending') }}
                </span>
            </div>
        </div>
    </div>
</div>

<div class="row mb-3 mt-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Medicines ({{ $ip->medicines->count() }})</h6>
                @if ($ip->medicines->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Medicine</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Discount</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ip->medicines as $medicine)
                                    <tr>
                                        <td>{{ $medicine->medicine->name ?? 'N/A' }}</td>
                                        <td>{{ $medicine->quantity }}</td>
                                        <td>₹{{ number_format($medicine->price, 2) }}</td>
                                        <td class="text-danger">₹{{ number_format($medicine->discount_amount ?? 0, 2) }}</td>
                                        <td>₹{{ number_format(($medicine->quantity * $medicine->price) - ($medicine->discount_amount ?? 0), 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">No medicines prescribed</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Lab Tests ({{ $ip->ipLabTests->count() }})</h6>
                @if ($ip->ipLabTests->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Test Name</th>
                                    <th>Price</th>
                                    <th>Paid</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ip->ipLabTests as $test)
                                    <tr>
                                        <td>{{ $test->labTest->name ?? 'N/A' }}</td>
                                        <td>₹{{ number_format($test->price, 2) }}</td>
                                        <td>₹{{ number_format($test->paid_amount, 2) }}</td>
                                        <td>
                                            <span class="badge badge-{{ $test->status == 'completed' ? 'success' : 'warning' }}">
                                                {{ $test->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">No lab tests prescribed</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Radiology Tests ({{ $ip->ipRadiologies->count() }})</h6>
                @if ($ip->ipRadiologies->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Test Name</th>
                                    <th>Price</th>
                                    <th>Paid</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ip->ipRadiologies as $test)
                                    <tr>
                                        <td>{{ $test->radiologyTest->name ?? 'N/A' }}</td>
                                        <td>₹{{ number_format($test->price, 2) }}</td>
                                        <td>₹{{ number_format($test->paid_amount, 2) }}</td>
                                        <td>
                                            <span class="badge badge-{{ $test->status == 'completed' ? 'success' : 'warning' }}">
                                                {{ $test->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">No radiology tests prescribed</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Financial Summary</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <tbody>
                            <tr>
                                <td><strong>Medicines Amount:</strong></td>
                                <td class="text-right">₹{{ number_format($medicineTotal, 2) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Lab Tests Amount:</strong></td>
                                <td class="text-right">₹{{ number_format($labTotal, 2) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Radiology Amount:</strong></td>
                                <td class="text-right">₹{{ number_format($radiologyTotal, 2) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Discount:</strong></td>
                                <td class="text-right text-danger">-₹{{ number_format($discount, 2) }}</td>
                            </tr>
                            <tr class="table-active">
                                <td><strong>Grand Total:</strong></td>
                                <td class="text-right text-primary">
                                    <strong>₹{{ number_format($grandTotal, 2) }}</strong></td>
                            </tr>
                            <tr>
                                <td><strong>Paid Amount:</strong></td>
                                <td class="text-right text-success">₹{{ number_format($paid, 2) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Balance Amount:</strong></td>
                                <td class="text-right text-danger">₹{{ number_format($balance, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@if ($ip->instructions || $ip->additional_info)
    <div class="card">
        <div class="card-body">
            <h6 class="card-title">Additional Information</h6>
            @if ($ip->instructions)
                <div class="mb-2">
                    <label class="small text-muted">Instructions:</label>
                    <p class="mb-0">{{ $ip->instructions }}</p>
                </div>
            @endif
            @if ($ip->additional_info)
                <div>
                    <label class="small text-muted">Additional Info:</label>
                    <p class="mb-0">{{ $ip->additional_info }}</p>
                </div>
            @endif
        </div>
    </div>
@endif
