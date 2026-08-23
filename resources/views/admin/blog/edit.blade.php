@extends('layouts.admin')

@section('title', 'Edit Blog Post')

@section('content')
<div class="page-header">
    <h1>Edit Blog Post</h1>
    <div style="display:flex; gap:0.5rem">
        @if($post->published)
        <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="btn btn-secondary">
            <i class="fas fa-eye"></i> Preview
        </a>
        @endif
        <a href="{{ route('admin.blog.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<form method="POST" action="{{ route('admin.blog.update', $post->id) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div style="display:grid; grid-template-columns:1fr 320px; gap:1.5rem; align-items:start">
        <!-- Main -->
        <div>
            <div class="form-card">
                <div class="form-group">
                    <label class="form-label" for="title">Post Title *</label>
                    <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $post->title) }}" required>
                    @error('title')<span style="font-size:0.8rem; color:#ef4444">{{ $message }}</span>@enderror
                </div>
                <div style="margin-bottom:1.25rem; padding:0.75rem 1rem; border-radius:10px; background:rgba(255,255,255,0.03); border:1px solid var(--border)">
                    <div style="font-size:0.78rem; color:var(--text-muted)">Slug: <code style="color:var(--accent-light)">/{{ $post->slug }}</code></div>
                </div>
                <div class="form-group">
                    <label class="form-label" for="excerpt">Excerpt / Summary</label>
                    <textarea id="excerpt" name="excerpt" class="form-control" style="min-height:80px">{{ old('excerpt', $post->excerpt) }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label" for="content">Content *</label>
                    <textarea id="content" name="content" class="form-control" style="min-height:400px" required>{{ old('content', $post->content) }}</textarea>
                    @error('content')<span style="font-size:0.8rem; color:#ef4444">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div>
            <div class="form-card" style="margin-bottom:1.5rem">
                <div class="form-group">
                    <label class="form-label">Current Cover Image</label>
                    @if($post->image)
                        <img src="{{ asset('storage/' . $post->image) }}" style="width:100%; border-radius:10px; border:1px solid var(--border); margin-bottom:0.75rem">
                    @else
                        <div style="background:rgba(255,255,255,0.04); border-radius:10px; height:100px; display:flex; align-items:center; justify-content:center; color:var(--text-muted); margin-bottom:0.75rem; border:1px solid var(--border); font-size:0.88rem">
                            No image
                        </div>
                    @endif
                    <label class="form-label" for="image">Replace Cover</label>
                    <input type="file" id="image" name="image" class="form-control" accept="image/*" onchange="previewImage(this)">
                    <div id="image-preview" style="margin-top:0.75rem; display:none">
                        <img id="preview-img" style="width:100%; border-radius:10px; border:1px solid var(--border)">
                    </div>
                </div>
                <div class="form-check">
                    <input type="checkbox" id="published" name="published" value="1" {{ old('published', $post->published) ? 'checked' : '' }}>
                    <label for="published">Published</label>
                </div>
                @if($post->published_at)
                <div style="margin-top:0.75rem; font-size:0.8rem; color:var(--text-muted)">
                    <i class="fas fa-calendar"></i> Published {{ $post->published_at->format('M d, Y') }}
                </div>
                @endif
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center">
                <i class="fas fa-save"></i> Save Changes
            </button>
        </div>
    </div>
</form>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('preview-img').src = e.target.result;
                document.getElementById('image-preview').style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
