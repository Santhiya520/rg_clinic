<?php

namespace App\Http\Controllers;

use App\Models\ManualRadiologyTest;
use App\Models\ManualRadiologyTestItem;
use App\Models\Patient;
use App\Models\RadiologyTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ManualRadiologyTestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tests = ManualRadiologyTest::with(['patient', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('manual-radiology-tests.index', compact('tests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $patients = Patient::orderBy('name')->get();
        $radiologyTests = RadiologyTest::orderBy('name')->get();

        return view('manual-radiology-tests.create', compact('patients', 'radiologyTests'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'items' => 'required|array|min:1',
            'items.*.radiology_test_id' => 'required|exists:radiology_tests,id',
            'items.*.price' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Create manual radiology test
        $manualRadiologyTest = ManualRadiologyTest::create([
            'reference_no' => ManualRadiologyTest::generateReferenceNo(),
            'patient_id' => $request->patient_id,
            'notes' => $request->notes,
            'payment_type' => $request->payment_type,
            'user_id' => auth()->id()
        ]);

        // Add items
        foreach ($request->items as $item) {
            ManualRadiologyTestItem::create([
                'manual_radiology_test_id' => $manualRadiologyTest->id,
                'radiology_test_id' => $item['radiology_test_id'],
                'price' => $item['price']
            ]);
        }

        // Update total amount
        $manualRadiologyTest->updateTotalAmount();

        return redirect()->route('manual-radiology-tests.index')
            ->with('success', 'Manual radiology test created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ManualRadiologyTest $manualRadiologyTest)
    {
        $manualRadiologyTest->load(['patient', 'user', 'items.radiologyTest']);

        return view('manual-radiology-tests.show', compact('manualRadiologyTest'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ManualRadiologyTest $manualRadiologyTest)
    {
        $manualRadiologyTest->load(['items']);
        $patients = Patient::orderBy('name')->get();
        $radiologyTests = RadiologyTest::orderBy('name')->get();

        return view('manual-radiology-tests.edit', compact('manualRadiologyTest', 'patients', 'radiologyTests'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ManualRadiologyTest $manualRadiologyTest)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'items' => 'required|array|min:1',
            'items.*.radiology_test_id' => 'required|exists:radiology_tests,id',
            'items.*.price' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000'
        ]);

        // Update manual radiology test
        $manualRadiologyTest->update([
            'patient_id' => $request->patient_id,
            'payment_type' => $request->payment_type,
            'notes' => $request->notes
        ]);

        // Delete existing items
        $manualRadiologyTest->items()->delete();

        // Add new items
        foreach ($request->items as $item) {
            ManualRadiologyTestItem::create([
                'manual_radiology_test_id' => $manualRadiologyTest->id,
                'radiology_test_id' => $item['radiology_test_id'],
                'price' => $item['price']
            ]);
        }

        // Update total amount
        $manualRadiologyTest->updateTotalAmount();

        return redirect()->route('manual-radiology-tests.index')
            ->with('success', 'Manual radiology test updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ManualRadiologyTest $manualRadiologyTest)
    {
        $manualRadiologyTest->delete();

        return redirect()->route('manual-radiology-tests.index')
            ->with('success', 'Manual radiology test deleted successfully.');
    }

    /**
     * Show form for editing result
     */
    public function editResult(ManualRadiologyTestItem $item)
    {
        $item->load([
            'manualRadiologyTest.patient',
            'radiologyTest',
            'technician'
        ]);

        return view('manual-radiology-tests.edit-result', [
            'manualRadiologyTestItem' => $item
        ]);
    }

    /**
     * Update the test results
     */
    public function updateResult(Request $request, ManualRadiologyTestItem $item)
    {
        // Validation
        $validated = $request->validate([
            'result' => 'nullable|string|max:1000',
            'status' => 'required|in:pending,completed,cancelled',
            'notes' => 'nullable|string|max:500',
            'result_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'technician_id' => 'nullable|exists:users,id'
        ]);

        // Handle file upload
        $documentPath = $item->result_document;
        if ($request->hasFile('result_document')) {
            $documentPath = $request->file('result_document')->store('radiology-documents', 'public');
        }

        // Prepare update data
        $updateData = [
            'result' => $validated['result'] ?? null,
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? $item->notes,
            'result_document' => $documentPath,
            'technician_id' => $validated['technician_id'] ?? $item->technician_id
        ];

        // Set completed_at if status changed to completed
        if ($validated['status'] === 'completed' && $item->status !== 'completed') {
            $updateData['completed_at'] = now();
        }

        // Update the item
        $item->update($updateData);

        // Update overall test status
        $this->updateOverallTestStatus($item->manual_radiology_test_id);

        return redirect()
            ->route('manual-radiology-tests.show', $item->manual_radiology_test_id)
            ->with('success', 'Radiology test result updated successfully.');
    }

    /**
     * Update overall test status
     */
    private function updateOverallTestStatus($manualRadiologyTestId)
    {
        $manualRadiologyTest = ManualRadiologyTest::with('items')->find($manualRadiologyTestId);

        if (!$manualRadiologyTest || $manualRadiologyTest->items->isEmpty()) {
            return;
        }

        $allCompleted = $manualRadiologyTest->items->every(function ($item) {
            return $item->status == 'completed';
        });

        $anyCancelled = $manualRadiologyTest->items->contains(function ($item) {
            return $item->status == 'cancelled';
        });

        $anyPending = $manualRadiologyTest->items->contains(function ($item) {
            return $item->status == 'pending';
        });

        $updateData = [];

        if ($allCompleted) {
            $updateData['test_status'] = 'completed';
            $updateData['completed_at'] = now();
        } elseif ($anyCancelled) {
            $updateData['test_status'] = 'cancelled';
        } elseif ($anyPending) {
            $updateData['test_status'] = 'pending';
        }

        if (!empty($updateData)) {
            $manualRadiologyTest->update($updateData);
        }
    }

    /**
     * Update payment
     */
    public function updatePayment(Request $request, ManualRadiologyTest $manualRadiologyTest)
    {
        $request->validate([
            'paid_amount' => 'required|numeric|min:0|max:' . $manualRadiologyTest->total_amount,
            'payment_status' => 'required|in:pending,partial,paid'
        ]);

        $manualRadiologyTest->update([
            'paid_amount' => $request->paid_amount,
            'payment_status' => $request->payment_status
        ]);

        return redirect()->back()
            ->with('success', 'Payment updated successfully.');
    }

    /**
     * Print bill
     */
    public function print(ManualRadiologyTest $manualRadiologyTest)
    {
        $manualRadiologyTest->load(['patient', 'user', 'items.radiologyTest']);

        return view('manual-radiology-tests.print', compact('manualRadiologyTest'));
    }
}
