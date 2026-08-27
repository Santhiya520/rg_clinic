@extends('online.layouts.app')

@section('title', 'Change Password - ' . $patient->name)

@section('content')
<div class="nk-block nk-block-lg">
    <div class="card card-preview">
        <div class="card-inner">
            <!-- Header -->
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Change Password</h3>
                        <div class="nk-block-des text-soft">
                            <p>Update your account password</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Password Change Form -->
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card card-bordered">
                        <div class="card-inner">
                            @if(session('error'))
                                <div class="alert alert-danger alert-icon">
                                    <em class="icon ni ni-cross-circle"></em> {{ session('error') }}
                                </div>
                            @endif

                            @if(session('success'))
                                <div class="alert alert-success alert-icon">
                                    <em class="icon ni ni-check-circle"></em> {{ session('success') }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('patient.changepassword.update') }}" id="changePasswordForm">
                                @csrf

                                <!-- Current Password -->
                                <div class="form-group mb-4">
                                    <label for="current_password" class="form-label">Current Password *</label>
                                    <div class="form-control-wrap">
                                        <div class="input-group">
                                            <input type="password"
                                                   class="form-control @error('current_password') is-invalid @enderror"
                                                   id="current_password"
                                                   name="current_password"
                                                   required
                                                   placeholder="Enter your current password">
                                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="current_password">
                                                <em class="icon ni ni-eye"></em>
                                            </button>
                                        </div>
                                        @error('current_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- New Password -->
                                <div class="form-group mb-4">
                                    <label for="new_password" class="form-label">New Password *</label>
                                    <div class="form-control-wrap">
                                        <div class="input-group">
                                            <input type="password"
                                                   class="form-control @error('new_password') is-invalid @enderror"
                                                   id="new_password"
                                                   name="new_password"
                                                   required
                                                   placeholder="Enter new password (min. 6 characters)"
                                                   minlength="6">
                                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="new_password">
                                                <em class="icon ni ni-eye"></em>
                                            </button>
                                        </div>
                                        @error('new_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">
                                            Password must be at least 6 characters long
                                        </small>
                                    </div>
                                </div>

                                <!-- Confirm New Password -->
                                <div class="form-group mb-4">
                                    <label for="new_password_confirmation" class="form-label">Confirm New Password *</label>
                                    <div class="form-control-wrap">
                                        <div class="input-group">
                                            <input type="password"
                                                   class="form-control"
                                                   id="new_password_confirmation"
                                                   name="new_password_confirmation"
                                                   required
                                                   placeholder="Confirm your new password">
                                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="new_password_confirmation">
                                                <em class="icon ni ni-eye"></em>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Password Strength Indicator -->
                                <div class="password-strength mb-4">
                                    <div class="progress" style="height: 5px;">
                                        <div class="progress-bar" id="password-strength-bar" role="progressbar" style="width: 0%"></div>
                                    </div>
                                    <small class="form-text text-muted" id="password-strength-text">
                                        Password strength: None
                                    </small>
                                </div>

                                <!-- Submit Button -->
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <em class="icon ni ni-check-circle"></em> Change Password
                                    </button>
                                </div>

                                <!-- Security Note -->
                                <div class="alert alert-light alert-icon mt-4">
                                    <em class="icon ni ni-shield-check"></em>
                                    <strong>Security Tips:</strong>
                                    <ul class="mt-2 mb-0 pl-3">
                                        <li>Use a strong, unique password</li>
                                        <li>Don't reuse passwords from other sites</li>
                                        <li>Consider using a password manager</li>
                                        <li>After changing password, you'll be logged out automatically</li>
                                    </ul>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Toggle password visibility
        $('.toggle-password').click(function() {
            const targetId = $(this).data('target');
            const input = $('#' + targetId);
            const icon = $(this).find('em');

            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('ni-eye').addClass('ni-eye-off');
            } else {
                input.attr('type', 'password');
                icon.removeClass('ni-eye-off').addClass('ni-eye');
            }
        });

        // Password strength checker
        $('#new_password').on('keyup', function() {
            const password = $(this).val();
            const strengthBar = $('#password-strength-bar');
            const strengthText = $('#password-strength-text');

            let strength = 0;
            let text = 'None';
            let color = '#dc3545'; // Red

            // Check password length
            if (password.length >= 6) strength += 25;
            if (password.length >= 8) strength += 25;

            // Check for mixed case
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength += 25;

            // Check for numbers and special characters
            if (password.match(/\d/)) strength += 15;
            if (password.match(/[^a-zA-Z\d]/)) strength += 10;

            // Update strength bar and text
            strengthBar.css('width', strength + '%');

            if (strength < 40) {
                text = 'Weak';
                color = '#dc3545';
            } else if (strength < 70) {
                text = 'Fair';
                color = '#ffc107';
            } else if (strength < 90) {
                text = 'Good';
                color = '#28a745';
            } else {
                text = 'Strong';
                color = '#20c997';
            }

            strengthBar.css('background-color', color);
            strengthText.text('Password strength: ' + text);
            strengthText.css('color', color);
        });

        // Form validation
        $('#changePasswordForm').submit(function(e) {
            const currentPassword = $('#current_password').val();
            const newPassword = $('#new_password').val();
            const confirmPassword = $('#new_password_confirmation').val();

            // Check if new passwords match
            if (newPassword !== confirmPassword) {
                e.preventDefault();
                alert('New password and confirmation password do not match.');
                $('#new_password_confirmation').focus();
                return false;
            }

            // Check if new password is different from current password
            if (currentPassword === newPassword) {
                e.preventDefault();
                alert('New password must be different from current password.');
                $('#new_password').focus();
                return false;
            }

            // Show loading state
            const submitBtn = $(this).find('button[type="submit"]');
            submitBtn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Changing password...');
            submitBtn.prop('disabled', true);

            return true;
        });

        // Clear validation errors on input
        $('input').on('input', function() {
            $(this).removeClass('is-invalid');
        });
    });
</script>

<style>
    .password-strength {
        margin-top: 10px;
    }

    .progress {
        background-color: #e9ecef;
        border-radius: 3px;
        margin-bottom: 5px;
    }

    .progress-bar {
        border-radius: 3px;
        transition: width 0.3s ease, background-color 0.3s ease;
    }

    .toggle-password {
        border-left: none;
    }

    .toggle-password:hover {
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }
</style>
@endpush
@endsection
