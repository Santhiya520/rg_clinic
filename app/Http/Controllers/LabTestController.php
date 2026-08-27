<?php

namespace App\Http\Controllers;

use App\Models\LabTest;
use Illuminate\Http\Request;

class LabTestController extends Controller
{
    // Display all lab tests
    public function index()
    {
        $tests = LabTest::all();
        return view('lab-tests.index', compact('tests'));
    }

    // Show create test form
    public function create()
    {
        return view('lab-tests.create');
    }

    // Store new test
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive'
        ]);
        $data = $request->all();
        $data['user_id'] = auth()->id(); // or $request->user()->id

        LabTest::create($data);

        return redirect()->route('lab-tests.success')->with('success', 'Lab test created successfully.');
    }

    // Show edit test form
    public function edit(LabTest $labTest)
    {
        return view('lab-tests.edit', compact('labTest'));
    }

    // Update test
    public function update(Request $request, LabTest $labTest)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive'
        ]);

        $labTest->update($request->all());

        return redirect()->route('lab-tests.success')->with('success', 'Lab test updated successfully.');
    }

    // Delete test
    public function destroy(LabTest $labTest)
    {
        $labTest->delete();
        return redirect()->route('lab-tests.success')->with('success', 'Lab test deleted successfully.');
    }

    // Success page
    public function success()
    {
        return view('lab-tests.success');
    }
}
