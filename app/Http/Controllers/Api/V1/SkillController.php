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
        $skills = Skill::orderBy('category')->orderBy('order')->get();

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
}
