<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    public function index()
    {
        $experiences = Experience::orderBy('order')->orderByDesc('start_date')->get();
        return view('admin.experiences.index', compact('experiences'));
    }

    public function create()
    {
        return view('admin.experiences.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'current' => 'nullable|boolean',
            'order' => 'nullable|integer',
        ]);

        $validated['current'] = $request->boolean('current');
        if ($validated['current']) {
            $validated['end_date'] = null;
        }
        $validated['order'] = $validated['order'] ?? 0;

        $experience = Experience::create($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Experience created successfully',
                'experience' => $experience,
            ], 201);
        }

        return redirect()->route('admin.experiences.index')->with('success', 'Experience created successfully');
    }

    public function edit(Experience $experience)
    {
        return view('admin.experiences.edit', compact('experience'));
    }

    public function update(Request $request, Experience $experience)
    {
        $validated = $request->validate([
            'company' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'current' => 'nullable|boolean',
            'order' => 'nullable|integer',
        ]);

        $validated['current'] = $request->boolean('current');
        if ($validated['current']) {
            $validated['end_date'] = null;
        }
        $validated['order'] = $validated['order'] ?? $experience->order ?? 0;

        $experience->update($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Experience updated successfully',
                'experience' => $experience,
            ]);
        }

        return redirect()->route('admin.experiences.index')->with('success', 'Experience updated successfully');
    }

    public function destroy(Request $request, Experience $experience)
    {
        $experience->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Experience deleted successfully',
            ]);
        }

        return redirect()->route('admin.experiences.index')->with('success', 'Experience deleted successfully');
    }
}
