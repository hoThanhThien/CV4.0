<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use App\Models\Technology;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function index()
    {
        $skills = Skill::orderBy('category')->orderBy('order')->get();
        return view('admin.skills.index', compact('skills'));
    }

    public function create()
    {
        $existingCategories = Skill::distinct()->pluck('category')->filter()->values();
        $defaultCategories = collect(['Frontend', 'Backend', 'Database', 'Tools & Practices', 'DevOps & Cloud']);
        $categories = $defaultCategories->merge($existingCategories)->unique()->values();
        return view('admin.skills.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'order' => 'nullable|integer',
            'level' => 'nullable|integer|min:0|max:100',
        ]);

        $validated['order'] = $validated['order'] ?? 0;
        $validated['level'] = $validated['level'] ?? 80;
        
        $skill = Skill::create($validated);

        // Auto-sync into technologies table so it is instantly selectable in Projects
        Technology::firstOrCreate(
            ['name' => trim($skill->name)],
            ['color' => '#6366f1']
        );

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Skill created successfully',
                'skill' => $skill,
            ], 201);
        }

        return redirect()->route('admin.skills.index')->with('success', 'Skill created successfully');
    }

    public function edit(Skill $skill)
    {
        $existingCategories = Skill::distinct()->pluck('category')->filter()->values();
        $defaultCategories = collect(['Frontend', 'Backend', 'Database', 'Tools & Practices', 'DevOps & Cloud']);
        $categories = $defaultCategories->merge($existingCategories)->unique()->values();
        return view('admin.skills.edit', compact('skill', 'categories'));
    }

    public function update(Request $request, Skill $skill)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'order' => 'nullable|integer',
            'level' => 'nullable|integer|min:0|max:100',
        ]);

        $validated['order'] = $validated['order'] ?? $skill->order ?? 0;
        $validated['level'] = $validated['level'] ?? $skill->level ?? 80;

        $oldName = $skill->name;
        $skill->update($validated);

        // Auto-sync into technologies if name changed or missing
        Technology::firstOrCreate(
            ['name' => trim($skill->name)],
            ['color' => '#6366f1']
        );

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Skill updated successfully',
                'skill' => $skill,
            ]);
        }

        return redirect()->route('admin.skills.index')->with('success', 'Skill updated successfully');
    }

    public function destroy(Request $request, Skill $skill)
    {
        $skill->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Skill deleted successfully',
            ]);
        }

        return redirect()->route('admin.skills.index')->with('success', 'Skill deleted successfully');
    }
}
