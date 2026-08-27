@extends('layouts.app')

@section('title', 'Change Password')
@section('page-title', 'Change Password')

@section('content')
<div class="nk-block">
    <div class="row justify-content-center">
        <div class="col-lg-5">

            <div class="card">
                <div class="card-inner">

                    <h5 class="mb-3">Change Password</h5>

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <!-- Current Password -->
                        <div class="form-group">
                            <label class="form-label">Current Password</label>
                            <input type="password"
                                   name="current_password"
                                   class="form-control @error('current_password') is-invalid @enderror"
                                   required>
                            @error('current_password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div class="form-group mt-3">
                            <label class="form-label">New Password</label>
                            <input type="password"
                                   name="new_password"
                                   class="form-control @error('new_password') is-invalid @enderror"
                                   required>
                            @error('new_password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-group mt-3">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password"
                                   name="new_password_confirmation"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-primary">
                                Update Password
                            </button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
