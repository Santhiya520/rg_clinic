<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\OpRegister;
use App\Models\InpatientRegister;
use App\Models\OpRadiology;
use App\Models\IpRadiology;
use App\Models\ManualRadiologyTest;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RadiologyController extends Controller
{
    // Dashboard showing both OP and IP radiology
    public function index()
    {
        // OP Radiology with pending tests
        $opRegisters = OpRegister::whereHas('radiologyTests', function ($query) {
            $query->where('status', 'pending');
        })
            ->with(['patient', 'medicalOfficer', 'radiologyTests' => function ($query) {
                $query->where('status', 'pending')->with('radiologyTest');
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        // IP Radiology with pending tests
        $inpatientRegisters = InpatientRegister::whereHas('radiologyTests', function ($query) {
            $query->where('status', 'pending');
        })
            ->with(['patient', 'radiologyTests' => function ($query) {
                $query->where('status', 'pending')->with('radiologyTest');
            }])
            ->whereNull('date_of_discharge')
            ->orderBy('date_of_admission', 'desc')
            ->get();

        return view('radiology.index', compact('opRegisters', 'inpatientRegisters'));
    }

    // OP Radiology Show
    public function showOp(OpRegister $opRegister)
    {
        $opRegister->load(['patient', 'medicalOfficer', 'radiologyTests.radiologyTest']);
        return view('radiology.op-show', compact('opRegister'));
    }

    // IP Radiology Show
    public function showIp(InpatientRegister $inpatientRegister)
    {
        $inpatientRegister->load(['patient', 'radiologyTests.radiologyTest']);
        return view('radiology.ip-show', compact('inpatientRegister'));
    }

    // OP Radiology Edit
    public function editOp(OpRadiology $opRadiology)
    {
        $opRadiology->load(['opRegister.patient', 'radiologyTest']);
        return view('radiology.op-edit', compact('opRadiology'));
    }

    // IP Radiology Edit
    public function editIp(IpRadiology $ipRadiology)
    {
        $ipRadiology->load(['inpatientRegister.patient', 'radiologyTest']);
        return view('radiology.ip-edit', compact('ipRadiology'));
    }

    // Update OP Test
    public function updateOp(Request $request, OpRadiology $opRadiology)
    {
        $validated = $request->validate([
            'result' => 'nullable|string|max:1000',
            'result_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'status' => 'required|in:pending,completed,cancelled'
        ]);

        $data = [
            'result' => $validated['result'] ?? $opRadiology->result,
            'status' => $validated['status'],
        ];

        // Handle file upload
        if ($request->hasFile('result_document')) {
            try {
                $file = $request->file('result_document');
                $directory = public_path('uploads/radiology-documents');
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }

                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                // Delete old file
                if ($opRadiology->result_document) {
                    $oldFile = $directory . '/' . basename($opRadiology->result_document);
                    if (file_exists($oldFile)) {
                        unlink($oldFile);
                    }
                }

                $file->move($directory, $fileName);
                $data['result_document'] = $fileName;
            } catch (\Exception $e) {
                return back()->with('error', 'File upload failed: ' . $e->getMessage());
            }
        }

        // Set completed date
        if ($validated['status'] === 'completed' && $opRadiology->status !== 'completed') {
            $data['completed_at'] = now();
        }

        $opRadiology->update($data);

        return redirect()
            ->route('radiology.op.show', $opRadiology->op_register_id)
            ->with('success', 'OP Radiology test result updated successfully.');
    }

    // Update IP Test
    public function updateIp(Request $request, IpRadiology $ipRadiology)
    {
        $validated = $request->validate([
            'result' => 'nullable|string|max:1000',
            'result_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'status' => 'required|in:pending,completed,cancelled'
        ]);

        $data = [
            'result' => $validated['result'] ?? $ipRadiology->result,
            'status' => $validated['status'],
        ];

        // Handle file upload for IP
        if ($request->hasFile('result_document')) {
            try {
                $file = $request->file('result_document');
                $directory = public_path('uploads/radiology-documents');
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }

                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                // Delete old file if exists
                if ($ipRadiology->result_document) {
                    $oldFile = $directory . '/' . basename($ipRadiology->result_document);
                    if (file_exists($oldFile)) {
                        unlink($oldFile);
                    }
                }

                $file->move($directory, $fileName);
                $data['result_document'] = $fileName;
            } catch (\Exception $e) {
                return back()->with('error', 'File upload failed: ' . $e->getMessage());
            }
        }

        // Set completed date for IP
        if ($validated['status'] === 'completed' && $ipRadiology->status !== 'completed') {
            $data['completed_at'] = now();
        }

        // Update user who completed the test
        $data['user_id'] = auth()->id();

        $ipRadiology->update($data);

        return redirect()
            ->route('radiology.ip.show', $ipRadiology->inpatient_register_id)
            ->with('success', 'IP Radiology test result updated successfully.');
    }

    // Print OP Report
    public function printOpReport($opRegisterId)
    {
        $opRegister = OpRegister::findOrFail($opRegisterId);
        $opRegister->load([
            'patient',
            'medicalOfficer',
            'radiologyTests' => function ($query) {
                $query->with('radiologyTest');
            }
        ]);

        $opRegisters = collect([$opRegister]);
        return view('radiology.op-print', compact('opRegisters'));
    }

    // Print IP Report
    public function printIpReport($inpatientRegisterId)
    {
        $inpatientRegister = InpatientRegister::findOrFail($inpatientRegisterId);
        $inpatientRegister->load([
            'patient',
            'radiologyTests' => function ($query) {
                $query->with('radiologyTest');
            }
        ]);

        $inpatientRegisters = collect([$inpatientRegister]);
        return view('radiology.ip-print', compact('inpatientRegisters'));
    }
    // Radiology Reports
    // Controller method
public function reports(Request $request)
{
    $patientId = $request->input('patient_id');
    $fromDate = $request->input('from_date');
    $toDate = $request->input('to_date');
    $search = $request->input('search');
    $searchBtn = $request->input('search_btn');

    // Get patients for dropdown
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

        // OP Radiology
        $opQuery = OpRegister::with(['patient', 'medicalOfficer', 'radiologyTests.radiologyTest'])
            ->has('radiologyTests');

        // IP Radiology
        $ipQuery = InpatientRegister::with(['patient', 'doctor', 'radiologyTests.radiologyTest'])
            ->has('radiologyTests');

        // Manual Radiology Tests
        $manualQuery = ManualRadiologyTest::with(['patient', 'user', 'items.radiologyTest'])
            ->whereHas('items');

        // Apply date filter for all queries
        if ($fromDate && $toDate) {
            $opQuery->whereBetween('created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
            $ipQuery->whereBetween('date_of_admission', [$fromDate, $toDate]);
            $manualQuery->whereBetween('created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
        } elseif ($fromDate) {
            $opQuery->whereDate('created_at', '>=', $fromDate);
            $ipQuery->whereDate('date_of_admission', '>=', $fromDate);
            $manualQuery->whereDate('created_at', '>=', $fromDate);
        } elseif ($toDate) {
            $opQuery->whereDate('created_at', '<=', $toDate);
            $ipQuery->whereDate('date_of_admission', '<=', $toDate);
            $manualQuery->whereDate('created_at', '<=', $toDate);
        }

        // Apply patient filter if provided
        if ($patientId) {
            $opQuery->where('patient_id', $patientId);
            $ipQuery->where('patient_id', $patientId);
            $manualQuery->where('patient_id', $patientId);
        }

        // Apply search if provided
        if ($search) {
            $opQuery->whereHas('patient', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('patient_id', 'like', "%$search%")
                    ->orWhere('mobile', 'like', "%$search%");
            });

            $ipQuery->whereHas('patient', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('patient_id', 'like', "%$search%")
                    ->orWhere('mobile', 'like', "%$search%");
            });

            $manualQuery->whereHas('patient', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('patient_id', 'like', "%$search%")
                    ->orWhere('mobile', 'like', "%$search%");
            });
        }

        // Get results with pagination
        $opResults = $opQuery->orderBy('created_at', 'desc')->paginate(10, ['*'], 'op_page');
        $ipResults = $ipQuery->orderBy('date_of_admission', 'desc')->paginate(10, ['*'], 'ip_page');
        $manualResults = $manualQuery->orderBy('created_at', 'desc')->paginate(10, ['*'], 'manual_page');

        // Calculate total records
        $totalRecords = ($opResults->total() ?? 0) + ($ipResults->total() ?? 0) + ($manualResults->total() ?? 0);
    }

    return view('radiology.reports', compact(
        'opResults',
        'ipResults',
        'manualResults',
        'patients',
        'patientId',
        'fromDate',
        'toDate',
        'search',
        'searchPerformed',
        'totalRecords'
    ));
}
}
