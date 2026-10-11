<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    /**
     * List all skills grouped by category
     */
    public function index(Request $request)
    {
        $query = Skill::query();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $skills = $query->orderBy('category')->orderBy('order')->get();

        $grouped = $skills->groupBy('category')->map(function ($items, $category) {
            return [
                'category' => $category,
                'skills' => $items->map(fn($s) => [
                    'id' => $s->id,
                    'name' => $s->name,
                    'level' => $s->level,
                    'order' => $s->order,
                ]),
            ];
        })->values();

        return response()->json([
            'success' => true,
            'total' => $skills->count(),
            'categories' => $grouped,
            'data' => $skills,
        ]);
    }

    /**
     * Get a specific skill by ID
     */
    public function show($id)
    {
        $skill = Skill::find($id);

        if (!$skill) {
            return response()->json([
                'success' => false,
                'message' => 'Skill not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $skill,
        ]);
    }

    /**
     * Create a new skill (Protected / Admin Only)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'level' => 'nullable|integer|min:0|max:100',
            'icon' => 'nullable|string|max:100',
            'order' => 'nullable|integer',
        ]);

        $skill = Skill::create([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'level' => $validated['level'] ?? 80,
            'icon' => $validated['icon'] ?? null,
            'order' => $validated['order'] ?? 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Skill created successfully',
            'data' => $skill,
        ], 201);
    }

    /**
     * Update an existing skill (Protected / Admin Only)
     */
    public function update(Request $request, $id)
    {
        $skill = Skill::find($id);

        if (!$skill) {
            return response()->json([
                'success' => false,
                'message' => 'Skill not found',
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'category' => 'sometimes|required|string|max:255',
            'level' => 'nullable|integer|min:0|max:100',
            'icon' => 'nullable|string|max:100',
            'order' => 'nullable|integer',
        ]);

        if (array_key_exists('order', $validated) && $validated['order'] === null) {
            $validated['order'] = 0;
        }

        $skill->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Skill updated successfully',
            'data' => $skill,
        ]);
    }

    /**
     * Delete a skill (Protected / Admin Only)
     */
    public function destroy($id)
    {
        $skill = Skill::find($id);

        if (!$skill) {
            return response()->json([
                'success' => false,
                'message' => 'Skill not found',
            ], 404);
        }

        $skill->delete();

        return response()->json([
            'success' => true,
            'message' => 'Skill deleted successfully',
        ]);
    }
}
