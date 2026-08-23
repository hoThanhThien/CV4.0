<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('technologies')->orderBy('order')->paginate(9);
        return view('projects.index', compact('projects'));
    }

    public function show($id)
    {
        $project = Project::with('technologies')->findOrFail($id);
        return view('projects.show', compact('project'));
    }
}
