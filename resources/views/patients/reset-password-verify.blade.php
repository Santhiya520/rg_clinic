<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP - RG Maruthuvamaiyam</title>
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
        .container {
            max-width: 450px;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: none;
        }
        .card-header {
            background: #1e55a7;
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 25px;
            text-align: center;
        }
        .card-body {
            padding: 30px;
        }
        .otp-inputs {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 20px;
        }
        .otp-input {
            width: 50px;
            height: 60px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            border: 2px solid #ddd;
            border-radius: 8px;
        }
        .otp-input:focus {
            border-color: #1e55a7;
            box-shadow: 0 0 0 0.2rem rgba(30, 85, 167, 0.25);
        }
        .btn-primary {
            background: #1e55a7;
            border: none;
            padding: 12px;
            font-weight: 600;
        }
        .resend-link {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Verify OTP</h4>
                <p class="mb-0">Enter the 6-digit OTP sent to your email</p>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <p class="text-muted mb-4">
                    OTP sent to: <strong>{{ session('email') }}</strong>
                </p>

                <form method="POST" action="{{ route('patient.reset.password.verify.submit') }}" id="otpForm">
                    @csrf
                    <input type="hidden" name="email" value="{{ session('email') }}">

                    <div class="otp-inputs">
                        @for($i = 1; $i <= 6; $i++)
                            <input type="text" class="otp-input form-control"
                                   maxlength="1"
                                   data-index="{{ $i-1 }}"
                                   autocomplete="off">
                        @endfor
                    </div>
                    <input type="hidden" name="otp" id="otp">

                    <button type="submit" class="btn btn-primary w-100 mb-3">
                        Verify OTP
                    </button>
                </form>

                <form method="POST" action="{{ route('patient.resend.otp') }}">
                    @csrf
                    <input type="hidden" name="email" value="{{ session('email') }}">
                    <button type="submit" class="btn btn-link w-100 text-decoration-none">
                        Resend OTP
                    </button>
                </form>

                <div class="text-center mt-3">
                    <a href="{{ route('patient.forgot.password') }}" class="text-decoration-none">
                        ← Use different email
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const otpInputs = document.querySelectorAll('.otp-input');
            const otpHidden = document.getElementById('otp');

            // Focus first input
            otpInputs[0].focus();

            // Handle input
            otpInputs.forEach((input, index) => {
                input.addEventListener('input', function(e) {
                    if (this.value.length === 1) {
                        if (index < otpInputs.length - 1) {
                            otpInputs[index + 1].focus();
                        }
                    }

                    // Update hidden OTP field
                    updateHiddenOtp();
                });

                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && this.value === '' && index > 0) {
                        otpInputs[index - 1].focus();
                    }
                });
            });

            function updateHiddenOtp() {
                let otp = '';
                otpInputs.forEach(input => {
                    otp += input.value;
                });
                otpHidden.value = otp;
            }

            // Form submission validation
            document.getElementById('otpForm').addEventListener('submit', function(e) {
                const otp = otpHidden.value;
                if (otp.length !== 6) {
                    e.preventDefault();
                    alert('Please enter 6-digit OTP');
                }
            });
        });
    </script>
</body>
</html>
