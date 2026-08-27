@extends('layouts.app')

@section('page-title', 'Add New User')

@section('content')
    <div class="nk-block nk-block-lg">
        <div class="card card-preview">
            <div class="card-inner">
                <div class="nk-block-head">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h5 class="nk-block-title">Add New User</h5>
                        </div>
                        <div class="nk-block-head-content">
                            <a href="{{ route('users.index') }}" class="btn btn-secondary" style="border-radius: 5px">
                                <em class="icon ni ni-arrow-left"></em> &nbsp; Back to Users
                            </a>
                        </div>
                    </div>
                </div>

                <form action="{{ route('users.store') }}" method="POST">
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
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Email *</label>
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}"
                                    required>
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Password *</label>
                                <input type="password" class="form-control" name="password" required>
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Role *</label>
                                <select class="form-control select2-search" id="roleSelect" name="role" required>
                                    <option value="">Select Role</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="doctor" {{ old('role') == 'doctor' ? 'selected' : '' }}>Doctor</option>
                                    <option value="reception" {{ old('role') == 'reception' ? 'selected' : '' }}>reception</option>
                                    <option value="lab" {{ old('role') == 'lab' ? 'selected' : '' }}>Lab</option>
                                    <option value="radiology" {{ old('role') == 'radiology' ? 'selected' : '' }}>Radiology</option>
                                    <option value="pharmacy" {{ old('role') == 'pharmacy' ? 'selected' : '' }}>Pharmacy</option>
                                </select>
                                @error('role')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Phone</label>
                                <input type="text" class="form-control" name="phone" value="{{ old('phone') }}">
                                @error('phone')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Consulting Fee (₹)</label>
                                <input type="number" step="0.01" class="form-control" name="consulting_fee"
                                    value="{{ old('consulting_fee') }}">
                                @error('consulting_fee')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <button type="submit" class="btn btn-primary" style="border-radius:5px">Create User</button>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary" style="border-radius: 5px">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single {
            height: 40px !important;
            border: 1px solid #dbdfea;
            border-radius: 4px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 15px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 38px;
        }
    </style>
@endpush

@push('scripts')
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('#roleSelect').select2({
                placeholder: "Select Role",
                allowClear: false,
                width: '100%'
            });

            // If you have more select elements that need Select2, add them here
            // $('#anotherSelect').select2();
        });
    </script>
@endpush
