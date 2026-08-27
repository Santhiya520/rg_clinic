<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    /**
     * Show the form for editing the notice.
     */
    public function index()
    {
        // Get the first notice or create a new one if none exists
        $notice = Notice::firstOrCreate(
            ['id' => 1],
            ['description' => '']
        );

        return view('website.notice.index', compact('notice'));
    }

    /**
     * Update the notice in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'description' => 'required|string'
        ]);

        // Get the first notice or create a new one
        $notice = Notice::firstOrCreate(['id' => 1]);

        $notice->update([
            'description' => $request->description
        ]);

        return redirect()->route('website.notice.index')
            ->with('success', 'Notice updated successfully.');
    }
}
