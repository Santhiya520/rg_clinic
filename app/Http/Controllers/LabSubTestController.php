<?php

namespace App\Http\Controllers;

use App\Models\LabTest;
use App\Models\LabSubTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LabSubTestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $labTests = LabTest::with('subTests')->get();

        // Debug: Log all data
        \Log::info('=== Debug Lab Tests and Sub Tests ===');

        foreach ($labTests as $labTest) {
            \Log::info("Lab Test: {$labTest->id} - {$labTest->name}");

            // Method 1: Check via relationship
            $relationshipCount = $labTest->subTests->count();
            \Log::info("Via relationship: {$relationshipCount} sub tests");

            // Method 2: Check via raw query
            $rawCount = \DB::table('lab_sub_tests')
                ->where('lab_test_id', $labTest->id)
                ->count();
            \Log::info("Raw DB query: {$rawCount} sub tests");

            // Method 3: Check if subTests collection is empty
            if ($labTest->subTests->isEmpty()) {
                \Log::info("SubTests collection is empty");
            } else {
                \Log::info("SubTests found via relationship:");
                foreach ($labTest->subTests as $subTest) {
                    \Log::info("  - {$subTest->name} (ID: {$subTest->id})");
                }
            }

            \Log::info('---');
        }

        // Also check total counts
        $totalLabTests = LabTest::count();
        $totalSubTests = LabSubTest::count();
        \Log::info("Total Lab Tests: {$totalLabTests}");
        \Log::info("Total Sub Tests: {$totalSubTests}");

        return view('lab-sub-tests.index', compact('labTests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $labTests = LabTest::all();
        return view('lab-sub-tests.create', compact('labTests'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'lab_test_id' => 'required|exists:lab_tests,id',
            'sub_tests' => 'required|array|min:1',
            'sub_tests.*.name' => 'required|string|max:255',
            'sub_tests.*.unit' => 'nullable|string|max:50',
            'sub_tests.*.normal_range' => 'nullable|string|max:100',
        ]);

        $labTest = LabTest::findOrFail($request->lab_test_id);

        // Check if sub-tests already exist
        if ($labTest->subTests()->count() > 0) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Sub-tests already exist for this lab test. Please edit instead.');
        }

        // Add new sub-tests
        foreach ($request->sub_tests as $index => $subTestData) {
            LabSubTest::create([
                'lab_test_id' => $request->lab_test_id,
                'name' => $subTestData['name'],
                'unit' => $subTestData['unit'] ?? null,
                'normal_range' => $subTestData['normal_range'] ?? null,
                'order' => $index
            ]);
        }

        return redirect()->route('lab-sub-tests.index')
            ->with('success', 'Sub-tests added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(LabTest $labTest)
    {
        $labTest->load('subTests');
        return view('lab-sub-tests.show', compact('labTest'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LabTest $labTest)
    {
        $labTest->load('subTests');
        return view('lab-sub-tests.edit', compact('labTest'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LabTest $labTest)
    {
        $request->validate([
            'sub_tests' => 'required|array|min:1',
            'sub_tests.*.name' => 'required|string|max:255',
            'sub_tests.*.unit' => 'nullable|string|max:50',
            'sub_tests.*.normal_range' => 'nullable|string|max:100',
        ]);

        // Use transaction for data consistency
        DB::transaction(function () use ($labTest, $request) {
            // Delete existing sub-tests
            $labTest->subTests()->delete();

            // Add updated sub-tests
            foreach ($request->sub_tests as $index => $subTestData) {
                LabSubTest::create([
                    'lab_test_id' => $labTest->id,
                    'name' => $subTestData['name'],
                    'unit' => $subTestData['unit'] ?? null,
                    'normal_range' => $subTestData['normal_range'] ?? null,
                    'order' => $index
                ]);
            }
        });

        return redirect()->route('lab-sub-tests.index')
            ->with('success', 'Sub-tests updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LabTest $labTest)
    {
        if ($labTest->subTests()->count() === 0) {
            return redirect()->route('lab-sub-tests.index')
                ->with('error', 'No sub-tests found to delete.');
        }

        $labTest->subTests()->delete();

        return redirect()->route('lab-sub-tests.index')
            ->with('success', 'Sub-tests deleted successfully.');
    }
}
