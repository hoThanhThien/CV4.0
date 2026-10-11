<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    /**
     * List all work and project experiences
     */
    public function index(Request $request)
    {
        $query = Experience::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('company', 'like', '%' . $request->search . '%')
                  ->orWhere('position', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $experiences = $query->orderBy('order')->orderByDesc('start_date')->get()->map(function ($exp) {
            return [
                'id' => $exp->id,
                'company' => $exp->company,
                'position' => $exp->position,
                'description' => $exp->description,
                'start_date' => $exp->start_date ? $exp->start_date->format('Y-m-d') : null,
                'end_date' => $exp->end_date ? $exp->end_date->format('Y-m-d') : null,
                'is_current' => (bool) $exp->current,
                'order' => $exp->order,
            ];
        });

        return response()->json([
            'success' => true,
            'total' => $experiences->count(),
            'data' => $experiences,
        ]);
    }

    /**
     * Get a specific experience by ID
     */
    public function show($id)
    {
        $exp = Experience::find($id);

        if (!$exp) {
            return response()->json([
                'success' => false,
                'message' => 'Experience not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $exp->id,
                'company' => $exp->company,
                'position' => $exp->position,
                'description' => $exp->description,
                'start_date' => $exp->start_date ? $exp->start_date->format('Y-m-d') : null,
                'end_date' => $exp->end_date ? $exp->end_date->format('Y-m-d') : null,
                'is_current' => (bool) $exp->current,
                'order' => $exp->order,
            ],
        ]);
    }

    /**
     * Create a new experience (Protected / Admin Only)
     */
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

        $isCurrent = $request->boolean('current');

        $exp = Experience::create([
            'company' => $validated['company'],
            'position' => $validated['position'],
            'description' => $validated['description'],
            'start_date' => $validated['start_date'],
            'end_date' => $isCurrent ? null : ($validated['end_date'] ?? null),
            'current' => $isCurrent,
            'order' => $validated['order'] ?? 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Experience created successfully',
            'data' => [
                'id' => $exp->id,
                'company' => $exp->company,
                'position' => $exp->position,
                'description' => $exp->description,
                'start_date' => $exp->start_date ? $exp->start_date->format('Y-m-d') : null,
                'end_date' => $exp->end_date ? $exp->end_date->format('Y-m-d') : null,
                'is_current' => (bool) $exp->current,
                'order' => $exp->order,
            ],
        ], 201);
    }

    /**
     * Update an existing experience (Protected / Admin Only)
     */
    public function update(Request $request, $id)
    {
        $exp = Experience::find($id);

        if (!$exp) {
            return response()->json([
                'success' => false,
                'message' => 'Experience not found',
            ], 404);
        }

        $validated = $request->validate([
            'company' => 'sometimes|required|string|max:255',
            'position' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'nullable|date',
            'current' => 'nullable|boolean',
            'order' => 'nullable|integer',
        ]);

        if ($request->has('current')) {
            $validated['current'] = $request->boolean('current');
            if ($validated['current']) {
                $validated['end_date'] = null;
            }
        }

        if (array_key_exists('order', $validated) && $validated['order'] === null) {
            $validated['order'] = 0;
        }

        $exp->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Experience updated successfully',
            'data' => [
                'id' => $exp->id,
                'company' => $exp->company,
                'position' => $exp->position,
                'description' => $exp->description,
                'start_date' => $exp->start_date ? $exp->start_date->format('Y-m-d') : null,
                'end_date' => $exp->end_date ? $exp->end_date->format('Y-m-d') : null,
                'is_current' => (bool) $exp->current,
                'order' => $exp->order,
            ],
        ]);
    }

    /**
     * Delete an experience (Protected / Admin Only)
     */
    public function destroy($id)
    {
        $exp = Experience::find($id);

        if (!$exp) {
            return response()->json([
                'success' => false,
                'message' => 'Experience not found',
            ], 404);
        }

        $exp->delete();

        return response()->json([
            'success' => true,
            'message' => 'Experience deleted successfully',
        ]);
    }
}
