@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <h1>Dashboard</h1>
    <span style="color: var(--text-muted); font-size: 0.9rem">Welcome back! Here's your portfolio overview.</span>
</div>

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card stat-purple">
        <div class="stat-icon"><i class="fas fa-code"></i></div>
        <div class="stat-label">Total Projects</div>
        <div class="stat-value">{{ $stats['projects'] }}</div>
    </div>
    <div class="stat-card stat-cyan">
        <div class="stat-icon"><i class="fas fa-newspaper"></i></div>
        <div class="stat-label">Blog Posts</div>
        <div class="stat-value">{{ $stats['blog_posts'] }}</div>
    </div>
    <div class="stat-card stat-green">
        <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
        <div class="stat-label">Published</div>
        <div class="stat-value">{{ $stats['published_posts'] }}</div>
    </div>
    <div class="stat-card stat-pink">
        <div class="stat-icon"><i class="fas fa-star"></i></div>
        <div class="stat-label">Skills</div>
        <div class="stat-value">{{ $stats['skills'] }}</div>
    </div>
</div>

<!-- Quick Actions -->
<div style="display:flex; gap:0.75rem; margin-bottom:2rem; flex-wrap:wrap">
    <a href="{{ route('admin.projects.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> New Project
    </a>
    <a href="{{ route('admin.blog.create') }}" class="btn btn-secondary">
        <i class="fas fa-pen"></i> New Blog Post
    </a>
    <a href="{{ route('home') }}" target="_blank" class="btn btn-secondary">
        <i class="fas fa-external-link-alt"></i> View Portfolio
    </a>
</div>

<!-- Recent Content -->
<div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.5rem">
    <!-- Recent Projects -->
    <div>
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem">
            <h3 style="font-size:1rem; font-weight:700">Recent Projects</h3>
            <a href="{{ route('admin.projects.index') }}" style="color: var(--accent-light); font-size:0.85rem; text-decoration:none">View all →</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Featured</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentProjects as $project)
                    <tr>
                        <td style="font-weight:600; max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap">{{ $project->title }}</td>
                        <td>
                            @if($project->featured)
                                <span class="badge badge-purple"><i class="fas fa-star"></i> Yes</span>
                            @else
                                <span class="badge badge-warning">No</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.projects.edit', $project->id) }}" class="btn btn-secondary btn-sm">Edit</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" style="text-align:center; color:var(--text-muted); padding:2rem">No projects yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Blog Posts -->
    <div>
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem">
            <h3 style="font-size:1rem; font-weight:700">Recent Blog Posts</h3>
            <a href="{{ route('admin.blog.index') }}" style="color: var(--accent-light); font-size:0.85rem; text-decoration:none">View all →</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPosts as $post)
                    <tr>
                        <td style="font-weight:600; max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap">{{ $post->title }}</td>
                        <td>
                            @if($post->published)
                                <span class="badge badge-success"><i class="fas fa-circle" style="font-size:0.5rem"></i> Published</span>
                            @else
                                <span class="badge badge-warning">Draft</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.blog.edit', $post->id) }}" class="btn btn-secondary btn-sm">Edit</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" style="text-align:center; color:var(--text-muted); padding:2rem">No posts yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
@media (max-width: 768px) {
    div[style*="grid-template-columns: 1fr 1fr"] { grid-template-columns: 1fr !important; }
}
</style>
@endsection
