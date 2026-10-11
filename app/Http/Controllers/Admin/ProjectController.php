<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Technology;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('technologies')->orderBy('order')->paginate(10);
        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        $technologies = Technology::orderBy('name')->get();
        $skills = Skill::all()->keyBy('name');
        return view('admin.projects.create', compact('technologies', 'skills'));
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
            'technologies' => 'nullable|array',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('projects', 'public');
        }

        $validated['featured'] = $request->boolean('featured');
        $validated['order'] = $validated['order'] ?? 0;

        $project = Project::create($validated);

        if ($request->has('technologies')) {
            $project->technologies()->sync($request->technologies);
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Project created successfully!',
                'project' => $project->load('technologies'),
            ], 201);
        }

        return redirect()->route('admin.projects.index')->with('success', 'Project created successfully!');
    }

    public function show($id)
    {
        $project = Project::with('technologies')->findOrFail($id);
        return view('admin.projects.show', compact('project'));
    }

    public function edit($id)
    {
        $project = Project::with('technologies')->findOrFail($id);
        $technologies = Technology::orderBy('name')->get();
        $selectedTechnologies = $project->technologies->pluck('id')->toArray();
        $skills = Skill::all()->keyBy('name');
        return view('admin.projects.edit', compact('project', 'technologies', 'selectedTechnologies', 'skills'));
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
        $project->technologies()->sync($request->technologies ?? []);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Project updated successfully!',
                'project' => $project->load('technologies'),
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

        $project->technologies()->detach();
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
