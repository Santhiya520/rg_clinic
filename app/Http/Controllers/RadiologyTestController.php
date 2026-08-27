<?php

namespace App\Http\Controllers;

use App\Models\RadiologyTest;
use Illuminate\Http\Request;

class RadiologyTestController extends Controller
{
    // Display all radiology tests
    public function index()
    {
        $tests = RadiologyTest::all();
        return view('radiology-tests.index', compact('tests'));
    }

    // Show create test form
    public function create()
    {
        return view('radiology-tests.create');
    }

    // Store new test
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:radiology_tests,name',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive'
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id(); // or $request->user()->id

        RadiologyTest::create($data);

        return redirect()->route('radiology-tests.success')->with('success', 'Radiology test created successfully.');
    }

    // Show edit test form
    public function edit(RadiologyTest $radiologyTest)
    {
        return view('radiology-tests.edit', compact('radiologyTest'));
    }

    // Update test
    public function update(Request $request, RadiologyTest $radiologyTest)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:radiology_tests,name,' . $radiologyTest->id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive'
        ]);

        $radiologyTest->update($request->all());

        return redirect()->route('radiology-tests.success')->with('success', 'Radiology test updated successfully.');
    }

    // Delete test
    public function destroy(RadiologyTest $radiologyTest)
    {
        $radiologyTest->delete();
        return redirect()->route('radiology-tests.success')->with('success', 'Radiology test deleted successfully.');
    }

    // Success page
    public function success()
    {
        return view('radiology-tests.success');
    }
}
