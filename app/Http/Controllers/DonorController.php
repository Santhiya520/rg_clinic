<?php

namespace App\Http\Controllers;

use App\Models\Donor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DonorController extends Controller
{
    /**
     * Display a listing of the donors.
     */
    public function index()
    {
        $donors = Donor::ordered()->get();
        $categories = Donor::getCategories();
        return view('website.donor.index', compact('donors', 'categories'));
    }

    /**
     * Show the form for creating a new donor.
     */
    public function create()
    {
        return view('website.donor.create');
    }

    /**
     * Store a newly created donor in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'category' => 'required|string',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg',
            'order' => 'nullable|integer|min:0',
            'status' => 'nullable'
        ]);

        $data = [
            'name' => $request->name,
            'category' => $request->category,
            'description' => $request->description,
            'order' => $request->order ?? 0,
            'status' => $request->has('status') ? true : false
        ];

        if ($request->hasFile('image')) {
            // Create directory if it doesn't exist
            $path = public_path('uploads/donors');
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0755, true, true);
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move($path, $imageName);
            $data['image'] = 'uploads/donors/' . $imageName;
        }

        Donor::create($data);

        return redirect()->route('website.donor.index')
            ->with('success', 'Donor created successfully.');
    }

    /**
     * Show the form for editing the specified donor.
     */
    public function edit(Donor $donor)
    {
        return view('website.donor.edit', compact('donor'));
    }

    /**
     * Update the specified donor in storage.
     */
    public function update(Request $request, Donor $donor)
    {
        $request->validate([
            'name' => 'required|string',
            'category' => 'required|string',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg',
            'order' => 'nullable|integer|min:0',
            'status' => 'nullable'
        ]);

        $data = [
            'name' => $request->name,
            'category' => $request->category,
            'description' => $request->description,
            'order' => $request->order ?? 0,
            'status' => $request->has('status') ? true : false
        ];

        if ($request->hasFile('image')) {
            // Delete old image
            if ($donor->image && file_exists(public_path($donor->image))) {
                unlink(public_path($donor->image));
            }

            // Create directory if it doesn't exist
            $path = public_path('uploads/donors');
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0755, true, true);
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move($path, $imageName);
            $data['image'] = 'uploads/donors/' . $imageName;
        }

        $donor->update($data);

        return redirect()->route('website.donor.index')
            ->with('success', 'Donor updated successfully.');
    }

    /**
     * Remove the specified donor from storage.
     */
    public function destroy(Donor $donor)
    {
        // Delete image file
        if ($donor->image && file_exists(public_path($donor->image))) {
            unlink(public_path($donor->image));
        }

        $donor->delete();

        return redirect()->route('website.donor.index')
            ->with('success', 'Donor deleted successfully.');
    }

    /**
     * Toggle donor status.
     */
    public function toggleStatus(Donor $donor)
    {
        $donor->status = !$donor->status;
        $donor->save();

        return redirect()->route('website.donor.index')
            ->with('success', 'Donor status updated successfully.');
    }
}
