@extends('layouts.admin')

@section('title', 'Experiences')

@section('content')
<div class="page-header">
    <h1>Work Experiences</h1>
    <a href="{{ route('admin.experiences.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Experience
    </a>
</div>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Company & Role</th>
                <th>Duration</th>
                <th>Order</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($experiences as $exp)
            <tr>
                <td>
                    <div style="font-weight:700">{{ $exp->position }}</div>
                    <div style="color:var(--text-muted); font-size:0.85rem">{{ $exp->company }}</div>
                </td>
                <td>
                    <div style="font-size:0.85rem">
                        {{ $exp->start_date->format('M Y') }} — 
                        @if($exp->current)
                            <span class="badge badge-success">Present</span>
                        @else
                            {{ $exp->end_date ? $exp->end_date->format('M Y') : 'Unknown' }}
                        @endif
                    </div>
                </td>
                <td style="color:var(--text-muted)">{{ $exp->order }}</td>
                <td>
                    <div style="display:flex; gap:0.4rem">
                        <a href="{{ route('admin.experiences.edit', $exp->id) }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.experiences.destroy', $exp->id) }}"
                            onsubmit="return confirm('Delete this experience?')">
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
                    No experiences added yet. <a href="{{ route('admin.experiences.create') }}" style="color:var(--accent-light)">Add one</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
