<?php

namespace App\Http\Controllers;

use App\Models\OpLabTest;
use App\Models\OpRegister;
use App\Models\InpatientRegister;
use App\Models\IpLabTest;
use App\Models\ManualLabTest;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OpLabTestController extends Controller
{
    // Display all OP registrations with lab tests
    public function index()
    {
        // OP Lab with pending tests
        $opRegisters = OpRegister::whereHas('labTests', function ($query) {
            $query->where('status', 'paid');
        })
            ->with(['patient', 'medicalOfficer', 'labTests' => function ($query) {
                $query->where('status', 'pending')->with('labTest');
            }])
            ->latest()
            ->get();

        // IP Lab with pending tests
        $inpatientRegisters = InpatientRegister::whereHas('labTests', function ($query) {
            $query->where('status', 'pending');
        })
            ->with(['patient', 'labTests' => function ($query) {
                $query->where('status', 'pending')->with('labTest');
            }])
            ->whereNull('date_of_discharge')
            ->orderBy('date_of_admission', 'desc')
            ->get();

        return view('op-lab-tests.index', compact('opRegisters', 'inpatientRegisters'));
    }

    // Display all lab tests for a patient
    public function show(OpRegister $opRegister)
    {
        return view('op-lab-tests.op-show', compact('opRegister')); // Changed to op-show
    }

    // Show update form for lab test
    public function edit(OpLabTest $opLabTest)
    {
        // Load necessary relationships
        $opLabTest->load(['labTest.subTests', 'subTests', 'opRegister.patient']);

        // If no sub-tests exist but the lab test template has them, create them with all fields
        if ($opLabTest->subTests->isEmpty() && $opLabTest->labTest->subTests->isNotEmpty()) {
            $order = 0;
            foreach ($opLabTest->labTest->subTests as $labSubTest) {
                \App\Models\OpLabSubTest::create([
                    'op_lab_test_id' => $opLabTest->id,
                    'lab_sub_test_id' => $labSubTest->id,
                    'test_name' => $labSubTest->name,
                    'unit' => $labSubTest->unit,
                    'normal_range' => $labSubTest->normal_range,
                    'result' => null,
                    'order' => $order++
                ]);
            }

            // Reload the subTests relationship
            $opLabTest->load('subTests');
        }

        return view('op-lab-tests.op-edit', compact('opLabTest'));
    }

    // Update lab test result
    public function update(Request $request, OpLabTest $opLabTest)
    {
        // Validation
        $validated = $request->validate([
            'sub_tests' => 'required|array',
            'sub_tests.*.result' => 'nullable|string|max:500',
            'result' => 'nullable|string|max:1000',
            'status' => 'required|in:pending,completed,cancelled',
            'notes' => 'nullable|string|max:500'
        ]);

        // Update sub test results - update existing records
        foreach ($request->sub_tests as $opLabSubTestId => $data) {
            $opLabSubTest = \App\Models\OpLabSubTest::find($opLabSubTestId);

            // Verify this record belongs to the current opLabTest
            if ($opLabSubTest && $opLabSubTest->op_lab_test_id == $opLabTest->id) {
                $opLabSubTest->update([
                    'result' => $data['result'] ?? null
                ]);
            }
        }

        // Prepare data for OpLabTest update
        $updateData = [
            'result' => $validated['result'] ?? null,
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? $opLabTest->notes,
        ];

        // Set completed date if status changed to completed
        if ($validated['status'] === 'completed' && $opLabTest->status !== 'completed') {
            $updateData['completed_at'] = now();
        }

        // Update OpLabTest
        $opLabTest->update($updateData);

        return redirect()
            ->route('lab.op.show', $opLabTest->op_register_id)
            ->with('success', 'Lab test result updated successfully.');
    }

    public function bill(OpLabTest $opLabTest)
    {
        $opLabTest->load(['subTests', 'labTest', 'opRegister.patient']);
        return view('op-lab-tests.op-bill', compact('opLabTest'));
    }

    // Download lab test document
    public function download(OpLabTest $opLabTest)
    {
        // Check if document exists
        if (!$opLabTest->result_document) {
            return back()->with('error', 'No document found for this test.');
        }

        $filePath = public_path('uploads/lab-documents/' . $opLabTest->result_document);

        // Check if file exists
        if (!file_exists($filePath)) {
            return back()->with('error', 'Document file not found.');
        }

        // Get the original filename for download
        $originalName = $opLabTest->labTest->name . '_result.' .
            pathinfo($opLabTest->result_document, PATHINFO_EXTENSION);

        return response()->download($filePath, $originalName);
    }

    public function printAllReports(OpRegister $opRegister)
    {
        // Get only completed tests with their relationships
        $completedTests = $opRegister->labTests()
            ->where('status', 'completed')
            ->with(['labTest', 'subTests'])
            ->get();

        // If no completed tests, redirect back with message
        if ($completedTests->isEmpty()) {
            return redirect()
                ->route('lab.op.show', $opRegister)
                ->with('error', 'No completed lab tests found to print.');
        }

        return view('op-lab-tests.op-print-all', [
            'opRegister' => $opRegister,
            'completedTests' => $completedTests,
        ]);
    }


    public function printReport(OpRegister $opRegister)
    {
        // Load the register with all necessary relationships
        $opRegister->load([
            'patient',
            'medicalOfficer',
            'labTests' => function ($query) {
                $query->with('labTest');
            }
        ]);

        // Wrap in collection for template compatibility
        $opRegisters = collect([$opRegister]);

        return view('op-lab-tests.op-print', compact('opRegisters')); // Changed to op-print
    }

    /**
     * Lab Reports - For both OP and IP lab tests
     */
    public function reports(Request $request)
    {
        $patientType = $request->input('patient_type', 'all'); // Default to 'all'
        $patientId = $request->input('patient_id');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $search = $request->input('search');
        $searchBtn = $request->input('search_btn');

        // Get all patients for dropdown
        $patients = Patient::orderBy('name')->get();

        // Initialize results
        $opResults = collect();
        $ipResults = collect();
        $manualResults = collect();
        $searchPerformed = false;
        $totalRecords = 0;

        // Only perform search if search button was clicked OR if there are search parameters
        if ($searchBtn || $request->hasAny(['patient_id', 'from_date', 'to_date', 'search'])) {
            $searchPerformed = true;

            // OP Patients Query
            if ($patientType === 'all' || $patientType === 'op') {
                $opQuery = OpRegister::with(['patient', 'medicalOfficer', 'labTests.labTest'])
                    ->has('labTests');

                // Apply date filter
                if ($fromDate && $toDate) {
                    $opQuery->whereBetween('created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
                } elseif ($fromDate) {
                    $opQuery->whereDate('created_at', '>=', $fromDate);
                } elseif ($toDate) {
                    $opQuery->whereDate('created_at', '<=', $toDate);
                }

                // Apply patient filter
                if ($patientId) {
                    $opQuery->where('patient_id', $patientId);
                }

                // Apply search
                if ($search) {
                    $opQuery->whereHas('patient', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%")
                            ->orWhere('patient_id', 'like', "%$search%")
                            ->orWhere('mobile', 'like', "%$search%");
                    });
                }

                $opResults = $opQuery->orderBy('created_at', 'desc')->paginate(10, ['*'], 'op_page');
            }

            // IP Patients Query
            if ($patientType === 'all' || $patientType === 'ip') {
                $ipQuery = InpatientRegister::with(['patient', 'doctor', 'labTests.labTest'])
                    ->has('labTests');

                // Apply date filter
                if ($fromDate && $toDate) {
                    $ipQuery->whereBetween('date_of_admission', [$fromDate, $toDate]);
                } elseif ($fromDate) {
                    $ipQuery->whereDate('date_of_admission', '>=', $fromDate);
                } elseif ($toDate) {
                    $ipQuery->whereDate('date_of_admission', '<=', $toDate);
                }

                // Apply patient filter
                if ($patientId) {
                    $ipQuery->where('patient_id', $patientId);
                }

                // Apply search
                if ($search) {
                    $ipQuery->whereHas('patient', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%")
                            ->orWhere('patient_id', 'like', "%$search%")
                            ->orWhere('mobile', 'like', "%$search%");
                    });
                }

                $ipResults = $ipQuery->orderBy('date_of_admission', 'desc')->paginate(10, ['*'], 'ip_page');
            }

            // Manual Tests Query
            if ($patientType === 'all' || $patientType === 'manual') {
                $manualQuery = ManualLabTest::with(['patient', 'user', 'items.labTest'])
                    ->whereHas('items');

                // Apply date filter
                if ($fromDate && $toDate) {
                    $manualQuery->whereBetween('created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
                } elseif ($fromDate) {
                    $manualQuery->whereDate('created_at', '>=', $fromDate);
                } elseif ($toDate) {
                    $manualQuery->whereDate('created_at', '<=', $toDate);
                }

                // Apply patient filter
                if ($patientId) {
                    $manualQuery->where('patient_id', $patientId);
                }

                // Apply search
                if ($search) {
                    $manualQuery->whereHas('patient', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%")
                            ->orWhere('patient_id', 'like', "%$search%")
                            ->orWhere('mobile', 'like', "%$search%");
                    });
                }

                $manualResults = $manualQuery->orderBy('created_at', 'desc')->paginate(10, ['*'], 'manual_page');
            }

            // Calculate total records
            $totalRecords = ($opResults->total() ?? 0) + ($ipResults->total() ?? 0) + ($manualResults->total() ?? 0);
        }

        return view('op-lab-tests.reports', compact(
            'opResults',
            'ipResults',
            'manualResults',
            'patients',
            'patientType',
            'patientId',
            'fromDate',
            'toDate',
            'search',
            'searchPerformed',
            'totalRecords'
        ));
    }

    /**
     * Edit IP Lab Test
     */
    public function editIp(IpLabTest $ipLabTest)
    {
        // Load necessary relationships
        $ipLabTest->load(['labTest.subTests', 'subTests', 'inpatientRegister.patient']);

        // If no sub-tests exist but the lab test template has them, create them with all fields
        if ($ipLabTest->subTests->isEmpty() && $ipLabTest->labTest->subTests->isNotEmpty()) {
            $order = 0;
            foreach ($ipLabTest->labTest->subTests as $labSubTest) {
                \App\Models\IpLabSubTest::create([
                    'ip_lab_test_id' => $ipLabTest->id,
                    'lab_sub_test_id' => $labSubTest->id,
                    'test_name' => $labSubTest->name,
                    'unit' => $labSubTest->unit,
                    'normal_range' => $labSubTest->normal_range,
                    'result' => null,
                    'order' => $order++
                ]);
            }

            // Reload the subTests relationship
            $ipLabTest->load('subTests');
        }

        return view('op-lab-tests.ip-edit', compact('ipLabTest'));
    }

    /**
     * Update IP Lab Test
     */
    public function updateIp(Request $request, IpLabTest $ipLabTest)
    {
        // Validation
        $validated = $request->validate([
            'sub_tests' => 'required|array',
            'sub_tests.*.result' => 'nullable|string|max:500',
            'result' => 'nullable|string|max:1000',
            'status' => 'required|in:pending,completed,cancelled',
            'notes' => 'nullable|string|max:500'
        ]);

        // Update sub test results
        foreach ($request->sub_tests as $id => $data) {
            $ipLabSubTest = \App\Models\IpLabSubTest::find($id);
            if ($ipLabSubTest && $ipLabSubTest->ip_lab_test_id == $ipLabTest->id) {
                $ipLabSubTest->update([
                    'result' => $data['result'] ?? null
                ]);
            }
        }

        // Prepare data for IpLabTest update
        $updateData = [
            'result' => $validated['result'] ?? null,
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? $ipLabTest->notes,
        ];

        // Set completed date if status changed to completed
        if ($validated['status'] === 'completed' && $ipLabTest->status !== 'completed') {
            $updateData['completed_at'] = now();
        }

        // Update IpLabTest
        $ipLabTest->update($updateData);

        return redirect()
            ->route('lab.ip.show', $ipLabTest->inpatient_register_id)
            ->with('success', 'IP Lab test result updated successfully.');
    }

    /**
     * Print IP Lab Test Report
     */
    public function billIp(IpLabTest $ipLabTest)
    {
        $ipLabTest->load(['subTests', 'labTest', 'inpatientRegister.patient']);
        return view('op-lab-tests.ip-bill', compact('ipLabTest'));
    }

    /**
     * Show IP Lab Tests for a patient
     */
    public function showIp(InpatientRegister $inpatientRegister)
    {
        return view('op-lab-tests.ip-show', compact('inpatientRegister'));
    }

    /**
     * Print IP Lab Report
     */
    public function printIpReport($inpatientRegisterId)
    {
        $inpatientRegister = InpatientRegister::findOrFail($inpatientRegisterId);
        $inpatientRegister->load([
            'patient',
            'labTests' => function ($query) {
                $query->with('labTest');
            }
        ]);

        $inpatientRegisters = collect([$inpatientRegister]);
        return view('op-lab-tests.ip-print', compact('inpatientRegisters'));
    }
}
