<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * List all projects with optional filtering
     */
    public function index(Request $request)
    {
        $query = Project::with('technologies')->orderBy('order')->latest('id');

        if ($request->has('featured')) {
            $query->where('featured', filter_var($request->featured, FILTER_VALIDATE_BOOLEAN));
        }

        if ($request->filled('tech')) {
            $techName = $request->tech;
            $query->whereHas('technologies', function ($q) use ($techName) {
                $q->where('name', 'like', "%{$techName}%");
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $projects = $query->get()->map(function ($proj) {
            return [
                'id' => $proj->id,
                'title' => $proj->title,
                'description' => $proj->description,
                'image_url' => $proj->image ? asset('storage/' . $proj->image) : asset('images/og-image.webp'),
                'github_url' => $proj->github_url,
                'demo_url' => $proj->demo_url,
                'featured' => (bool) $proj->featured,
                'order' => $proj->order,
                'technologies' => $proj->technologies->map(fn($t) => [
                    'id' => $t->id,
                    'name' => $t->name,
                    'color' => $t->color,
                ]),
                'created_at' => $proj->created_at,
                'updated_at' => $proj->updated_at,
            ];
        });

        return response()->json([
            'success' => true,
            'total' => $projects->count(),
            'data' => $projects,
        ]);
    }

    /**
     * Get a specific project by ID
     */
    public function show($id)
    {
        $proj = Project::with('technologies')->find($id);

        if (!$proj) {
            return response()->json([
                'success' => false,
                'message' => 'Project not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $proj->id,
                'title' => $proj->title,
                'description' => $proj->description,
                'image_url' => $proj->image ? asset('storage/' . $proj->image) : asset('images/og-image.webp'),
                'github_url' => $proj->github_url,
                'demo_url' => $proj->demo_url,
                'featured' => (bool) $proj->featured,
                'order' => $proj->order,
                'technologies' => $proj->technologies->map(fn($t) => [
                    'id' => $t->id,
                    'name' => $t->name,
                    'color' => $t->color,
                ]),
                'created_at' => $proj->created_at,
                'updated_at' => $proj->updated_at,
            ],
        ]);
    }

    /**
     * Create a new project (Admin Only)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'github_url' => 'nullable|url',
            'demo_url' => 'nullable|url',
            'featured' => 'nullable|boolean',
            'order' => 'nullable|integer',
            'technology_ids' => 'nullable|array',
            'technology_ids.*' => 'integer|exists:technologies,id',
        ]);

        $project = Project::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'github_url' => $validated['github_url'] ?? null,
            'demo_url' => $validated['demo_url'] ?? null,
            'featured' => $validated['featured'] ?? false,
            'order' => $validated['order'] ?? 0,
        ]);

        if (!empty($validated['technology_ids'])) {
            $project->technologies()->sync($validated['technology_ids']);
        }

        $project->load('technologies');

        return response()->json([
            'success' => true,
            'message' => 'Project created successfully',
            'data' => $project,
        ], 201);
    }

    /**
     * Update an existing project (Admin Only)
     */
    public function update(Request $request, $id)
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Project not found',
            ], 404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'github_url' => 'nullable|url',
            'demo_url' => 'nullable|url',
            'featured' => 'nullable|boolean',
            'order' => 'nullable|integer',
            'technology_ids' => 'nullable|array',
            'technology_ids.*' => 'integer|exists:technologies,id',
        ]);

        $project->update($validated);

        if (isset($validated['technology_ids'])) {
            $project->technologies()->sync($validated['technology_ids']);
        }

        $project->load('technologies');

        return response()->json([
            'success' => true,
            'message' => 'Project updated successfully',
            'data' => $project,
        ]);
    }

    /**
     * Delete a project (Admin Only)
     */
    public function destroy($id)
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Project not found',
            ], 404);
        }

        $project->technologies()->detach();
        $project->delete();

        return response()->json([
            'success' => true,
            'message' => 'Project deleted successfully',
        ]);
    }
}
