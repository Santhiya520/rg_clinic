<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientVerified
{
    public function handle(Request $request, Closure $next)
    {
        $patient = Auth::guard('patient')->user();

        if (!$patient) {
            return redirect()->route('patient.login');
        }

        if (!$patient->is_verified) {
            Auth::guard('patient')->logout();
            return redirect()->route('patient.login')
                ->with('error', 'Please verify your email first.');
        }

        return $next($request);
    }
}
