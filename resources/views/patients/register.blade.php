<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Patient Registration - RG Maruthuvamaiyam</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f6fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        .register-container {
            width: 100%;
            max-width: 500px;
            padding: 20px;
        }

        .register-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .register-header {
            background: #1e55a7;
            color: white;
            padding: 25px 20px;
            text-align: center;
        }

        .register-header h4 {
            margin: 0;
            font-weight: 600;
            font-size: 24px;
        }

        .register-header p {
            margin: 5px 0 0 0;
            opacity: 0.9;
            font-size: 14px;
        }

        .register-body {
            padding: 30px;
        }

        .form-label {
            font-weight: 500;
            color: #333;
            margin-bottom: 8px;
        }

        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 15px;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #1e55a7;
            box-shadow: 0 0 0 0.2rem rgba(30, 85, 167, 0.25);
        }

        .btn-register {
            background: #1e55a7;
            border: none;
            color: white;
            padding: 14px;
            font-weight: 600;
            font-size: 16px;
            border-radius: 8px;
            width: 100%;
            transition: all 0.3s;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(30, 85, 167, 0.4);
        }

        .alert {
            border-radius: 8px;
            border: none;
            margin-bottom: 20px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .register-links {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .register-links a {
            color: #1e55a7;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .register-links a:hover {
            color: #15417e;
            text-decoration: underline;
        }

        .register-footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }

        .logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo img {
            max-height: 60px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .invalid-feedback {
            display: block;
            margin-top: 5px;
            font-size: 14px;
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -5px;
            margin-left: -5px;
        }

        .form-col {
            flex: 1 0 0%;
            padding-right: 5px;
            padding-left: 5px;
        }

        .password-toggle {
            cursor: pointer;
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #666;
        }

        .password-wrapper {
            position: relative;
        }

        .terms-text {
            font-size: 13px;
            color: #666;
            margin-top: 20px;
            text-align: center;
        }

        .gender-options {
            display: flex;
            gap: 15px;
        }

        .gender-option {
            flex: 1;
        }

        .gender-option input[type="radio"] {
            display: none;
        }

        .gender-option label {
            display: block;
            padding: 10px;
            text-align: center;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .gender-option input[type="radio"]:checked+label {
            border-color: #1e55a7;
            background-color: rgba(30, 85, 167, 0.1);
            color: #1e55a7;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="register-container">
        <div class="register-card">
            <div class="register-body">
                <!-- Logo -->
                <div class="logo">
                    <img src="{{ asset('images/logo.png') }}" alt="RG Maruthuvamaiyam Logo"
                        onerror="this.style.display='none'">
                </div>

                <!-- Display Messages -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Registration Form -->
                <form method="POST" action="{{ route('patient.register.submit') }}" id="registrationForm">
                    @csrf

                    <!-- Personal Information -->
                    <div class="form-group">
                        <label for="name" class="form-label">{{ __('Full Name') }} *</label>
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                            name="name" value="{{ old('name') }}" required autocomplete="name" autofocus
                            placeholder="Enter your full name">
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="email" class="form-label">{{ __('Email') }} *</label>
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" required autocomplete="email"
                                    placeholder="Enter your email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="mobile" class="form-label">{{ __('Mobile Number') }} *</label>
                                <input id="mobile" type="text"
                                    class="form-control @error('mobile') is-invalid @enderror" name="mobile"
                                    value="{{ old('mobile') }}" required autocomplete="tel"
                                    placeholder="Enter mobile number">
                                @error('mobile')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="age" class="form-label">{{ __('Age') }} *</label>
                                <input id="age" type="number"
                                    class="form-control @error('age') is-invalid @enderror" name="age"
                                    value="{{ old('age') }}" required min="1" max="120"
                                    placeholder="Enter age">
                                @error('age')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label">{{ __('Gender') }} *</label>
                                <div class="gender-options">
                                    <div class="gender-option">
                                        <input type="radio" id="male" name="sex" value="male"
                                            {{ old('sex') == 'male' ? 'checked' : '' }} required>
                                        <label for="male">Male</label>
                                    </div>
                                    <div class="gender-option">
                                        <input type="radio" id="female" name="sex" value="female"
                                            {{ old('sex') == 'female' ? 'checked' : '' }}>
                                        <label for="female">Female</label>
                                    </div>
                                    <div class="gender-option">
                                        <input type="radio" id="other" name="sex" value="other"
                                            {{ old('sex') == 'other' ? 'checked' : '' }}>
                                        <label for="other">Other</label>
                                    </div>
                                </div>
                                @error('sex')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address" class="form-label">{{ __('Address') }}</label>
                        <textarea id="address" class="form-control @error('address') is-invalid @enderror" name="address" rows="2"
                            placeholder="Enter your address">{{ old('address') }}</textarea>
                        @error('address')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Password Section -->
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="password" class="form-label">{{ __('Password') }} *</label>
                                <div class="password-wrapper">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="new-password" placeholder="Create password">
                                    <button type="button" class="password-toggle"
                                        onclick="togglePassword('password')">
                                        👁️
                                    </button>
                                </div>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <small class="form-text text-muted">Minimum 6 characters</small>
                            </div>
                        </div>
                        </div>

                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}
                                    *</label>
                                <div class="password-wrapper">
                                    <input id="password_confirmation" type="password" class="form-control"
                                        name="password_confirmation" required autocomplete="new-password"
                                        placeholder="Confirm password">
                                    <button type="button" class="password-toggle"
                                        onclick="togglePassword('password_confirmation')">
                                        👁️
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-register" id="submitBtn">
                            {{ __('Create Account') }}
                        </button>
                    </div>

                    <div class="register-links">
                        <p>Already have an account? <a href="{{ route('patient.login') }}">{{ __('Login here') }}</a>
                        </p>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Terms & Conditions Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Terms & Conditions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Please read these terms and conditions carefully before using our service.</p>
                    <!-- Add your terms and conditions here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Privacy Policy Modal -->
    <div class="modal fade" id="privacyModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Privacy Policy</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Your privacy is important to us. This privacy policy explains how we collect, use, and protect
                        your information.</p>
                    <!-- Add your privacy policy here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom Scripts -->
    <script>
        // Toggle Password Visibility
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
        }

        // Auto-dismiss alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });

        // Add loading state to submit button
        document.getElementById('registrationForm').addEventListener('submit', function() {
            const submitButton = document.getElementById('submitBtn');
            submitButton.innerHTML =
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Creating Account...';
            submitButton.disabled = true;
        });

        // Form Validation
        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;

            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match!');
                return false;
            }

            if (password.length < 6) {
                e.preventDefault();
                alert('Password must be at least 6 characters long!');
                return false;
            }

            const age = document.getElementById('age').value;
            if (age < 1 || age > 120) {
                e.preventDefault();
                alert('Please enter a valid age (1-120)');
                return false;
            }

            const terms = document.getElementById('terms').checked;
            if (!terms) {
                e.preventDefault();
                alert('Please accept the Terms & Conditions');
                return false;
            }

            return true;
        });

        // Mobile number validation (only numbers)
        document.getElementById('mobile').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    </script>
</body>

</html>
