@extends('layouts.admin')

@section('title', 'Technologies')

@section('content')
<div class="page-header">
    <h1>Technologies</h1>
    <span style="color: var(--text-muted); font-size: 0.9rem">Manage technologies and tech stack tags used across projects.</span>
</div>

<div style="display:grid; grid-template-columns: 1fr 340px; gap:1.5rem; align-items:start">
    <!-- List of Technologies -->
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Technology</th>
                    <th>Color</th>
                    <th>Used In</th>
                    <th style="text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($technologies as $tech)
                <tr>
                    <td>
                        <span class="badge" style="background: {{ $tech->color }}20; color: {{ $tech->color }}; border: 1px solid {{ $tech->color }}50; font-size:0.85rem; padding:0.3rem 0.65rem">
                            {{ $tech->name }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex; align-items:center; gap:0.5rem; font-size:0.85rem">
                            <span style="width:14px; height:14px; border-radius:4px; background:{{ $tech->color }}; display:inline-block; border:1px solid rgba(0,0,0,0.1)"></span>
                            <code>{{ $tech->color }}</code>
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-purple">{{ $tech->projects_count }} projects</span>
                    </td>
                    <td style="text-align:right">
                        <form method="POST" action="{{ route('admin.technologies.destroy', $tech->id) }}" style="display:inline" onsubmit="return confirm('Are you sure you want to delete {{ $tech->name }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align:center; color:var(--text-muted); padding:2rem">
                        No technologies yet. Add one on the right!
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Add New Technology Form -->
    <div class="form-card">
        <h3 style="font-size:1.1rem; font-weight:700; margin-bottom:1.25rem">
            <i class="fas fa-plus-circle" style="color:var(--accent)"></i> Add Technology
        </h3>

        <form method="POST" action="{{ route('admin.technologies.store') }}">
            @csrf
            <div class="form-group">
                <label class="form-label" for="name">Technology Name *</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="e.g. Docker, Laravel, Nginx" required>
                @error('name')<span style="font-size:0.8rem; color:#ef4444">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="color">Tag Accent Color</label>
                <div style="display:flex; gap:0.5rem; align-items:center">
                    <input type="color" id="color_picker" value="#6366f1" style="width:42px; height:42px; border:1px solid var(--border); border-radius:8px; cursor:pointer; padding:2px" oninput="document.getElementById('color').value = this.value">
                    <input type="text" id="color" name="color" class="form-control" value="#6366f1" placeholder="#6366f1" oninput="document.getElementById('color_picker').value = this.value">
                </div>
                @error('color')<span style="font-size:0.8rem; color:#ef4444">{{ $message }}</span>@enderror
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center">
                <i class="fas fa-save"></i> Save Technology
            </button>
        </form>
    </div>
</div>

<style>
@media (max-width: 860px) {
    div[style*="grid-template-columns: 1fr 340px"] { grid-template-columns: 1fr !important; }
}
</style>
@endsection
