@extends('layouts.app')

@section('page-title', 'Edit Supplier')

@section('content')
    <div class="nk-block nk-block-lg">
        <div class="card card-preview">
            <div class="card-inner">
                <div class="nk-block-head">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h5 class="nk-block-title">Edit Supplier: {{ \App\Helpers\StringHelper::decodeQuotes($supplier->name) }}</h5>
                        </div>
                        <div class="nk-block-head-content">
                            <a href="{{ route('suppliers.index') }}" class="btn btn-secondary" style="border-radius: 5px">
                                <em class="icon ni ni-arrow-left"></em>&nbsp; Back to Suppliers
                            </a>
                        </div>
                    </div>
                </div>

                <form action="{{ route('suppliers.update', $supplier) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Supplier Name *</label>
                                <input type="text" class="form-control" name="name"
                                    value="{{ old('name', \App\Helpers\StringHelper::decodeQuotes($supplier->name)) }}" required>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Contact Person</label>
                                <input type="text" class="form-control" name="contact_person"
                                    value="{{ old('contact_person', \App\Helpers\StringHelper::decodeQuotes($supplier->contact_person ?? '')) }}">
                                @error('contact_person')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email"
                                    value="{{ old('email', $supplier->email) }}">
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Phone</label>
                                <input type="text" class="form-control" name="phone"
                                    value="{{ old('phone', $supplier->phone) }}">
                                @error('phone')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Address</label>
                        <textarea class="form-control" name="address" rows="2">{{ old('address', \App\Helpers\StringHelper::decodeQuotes($supplier->address ?? '')) }}</textarea>
                        @error('address')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">City</label>
                                <input type="text" class="form-control" name="city"
                                    value="{{ old('city', $supplier->city) }}">
                                @error('city')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">State</label>
                                <input type="text" class="form-control" name="state"
                                    value="{{ old('state', $supplier->state) }}">
                                @error('state')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Country</label>
                                <input type="text" class="form-control" name="country"
                                    value="{{ old('country', $supplier->country) }}">
                                @error('country')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Postal Code</label>
                                <input type="text" class="form-control" name="postal_code"
                                    value="{{ old('postal_code', $supplier->postal_code) }}">
                                @error('postal_code')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Website</label>
                                <input type="url" class="form-control" name="website"
                                    value="{{ old('website', $supplier->website) }}">
                                @error('website')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Tax Number (GST/PAN)</label>
                                <input type="text" class="form-control" name="tax_number"
                                    value="{{ old('tax_number', $supplier->tax_number) }}">
                                @error('tax_number')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Status *</label>
                                <select class="form-control" id="statusSelect" name="status" required>
                                    @foreach (\App\Models\Supplier::STATUSES as $key => $value)
                                        <option value="{{ $key }}"
                                            {{ old('status', $supplier->status) == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" name="notes" rows="3">{{ old('notes', \App\Helpers\StringHelper::decodeQuotes($supplier->notes ?? '')) }}</textarea>
                        @error('notes')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" style="border-radius: 5px">Update Supplier</button>
                        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary" style="border-radius: 5px">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single {
            height: 40px;
            border: 1px solid #dbdfea;
            border-radius: 4px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 38px;
            padding-left: 12px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 38px;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#statusSelect, #paymentTermsSelect').select2({
                placeholder: "Select Option",
                allowClear: false,
                width: '100%'
            });
            $('#statusSelect, #paymentTermsSelect').trigger('change.select2');
        });
    </script>
@endpush
