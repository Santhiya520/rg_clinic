<div class="patient-details">
    <h6 class="mb-3">Patient Information</h6>
    <div class="row">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="detail-item">
                <label>Patient Name:</label>
                <span>{{ $opRegister->patient->name ?? 'N/A' }}</span>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="detail-item">
                <label>Patient ID:</label>
                <span>{{ $opRegister->patient->patient_id ?? 'N/A' }}</span>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="detail-item">
                <label>Token Number:</label>
                <span>{{ $opRegister->token_number ?? 'N/A' }}</span>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="detail-item">
                <label>Date:</label>
                <span>{{ $opRegister->date->format('d/m/Y') }}</span>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="detail-item">
                <label>Doctor:</label>
                <span>{{ $opRegister->medicalOfficer->name ?? 'N/A' }}</span>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="detail-item">
                <label>Payment Status:</label>
                <span
                    class="badge bg-{{ $opRegister->paid_status == 'paid' ? 'success' : ($opRegister->paid_status == 'partial' ? 'warning' : 'danger') }}">
                    {{ ucfirst($opRegister->paid_status ?? 'pending') }}
                </span>
            </div>
        </div>
    </div>
</div>

<div class="row mb-3 mt-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Medicines ({{ $opRegister->medicines->count() }})</h6>
                @if ($opRegister->medicines->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Medicine</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($opRegister->medicines as $medicine)
                                    <tr>
                                        <td>{{ $medicine->medicine->name ?? 'N/A' }}</td>
                                        <td>{{ $medicine->quantity }}</td>
                                        <td>₹{{ number_format($medicine->price, 2) }}</td>
                                        <td>₹{{ number_format($medicine->quantity * $medicine->price, 2) }}</td>
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
                <h6 class="card-title">Lab Tests ({{ $opRegister->labTests->count() }})</h6>
                @if ($opRegister->labTests->count() > 0)
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
                                @foreach ($opRegister->labTests as $test)
                                    <tr>
                                        <td>{{ $test->labTest->name ?? 'N/A' }}</td>
                                        <td>₹{{ number_format($test->price, 2) }}</td>
                                        <td>₹{{ number_format($test->paid_amount, 2) }}</td>
                                        <td>
                                            <span
                                                class="badge badge-{{ $test->status == 'completed' ? 'success' : 'warning' }}">
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
                <h6 class="card-title">Radiology Tests ({{ $opRegister->radiologies->count() }})</h6>
                @if ($opRegister->radiologies->count() > 0)
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
                                @foreach ($opRegister->radiologies as $test)
                                    <tr>
                                        <td>{{ $test->radiologyTest->name ?? 'N/A' }}</td>
                                        <td>₹{{ number_format($test->price, 2) }}</td>
                                        <td>₹{{ number_format($test->paid_amount, 2) }}</td>
                                        <td>
                                            <span
                                                class="badge badge-{{ $test->status == 'completed' ? 'success' : 'warning' }}">
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
                                <td><strong>Doctor Fees:</strong></td>
                                <td class="text-right">₹{{ number_format($doctorFees, 2) }}</td>
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

@if ($opRegister->instructions || $opRegister->additional_info)
    <div class="card">
        <div class="card-body">
            <h6 class="card-title">Additional Information</h6>
            @if ($opRegister->instructions)
                <div class="mb-2">
                    <label class="small text-muted">Instructions:</label>
                    <p class="mb-0">{{ $opRegister->instructions }}</p>
                </div>
            @endif
            @if ($opRegister->additional_info)
                <div>
                    <label class="small text-muted">Additional Info:</label>
                    <p class="mb-0">{{ $opRegister->additional_info }}</p>
                </div>
            @endif
        </div>
    </div>
@endif
