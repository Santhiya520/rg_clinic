@extends('layouts.app')

@section('page-title', 'Add New Medicine')

@section('content')
    <div class="nk-block nk-block-lg">
        <div class="card card-preview">
            <div class="card-inner">
                <div class="nk-block-head">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h5 class="nk-block-title">Add New Medicine</h5>
                        </div>
                        <div class="nk-block-head-content">
                            <a href="{{ route('medicines.index') }}" class="btn btn-secondary">
                                <em class="icon ni ni-arrow-left"></em> &nbsp; Back to Medicines
                            </a>
                        </div>
                    </div>
                </div>

                <form action="{{ route('medicines.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Name *</label>
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}"
                                    required>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">
                                    Special characters: ' will be stored as #1sp#, " will be stored as #2sp#
                                </small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Category *</label>
                                <select class="form-control" id="categorySelect" name="category" required>
                                    <option value="">Select Category</option>
                                    @foreach (\App\Models\Medicine::CATEGORIES as $key => $value)
                                        <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Price (₹) *</label>
                                <input type="number" step="0.01" class="form-control" name="price"
                                    value="{{ old('price') }}" required>
                                @error('price')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Stock *</label>
                                <input type="number" class="form-control" name="stock" value="{{ old('stock', 0) }}" required>
                                @error('stock')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Supplier</label>
                                <select class="form-control" id="supplierSelect" name="supplier_id">
                                    <option value="">Select Supplier (Optional)</option>
                                    @foreach (\App\Models\Supplier::active()->get() as $supplier)
                                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                            {{ \App\Helpers\StringHelper::decodeQuotes($supplier->name) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Expiry Date</label>
                                <input type="date" class="form-control" name="expiry_date"
                                    value="{{ old('expiry_date') }}">
                                @error('expiry_date')
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
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="discontinued" {{ old('status') == 'discontinued' ? 'selected' : '' }}>Discontinued</option>
                                </select>
                                @error('status')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">
                            Special characters in description will also be encoded
                        </small>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Create Medicine</button>
                        <a href="{{ route('medicines.index') }}" class="btn btn-secondary">Cancel</a>
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
        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #6c757d;
        }
        .select2-container .select2-selection--single .select2-selection__rendered {
            padding-left: 0;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2 for category select
            $('#categorySelect').select2({
                placeholder: "Select Category",
                allowClear: false,
                width: '100%'
            });

            // Initialize Select2 for supplier select
            $('#supplierSelect').select2({
                placeholder: "Select Supplier (Optional)",
                allowClear: true,
                width: '100%'
            });

            // Initialize Select2 for status select
            $('#statusSelect').select2({
                placeholder: "Select Status",
                allowClear: false,
                width: '100%'
            });

            // Set minimum date for expiry date (today)
            var today = new Date().toISOString().split('T')[0];
            $('input[name="expiry_date"]').attr('min', today);
        });
    </script>
@endpush
