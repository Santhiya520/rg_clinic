<?php

namespace App\Http\Controllers;

use App\Models\InpatientRegister;
use App\Models\OpRegister;
use App\Models\Patient;
use App\Models\User;
use App\Models\Medicine;
use App\Models\OperationRegister;
use App\Models\OpLabTest;
use App\Models\OpRadiology;
use App\Models\OpMedicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;


class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // If the user is a reception, redirect to reception dashboard
        if ($user && $user->role === 'reception') {
            return redirect()->route('reception.dashboard'); // make sure this route exists
        }

        if ($user && $user->role === 'doctor') {
            return redirect()->route('doctor.dashboard');
        }

        if ($user && $user->role === 'pharmacy') {
            return redirect()->route('pharmacy.dashboard');
        }

        if ($user && $user->role === 'radiology') {
            return redirect()->route('radiology.dashboard');
        }

        if ($user && $user->role === 'lab') {
            return redirect()->route('lab.dashboard');
        }

        // Today's statistics
        $todayOpCount = OpRegister::whereDate('created_at', today())->count();
        $yesterdayOpCount = OpRegister::whereDate('created_at', today()->subDay())->count();
        $opGrowth = $yesterdayOpCount > 0 ? round((($todayOpCount - $yesterdayOpCount) / $yesterdayOpCount) * 100, 1) : 100;

        $totalPatients = Patient::count();

        $pendingTests = OpLabTest::where('status', 'pending')->count() +
            OpRadiology::where('status', 'pending')->count();

        // Monthly revenue (sum of all test prices)
        $monthlyRevenue = OpLabTest::whereMonth('created_at', now()->month)
            ->sum('price') + OpRadiology::whereMonth('created_at', now()->month)
            ->sum('price');

        $lastMonthRevenue = OpLabTest::whereMonth('created_at', now()->subMonth()->month)
            ->sum('price') + OpRadiology::whereMonth('created_at', now()->subMonth()->month)
            ->sum('price');

        $revenueGrowth = $lastMonthRevenue > 0 ? round((($monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1) : 100;

        // Recent registrations
        $recentRegisters = OpRegister::with(['patient', 'medicalOfficer'])
            ->whereDate('created_at', today())
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Today's test counts
        $todayLabTests = OpLabTest::whereDate('created_at', today())->count();
        $todayRadiologyTests = OpRadiology::whereDate('created_at', today())->count();
        $todayMedicines = OpMedicine::whereDate('created_at', today())->count();

        // Available doctors
        $availableDoctors = User::where('role', 'doctor')->count();

        // Low stock medicines
        $lowStockMedicines = Medicine::where('stock', '<=', 10)
            ->orderBy('stock')
            ->take(5)
            ->get();

        // Pending test results
        $pendingLabTests = OpLabTest::with(['opRegister.patient', 'labTest'])
            ->where('status', 'pending')
            ->take(3)
            ->get();

        $pendingRadiologyTests = OpRadiology::with(['opRegister.patient', 'radiologyTest'])
            ->where('status', 'pending')
            ->take(3)
            ->get();

        $pendingTestResults = $pendingLabTests->merge($pendingRadiologyTests);

        return view('dashboard', compact(
            'todayOpCount',
            'opGrowth',
            'totalPatients',
            'pendingTests',
            'monthlyRevenue',
            'revenueGrowth',
            'recentRegisters',
            'todayLabTests',
            'todayRadiologyTests',
            'todayMedicines',
            'availableDoctors',
            'lowStockMedicines',
            'pendingTestResults'
        ));
    }
    // In DashboardController.php or create receptionController.php
    public function receptionDashboard()
    {
        $receptionId = auth()->id();

        // Today's OP registrations by reception
        $todayOpCount = OpRegister::whereDate('created_at', today())
            ->where('user_id', $receptionId)
            ->count();

        // Today's IP registrations by reception
        $todayIpCount = InpatientRegister::whereDate('created_at', today())
            ->where('user_id', $receptionId)
            ->count();

        // Today's Operations registered by reception
        $todayOperationCount = OperationRegister::whereDate('created_at', today())
            ->where('user_id', $receptionId)
            ->count();

        // Total OP/IP/Operation registered by reception
        $totalRegistrations =
            $todayOpCount +
            $todayIpCount +
            $todayOperationCount;

        return view('reception.dashboard', compact(
            'todayOpCount',
            'todayIpCount',
            'todayOperationCount',
            'totalRegistrations'
        ));
    }

    public function doctorDashboard()
    {
        $doctorId = auth()->id();

        // Today's OP cases for doctor
        $todayOpCount = OpRegister::whereDate('created_at', today())
            ->where('medical_officer_id', $doctorId)
            ->count();

        // Today's IP cases for doctor
        $todayIpCount = InpatientRegister::whereDate('created_at', today())
            ->where('medical_officer_id', $doctorId)
            ->count();

        // Today's Operations for doctor
        $todayOperationCount = OperationRegister::whereDate('created_at', today())
            ->where('medical_officer_id', $doctorId)
            ->count();

        // Total cases today
        $totalCases =
            $todayOpCount +
            $todayIpCount +
            $todayOperationCount;

        return view('doctor.dashboard', compact(
            'todayOpCount',
            'todayIpCount',
            'todayOperationCount',
            'totalCases'
        ));
    }

    public function pharmacyDashboard()
    {
        $today = today();

        // OP medicine prescriptions (grouped by OP register)
        $opPrescriptionCount = DB::table('op_medicines')
            ->select('op_register_id')
            ->whereDate('created_at', $today)
            ->groupBy('op_register_id')
            ->get()
            ->count();

        // IP medicine prescriptions (grouped by IP register)
        $ipPrescriptionCount = DB::table('ip_medicines')
            ->select('inpatient_register_id')
            ->whereDate('created_at', $today)
            ->groupBy('inpatient_register_id')
            ->get()
            ->count();

        // Today medicine sales
        $todaySalesCount = DB::table('medicine_sales')
            ->whereDate('sale_date', $today)
            ->count();

        $todaySalesAmount = DB::table('medicine_sales')
            ->whereDate('sale_date', $today)
            ->sum('grand_total');

        // Today medicine purchases
        $todayPurchaseCount = DB::table('medicine_purchases')
            ->whereDate('purchase_date', $today)
            ->count();

        $todayPurchaseAmount = DB::table('medicine_purchases')
            ->whereDate('purchase_date', $today)
            ->sum('total_amount');

        // Stock
        $totalMedicines = Medicine::count();

        $lowStockMedicines = Medicine::where('stock', '<=', 10)
            ->orderBy('stock')
            ->take(5)
            ->get();

        return view('pharmacy.dashboard', compact(
            'opPrescriptionCount',
            'ipPrescriptionCount',
            'todaySalesCount',
            'todaySalesAmount',
            'todayPurchaseCount',
            'todayPurchaseAmount',
            'totalMedicines',
            'lowStockMedicines'
        ));
    }

    public function radiologyDashboard()
    {
        $today = today();

        // OP radiology cases (grouped by OP register)
        $opRadiologyCount = DB::table('op_radiologies')
            ->select('op_register_id')
            ->whereDate('created_at', $today)
            ->groupBy('op_register_id')
            ->get()
            ->count();

        // IP radiology cases (grouped by IP register)
        $ipRadiologyCount = DB::table('ip_radiologies')
            ->select('inpatient_register_id')
            ->whereDate('created_at', $today)
            ->groupBy('inpatient_register_id')
            ->get()
            ->count();

        // Pending tests (OP + IP)
        $pendingRadiology = DB::table('op_radiologies')
            ->where('status', 'pending')
            ->count()
            +
            DB::table('ip_radiologies')
            ->where('status', 'pending')
            ->count();

        // Completed tests (OP + IP)
        $completedRadiology = DB::table('op_radiologies')
            ->where('status', 'completed')
            ->count()
            +
            DB::table('ip_radiologies')
            ->where('status', 'completed')
            ->count();

        // Today revenue
        $todayRevenue =
            DB::table('op_radiologies')
            ->whereDate('created_at', $today)
            ->sum('paid_amount')
            +
            DB::table('ip_radiologies')
            ->whereDate('created_at', $today)
            ->sum('paid_amount');

        return view('radiology.dashboard', compact(
            'opRadiologyCount',
            'ipRadiologyCount',
            'pendingRadiology',
            'completedRadiology',
            'todayRevenue'
        ));
    }

    public function labDashboard()
    {
        $today = today();

        // OP lab cases (grouped by OP register)
        $opLabCount = DB::table('op_lab_tests')
            ->select('op_register_id')
            ->whereDate('created_at', $today)
            ->groupBy('op_register_id')
            ->get()
            ->count();

        // IP lab cases (grouped by IP register)
        $ipLabCount = DB::table('ip_lab_tests')
            ->select('inpatient_register_id')
            ->whereDate('created_at', $today)
            ->groupBy('inpatient_register_id')
            ->get()
            ->count();

        // Pending lab tests (OP + IP)
        $pendingLabTests =
            DB::table('op_lab_tests')->where('status', 'pending')->count()
            +
            DB::table('ip_lab_tests')->where('status', 'pending')->count();

        // Completed lab tests (OP + IP)
        $completedLabTests =
            DB::table('op_lab_tests')->where('status', 'completed')->count()
            +
            DB::table('ip_lab_tests')->where('status', 'completed')->count();

        // Today revenue
        $todayLabRevenue =
            DB::table('op_lab_tests')
            ->whereDate('created_at', $today)
            ->sum('paid_amount')
            +
            DB::table('ip_lab_tests')
            ->whereDate('created_at', $today)
            ->sum('paid_amount');

        return view('lab.dashboard', compact(
            'opLabCount',
            'ipLabCount',
            'pendingLabTests',
            'completedLabTests',
            'todayLabRevenue'
        ));
    }

    public function showChangePassword()
    {
        return view('auth.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'confirmed'],
        ]);

        $user = auth()->user();

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Current password is incorrect.',
            ]);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Password changed successfully.');
    }
}
