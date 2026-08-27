<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ServiceController extends Controller
{
    /**
     * Display a listing of the services.
     */
    public function index()
    {
        $services = Service::ordered()->get();
        return view('website.service.index', compact('services'));
    }

    /**
     * Show the form for creating a new service.
     */
    public function create()
    {
        return view('website.service.create');
    }

    /**
     * Store a newly created service in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,svg', // Added svg and changed to file
            'order' => 'nullable|integer|min:0',
            'status' => 'nullable'
        ]);
        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'order' => $request->order ?? 0,
            'status' => $request->has('status') ? true : false
        ];

        if ($request->hasFile('image')) {
            // Create directory if it doesn't exist
            $path = public_path('uploads/services');
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0755, true, true);
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move($path, $imageName);
            $data['image'] = 'uploads/services/' . $imageName;
        }

        Service::create($data);

        return redirect()->route('website.service.index')
            ->with('success', 'Service created successfully.');
    }

    /**
     * Show the form for editing the specified service.
     */
    public function edit(Service $service)
    {
        return view('website.service.edit', compact('service'));
    }

    /**
     * Update the specified service in storage.
     */
    public function update(Request $request, Service $service)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,svg', // Added svg and changed to file
            'order' => 'nullable|integer|min:0',
            'status' => 'nullable'
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'order' => $request->order ?? 0,
            'status' => $request->has('status') ? true : false
        ];

        if ($request->hasFile('image')) {
            // Delete old image
            if ($service->image && file_exists(public_path($service->image))) {
                unlink(public_path($service->image));
            }

            // Create directory if it doesn't exist
            $path = public_path('uploads/services');
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0755, true, true);
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move($path, $imageName);
            $data['image'] = 'uploads/services/' . $imageName;
        }

        $service->update($data);

        return redirect()->route('website.service.index')
            ->with('success', 'Service updated successfully.');
    }

    /**
     * Remove the specified service from storage.
     */
    public function destroy(Service $service)
    {
        // Delete image file
        if ($service->image && file_exists(public_path($service->image))) {
            unlink(public_path($service->image));
        }

        $service->delete();

        return redirect()->route('website.service.index')
            ->with('success', 'Service deleted successfully.');
    }

    /**
     * Toggle service status.
     */
    public function toggleStatus(Service $service)
    {
        $service->status = !$service->status;
        $service->save();

        return redirect()->route('website.service.index')
            ->with('success', 'Service status updated successfully.');
    }
}
