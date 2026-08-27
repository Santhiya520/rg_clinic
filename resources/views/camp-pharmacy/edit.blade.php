@extends('layouts.app')

@section('title', 'Edit Camp Pharmacy Record')
@section('page-title', 'Edit Camp Pharmacy Record')

@section('content')
<div class="nk-block nk-block-lg">
    <div class="card card-preview">
        <div class="card-inner">
            <form action="{{ route('camp-pharmacy.update', $record->id) }}" method="POST" id="campPharmacyForm">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Token & Basic Info -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label" for="token_number">Token Number *</label>
                            <input type="text" class="form-control" id="token_number" name="token_number"
                                   value="{{ old('token_number', $record->token_number) }}" required>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label" for="patient_name">Patient Name *</label>
                            <input type="text" class="form-control" id="patient_name" name="patient_name"
                                   value="{{ old('patient_name', $record->patient_name) }}" required>
                        </div>
                    </div>

                    <!-- Contact Details -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label" for="mobile_number">Mobile Number</label>
                            <input type="text" class="form-control" id="mobile_number" name="mobile_number"
                                   value="{{ old('mobile_number', $record->mobile_number) }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label" for="age">Age</label>
                            <input type="number" class="form-control" id="age" name="age"
                                   value="{{ old('age', $record->age) }}" min="0" max="150">
                        </div>
                    </div>

                    <!-- Gender & Address -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label" for="gender">Gender</label>
                            <select class="form-control" id="gender" name="gender">
                                <option value="">Select Gender</option>
                                <option value="male" {{ (old('gender', $record->gender) == 'male') ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ (old('gender', $record->gender) == 'female') ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ (old('gender', $record->gender) == 'other') ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label" for="address">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="1">{{ old('address', $record->address) }}</textarea>
                        </div>
                    </div>

                    <!-- Payment Details -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label" for="payment_type">Payment Type</label>
                            <select class="form-control" id="payment_type" name="payment_type">
                                <option value="cash" {{ (old('payment_type', $record->payment_type) == 'cash') ? 'selected' : '' }}>Cash</option>
                                <option value="card" {{ (old('payment_type', $record->payment_type) == 'card') ? 'selected' : '' }}>Card</option>
                                <option value="upi" {{ (old('payment_type', $record->payment_type) == 'upi') ? 'selected' : '' }}>UPI</option>
                                <option value="insurance" {{ (old('payment_type', $record->payment_type) == 'insurance') ? 'selected' : '' }}>Insurance</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label" for="payment_status">Payment Status</label>
                            <select class="form-control" id="payment_status" name="payment_status">
                                <option value="pending" {{ (old('payment_status', $record->payment_status) == 'pending') ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ (old('payment_status', $record->payment_status) == 'paid') ? 'selected' : '' }}>Paid</option>
                                <option value="partial" {{ (old('payment_status', $record->payment_status) == 'partial') ? 'selected' : '' }}>Partial</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label" for="total_amount">Total Amount (₹)</label>
                            <input type="number" class="form-control" id="total_amount" name="total_amount"
                                   value="{{ old('total_amount', $record->total_amount) }}" min="0" step="0.01">
                        </div>
                    </div>

                    <!-- Paid Amount & Balance -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label" for="paid_amount">Paid Amount (₹)</label>
                            <input type="number" class="form-control" id="paid_amount" name="paid_amount"
                                   value="{{ old('paid_amount', $record->paid_amount) }}" min="0" step="0.01">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">Balance Amount (₹)</label>
                            <input type="text" class="form-control" id="balance_amount" readonly
                                   value="{{ $record->balance_amount }}" style="background-color: #f8f9fa;">
                        </div>
                    </div>

                    <!-- Bill Info -->
                    @if($record->bill_number)
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Bill Number</label>
                            <input type="text" class="form-control" value="{{ $record->bill_number }}" readonly style="background-color: #f8f9fa;">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Bill Date</label>
                            <input type="text" class="form-control" value="{{ date('d/m/Y', strtotime($record->bill_date)) }}" readonly style="background-color: #f8f9fa;">
                        </div>
                    </div>
                    @endif


                    <!-- Remarks -->
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label" for="remarks">Remarks</label>
                            <textarea class="form-control" id="remarks" name="remarks" rows="2">{{ old('remarks', $record->remarks) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-lg btn-primary">
                        <em class="icon ni ni-save"></em> Update Record
                    </button>
                    <a href="{{ route('camp-pharmacy.index') }}" class="btn btn-lg btn-outline-light ml-2">
                        <em class="icon ni ni-arrow-left"></em> Back to List
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let medicineIndex = {{ $medIndex ?? 1 }};

    // Add medicine row
    $('#addMedicine').click(function() {
        const row = `
            <div class="row medicine-row mb-2">
                <div class="col-md-3">
                    <input type="text" class="form-control" name="medicines[${medicineIndex}][name]"
                           placeholder="Medicine Name">
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control" name="medicines[${medicineIndex}][qty]"
                           placeholder="Qty">
                </div>
                <div class="col-md-2">
                    <select class="form-control" name="medicines[${medicineIndex}][type]">
                        <option value="tablet">Tablet</option>
                        <option value="capsule">Capsule</option>
                        <option value="syrup">Syrup</option>
                        <option value="injection">Injection</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="number" class="form-control" name="medicines[${medicineIndex}][price]"
                           placeholder="Price" step="0.01">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-sm remove-medicine">
                        <em class="icon ni ni-trash"></em>
                    </button>
                </div>
            </div>`;
        $('#medicinesContainer').append(row);
        medicineIndex++;
    });

    // Remove medicine row
    $(document).on('click', '.remove-medicine', function() {
        if ($('.medicine-row').length > 1) {
            $(this).closest('.medicine-row').remove();
        }
    });

    // Calculate balance amount
    function calculateBalance() {
        const total = parseFloat($('#total_amount').val()) || 0;
        const paid = parseFloat($('#paid_amount').val()) || 0;
        const balance = total - paid;
        $('#balance_amount').val(balance.toFixed(2));
    }

    $('#total_amount, #paid_amount').on('input', calculateBalance);
});
</script>
@endpush
