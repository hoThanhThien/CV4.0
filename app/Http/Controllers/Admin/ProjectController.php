<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
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
        return view('admin.projects.create', compact('technologies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'github_url' => 'nullable|url',
            'demo_url' => 'nullable|url',
            'featured' => 'boolean',
            'order' => 'integer',
            'technologies' => 'nullable|array',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('projects', 'public');
        }

        $validated['featured'] = $request->boolean('featured');

        $project = Project::create($validated);

        if ($request->has('technologies')) {
            $project->technologies()->sync($request->technologies);
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
        return view('admin.projects.edit', compact('project', 'technologies', 'selectedTechnologies'));
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
            'featured' => 'boolean',
            'order' => 'integer',
            'technologies' => 'nullable|array',
        ]);

        if ($request->hasFile('image')) {
            if ($project->image) {
                Storage::disk('public')->delete($project->image);
            }
            $validated['image'] = $request->file('image')->store('projects', 'public');
        }

        $validated['featured'] = $request->boolean('featured');

        $project->update($validated);
        $project->technologies()->sync($request->technologies ?? []);

        return redirect()->route('admin.projects.index')->with('success', 'Project updated successfully!');
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);

        if ($project->image) {
            Storage::disk('public')->delete($project->image);
        }

        $project->technologies()->detach();
        $project->delete();

        return redirect()->route('admin.projects.index')->with('success', 'Project deleted successfully!');
    }
}
