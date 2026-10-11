<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('skills')->orderBy('order')->paginate(10);
        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        $skills = Skill::orderBy('category')->orderBy('order')->get();
        return view('admin.projects.create', compact('skills'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'github_url' => 'nullable|url',
            'demo_url' => 'nullable|url',
            'featured' => 'nullable|boolean',
            'order' => 'nullable|integer',
            'skills' => 'nullable|array',
            'technologies' => 'nullable|array',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('projects', 'public');
        }

        $validated['featured'] = $request->boolean('featured');
        $validated['order'] = $validated['order'] ?? 0;

        $project = Project::create($validated);

        $skillIds = $request->input('skills', $request->input('technologies', []));
        if (!empty($skillIds)) {
            $project->skills()->sync($skillIds);
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Project created successfully!',
                'project' => $project->load('skills'),
            ], 201);
        }

        return redirect()->route('admin.projects.index')->with('success', 'Project created successfully!');
    }

    public function show($id)
    {
        $project = Project::with('skills')->findOrFail($id);
        return view('admin.projects.show', compact('project'));
    }

    public function edit($id)
    {
        $project = Project::with('skills')->findOrFail($id);
        $skills = Skill::orderBy('category')->orderBy('order')->get();
        $selectedSkills = $project->skills->pluck('id')->toArray();
        return view('admin.projects.edit', compact('project', 'skills', 'selectedSkills'));
    }

    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'github_url' => 'nullable|url',
            'demo_url' => 'nullable|url',
            'featured' => 'nullable|boolean',
            'order' => 'nullable|integer',
            'skills' => 'nullable|array',
            'technologies' => 'nullable|array',
        ]);

        if ($request->hasFile('image')) {
            if ($project->image) {
                Storage::disk('public')->delete($project->image);
            }
            $validated['image'] = $request->file('image')->store('projects', 'public');
        }

        $validated['featured'] = $request->boolean('featured');
        $validated['order'] = $validated['order'] ?? $project->order ?? 0;

        $project->update($validated);

        $skillIds = $request->input('skills', $request->input('technologies', []));
        $project->skills()->sync($skillIds);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Project updated successfully!',
                'project' => $project->load('skills'),
            ]);
        }

        return redirect()->route('admin.projects.index')->with('success', 'Project updated successfully!');
    }

    public function destroy(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        if ($project->image) {
            Storage::disk('public')->delete($project->image);
        }

        $project->skills()->detach();
        $project->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Project deleted successfully!',
            ]);
        }

        return redirect()->route('admin.projects.index')->with('success', 'Project deleted successfully!');
    }
}
