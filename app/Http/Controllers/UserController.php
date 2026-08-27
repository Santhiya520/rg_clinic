<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // ---------- LOGIN METHODS ----------
    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        // Validate form input
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        // Login attempt
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

            // Redirect based on role
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('dashboard');
                case 'reception':
                    return redirect()->route('reception.dashboard');
                case 'doctor':
                    return redirect()->route('doctor.dashboard');
                case 'lab':
                    return redirect()->route('lab.dashboard');
                case 'radiology':
                    return redirect()->route('radiology.dashboard');
                case 'pharmacy':
                    return redirect()->route('pharmacy.dashboard');
                default:
                    Auth::logout();
                    return back()->with('error', 'Unauthorized role');
            }
        }

        return back()->with('error', 'Invalid login details');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'You have been logged out successfully.');
    }

    // ---------- DASHBOARD METHOD ----------
    public function dashboard()
    {
        $users = User::all();
        return view('dashboard', compact('users'));
    }

    // ---------- USER CRUD METHODS ----------

    // Display all users - USER LIST PAGE
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    // Show create user form
    public function create()
    {
        return view('users.create');
    }

    // Show edit user form
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    // Store new user - REDIRECT TO USER LIST
    public function store(Request $request)
    {
        \Log::info('User creation request data:', $request->all());

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:3',
            'role' => 'required|in:admin,doctor,reception,lab,radiology,pharmacy',
            'phone' => 'nullable|string',
            'consulting_fee' => 'nullable|numeric'
        ]);

        \Log::info('Validation passed');

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'phone' => $request->phone,
                'consulting_fee' => $request->consulting_fee
            ]);

            \Log::info('User created successfully with ID: ' . $user->id);

            // REDIRECT TO SUCCESS PAGE
            return redirect()->route('users.success')->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            \Log::error('User creation failed: ' . $e->getMessage());
            return back()->with('error', 'User creation failed: ' . $e->getMessage());
        }
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,doctor,reception,lab,radiology,pharmacy',
            'phone' => 'nullable|string',
            'consulting_fee' => 'nullable|numeric',
            'password' => 'nullable|min:3' // Add confirmed rule if you have password confirmation
        ]);

        // Start with basic user data
        $userData = $request->only(['name', 'email', 'role', 'phone', 'consulting_fee']);

        // Handle password update if provided
        if ($request->has('password') && !empty($request->password)) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        return redirect()->route('users.success')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        // REDIRECT TO SUCCESS PAGE
        return redirect()->route('users.success')->with('success', 'User deleted successfully.');
    }

    // Success page method
    public function success()
    {
        return view('users.success');
    }
}
