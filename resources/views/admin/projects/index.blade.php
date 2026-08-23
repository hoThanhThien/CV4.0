@extends('layouts.admin')

@section('title', 'Projects')

@section('content')
<div class="page-header">
    <h1>Projects</h1>
    <a href="{{ route('admin.projects.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Project
    </a>
</div>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Technologies</th>
                <th>Featured</th>
                <th>Order</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($projects as $project)
            <tr>
                <td style="color:var(--text-muted)">{{ $project->id }}</td>
                <td>
                    <div style="font-weight:600">{{ $project->title }}</div>
                    <div style="font-size:0.8rem; color:var(--text-muted)">{{ Str::limit($project->description, 60) }}</div>
                </td>
                <td>
                    <div style="display:flex; flex-wrap:wrap; gap:0.3rem">
                        @foreach($project->technologies->take(3) as $tech)
                            <span class="badge badge-purple">{{ $tech->name }}</span>
                        @endforeach
                        @if($project->technologies->count() > 3)
                            <span class="badge" style="background:rgba(255,255,255,0.05); color:var(--text-muted)">+{{ $project->technologies->count() - 3 }}</span>
                        @endif
                    </div>
                </td>
                <td>
                    @if($project->featured)
                        <span class="badge badge-success"><i class="fas fa-star"></i> Yes</span>
                    @else
                        <span class="badge badge-warning">No</span>
                    @endif
                </td>
                <td style="color:var(--text-muted)">{{ $project->order }}</td>
                <td style="color:var(--text-muted); font-size:0.85rem">{{ $project->created_at->format('M d, Y') }}</td>
                <td>
                    <div style="display:flex; gap:0.4rem">
                        <a href="{{ route('projects.show', $project->id) }}" target="_blank" class="btn btn-secondary btn-sm" title="Preview">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.projects.edit', $project->id) }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.projects.destroy', $project->id) }}"
                            onsubmit="return confirm('Delete this project?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center; padding:3rem; color:var(--text-muted)">
                    <i class="fas fa-code" style="font-size:2rem; margin-bottom:0.75rem; display:block; opacity:0.3"></i>
                    No projects yet. <a href="{{ route('admin.projects.create') }}" style="color:var(--accent-light)">Create one</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($projects->hasPages())
<div class="pagination-wrap" style="display:flex; gap:0.5rem; margin-top:1.5rem; flex-wrap:wrap">
    @foreach($projects->getUrlRange(1, $projects->lastPage()) as $page => $url)
        <a href="{{ $url }}" style="padding:0.4rem 0.8rem; border-radius:7px; text-decoration:none; background:var(--bg-card); border:1px solid var(--border); color:{{ $projects->currentPage() == $page ? 'white' : 'var(--text-muted)' }}; background:{{ $projects->currentPage() == $page ? 'var(--accent)' : 'var(--bg-card)' }}">{{ $page }}</a>
    @endforeach
</div>
@endif
@endsection
