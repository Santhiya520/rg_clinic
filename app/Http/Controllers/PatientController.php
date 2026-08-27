<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PatientController extends Controller
{
    // Display all patients
    public function index()
    {
        $patients = Patient::withCount('opRegisters')->latest()->get();
        return view('patients.index', compact('patients'));
    }

    // Show create patient form
    public function create()
    {
        return view('patients.create');
    }

    // Store new patient
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'mobile' => 'nullable|string|max:15',
            'age' => 'required|integer|min:0|max:150',
            'sex' => 'required|in:male,female,other'
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id(); // or $request->user()->id

        Patient::create($data);

        return redirect()->route('patients.success')->with('success', 'Patient registered successfully.');
    }

    // Show edit patient form
    public function edit(Patient $patient)
    {
        return view('patients.edit', compact('patient'));
    }

    // Update patient
    public function update(Request $request, Patient $patient)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'mobile' => 'nullable|string|max:15',
            'age' => 'required|integer|min:0|max:150',
            'sex' => 'required|in:male,female,other'
        ]);

        $patient->update($request->all());

        return redirect()->route('patients.success')->with('success', 'Patient updated successfully.');
    }

    // Delete patient
    public function destroy(Patient $patient)
    {
        $patient->delete();
        return redirect()->route('patients.success')->with('success', 'Patient deleted successfully.');
    }

    // Success page
    public function success()
    {
        return view('patients.success');
    }

    // Search patients for OP register
    public function search(Request $request)
    {
        $search = $request->get('search');

        $patients = Patient::where('name', 'like', "%{$search}%")
            ->orWhere('patient_id', 'like', "%{$search}%")
            ->orWhere('mobile', 'like', "%{$search}%")
            ->get();

        return response()->json($patients);
    }

    public function patientReport(Request $request)
    {
        // Get all patients for dropdown
        $patientsForDropdown = Patient::orderBy('name')->get(['id', 'patient_id', 'name']);

        $query = Patient::with([
            'opRegisters.medicalOfficer',
            'opRegisters.radiologyTests',
            'opRegisters.labTests',
            'opRegisters.medicines',
            'inpatientRegisters.radiologyTests',
            'inpatientRegisters.labTests',
            'inpatientRegisters.medicines'
        ]);

        // Apply filters only if request has any filter parameters
        if ($request->hasAny(['search', 'patient_id', 'from_date', 'to_date'])) {

            // Patient filter
            if ($request->filled('patient_id')) {
                $query->where('id', $request->patient_id);
            }

            // Search filter
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('patient_id', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('mobile', 'like', "%{$search}%");
                });
            }

            // Date range filter
            if ($request->filled('from_date') && $request->filled('to_date')) {
                $query->where(function ($q) use ($request) {
                    $q->whereHas('opRegisters', function ($q) use ($request) {
                        $q->whereBetween('created_at', [
                            $request->from_date . ' 00:00:00',
                            $request->to_date . ' 23:59:59'
                        ]);
                    })->orWhereHas('inpatientRegisters', function ($q) use ($request) {
                        $q->whereBetween('created_at', [
                            $request->from_date . ' 00:00:00',
                            $request->to_date . ' 23:59:59'
                        ]);
                    });
                });
            } elseif ($request->filled('from_date')) {
                $query->where(function ($q) use ($request) {
                    $q->whereHas('opRegisters', function ($q) use ($request) {
                        $q->where('created_at', '>=', $request->from_date . ' 00:00:00');
                    })->orWhereHas('inpatientRegisters', function ($q) use ($request) {
                        $q->where('created_at', '>=', $request->from_date . ' 00:00:00');
                    });
                });
            } elseif ($request->filled('to_date')) {
                $query->where(function ($q) use ($request) {
                    $q->whereHas('opRegisters', function ($q) use ($request) {
                        $q->where('created_at', '<=', $request->to_date . ' 23:59:59');
                    })->orWhereHas('inpatientRegisters', function ($q) use ($request) {
                        $q->where('created_at', '<=', $request->to_date . ' 23:59:59');
                    });
                });
            }

            $patients = $query->orderBy('name')->get();
        } else {
            // If no filters applied, return empty collection
            $patients = collect();
            return view('patient-reports.index', compact('patients', 'patientsForDropdown'));
        }

        return view('patient-reports.index', compact('patients', 'patientsForDropdown'));
    }

    public function patientDetails(Patient $patient)
    {
        $patient->load([
            'opRegisters.medicalOfficer',
            'opRegisters.radiologyTests.radiologyTest',
            'opRegisters.labTests.labTest',
            'opRegisters.medicines.medicine',
            'inpatientRegisters.radiologyTests.radiologyTest',
            'inpatientRegisters.labTests.labTest',
            'inpatientRegisters.medicines.medicine',
            'operationRegisters.operatingSurgeon',
            'operationRegisters.assistantSurgeon',
            'operationRegisters.anaesthetist',
            'operationRegisters.staffreception',
            'operationRegisters.medicalOfficer'
        ]);

        // Calculate totals
        $totalOpVisits = $patient->opRegisters->count();
        $totalIpAdmissions = $patient->inpatientRegisters->count();
        $totalOperations = $patient->operationRegisters->count();

        // OP Amounts
        $totalOpAmount = $patient->opRegisters->sum(function ($register) {
            return $register->radiologyTests->sum('price') +
                $register->labTests->sum('price') +
                $register->medicines->sum('price');
        });

        // IP Amounts
        $totalIpAmount = $patient->inpatientRegisters->sum(function ($register) {
            return $register->radiologyTests->sum('price') +
                $register->labTests->sum('price') +
                $register->medicines->sum('price');
        });

        // Operation amounts (operations typically don't have medicines/tests in register)
        $totalOperationAmount = 0; // Add if you have operation charges

        $totalAmount = $totalOpAmount + $totalIpAmount + $totalOperationAmount;

        // Counts for display
        $totalRadiologyTests = $patient->opRegisters->sum(function ($r) {
            return $r->radiologyTests->count();
        }) + $patient->inpatientRegisters->sum(function ($r) {
            return $r->radiologyTests->count();
        });

        $totalLabTests = $patient->opRegisters->sum(function ($r) {
            return $r->labTests->count();
        }) + $patient->inpatientRegisters->sum(function ($r) {
            return $r->labTests->count();
        });

        $totalMedicines = $patient->opRegisters->sum(function ($r) {
            return $r->medicines->count();
        }) + $patient->inpatientRegisters->sum(function ($r) {
            return $r->medicines->count();
        });

        return view('patient-reports.details', compact(
            'patient',
            'totalOpVisits',
            'totalIpAdmissions',
            'totalOperations',
            'totalAmount',
            'totalOpAmount',
            'totalIpAmount',
            'totalOperationAmount',
            'totalRadiologyTests',
            'totalLabTests',
            'totalMedicines'
        ));
    }
    public function patientPrint(Patient $patient)
    {
        // Calculate totals
        $totalOpVisits = $patient->opRegisters->count();
        $totalIpAdmissions = $patient->inpatientRegisters->count();
        $totalOperations = $patient->operationRegisters->count();

        // Calculate amounts
        $totalOpAmount = $patient->opRegisters->sum(function ($register) {
            return $register->radiologyTests->sum('price') +
                $register->labTests->sum('price') +
                $register->medicines->sum('price');
        });

        $totalIpAmount = $patient->inpatientRegisters->sum(function ($register) {
            return $register->radiologyTests->sum('price') +
                $register->labTests->sum('price') +
                $register->medicines->sum('price');
        });

        $totalOperationAmount = $patient->operationRegisters->sum('total_amount') ?? 0;

        $totalAmount = $totalOpAmount + $totalIpAmount + $totalOperationAmount;

        return view('patient-reports.print', compact(
            'patient',
            'totalOpVisits',
            'totalIpAdmissions',
            'totalOperations',
            'totalOpAmount',
            'totalIpAmount',
            'totalOperationAmount',
            'totalAmount'
        ));
    }
    public function dashboard()
    {
        $patient = Auth::guard('patient')->user();

        if (!$patient) {
            abort(403, 'Unauthorized access.');
        }

        // Load patient with relationships
        $patient->load([
            'opRegisters' => function ($query) {
                $query->orderBy('created_at', 'desc');
            },
            'opRegisters.medicalOfficer',
            'opRegisters.labTests.labTest',
            'opRegisters.radiologyTests.radiologyTest',
            'opRegisters.medicines.medicine'
        ]);

        // Today's Appointments
        $todayAppointments = $patient->opRegisters
            ->where('created_at', '>=', Carbon::today())
            ->count();

        // Total Visits
        $totalVisits = $patient->opRegisters->count();

        // Pending Payments (simplified calculation)
        $pendingPayments = 0;
        foreach ($patient->opRegisters as $register) {
            // Calculate total cost for each register
            $registerTotal = $register->radiologyTests->sum('price') +
                $register->labTests->sum('price') +
                $register->medicines->sum('price');
            // Assuming paid_amount field exists or use status
            // For now, we'll show total amount as pending if not paid
            if ($register->status != 'completed') {
                $pendingPayments += $registerTotal;
            }
        }

        // Completed Treatments
        $completedTreatments = $patient->opRegisters
            ->where('status', 'completed')
            ->count();

        // Recent Visits (last 5)
        $recentVisits = $patient->opRegisters->take(5);

        // Lab Tests (from all visits)
        $labTests = collect();
        foreach ($patient->opRegisters as $register) {
            foreach ($register->labTests as $test) {
                $labTests->push($test);
            }
        }

        // Radiology Tests (from all visits)
        $radiologyTests = collect();
        foreach ($patient->opRegisters as $register) {
            foreach ($register->radiologyTests as $test) {
                $radiologyTests->push($test);
            }
        }

        // Upcoming Appointments (next 7 days)
        $upcomingAppointments = $patient->opRegisters
            ->where('created_at', '>=', Carbon::today())
            ->where('created_at', '<=', Carbon::today()->addDays(7))
            ->take(5);

        // Prescriptions (active medicines)
        $prescriptions = collect();
        foreach ($patient->opRegisters as $register) {
            foreach ($register->medicines as $medicine) {
                $prescriptions->push($medicine);
            }
        }

        return view('online.dashboard', compact(
            'patient',
            'todayAppointments',
            'totalVisits',
            'pendingPayments',
            'completedTreatments',
            'recentVisits',
            'labTests',
            'radiologyTests',
            'upcomingAppointments',
            'prescriptions'
        ));
    }
}
