@extends('layouts.app')

@section('title', 'Edit Free Camp Pharmacy')
@section('page-title', 'Edit Free Camp Pharmacy Record')

@section('content')
<div class="nk-block nk-block-lg">
    <div class="card card-preview">
        <div class="card-inner">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="card-title mb-0">Edit Free Camp Pharmacy Record</h5>
                <a href="{{ route('free-camp-pharmacy.index') }}" class="btn btn-outline-primary">
                    <em class="icon ni ni-arrow-left"></em> Back to List
                </a>
            </div>

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-bs-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            @endif

            <form action="{{ route('free-camp-pharmacy.update', $record->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="token_number">Token Number <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('token_number') is-invalid @enderror"
                                   id="token_number"
                                   name="token_number"
                                   value="{{ old('token_number', $record->token_number) }}"
                                   required>
                            @error('token_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="patient_name">Patient Name <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('patient_name') is-invalid @enderror"
                                   id="patient_name"
                                   name="patient_name"
                                   value="{{ old('patient_name', $record->patient_name) }}"
                                   required>
                            @error('patient_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="mobile_number">Mobile Number</label>
                            <input type="text"
                                   class="form-control @error('mobile_number') is-invalid @enderror"
                                   id="mobile_number"
                                   name="mobile_number"
                                   value="{{ old('mobile_number', $record->mobile_number) }}">
                            @error('mobile_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="age">Age</label>
                                    <input type="number"
                                           class="form-control @error('age') is-invalid @enderror"
                                           id="age"
                                           name="age"
                                           value="{{ old('age', $record->age) }}"
                                           min="0"
                                           max="150">
                                    @error('age')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="gender">Gender</label>
                                    <select class="form-control @error('gender') is-invalid @enderror"
                                            id="gender"
                                            name="gender">
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('gender', $record->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender', $record->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender', $record->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label" for="address">Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror"
                                      id="address"
                                      name="address"
                                      rows="2">{{ old('address', $record->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label" for="remarks">Remarks</label>
                            <textarea class="form-control @error('remarks') is-invalid @enderror"
                                      id="remarks"
                                      name="remarks"
                                      rows="3">{{ old('remarks', $record->remarks) }}</textarea>
                            @error('remarks')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <em class="icon ni ni-save"></em> Update Record
                        </button>
                        <a href="{{ route('free-camp-pharmacy.index') }}" class="btn btn-light">
                            Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Get the current medicine count
    let medicineIndex = $('.medicine-item').length;

    // Function to add new medicine row
    function addMedicineRow() {
        let html = `
            <div class="row medicine-item mb-2">
                <div class="col-md-5">
                    <input type="text"
                           class="form-control"
                           name="medicines[${medicineIndex}][name]"
                           placeholder="Medicine Name">
                </div>
                <div class="col-md-3">
                    <input type="text"
                           class="form-control"
                           name="medicines[${medicineIndex}][dosage]"
                           placeholder="Dosage">
                </div>
                <div class="col-md-3">
                    <input type="number"
                           class="form-control"
                           name="medicines[${medicineIndex}][quantity]"
                           placeholder="Quantity"
                           min="1">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger remove-medicine">
                        <em class="icon ni ni-trash"></em>
                    </button>
                </div>
            </div>
        `;

        $('#medicines-container').append(html);
        medicineIndex++;
    }

    // Add medicine on plus button click (for the first row if it's the only one)
    $(document).on('click', '.add-medicine', function() {
        addMedicineRow();
    });

    // Add medicine from bottom button
    $(document).on('click', '.add-medicine-bottom', function() {
        addMedicineRow();
    });

    // Remove medicine row
    $(document).on('click', '.remove-medicine', function() {
        if ($('.medicine-item').length > 1) {
            $(this).closest('.medicine-item').remove();
        } else {
            alert('At least one medicine row is required. You can clear the fields instead.');
        }
    });

    // Form validation
    $('form').on('submit', function(e) {
        let isValid = true;
        let firstError = null;

        // Check required fields
        if (!$('#token_number').val()) {
            $('#token_number').addClass('is-invalid');
            $('#token_number').next('.invalid-feedback').text('Token number is required');
            isValid = false;
            firstError = firstError || $('#token_number');
        }

        if (!$('#patient_name').val()) {
            $('#patient_name').addClass('is-invalid');
            $('#patient_name').next('.invalid-feedback').text('Patient name is required');
            isValid = false;
            firstError = firstError || $('#patient_name');
        }

        if (!isValid && firstError) {
            e.preventDefault();
            firstError.focus();
        }
    });

    // Remove validation error on input
    $('input, select, textarea').on('input change', function() {
        $(this).removeClass('is-invalid');
    });
});
</script>
@endpush
