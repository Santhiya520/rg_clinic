<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Helpers\StringHelper;

class SupplierController extends Controller
{
    // Display all suppliers
    public function index()
    {
        $suppliers = Supplier::all();

        // Decode quotes for all suppliers
        $suppliers = $suppliers->map(function($supplier) {
            return StringHelper::decodeQuotesInItem($supplier, ['name', 'contact_person', 'address', 'notes']);
        });

        return view('suppliers.index', compact('suppliers'));
    }

    // Show create supplier form
    public function create()
    {
        return view('suppliers.create');
    }

    // Store new supplier
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'tax_number' => 'nullable|string|max:50',
            'status' => 'required|in:active,inactive,blacklisted',
            'notes' => 'nullable|string'
        ]);

        $data = $request->all();

        // Encode quotes in text fields before saving to database
        $data = StringHelper::encodeQuotesInArray($data, [
            'name',
            'contact_person',
            'address',
            'notes'
        ]);

        $data['user_id'] = auth()->id();

        Supplier::create($data);

        return redirect()->route('suppliers.success')->with('success', 'Supplier created successfully.');
    }

    // Show edit supplier form
    public function edit(Supplier $supplier)
    {
        // Decode quotes for display in the edit form
        $supplier = StringHelper::decodeQuotesInItem($supplier, [
            'name',
            'contact_person',
            'address',
            'notes'
        ]);

        return view('suppliers.edit', compact('supplier'));
    }

    // Update supplier
    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'tax_number' => 'nullable|string|max:50',
            'status' => 'required|in:active,inactive,blacklisted',
            'notes' => 'nullable|string'
        ]);

        $data = $request->all();

        // Encode quotes in text fields before updating
        $data = StringHelper::encodeQuotesInArray($data, [
            'name',
            'contact_person',
            'address',
            'notes'
        ]);

        $supplier->update($data);

        return redirect()->route('suppliers.success')->with('success', 'Supplier updated successfully.');
    }

    // Delete supplier
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('suppliers.success')->with('success', 'Supplier deleted successfully.');
    }

    // Success page
    public function success()
    {
        return view('suppliers.success');
    }
}
