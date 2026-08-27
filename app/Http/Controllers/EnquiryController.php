<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    /**
     * Display a listing of enquiries.
     */
    public function index()
    {
        $enquiries = Enquiry::latest()->get();
        $unreadCount = Enquiry::unread()->count();

        return view('website.enquiry.index', compact('enquiries', 'unreadCount'));
    }

    /**
     * Store a newly created enquiry from frontend.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string'
        ]);

        Enquiry::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'message' => $request->message,
            'is_read' => false,
            'is_replied' => false
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you for contacting us. We will get back to you soon!'
        ]);
    }

    /**
     * Display the specified enquiry.
     */
    public function show(Enquiry $enquiry)
    {
        // Mark as read when viewed
        if (!$enquiry->is_read) {
            $enquiry->update(['is_read' => true]);
        }

        return view('website.enquiry.show', compact('enquiry'));
    }

    /**
     * Mark as replied.
     */
    public function markAsReplied(Enquiry $enquiry)
    {
        $enquiry->update(['is_replied' => true]);

        return redirect()->back()->with('success', 'Enquiry marked as replied.');
    }

    /**
     * Mark as unread.
     */
    public function markAsUnread(Enquiry $enquiry)
    {
        $enquiry->update(['is_read' => false]);

        return redirect()->back()->with('success', 'Enquiry marked as unread.');
    }

    /**
     * Remove the specified enquiry.
     */
    public function destroy(Enquiry $enquiry)
    {
        $enquiry->delete();

        return redirect()->route('website.enquiry.index')
            ->with('success', 'Enquiry deleted successfully.');
    }

    /**
     * Bulk delete enquiries.
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:enquiries,id'
        ]);

        Enquiry::whereIn('id', $request->ids)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Selected enquiries deleted successfully.'
        ]);
    }
}
