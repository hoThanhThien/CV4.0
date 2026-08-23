<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use App\Models\Skill;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()
    {
        $skills = Skill::orderBy('order')->get()->groupBy('category');
        $experiences = Experience::orderBy('order')->orderByDesc('start_date')->get();
        return view('pages.about', compact('skills', 'experiences'));
    }
}
