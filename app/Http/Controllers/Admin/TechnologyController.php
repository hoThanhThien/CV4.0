<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Technology;
use Illuminate\Http\Request;

class TechnologyController extends Controller
{
    public function index()
    {
        $technologies = Technology::withCount('projects')->orderBy('name')->get();
        return view('admin.technologies.index', compact('technologies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:technologies,name',
            'color' => 'nullable|string|max:30',
            'icon' => 'nullable|string|max:50',
        ]);

        if (empty($validated['color'])) {
            $validated['color'] = '#6366f1';
        }

        $tech = Technology::create($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'technology' => $tech,
            ]);
        }

        return redirect()->route('admin.technologies.index')->with('success', 'Technology added successfully!');
    }

    public function quick(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'color' => 'nullable|string|max:30',
        ]);

        $tech = Technology::firstOrCreate(
            ['name' => trim($validated['name'])],
            ['color' => $validated['color'] ?? '#6366f1']
        );

        return response()->json([
            'success' => true,
            'technology' => $tech,
        ]);
    }

    public function update(Request $request, $id)
    {
        $tech = Technology::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:technologies,name,' . $id,
            'color' => 'nullable|string|max:30',
            'icon' => 'nullable|string|max:50',
        ]);

        $tech->update($validated);

        return redirect()->route('admin.technologies.index')->with('success', 'Technology updated successfully!');
    }

    public function destroy($id)
    {
        $tech = Technology::findOrFail($id);
        $tech->projects()->detach();
        $tech->delete();

        return redirect()->route('admin.technologies.index')->with('success', 'Technology deleted successfully!');
    }
}
