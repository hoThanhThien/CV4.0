<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use App\Models\Project;
use App\Models\Skill;
use App\Models\BlogPost;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $skills = Skill::orderBy('order')->get()->groupBy('category');
        $experiences = Experience::orderBy('order')->orderByDesc('start_date')->get();
        $featuredProjects = Project::where('featured', true)->with('technologies')->orderBy('order')->take(6)->get();
        $recentPosts = BlogPost::published()->take(3)->get();

        return view('welcome', compact('skills', 'experiences', 'featuredProjects', 'recentPosts'));
    }
}
