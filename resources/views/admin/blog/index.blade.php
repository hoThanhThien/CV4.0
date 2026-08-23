@extends('layouts.admin')

@section('title', 'Blog Posts')

@section('content')
<div class="page-header">
    <h1>Blog Posts</h1>
    <a href="{{ route('admin.blog.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> New Post
    </a>
</div>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Slug</th>
                <th>Status</th>
                <th>Published</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($posts as $post)
            <tr>
                <td style="color:var(--text-muted)">{{ $post->id }}</td>
                <td>
                    <div style="font-weight:600">{{ $post->title }}</div>
                    @if($post->excerpt)
                    <div style="font-size:0.8rem; color:var(--text-muted)">{{ Str::limit($post->excerpt, 60) }}</div>
                    @endif
                </td>
                <td><code style="font-size:0.78rem; color:var(--accent-light); background:rgba(124,58,237,0.1); padding:0.2rem 0.5rem; border-radius:5px">/{{ $post->slug }}</code></td>
                <td>
                    @if($post->published)
                        <span class="badge badge-success"><i class="fas fa-circle" style="font-size:0.45rem; vertical-align:middle"></i> Published</span>
                    @else
                        <span class="badge badge-warning">Draft</span>
                    @endif
                </td>
                <td style="color:var(--text-muted); font-size:0.85rem">
                    {{ $post->published_at ? $post->published_at->format('M d, Y') : '—' }}
                </td>
                <td>
                    <div style="display:flex; gap:0.4rem">
                        @if($post->published)
                        <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="btn btn-secondary btn-sm" title="Preview">
                            <i class="fas fa-eye"></i>
                        </a>
                        @endif
                        <a href="{{ route('admin.blog.edit', $post->id) }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.blog.destroy', $post->id) }}"
                            onsubmit="return confirm('Delete this post?')">
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
                <td colspan="6" style="text-align:center; padding:3rem; color:var(--text-muted)">
                    <i class="fas fa-pen-nib" style="font-size:2rem; margin-bottom:0.75rem; display:block; opacity:0.3"></i>
                    No blog posts yet. <a href="{{ route('admin.blog.create') }}" style="color:var(--accent-light)">Write one</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($posts->hasPages())
<div style="display:flex; gap:0.5rem; margin-top:1.5rem; flex-wrap:wrap">
    @foreach($posts->getUrlRange(1, $posts->lastPage()) as $page => $url)
        <a href="{{ $url }}" style="padding:0.4rem 0.8rem; border-radius:7px; text-decoration:none; background:{{ $posts->currentPage() == $page ? 'var(--accent)' : 'var(--bg-card)' }}; border:1px solid var(--border); color:{{ $posts->currentPage() == $page ? 'white' : 'var(--text-muted)' }}">{{ $page }}</a>
    @endforeach
</div>
@endif
@endsection
