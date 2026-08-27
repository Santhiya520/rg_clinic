<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    /**
     * Display a listing of the galleries.
     */
    public function index()
    {
        $galleries = Gallery::ordered()->get();
        return view('website.gallery.index', compact('galleries'));
    }

    /**
     * Show the form for creating a new gallery.
     */
    public function create()
    {
        return view('website.gallery.create');
    }

    /**
     * Store a newly created gallery in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'order' => 'nullable|integer'
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/galleries'), $imageName);

            Gallery::create([
                'image' => 'uploads/galleries/' . $imageName,
                'order' => $request->order ?? 0
            ]);
        }

        return redirect()->route('website.gallery.index')
            ->with('success', 'Gallery image created successfully.');
    }

    /**
     * Show the form for editing the specified gallery.
     */
    public function edit(Gallery $gallery)
    {
        return view('website.gallery.edit', compact('gallery'));
    }

    /**
     * Update the specified gallery in storage.
     */
    public function update(Request $request, Gallery $gallery)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'order' => 'nullable|integer'
        ]);

        $data = ['order' => $request->order ?? 0];

        if ($request->hasFile('image')) {
            // Delete old image
            if ($gallery->image && file_exists(public_path($gallery->image))) {
                unlink(public_path($gallery->image));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/galleries'), $imageName);
            $data['image'] = 'uploads/galleries/' . $imageName;
        }

        $gallery->update($data);

        return redirect()->route('website.gallery.index')
            ->with('success', 'Gallery image updated successfully.');
    }

    /**
     * Remove the specified gallery from storage.
     */
    public function destroy(Gallery $gallery)
    {
        // Delete image file
        if ($gallery->image && file_exists(public_path($gallery->image))) {
            unlink(public_path($gallery->image));
        }

        $gallery->delete();

        return redirect()->route('website.gallery.index')
            ->with('success', 'Gallery image deleted successfully.');
    }
}
