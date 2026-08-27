<?php

namespace App\Http\Controllers;

use App\Models\ManualLabTest;
use App\Models\ManualLabTestItem;
use App\Models\Patient;
use App\Models\LabTest;
use App\Models\ManualLabTestSubTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManualLabTestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tests = ManualLabTest::with(['patient', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('manual-lab-tests.index', compact('tests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $patients = Patient::orderBy('name')->get();
        $labTests = LabTest::orderBy('name')->get();

        return view('manual-lab-tests.create', compact('patients', 'labTests'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'items' => 'required|array|min:1',
            'items.*.lab_test_id' => 'required|exists:lab_tests,id',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.notes' => 'nullable|string|max:1000',
            'items.*.sub_tests' => 'nullable|array',
            'notes' => 'nullable|string|max:1000'
        ]);

        DB::transaction(function () use ($request) {
            // Create manual lab test
            $manualLabTest = ManualLabTest::create([
                'patient_id' => $request->patient_id,
                'notes' => $request->notes,
                'payment_type' => $request->payment_type,
                'user_id' => auth()->id()
            ]);

            // Add items and their sub-tests
            foreach ($request->items as $itemData) {
                // Create main test item
                $manualLabTestItem = ManualLabTestItem::create([
                    'manual_lab_test_id' => $manualLabTest->id,
                    'lab_test_id' => $itemData['lab_test_id'],
                    'price' => $itemData['price'],
                    'notes' => $itemData['notes'] ?? null
                ]);

                // Save only checked sub-tests
                if (isset($itemData['sub_tests']) && is_array($itemData['sub_tests'])) {
                    foreach ($itemData['sub_tests'] as $subTestId => $subTestData) {
                        // Only save if the checkbox is checked (exists and value is 1)
                        if (isset($subTestData['checked']) && $subTestData['checked'] == 1) {
                            ManualLabTestSubTest::create([
                                'manual_lab_test_item_id' => $manualLabTestItem->id,
                                'test_name' => $subTestData['test_name'] ?? '',
                                'unit' => $subTestData['unit'] ?? null,
                                'normal_range' => $subTestData['normal_range'] ?? null,
                                'result' => null // Will be updated later when results are entered
                            ]);
                        }
                    }
                }
            }

            // Update total amount
            $manualLabTest->updateTotalAmount();
        });

        return redirect()->route('manual-lab-tests.index')
            ->with('success', 'Manual lab test created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ManualLabTest $manualLabTest)
    {
        $manualLabTest->load(['patient', 'user', 'items.labTest']);

        return view('manual-lab-tests.show', compact('manualLabTest'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ManualLabTest $manualLabTest)
    {
        $manualLabTest->load(['items']);
        $patients = Patient::orderBy('name')->get();
        $labTests = LabTest::orderBy('name')->get();

        return view('manual-lab-tests.edit', compact('manualLabTest', 'patients', 'labTests'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ManualLabTest $manualLabTest)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'items' => 'required|array|min:1',
            'items.*.lab_test_id' => 'required|exists:lab_tests,id',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.notes' => 'nullable|string|max:1000',
            'items.*.sub_tests' => 'nullable|array',
            'notes' => 'nullable|string|max:1000'
        ]);

        DB::transaction(function () use ($request, $manualLabTest) {
            // Update manual lab test
            $manualLabTest->update([
                'patient_id' => $request->patient_id,
                'payment_type' => $request->payment_type,
                'notes' => $request->notes
            ]);

            // Delete existing items and their sub-tests (cascade will handle sub-tests if foreign key is set)
            $manualLabTest->items()->delete();

            // Add new items with their sub-tests
            foreach ($request->items as $itemData) {
                // Create main test item
                $manualLabTestItem = ManualLabTestItem::create([
                    'manual_lab_test_id' => $manualLabTest->id,
                    'lab_test_id' => $itemData['lab_test_id'],
                    'price' => $itemData['price'],
                    'notes' => $itemData['notes'] ?? null
                ]);

                // Save only checked sub-tests
                if (isset($itemData['sub_tests']) && is_array($itemData['sub_tests'])) {
                    foreach ($itemData['sub_tests'] as $subTestId => $subTestData) {
                        // Only save if the checkbox is checked
                        if (isset($subTestData['checked']) && $subTestData['checked'] == 1) {
                            ManualLabTestSubTest::create([
                                'manual_lab_test_item_id' => $manualLabTestItem->id,
                                'test_name' => $subTestData['test_name'] ?? '',
                                'unit' => $subTestData['unit'] ?? null,
                                'normal_range' => $subTestData['normal_range'] ?? null,
                                'result' => null // Will be updated later when results are entered
                            ]);
                        }
                    }
                }
            }

            // Update total amount
            $manualLabTest->updateTotalAmount();
        });

        return redirect()->route('manual-lab-tests.index')
            ->with('success', 'Manual lab test updated successfully.');
    }

    /**
     * Print individual test result
     */
    public function printItemResult(ManualLabTestItem $item)
    {
        // Load necessary relationships
        $item->load([
            'manualLabTest.patient',
            'labTest',
            'subTests',
            'technician'
        ]);

        // Check if result exists
        if ($item->status !== 'completed') {
            return redirect()->back()
                ->with('error', 'Test result is not completed yet.');
        }

        return view('manual-lab-tests.print-item-result', compact('item'));
    }

    /**
     * Print all completed test results for a manual lab test
     */
    public function printAllResults(ManualLabTest $manualLabTest)
    {
        // Load necessary relationships
        $manualLabTest->load([
            'patient',
            'user',
            'items' => function ($query) {
                $query->where('status', 'completed')
                    ->with(['labTest', 'subTests', 'technician']);
            }
        ]);

        // Get only completed items
        $completedItems = $manualLabTest->items->where('status', 'completed');

        // If no completed tests, redirect back with message
        if ($completedItems->isEmpty()) {
            return redirect()
                ->route('manual-lab-tests.show', $manualLabTest)
                ->with('error', 'No completed lab tests found to print.');
        }

        return view('manual-lab-tests.print-all-results', [
            'manualLabTest' => $manualLabTest,
            'completedItems' => $completedItems
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ManualLabTest $manualLabTest)
    {
        $manualLabTest->delete();

        return redirect()->route('manual-lab-tests.index')
            ->with('success', 'Manual lab test deleted successfully.');
    }

    /**
     * Update test result for an item
     */


    /**
     * Update payment
     */
    public function updatePayment(Request $request, ManualLabTest $manualLabTest)
    {
        $request->validate([
            'paid_amount' => 'required|numeric|min:0|max:' . $manualLabTest->total_amount,
            'payment_status' => 'required|in:pending,partial,paid'
        ]);

        $manualLabTest->update([
            'paid_amount' => $request->paid_amount,
            'payment_status' => $request->payment_status
        ]);

        return redirect()->back()
            ->with('success', 'Payment updated successfully.');
    }

    /**
     * Print bill
     */
    public function print(ManualLabTest $manualLabTest)
    {
        $manualLabTest->load(['patient', 'user', 'items.labTest']);

        return view('manual-lab-tests.print', compact('manualLabTest'));
    }

    public function editResult(ManualLabTestItem $item)
    {
        // Load necessary relationships with null checks
        $item->load([
            'manualLabTest.patient',
            'labTest.subTests',  // Load lab test template sub-tests
            'subTests'
        ]);



        // If no sub-tests exist but the lab test template has them, create them
        if ($item->subTests->isEmpty() && $item->labTest && $item->labTest->subTests->isNotEmpty()) {
            $order = 0;
            foreach ($item->labTest->subTests as $labSubTest) {
                ManualLabTestSubTest::create([
                    'manual_lab_test_item_id' => $item->id,
                    'test_name' => $labSubTest->name,
                    'unit' => $labSubTest->unit,
                    'normal_range' => $labSubTest->normal_range,
                    'result' => null,
                    'order' => $order++
                ]);
            }

            // Reload the subTests relationship
            $item->load('subTests');
        }

        return view('manual-lab-tests.edit-result', [
            'manualLabTestItem' => $item
        ]);
    }

    /**
     * Update the test results
     */
    public function updateResult(Request $request, ManualLabTestItem $item)
    {
        // Validation
        $validated = $request->validate([
            'sub_tests' => 'required|array',
            'sub_tests.*.result' => 'nullable|string|max:500',
            'result' => 'nullable|string|max:1000',
            'status' => 'required|in:pending,completed,cancelled',
            'notes' => 'nullable|string|max:500',
            'result_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'technician_id' => 'nullable|exists:users,id'
        ]);

        // Handle file upload
        $documentPath = $item->result_document;
        if ($request->hasFile('result_document')) {
            $documentPath = $request->file('result_document')->store('lab-test-documents', 'public');
        }

        // Update sub test results
        foreach ($request->sub_tests as $subTestId => $data) {
            $subTest = ManualLabTestSubTest::find($subTestId);
            if ($subTest && $subTest->manual_lab_test_item_id == $item->id) {
                $subTest->update([
                    'result' => $data['result'] ?? null
                ]);
            }
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
        $this->updateOverallTestStatus($item->manual_lab_test_id);

        return redirect()
            ->route('manual-lab-tests.show', $item->manual_lab_test_id)
            ->with('success', 'Lab test result updated successfully.');
    }

    /**
     * Update overall test status
     */
    private function updateOverallTestStatus($manualLabTestId)
    {
        $manualLabTest = ManualLabTest::with('items')->find($manualLabTestId);

        if (!$manualLabTest || $manualLabTest->items->isEmpty()) {
            return;
        }

        $allCompleted = $manualLabTest->items->every(function ($item) {
            return $item->status == 'completed';
        });

        $anyCancelled = $manualLabTest->items->contains(function ($item) {
            return $item->status == 'cancelled';
        });

        $anyPending = $manualLabTest->items->contains(function ($item) {
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
            $manualLabTest->update($updateData);
        }
    }
}
