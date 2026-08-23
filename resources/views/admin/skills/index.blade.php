@extends('layouts.admin')

@section('title', 'Skills')

@section('content')
<div class="page-header">
    <h1>Skills</h1>
    <a href="{{ route('admin.skills.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Skill
    </a>
</div>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Category</th>
                <th>Name</th>
                <th>Order</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($skills as $skill)
            <tr>
                <td><span class="badge badge-purple">{{ $skill->category }}</span></td>
                <td style="font-weight:600">{{ $skill->name }}</td>
                <td style="color:var(--text-muted)">{{ $skill->order }}</td>
                <td>
                    <div style="display:flex; gap:0.4rem">
                        <a href="{{ route('admin.skills.edit', $skill->id) }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.skills.destroy', $skill->id) }}"
                            onsubmit="return confirm('Delete this skill?')">
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
                <td colspan="4" style="text-align:center; padding:3rem; color:var(--text-muted)">
                    No skills added yet. <a href="{{ route('admin.skills.create') }}" style="color:var(--accent-light)">Add one</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
