<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\BlogPost;
use App\Models\Experience;
use App\Models\Skill;
use App\Models\ContactMessage;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'projects' => Project::count(),
            'blog_posts' => BlogPost::count(),
            'published_posts' => BlogPost::where('published', true)->count(),
            'experiences' => Experience::count(),
            'skills' => Skill::count(),
            'unread_messages' => ContactMessage::where('is_read', false)->count(),
        ];

        $recentProjects = Project::latest()->take(5)->get();
        $recentPosts = BlogPost::latest()->take(5)->get();
        $recentMessages = ContactMessage::latest()->take(5)->get();

        return view('admin.dashboard.index', compact('stats', 'recentProjects', 'recentPosts', 'recentMessages'));
    }
}
