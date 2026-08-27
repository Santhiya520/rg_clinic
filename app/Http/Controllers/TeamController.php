<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class TeamController extends Controller
{
    /**
     * Display a listing of the team members.
     */
    public function index()
    {
        $teams = Team::ordered()->get();
        return view('website.team.index', compact('teams'));
    }

    /**
     * Show the form for creating a new team member.
     */
    public function create()
    {
        return view('website.team.create');
    }

    /**
     * Store a newly created team member in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'role' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg',
            'order' => 'nullable|integer|min:0',
            'status' => 'nullable'
        ]);

        $data = [
            'name' => $request->name,
            'role' => $request->role,
            'order' => $request->order ?? 0,
            'status' => $request->has('status') ? true : false
        ];

        if ($request->hasFile('image')) {
            // Create directory if it doesn't exist
            $path = public_path('uploads/team');
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0755, true, true);
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move($path, $imageName);
            $data['image'] = 'uploads/team/' . $imageName;
        }

        Team::create($data);

        return redirect()->route('website.team.index')
            ->with('success', 'Team member created successfully.');
    }

    /**
     * Show the form for editing the specified team member.
     */
    public function edit(Team $team)
    {
        return view('website.team.edit', compact('team'));
    }

    /**
     * Update the specified team member in storage.
     */
    public function update(Request $request, Team $team)
    {
        $request->validate([
            'name' => 'required|string',
            'role' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg',
            'order' => 'nullable|integer|min:0',
            'status' => 'nullable'
        ]);

        $data = [
            'name' => $request->name,
            'role' => $request->role,
            'order' => $request->order ?? 0,
            'status' => $request->has('status') ? true : false
        ];

        if ($request->hasFile('image')) {
            // Delete old image
            if ($team->image && file_exists(public_path($team->image))) {
                unlink(public_path($team->image));
            }

            // Create directory if it doesn't exist
            $path = public_path('uploads/team');
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0755, true, true);
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move($path, $imageName);
            $data['image'] = 'uploads/team/' . $imageName;
        }

        $team->update($data);

        return redirect()->route('website.team.index')
            ->with('success', 'Team member updated successfully.');
    }

    /**
     * Remove the specified team member from storage.
     */
    public function destroy(Team $team)
    {
        // Delete image file
        if ($team->image && file_exists(public_path($team->image))) {
            unlink(public_path($team->image));
        }

        $team->delete();

        return redirect()->route('website.team.index')
            ->with('success', 'Team member deleted successfully.');
    }

    /**
     * Toggle team member status.
     */
    public function toggleStatus(Team $team)
    {
        $team->status = !$team->status;
        $team->save();

        return redirect()->route('website.team.index')
            ->with('success', 'Team member status updated successfully.');
    }
}
