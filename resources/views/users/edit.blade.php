@extends('layouts.app')

@section('page-title', 'Edit User')

@section('content')
    <div class="nk-block nk-block-lg">
        <div class="card card-preview">
            <div class="card-inner">
                <div class="nk-block-head">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h5 class="nk-block-title">Edit User: {{ $user->name }}</h5>
                        </div>
                        <div class="nk-block-head-content">
                            <a href="{{ route('users.index') }}" class="btn btn-secondary" style="border-radius: 5px">
                                <em class="icon ni ni-arrow-left"></em>&nbsp; Back to Users
                            </a>
                        </div>
                    </div>
                </div>

                <form action="{{ route('users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Name *</label>
                                <input type="text" class="form-control" name="name"
                                    value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Email *</label>
                                <input type="email" class="form-control" name="email"
                                    value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Role *</label>
                                <select class="form-control" id="roleSelect" name="role" required>
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="doctor" {{ old('role', $user->role) == 'doctor' ? 'selected' : '' }}>Doctor</option>
                                    <option value="reception" {{ old('role', $user->role) == 'reception' ? 'selected' : '' }}>reception</option>
                                    <option value="lab" {{ old('role', $user->role) == 'lab' ? 'selected' : '' }}>Lab</option>
                                    <option value="radiology" {{ old('role', $user->role) == 'radiology' ? 'selected' : '' }}>Radiology</option>
                                    <option value="pharmacy" {{ old('role', $user->role) == 'pharmacy' ? 'selected' : '' }}>Pharmacy</option>
                                </select>
                                @error('role')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Phone</label>
                                <input type="text" class="form-control" name="phone"
                                    value="{{ old('phone', $user->phone) }}">
                                @error('phone')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Consulting Fee (₹)</label>
                                <input type="number" step="0.01" class="form-control" name="consulting_fee"
                                    value="{{ old('consulting_fee', $user->consulting_fee) }}">
                                @error('consulting_fee')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">New Password (Leave blank to keep current)</label>
                                <input type="password" class="form-control" name="password">
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <button type="submit" class="btn btn-primary" style="border-radius: 5px">Update User</button>
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
    </style>
@endpush

@push('scripts')
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2 for role select
            $('#roleSelect').select2({
                placeholder: "Select a role",
                allowClear: false,
                width: '100%'
            });
        });
    </script>
@endpush
