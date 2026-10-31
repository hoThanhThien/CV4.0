<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use App\Models\Skill;
use App\Models\Certificate;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()
    {
        $skills = Skill::orderBy('order')->get()->groupBy('category');
        $experiences = Experience::orderBy('order')->orderByDesc('start_date')->get();
        $certificates = Certificate::where('featured', true)->orderBy('order')->orderByDesc('created_at')->get();
        return view('pages.about', compact('skills', 'experiences', 'certificates'));
    }

    public function certificates(Request $request)
    {
        $query = Certificate::query();
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }
        $certificates = $query->orderBy('order')->orderByDesc('created_at')->get();
        $categories = Certificate::select('category')->distinct()->pluck('category');
        return view('pages.certificates', compact('certificates', 'categories'));
    }
}
