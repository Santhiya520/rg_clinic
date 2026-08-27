<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfPatientNotAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if patient is not authenticated
        if (!Auth::guard('patient')->check()) {
            return redirect()->route('patient.login')
                ->with('error', 'Please login to access this page.');
        }

        return $next($request);
    }
}
