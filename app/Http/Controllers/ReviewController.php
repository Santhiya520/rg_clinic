<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the reviews.
     */
    public function index()
    {
        $reviews = Review::ordered()->get();
        return view('website.review.index', compact('reviews'));
    }

    /**
     * Show the form for creating a new review.
     */
    public function create()
    {
        return view('website.review.create');
    }

    /**
     * Store a newly created review in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'review' => 'required|string',
            'star_count' => 'required|integer|min:1|max:5',
            'order' => 'nullable|integer|min:0',
            'status' => 'nullable'
        ]);

        $data = [
            'name' => $request->name,
            'review' => $request->review,
            'star_count' => $request->star_count,
            'order' => $request->order ?? 0,
            'status' => $request->has('status') ? true : false
        ];

        Review::create($data);

        return redirect()->route('website.review.index')
            ->with('success', 'Review created successfully.');
    }

    /**
     * Show the form for editing the specified review.
     */
    public function edit(Review $review)
    {
        return view('website.review.edit', compact('review'));
    }

    /**
     * Update the specified review in storage.
     */
    public function update(Request $request, Review $review)
    {
        $request->validate([
            'name' => 'required|string',
            'review' => 'required|string',
            'star_count' => 'required|integer|min:1|max:5',
            'order' => 'nullable|integer|min:0',
            'status' => 'nullable'
        ]);

        $data = [
            'name' => $request->name,
            'review' => $request->review,
            'star_count' => $request->star_count,
            'order' => $request->order ?? 0,
            'status' => $request->has('status') ? true : false
        ];

        $review->update($data);

        return redirect()->route('website.review.index')
            ->with('success', 'Review updated successfully.');
    }

    /**
     * Remove the specified review from storage.
     */
    public function destroy(Review $review)
    {
        $review->delete();

        return redirect()->route('website.review.index')
            ->with('success', 'Review deleted successfully.');
    }

    /**
     * Toggle review status.
     */
    public function toggleStatus(Review $review)
    {
        $review->status = !$review->status;
        $review->save();

        return redirect()->route('website.review.index')
            ->with('success', 'Review status updated successfully.');
    }
}
