<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use App\Models\Project;
use App\Models\Skill;
use App\Models\BlogPost;
use App\Models\Certificate;
use App\Support\SkillMatrixBuilder;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $rawSkills = Skill::with('projects')->orderBy('order')->get();
        $skills = $rawSkills->groupBy('category');
        $skillCategories = SkillMatrixBuilder::build($rawSkills);
        $experiences = Experience::orderBy('order')->orderByDesc('start_date')->get();
        $featuredProjects = Project::where('featured', true)->with('technologies')->orderBy('order')->take(6)->get();
        $recentPosts = BlogPost::published()->take(3)->get();
        $certificates = Certificate::where('featured', true)->orderBy('order')->orderByDesc('created_at')->get();

        return view('welcome', compact('skills', 'skillCategories', 'experiences', 'featuredProjects', 'recentPosts', 'certificates'));
    }
}
