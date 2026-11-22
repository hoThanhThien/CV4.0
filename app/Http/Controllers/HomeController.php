<?php

namespace App\Http\Controllers;

use App\Support\PortfolioCache;

class HomeController extends Controller
{
    public function index()
    {
        $rawSkills = PortfolioCache::rememberSkills();
        $skills = $rawSkills->groupBy('category');
        $skillCategories = PortfolioCache::rememberSkillCategories();
        $experiences = PortfolioCache::rememberExperiences();
        $featuredProjects = PortfolioCache::rememberFeaturedProjects();
        $recentPosts = PortfolioCache::rememberRecentPosts();
        $certificates = PortfolioCache::rememberFeaturedCertificates();

        return view('welcome', compact('skills', 'skillCategories', 'experiences', 'featuredProjects', 'recentPosts', 'certificates'));
    }
}
