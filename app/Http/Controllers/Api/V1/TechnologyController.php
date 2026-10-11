<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Technology;
use Illuminate\Http\Request;

class TechnologyController extends Controller
{
    /**
     * List all technologies with project counts
     */
    public function index(Request $request)
    {
        $query = Technology::withCount('projects');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $technologies = $query->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'total' => $technologies->count(),
            'data' => $technologies,
        ]);
    }

    /**
     * Get a specific technology by ID
     */
    public function show($id)
    {
        $tech = Technology::withCount('projects')->find($id);

        if (!$tech) {
            return response()->json([
                'success' => false,
                'message' => 'Technology not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $tech,
        ]);
    }

    /**
     * Create a new technology (Protected / Admin Only)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:technologies,name',
            'color' => 'nullable|string|max:30',
            'icon' => 'nullable|string|max:50',
        ]);

        $tech = Technology::create([
            'name' => trim($validated['name']),
            'color' => !empty($validated['color']) ? $validated['color'] : '#6366f1',
            'icon' => $validated['icon'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Technology created successfully',
            'data' => $tech,
        ], 201);
    }

    /**
     * Update an existing technology (Protected / Admin Only)
     */
    public function update(Request $request, $id)
    {
        $tech = Technology::find($id);

        if (!$tech) {
            return response()->json([
                'success' => false,
                'message' => 'Technology not found',
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:100|unique:technologies,name,' . $id,
            'color' => 'nullable|string|max:30',
            'icon' => 'nullable|string|max:50',
        ]);

        if (array_key_exists('color', $validated) && empty($validated['color'])) {
            $validated['color'] = '#6366f1';
        }

        if (isset($validated['name'])) {
            $validated['name'] = trim($validated['name']);
        }

        $tech->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Technology updated successfully',
            'data' => $tech,
        ]);
    }

    /**
     * Delete a technology (Protected / Admin Only)
     */
    public function destroy($id)
    {
        $tech = Technology::find($id);

        if (!$tech) {
            return response()->json([
                'success' => false,
                'message' => 'Technology not found',
            ], 404);
        }

        $tech->projects()->detach();
        $tech->delete();

        return response()->json([
            'success' => true,
            'message' => 'Technology deleted successfully',
        ]);
    }
}
