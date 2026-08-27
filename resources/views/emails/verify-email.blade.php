<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Verify Email - RG Maruthuvamaiyam</title>

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

        .verify-container {
            width: 100%;
            max-width: 450px;
            padding: 20px;
        }

        .verify-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .verify-header {
            background: #1e55a7;
            color: white;
            padding: 25px 20px;
            text-align: center;
        }

        .verify-header h4 {
            margin: 0;
            font-weight: 600;
            font-size: 24px;
        }

        .verify-body {
            padding: 30px;
        }

        .otp-display {
            background: #f0f8ff;
            border: 2px dashed #1e55a7;
            padding: 20px;
            text-align: center;
            font-size: 32px;
            font-weight: 700;
            letter-spacing: 8px;
            color: #1e55a7;
            border-radius: 10px;
            margin: 20px 0;
        }

        .alert {
            border-radius: 8px;
            border: none;
            margin-bottom: 20px;
        }

        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 15px;
            transition: all 0.3s;
            text-align: center;
            letter-spacing: 4px;
            font-size: 20px;
            font-weight: 600;
        }

        .form-control:focus {
            border-color: #1e55a7;
            box-shadow: 0 0 0 0.2rem rgba(30, 85, 167, 0.25);
        }

        .btn-verify {
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

        .btn-verify:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(30, 85, 167, 0.4);
        }

        .timer {
            text-align: center;
            font-size: 14px;
            color: #666;
            margin-top: 10px;
        }

        .timer.expired {
            color: #dc3545;
        }

        .email-display {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 8px;
            text-align: center;
            margin: 15px 0;
            font-weight: 500;
        }

        .note {
            background: #fff3cd;
            border: 1px solid #ffecb5;
            color: #856404;
            padding: 12px;
            border-radius: 6px;
            margin: 20px 0;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="verify-container">
        <div class="verify-card">
            <div class="verify-header">
                <h4>Verify Your Email</h4>
                <p>Enter the OTP sent to your email</p>
            </div>

            <div class="verify-body">
                <!-- Display Email -->
                @if(session('email'))
                    <div class="email-display">
                        📧 Email: <strong>{{ session('email') }}</strong>
                    </div>
                @endif

                <!-- Display OTP for development -->
                @if(session('otp_display'))
                    <div class="note">
                        <strong>Development Mode:</strong> Your OTP is
                        <div class="otp-display">{{ session('otp_display') }}</div>
                        <small>This OTP is stored in your database</small>
                    </div>
                @endif

                <!-- Display Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- OTP Form -->
                <form method="POST" action="{{ route('patient.verify.otp') }}" id="otpForm">
                    @csrf

                    <!-- Hidden email field -->
                    <input type="hidden" name="email" value="{{ session('email') }}">

                    <div class="mb-4">
                        <label for="otp" class="form-label">Enter 6-digit OTP</label>
                        <input type="text"
                               class="form-control @error('otp') is-invalid @enderror"
                               id="otp"
                               name="otp"
                               required
                               maxlength="6"
                               minlength="6"
                               pattern="[0-9]{6}"
                               placeholder="000000"
                               autocomplete="off"
                               autofocus
                               value="{{ old('otp') }}">
                        @error('otp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="timer" id="timer">OTP expires in: <span id="countdown">15:00</span></div>
                    </div>

                    <button type="submit" class="btn btn-verify mb-3" id="submitBtn">
                        Verify Email
                    </button>

                    <div class="text-center">
                        <p>Didn't receive OTP?
                            <a href="#" onclick="event.preventDefault(); document.getElementById('resend-form').submit();">
                                Resend OTP
                            </a>
                        </p>
                    </div>
                </form>

                <!-- Resend OTP Form -->
                <form id="resend-form" action="{{ route('patient.resend.otp') }}" method="POST" style="display: none;">
                    @csrf
                    <input type="hidden" name="email" value="{{ session('email') }}">
                </form>

                <div class="text-center mt-4">
                    <a href="{{ route('patient.login') }}" class="text-decoration-none">Back to Login</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom Scripts -->
    <script>
        // Auto-focus on OTP input
        document.getElementById('otp').focus();

        // Auto move to next input
        document.getElementById('otp').addEventListener('input', function(e) {
            // Allow only numbers
            this.value = this.value.replace(/[^0-9]/g, '');

            if (this.value.length === 6) {
                document.getElementById('submitBtn').focus();
            }
        });

        // Countdown timer (15 minutes)
        let timeLeft = 15 * 60; // 15 minutes in seconds
        const timerElement = document.getElementById('countdown');
        const timerContainer = document.getElementById('timer');

        function updateTimer() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            timerElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                timerElement.textContent = "Expired!";
                timerContainer.classList.add('expired');
                document.getElementById('otp').setAttribute('disabled', 'true');
                document.getElementById('submitBtn').setAttribute('disabled', 'true');
                document.getElementById('otp').setAttribute('placeholder', 'OTP Expired');
            } else {
                timeLeft--;
            }
        }

        const timerInterval = setInterval(updateTimer, 1000);
        updateTimer(); // Initial call

        // Add loading state to submit button
        document.getElementById('otpForm').addEventListener('submit', function() {
            const submitButton = document.getElementById('submitBtn');
            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Verifying...';
            submitButton.disabled = true;
        });

        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
</body>
</html>
