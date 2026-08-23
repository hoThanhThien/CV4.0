@extends('layouts.admin')

@section('title', 'Create Blog Post')

@section('content')
<div class="page-header">
    <h1>Create Blog Post</h1>
    <a href="{{ route('admin.blog.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

<form method="POST" action="{{ route('admin.blog.store') }}" enctype="multipart/form-data">
    @csrf
    <div style="display:grid; grid-template-columns:1fr 320px; gap:1.5rem; align-items:start">
        <!-- Main -->
        <div>
            <div class="form-card">
                <div class="form-group">
                    <label class="form-label" for="title">Post Title *</label>
                    <input type="text" id="title" name="title" class="form-control" value="{{ old('title') }}" placeholder="Enter a compelling title..." required>
                    @error('title')<span style="font-size:0.8rem; color:#ef4444">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="excerpt">Excerpt / Summary</label>
                    <textarea id="excerpt" name="excerpt" class="form-control" style="min-height:80px" placeholder="Short summary shown in blog listing...">{{ old('excerpt') }}</textarea>
                    @error('excerpt')<span style="font-size:0.8rem; color:#ef4444">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="content">Content *</label>
                    <textarea id="content" name="content" class="form-control" style="min-height:400px" placeholder="Write your article here..." required>{{ old('content') }}</textarea>
                    @error('content')<span style="font-size:0.8rem; color:#ef4444">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div>
            <div class="form-card" style="margin-bottom:1.5rem">
                <div class="form-group">
                    <label class="form-label" for="image">Cover Image</label>
                    <input type="file" id="image" name="image" class="form-control" accept="image/*" onchange="previewImage(this)">
                    <div id="image-preview" style="margin-top:0.75rem; display:none">
                        <img id="preview-img" style="width:100%; border-radius:10px; border:1px solid var(--border)">
                    </div>
                </div>
                <div class="form-check">
                    <input type="checkbox" id="published" name="published" value="1" {{ old('published') ? 'checked' : '' }}>
                    <label for="published">Publish immediately</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center">
                <i class="fas fa-save"></i> Save Post
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
