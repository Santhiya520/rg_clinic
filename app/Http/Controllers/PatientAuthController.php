<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\PatientOtpMail;
use App\Mail\PatientResetPasswordMail;
use Carbon\Carbon;

class PatientAuthController extends Controller
{
    // Show Patient Login Form
    public function showLogin()
    {
        return view('patients.login');
    }

    // Patient Login - Now OTP based
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Find patient
        $patient = Patient::where('email', $request->email)->first();

        if (!$patient) {
            return back()->with('error', 'Patient not found.');
        }

        // Check if verified
        if (!$patient->is_verified) {
            // Generate new OTP
            $patient->otp = rand(100000, 999999);
            $patient->otp_expires_at = Carbon::now()->addMinutes(15);
            $patient->save();

            // Send OTP email
            Mail::to($patient->email)->send(new PatientOtpMail($patient->otp, 'email_verification'));

            return redirect()->route('patient.verify.email')
                ->with('email', $patient->email)
                ->with('warning', 'Please verify your email with OTP.');
        }

        // Check password
        if (!Hash::check($request->password, $patient->password)) {
            return back()->with('error', 'Invalid credentials.');
        }

        // Login patient using custom guard
        Auth::guard('patient')->login($patient);

        // Update last login
        $patient->last_login_at = now();
        $patient->save();

        return redirect()->route('patient.dashboard');
    }

    // Show Registration Form
    public function showRegister()
    {
        return view('patients.register');
    }

    // Patient Registration
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:patients,email',
            'mobile' => 'required|string|max:15',
            'password' => 'required|min:6|confirmed',
            'age' => 'required|integer',
            'sex' => 'required|in:male,female,other,Male,Female,Other', // Accept both cases
            'address' => 'nullable|string',
        ]);

        // Generate patient ID
        $patientId = 'RG' . rand(100, 999);

        // Normalize sex to lowercase
        $sex = strtolower($request->sex);

        // Generate OTP
        $otp = rand(100000, 999999);

        // Create patient
        $patient = Patient::create([
            'patient_id' => $patientId,
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
            'age' => $request->age,
            'sex' => $sex,
            'address' => $request->address,
            'otp' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(15),
            'is_verified' => false,
        ]);

        try {
            // Send OTP email
            Mail::to($patient->email)
                ->send(new PatientOtpMail($otp, 'email_verification'));


            return redirect()->route('patient.verify.email')
                ->with('email', $patient->email)
                ->with('success', 'Registration successful! Please check your email for OTP.');
        } catch (\Exception $e) {
            dd($e);
            // Log the error
            \Log::error('Email sending failed: ' . $e->getMessage());

            // If email fails, still allow verification with OTP displayed on screen
            return redirect()->route('patient.verify.email')
                ->with('email', $patient->email)
                ->with('otp_display', $otp)
                ->with('warning', 'Email could not be sent. Please use this OTP: ' . $otp);
        }
    }

    // Show OTP Verification Form
    public function showVerifyEmail()
    {
        if (!session('email')) {
            return redirect()->route('patient.login');
        }
        return view('patients.verify-email');
    }

    // Verify OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric|digits:6',
            'email' => 'required|email'
        ]);

        $patient = Patient::where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('otp_expires_at', '>', Carbon::now())
            ->first();

        if (!$patient) {
            return back()->with('error', 'Invalid or expired OTP.');
        }

        // Verify patient
        $patient->is_verified = true;
        $patient->email_verified_at = now();
        $patient->otp = null;
        $patient->otp_expires_at = null;
        $patient->save();

        return redirect()->route('patient.login')
            ->with('success', 'Email verified successfully! You can now login.');
    }

    // Resend OTP
    public function resendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $patient = Patient::where('email', $request->email)->first();

        if (!$patient) {
            return back()->with('error', 'Patient not found.');
        }

        // Generate new OTP
        $patient->otp = rand(100000, 999999);
        $patient->otp_expires_at = Carbon::now()->addMinutes(15);
        $patient->save();

        // Send OTP email
        Mail::to($patient->email)->send(new PatientOtpMail($patient->otp, 'email_verification'));

        return back()->with('success', 'New OTP sent to your email.');
    }

    // Show Forgot Password Form - Updated for email only
    public function showForgotPassword()
    {
        return view('patients.forgot-password');
    }

    // NEW: Send OTP for Forgot Password
    // Updated sendForgotPasswordOtp method
    public function sendForgotPasswordOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $patient = Patient::where('email', $request->email)->first();

        if (!$patient) {
            return back()->with('error', 'Patient not found.');
        }

        // Check if patient is verified
        if (!$patient->is_verified) {
            return back()->with('error', 'Please verify your email first.');
        }

        // Generate OTP for password reset
        $otp = rand(100000, 999999);
        $patient->otp = $otp;
        $patient->otp_expires_at = Carbon::now()->addMinutes(15);

        try {
            $patient->save();
            \Log::info('Sending OTP to: ' . $patient->email);

            // Send OTP email
            Mail::to($patient->email)->send(new PatientOtpMail($otp, 'password_reset'));


            return redirect()->route('patient.reset.password.verify')
                ->with('email', $patient->email)
                ->with('success', 'OTP sent to your email. Please check your inbox.');
        } catch (\Exception $e) {
            \Log::error('OTP Email Failed: ' . $e->getMessage());
            \Log::error('Patient Email: ' . $patient->email);
            \Log::error('Full Exception: ', ['exception' => $e]);

            // Save OTP anyway for testing
            $patient->save();

            // For development, show OTP on screen
            if (config('app.debug')) {
                return redirect()->route('patient.reset.password.verify')
                    ->with('email', $patient->email)
                    ->with('otp_display', $otp)
                    ->with('warning', 'Email could not be sent. Use this OTP for testing: ' . $otp);
            }

            return back()->with('error', 'Failed to send OTP. Please try again later.');
        }
    }

    // NEW: Show Verify OTP for Password Reset
    public function showResetPasswordVerify()
    {
        if (!session('email')) {
            return redirect()->route('patient.forgot.password');
        }
        return view('patients.reset-password-verify');
    }

    // NEW: Verify OTP for Password Reset
    public function verifyResetPasswordOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric|digits:6',
            'email' => 'required|email'
        ]);

        $patient = Patient::where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('otp_expires_at', '>', Carbon::now())
            ->first();

        if (!$patient) {
            return back()->with('error', 'Invalid or expired OTP.');
        }

        // Clear OTP after successful verification
        $patient->otp = null;
        $patient->otp_expires_at = null;
        $patient->save();

        // Create a temporary token for password reset
        $tempToken = Str::random(60);
        session(['password_reset_token' => $tempToken]);
        session(['password_reset_email' => $patient->email]);

        return redirect()->route('patient.reset.password.form')
            ->with('success', 'OTP verified successfully. Please set your new password.');
    }

    // NEW: Show Reset Password Form (after OTP verification)
    public function showResetPasswordForm()
    {
        if (!session('password_reset_token')) {
            return redirect()->route('patient.forgot.password');
        }
        return view('patients.reset-password-form');
    }

    // NEW: Reset Password (after OTP verification)
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed'
        ]);

        // Verify session token
        if (!session('password_reset_token')) {
            return redirect()->route('patient.forgot.password')
                ->with('error', 'Session expired. Please try again.');
        }

        $email = session('password_reset_email');
        $patient = Patient::where('email', $email)->first();

        if (!$patient) {
            return redirect()->route('patient.forgot.password')
                ->with('error', 'Patient not found.');
        }

        // Update password
        $patient->password = Hash::make($request->password);
        $patient->save();

        // Clear session data
        session()->forget(['password_reset_token', 'password_reset_email']);

        return redirect()->route('patient.login')
            ->with('success', 'Password reset successfully! Please login with your new password.');
    }

    // Patient Logout
    public function logout()
    {
        Auth::guard('patient')->logout();
        return redirect()->route('patient.login');
    }
    /**
     * Show change password form for logged-in patients
     */
    public function showChangePasswordForm()
    {
        $patient = Auth::guard('patient')->user();

        if (!$patient) {
            return redirect()->route('patient.login')->with('error', 'Please login first.');
        }

        return view('patients.change-password', compact('patient'));
    }

    /**
     * Update password for logged-in patients
     */
    public function updatePassword(Request $request)
    {
        $patient = Auth::guard('patient')->user();

        if (!$patient) {
            return redirect()->route('patient.login')->with('error', 'Please login first.');
        }

        // Validate the request
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
            'new_password_confirmation' => 'required'
        ]);

        // Check current password
        if (!Hash::check($request->current_password, $patient->password)) {
            return back()->with('error', 'Current password is incorrect.');
        }

        // Update password
        $patient->password = Hash::make($request->new_password);
        $patient->save();

        // Logout and redirect to login page
        Auth::guard('patient')->logout();

        return redirect()->route('patient.login')
            ->with('success', 'Password changed successfully. Please login with your new password.');
    }
}
