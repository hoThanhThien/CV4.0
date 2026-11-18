<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\RAG\PortfolioRagService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Handles public RAG AI Copilot queries grounded in live Portfolio database records.
 */
class CopilotController extends Controller
{
    public function ask(Request $request, PortfolioRagService $rag): JsonResponse
    {
        $validated = $request->validate([
            'query' => 'required|string|min:2|max:500',
            'locale' => 'nullable|string|in:en,vi',
            'history' => 'nullable|array|max:6',
            'history.*.role' => 'required_with:history|string|in:user,assistant',
            'history.*.text' => 'required_with:history|string|max:500',
        ]);

        $locale = $validated['locale'] ?? (app()->getLocale() === 'vi' ? 'vi' : 'en');
        $history = $validated['history'] ?? [];

        $result = $rag->ask($validated['query'], $locale, $history);

        return response()->json($result, 200);
    }
}
