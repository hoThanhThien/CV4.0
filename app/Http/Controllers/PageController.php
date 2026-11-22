<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Support\PortfolioCache;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()
    {
        $skills = PortfolioCache::rememberSkills()->groupBy('category');
        $experiences = PortfolioCache::rememberExperiences();
        $certificates = PortfolioCache::rememberFeaturedCertificates();
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
        $categoryCounts = PortfolioCache::certificateCategoryCounts();
        return view('pages.certificates', compact('certificates', 'categories', 'categoryCounts'));
    }
}
