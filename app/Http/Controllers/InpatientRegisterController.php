<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\InpatientRegister;
use App\Models\IpLabTest;
use App\Models\IpMedicine;
use App\Models\IpRadiology;
use App\Models\LabTest;
use App\Models\Medicine;
use App\Models\Patient;
use App\Models\RadiologyTest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InpatientRegisterController extends Controller
{
    public function index()
    {
        $inpatients = InpatientRegister::with(['patient', 'doctor'])
            ->whereNull('date_of_discharge')
            ->orderBy('id', 'desc')
            ->get();
        return view('inpatient-register.index', compact('inpatients'));
    }

    public function create()
    {
        $patients = Patient::all();
        $doctors = User::where('role', 'doctor')->orderBy('name')->get();
        return view('inpatient-register.create', compact('patients', 'doctors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'date_of_admission' => 'required|date',
            'provisional_diagnosis' => 'required|string|max:1000',
            'investigations' => 'nullable|string|max:1000',
            'final_diagnosis' => 'required|string|max:1000',
            'treatment' => 'required|string|max:1000',
            'date_of_discharge' => 'nullable|date|after_or_equal:date_of_admission',
            'result' => 'required|in:Cured,Same condition,Referred,Expired',
            'additional_info' => 'nullable|string|max:1000',
            'medical_officer_id' => 'required|exists:users,id'
        ]);

        // Auto-generate Hospital IP No.
        $currentYear = date('Y');
        $currentMonth = date('m');
        $prefix = "IP{$currentYear}{$currentMonth}";

        // Find the last IP number for this year-month
        $lastInpatient = InpatientRegister::whereYear('date_of_admission', $currentYear)
            ->whereMonth('date_of_admission', $currentMonth)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastInpatient && str_starts_with($lastInpatient->hospital_ip_no, $prefix)) {
            // Extract the sequential number and increment
            $lastNumber = (int) substr($lastInpatient->hospital_ip_no, strlen($prefix));
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            $hospitalIpNo = $prefix . $newNumber;
        } else {
            // First record for this month
            $hospitalIpNo = $prefix . '001';
        }

        // Create the record with auto-generated hospital_ip_no
        InpatientRegister::create([
            'patient_id' => $request->patient_id,
            'hospital_ip_no' => $hospitalIpNo,
            'date_of_admission' => $request->date_of_admission,
            'provisional_diagnosis' => $request->provisional_diagnosis,
            'investigations' => $request->investigations,
            'final_diagnosis' => $request->final_diagnosis,
            'treatment' => $request->treatment,
            'date_of_discharge' => $request->date_of_discharge,
            'result' => $request->result,
            'additional_info' => $request->additional_info,
            'medical_officer_id' => $request->medical_officer_id,
            'user_id' => auth()->id()
        ]);

        return redirect()->route('inpatient-register.index')
            ->with('success', 'Inpatient record created successfully. Hospital IP No: ' . $hospitalIpNo);
    }
    public function show(InpatientRegister $inpatientRegister)
    {
        $inpatientRegister->load(['patient', 'doctor']);
        return view('inpatient-register.show', compact('inpatientRegister'));
    }

    public function edit(InpatientRegister $inpatientRegister)
    {
        $patients = Patient::all();
        $doctors = User::where('role', 'doctor')->orderBy('name')->get();
        return view('inpatient-register.edit', compact('inpatientRegister', 'patients', 'doctors'));
    }

    public function update(Request $request, InpatientRegister $inpatientRegister)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'hospital_ip_no' => 'required|string|max:50',
            'date_of_admission' => 'required|date',
            'provisional_diagnosis' => 'required|string|max:1000',
            'investigations' => 'nullable|string|max:1000',
            'final_diagnosis' => 'required|string|max:1000',
            'treatment' => 'required|string|max:1000',
            'date_of_discharge' => 'nullable|date',
            'result' => 'required|in:Cured,Same condition,Referred,Expired',
            'additional_info' => 'nullable|string|max:1000',
            'medical_officer_id' => 'required|exists:users,id'
        ]);

        // Update the record with all fields
        $inpatientRegister->update([
            'patient_id' => $request->patient_id,
            'hospital_ip_no' => $request->hospital_ip_no,
            'date_of_admission' => $request->date_of_admission,
            'provisional_diagnosis' => $request->provisional_diagnosis,
            'investigations' => $request->investigations,
            'final_diagnosis' => $request->final_diagnosis,
            'treatment' => $request->treatment,
            'date_of_discharge' => $request->date_of_discharge,
            'result' => $request->result,
            'additional_info' => $request->additional_info,
            'medical_officer_id' => $request->medical_officer_id,
        ]);

        return redirect()->route('inpatient-register.index')
            ->with('success', 'Inpatient record updated successfully.');
    }



    public function destroy(InpatientRegister $inpatientRegister)
    {
        $inpatientRegister->delete();

        return redirect()->route('inpatient-register.index')
            ->with('success', 'Inpatient record deleted successfully.');
    }

    public function doctorIp()
    {
        $user = auth()->user();

        $query = InpatientRegister::with([
            'patient',
            'medicines',
            'radiologyTests',
            'labTests'
        ])->whereNull('date_of_discharge');

        // Doctor should see only his patients
        if ($user->role === 'doctor') {
            $query->where('medical_officer_id', $user->id);
        }

        $inpatients = $query->orderBy('id', 'desc')->get();

        $medicines = Medicine::all();
        $radiologyTests = RadiologyTest::all();
        $labTests = LabTest::all();

        return view(
            'inpatient-register.doctor-ip',
            compact('inpatients', 'medicines', 'radiologyTests', 'labTests')
        );
    }


    public function createPrescription(InpatientRegister $inpatientRegister)
    {
        $inpatientRegister->load('patient');
        $medicines = Medicine::all();
        $radiologyTests = RadiologyTest::all();
        $labTests = LabTest::all();

        return view('inpatient-register.prescription-create', compact(
            'inpatientRegister',
            'medicines',
            'radiologyTests',
            'labTests'
        ));
    }

    public function storePrescription(Request $request, InpatientRegister $inpatientRegister)
    {
        $validated = $request->validate([
            'provisional_diagnosis' => 'required|string',
            'investigations' => 'nullable|string',
            'final_diagnosis' => 'nullable|string',
            'treatment' => 'nullable|string',
            'result' => 'nullable|string',
            'additional_info' => 'nullable|string',
            'medicines' => 'nullable|array',
            'medicines.*.medicine_id' => 'required|exists:medicines,id',
            'medicines.*.no_of_days' => 'required|integer|min:1',
            'medicines.*.quantity' => 'required|integer|min:1',
            'medicines.*.price' => 'required|numeric|min:0',
            'medicines.*.morning' => 'nullable|boolean',
            'medicines.*.afternoon' => 'nullable|boolean',
            'medicines.*.night' => 'nullable|boolean',
            'medicines.*.instructions' => 'nullable|string',
            'radiologies' => 'nullable|array',
            'radiologies.*.radiology_test_id' => 'required|exists:radiology_tests,id',
            'radiologies.*.notes' => 'nullable|string',
            'lab_tests' => 'nullable|array',
            'lab_tests.*.lab_test_id' => 'required|exists:lab_tests,id',
            'lab_tests.*.notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($inpatientRegister, $validated) {
            $inpatientRegister->update([
                'provisional_diagnosis' => $validated['provisional_diagnosis'] ?? null,
                'investigations' => $validated['investigations'] ?? null,
                'final_diagnosis' => $validated['final_diagnosis'] ?? null,
                'treatment' => $validated['treatment'] ?? null,
                'result' => $validated['result'] ?? null,
                'additional_info' => $validated['additional_info'] ?? null,
            ]);

            if (isset($validated['medicines'])) {
                foreach ($validated['medicines'] as $medicineData) {
                    if ($medicineData['medicine_id'] > 0) {
                        IpMedicine::create([
                            'inpatient_register_id' => $inpatientRegister->id,
                            'medicine_id' => $medicineData['medicine_id'],
                            'morning' => $medicineData['morning'] ?? 0,
                            'afternoon' => $medicineData['afternoon'] ?? 0,
                            'night' => $medicineData['night'] ?? 0,
                            'no_of_days' => $medicineData['no_of_days'],
                            'quantity' => $medicineData['quantity'],
                            'price' => $medicineData['price'],
                            'instructions' => $medicineData['instructions'] ?? null,
                            'user_id' => auth()->id()
                        ]);
                    }
                }
            }

            if (isset($validated['radiologies'])) {
                foreach ($validated['radiologies'] as $radiologyData) {
                    if ($radiologyData['radiology_test_id'] > 0) {
                        $test = RadiologyTest::find($radiologyData['radiology_test_id']);
                        IpRadiology::create([
                            'inpatient_register_id' => $inpatientRegister->id,
                            'radiology_test_id' => $radiologyData['radiology_test_id'],
                            'price' => $test->price ?? 0,
                            'notes' => $radiologyData['notes'] ?? null,
                            'user_id' => auth()->id()
                        ]);
                    }
                }
            }

            if (isset($validated['lab_tests'])) {
                foreach ($validated['lab_tests'] as $labTestData) {
                    if ($labTestData['lab_test_id'] > 0) {
                        $test = LabTest::find($labTestData['lab_test_id']);
                        IpLabTest::create([
                            'inpatient_register_id' => $inpatientRegister->id,
                            'lab_test_id' => $labTestData['lab_test_id'],
                            'price' => $test->price ?? 0,
                            'notes' => $labTestData['notes'] ?? null,
                            'user_id' => auth()->id()
                        ]);
                    }
                }
            }
        });

        return redirect()->route('inpatient-register.doctor-ip')
            ->with('success', 'Prescription added successfully');
    }

    public function editPrescription(InpatientRegister $inpatientRegister)
    {
        $inpatientRegister->load(['patient', 'medicines', 'radiologyTests', 'labTests']);
        $medicines = Medicine::latest()->get();
        $radiologyTests = RadiologyTest::latest()->get();
        $labTests = LabTest::latest()->get();

        return view('inpatient-register.prescription-edit', compact(
            'inpatientRegister',
            'medicines',
            'radiologyTests',
            'labTests'
        ));
    }

    public function updatePrescription(Request $request, InpatientRegister $inpatientRegister)
    {
        $validated = $request->validate([
            'provisional_diagnosis' => 'required|string',
            'investigations' => 'nullable|string',
            'final_diagnosis' => 'nullable|string',
            'treatment' => 'nullable|string',
            'result' => 'nullable|string',
            'additional_info' => 'nullable|string',
            'medicines' => 'nullable|array',
            'medicines.*.medicine_id' => 'required|exists:medicines,id',
            'medicines.*.no_of_days' => 'required|integer|min:1',
            'medicines.*.quantity' => 'required|integer|min:1',
            'medicines.*.price' => 'required|numeric|min:0',
            'medicines.*.morning' => 'nullable|boolean',
            'medicines.*.afternoon' => 'nullable|boolean',
            'medicines.*.night' => 'nullable|boolean',
            'medicines.*.instructions' => 'nullable|string',
            'radiologies' => 'nullable|array',
            'radiologies.*.radiology_test_id' => 'required|exists:radiology_tests,id',
            'radiologies.*.notes' => 'nullable|string',
            'lab_tests' => 'nullable|array',
            'lab_tests.*.lab_test_id' => 'required|exists:lab_tests,id',
            'lab_tests.*.notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($inpatientRegister, $validated) {
            $inpatientRegister->update([
                'provisional_diagnosis' => $validated['provisional_diagnosis'] ?? null,
                'investigations' => $validated['investigations'] ?? null,
                'final_diagnosis' => $validated['final_diagnosis'] ?? null,
                'treatment' => $validated['treatment'] ?? null,
                'result' => $validated['result'] ?? null,
                'additional_info' => $validated['additional_info'] ?? null,
            ]);

            if (isset($validated['medicines'])) {
                $inpatientRegister->medicines()->delete();
                foreach ($validated['medicines'] as $medicineData) {
                    if ($medicineData['medicine_id'] > 0) {
                        IpMedicine::create([
                            'inpatient_register_id' => $inpatientRegister->id,
                            'medicine_id' => $medicineData['medicine_id'],
                            'morning' => $medicineData['morning'] ?? 0,
                            'afternoon' => $medicineData['afternoon'] ?? 0,
                            'night' => $medicineData['night'] ?? 0,
                            'no_of_days' => $medicineData['no_of_days'],
                            'quantity' => $medicineData['quantity'],
                            'price' => $medicineData['price'],
                            'instructions' => $medicineData['instructions'] ?? null,
                        ]);
                    }
                }
            } else {
                $inpatientRegister->medicines()->delete();
            }

            if (isset($validated['radiologies'])) {
                $inpatientRegister->radiologyTests()->delete();
                foreach ($validated['radiologies'] as $radiologyData) {
                    if ($radiologyData['radiology_test_id'] > 0) {
                        $test = RadiologyTest::find($radiologyData['radiology_test_id']);
                        IpRadiology::create([
                            'inpatient_register_id' => $inpatientRegister->id,
                            'radiology_test_id' => $radiologyData['radiology_test_id'],
                            'price' => $test->price ?? 0,
                            'notes' => $radiologyData['notes'] ?? null,
                        ]);
                    }
                }
            } else {
                $inpatientRegister->radiologyTests()->delete();
            }

            if (isset($validated['lab_tests'])) {
                $inpatientRegister->labTests()->delete();
                foreach ($validated['lab_tests'] as $labTestData) {
                    if ($labTestData['lab_test_id'] > 0) {
                        $test = LabTest::find($labTestData['lab_test_id']);
                        IpLabTest::create([
                            'inpatient_register_id' => $inpatientRegister->id,
                            'lab_test_id' => $labTestData['lab_test_id'],
                            'price' => $test->price ?? 0,
                            'notes' => $labTestData['notes'] ?? null,
                        ]);
                    }
                }
            } else {
                $inpatientRegister->labTests()->delete();
            }
        });

        return redirect()->route('inpatient-register.doctor-ip')
            ->with('success', 'Prescription updated successfully');
    }

    public function prescriptionView(InpatientRegister $inpatientRegister)
    {
        $inpatientRegister->load([
            'patient',
            'medicines' => function ($query) {
                $query->orderBy('created_at', 'desc')->with('medicine');
            },
            'radiologyTests' => function ($query) {
                $query->orderBy('created_at', 'desc')->with('radiologyTest');
            },
            'labTests' => function ($query) {
                $query->orderBy('created_at', 'desc')->with('labTest');
            }
        ]);

        return view('inpatient-register.prescription-view', compact('inpatientRegister'));
    }

    public function discharge(Request $request, InpatientRegister $inpatientRegister)
    {
        $request->validate([
            'date_of_discharge' => 'required|date|after_or_equal:date_of_admission',
            'final_diagnosis' => 'required|string',
            'result' => 'required|in:Cured,Same condition,Referred,Expired',
        ]);

        $inpatientRegister->update([
            'date_of_discharge' => $request->date_of_discharge,
            'final_diagnosis' => $request->final_diagnosis,
            'result' => $request->result,
            'status' => 'discharged',
            'additional_info' => $request->additional_info,
        ]);

        return redirect()->route('inpatient-register.doctor-ip')
            ->with('success', 'Patient discharged successfully');
    }

    // NEW: IP Report Method - FIXED
    public function report(Request $request)
    {
        // Get all patients for dropdown
        $patients = Patient::orderBy('name')->get(['id', 'patient_id', 'name']);

        // Get all doctors for dropdown
        $doctors = User::where('role', 'doctor')
            ->orderBy('name')
            ->get(['id', 'name']);

        $query = InpatientRegister::with([
            'patient',
            'medicalOfficer',
            'medicines.medicine',
            'radiologyTests.radiologyTest',
            'labTests.labTest'
        ]);

        // Apply filters only if request has any filter parameters
        if ($request->hasAny(['from_date', 'to_date', 'patient_id', 'ip_number', 'status', 'ward_bed', 'medical_officer_id', 'search'])) {

            // Date range filter
            if ($request->filled('from_date') && $request->filled('to_date')) {
                $query->whereBetween('date_of_admission', [
                    $request->from_date . ' 00:00:00',
                    $request->to_date . ' 23:59:59'
                ]);
            } elseif ($request->filled('from_date')) {
                $query->where('date_of_admission', '>=', $request->from_date . ' 00:00:00');
            } elseif ($request->filled('to_date')) {
                $query->where('date_of_admission', '<=', $request->to_date . ' 23:59:59');
            }

            // Patient filter
            if ($request->filled('patient_id')) {
                $query->where('patient_id', $request->patient_id);
            }

            // Doctor filter
            if ($request->filled('medical_officer_id')) {
                $query->where('medical_officer_id', $request->medical_officer_id);
            }

            // IP Number filter
            if ($request->filled('ip_number')) {
                $query->where('hospital_ip_no', 'like', '%' . $request->ip_number . '%');
            }

            // Status filter
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }


            // Search filter (for backward compatibility)
            if ($request->filled('search')) {
                $search = $request->search;
                $query->whereHas('patient', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('patient_id', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            }
        } else {
            // If no filters applied, return empty collection
            $inpatients = collect();
            return view('inpatient-register.report', compact(
                'inpatients',
                'patients',
                'doctors'
            ));
        }

        // Order by admission date (most recent first)
        $query->orderBy('date_of_admission', 'desc');

        // Get results
        $inpatients = $query->get();

        // Calculate totals
        $totalAmountSum = 0;

        foreach ($inpatients as $inpatient) {
            $medicineTotal = $inpatient->medicines->sum('price');
            $labTotal = $inpatient->labTests->sum('price');
            $radiologyTotal = $inpatient->radiologyTests->sum('price');

            $totalAmountSum += ($medicineTotal + $labTotal + $radiologyTotal);
        }

        return view('inpatient-register.report', compact(
            'inpatients',
            'patients',
            'doctors',
            'totalAmountSum'
        ));
    }

    // NEW: IP Preview Method - FIXED
    public function preview(InpatientRegister $inpatientRegister)
    {
        // Load all relationships
        $inpatientRegister->load([
            'patient',
            'medicines.medicine',
            'radiologyTests.radiologyTest',
            'labTests.labTest'
        ]);

        // Calculate totals from related models
        $medicineTotal = $inpatientRegister->medicines->sum('price');
        $medicinePaid = $inpatientRegister->medicines->where('status', 'paid')->sum('price');

        $radiologyTotal = $inpatientRegister->radiologyTests->sum('price');
        $radiologyPaid = $inpatientRegister->radiologyTests->where('status', 'paid')->sum('price');

        $labTotal = $inpatientRegister->labTests->sum('price');
        $labPaid = $inpatientRegister->labTests->where('status', 'paid')->sum('price');

        // Calculate grand totals
        $totalAmount = $medicineTotal + $radiologyTotal + $labTotal;
        $totalPaid = $medicinePaid + $radiologyPaid + $labPaid;

        // Calculate admission days
        $admissionDays = 0;
        if ($inpatientRegister->date_of_admission && $inpatientRegister->date_of_discharge) {
            $admissionDays = \Carbon\Carbon::parse($inpatientRegister->date_of_admission)
                ->diffInDays($inpatientRegister->date_of_discharge) + 1;
        } elseif ($inpatientRegister->date_of_admission) {
            $admissionDays = \Carbon\Carbon::parse($inpatientRegister->date_of_admission)
                ->diffInDays(now()) + 1;
        }

        return view('inpatient-register.preview', compact(
            'inpatientRegister',
            'totalAmount',
            'totalPaid',
            'medicineTotal',
            'medicinePaid',
            'radiologyTotal',
            'radiologyPaid',
            'labTotal',
            'labPaid',
            'admissionDays'
        ));
    }

    public function print(InpatientRegister $inpatientRegister)
    {
        // Calculate totals
        $medicineTotal = $inpatientRegister->medicines->sum(function ($medicine) {
            return $medicine->quantity * $medicine->price;
        });

        $medicinePaid = $inpatientRegister->medicines->sum('paid_amount');

        $labTotal = $inpatientRegister->labTests->sum('price');
        $labPaid = $inpatientRegister->labTests->sum('paid_amount');

        $radiologyTotal = $inpatientRegister->radiologyTests->sum('price');
        $radiologyPaid = $inpatientRegister->radiologyTests->sum('paid_amount');

        $totalAmount = $medicineTotal + $labTotal + $radiologyTotal;
        $totalPaid = $medicinePaid + $labPaid + $radiologyPaid;

        // Calculate admission days
        $admissionDate = \Carbon\Carbon::parse($inpatientRegister->date_of_admission);
        $dischargeDate = $inpatientRegister->date_of_discharge
            ? \Carbon\Carbon::parse($inpatientRegister->date_of_discharge)
            : \Carbon\Carbon::now();
        $admissionDays = $admissionDate->diffInDays($dischargeDate);

        // Use a simpler view path
        return view('inpatient-register.print', compact(
            'inpatientRegister',
            'medicineTotal',
            'medicinePaid',
            'labTotal',
            'labPaid',
            'radiologyTotal',
            'radiologyPaid',
            'totalAmount',
            'totalPaid',
            'admissionDays'
        ));
    }

    public function printReport(InpatientRegister $inpatient)
    {
        // Load the relationships
        $inpatient->load([
            'patient',
            'doctor'
        ]);

        // Wrap in collection for template compatibility
        $inpatients = collect([$inpatient]);

        return view('inpatient-register.print-single', compact('inpatients'));
    }
}
