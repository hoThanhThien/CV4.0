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
        $experiences = Experience::orderBy('order')->orderByDesc('start_date')->get()->map(function ($exp) {
            return [
                'id' => $exp->id,
                'company' => $exp->company,
                'position' => $exp->position,
                'description' => $exp->description,
                'start_date' => $exp->start_date,
                'end_date' => $exp->end_date,
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
}
