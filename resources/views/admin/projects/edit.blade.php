@extends('layouts.admin')

@section('title', 'Edit Project')

@section('content')
<div class="page-header">
    <h1>Edit Project</h1>
    <div style="display:flex; gap:0.5rem">
        <a href="{{ route('projects.show', $project->id) }}" target="_blank" class="btn btn-secondary">
            <i class="fas fa-eye"></i> Preview
        </a>
        <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<form method="POST" action="{{ route('admin.projects.update', $project->id) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div style="display:grid; grid-template-columns:1fr 350px; gap:1.5rem; align-items:start">
        <!-- Main fields -->
        <div>
            <div class="form-card" style="margin-bottom:1.5rem">
                <div class="form-group">
                    <label class="form-label" for="title">Project Title *</label>
                    <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $project->title) }}" required>
                    @error('title')<span style="font-size:0.8rem; color:#ef4444">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="description">Description *</label>
                    <textarea id="description" name="description" class="form-control" required>{{ old('description', $project->description) }}</textarea>
                    @error('description')<span style="font-size:0.8rem; color:#ef4444">{{ $message }}</span>@enderror
                </div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem">
                    <div class="form-group">
                        <label class="form-label" for="github_url">GitHub URL</label>
                        <input type="url" id="github_url" name="github_url" class="form-control" value="{{ old('github_url', $project->github_url) }}" placeholder="https://github.com/...">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="demo_url">Demo URL</label>
                        <input type="url" id="demo_url" name="demo_url" class="form-control" value="{{ old('demo_url', $project->demo_url) }}" placeholder="https://...">
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div>
            <div class="form-card" style="margin-bottom:1.5rem">
                <div class="form-group">
                    <label class="form-label">Current Image</label>
                    @if($project->image)
                        <img src="{{ asset('storage/' . $project->image) }}" style="width:100%; border-radius:10px; border:1px solid var(--border); margin-bottom:0.75rem">
                    @else
                        <div style="background:rgba(255,255,255,0.04); border-radius:10px; height:120px; display:flex; align-items:center; justify-content:center; color:var(--text-muted); margin-bottom:0.75rem; border:1px solid var(--border)">
                            No image
                        </div>
                    @endif
                    <label class="form-label" for="image">Replace Image</label>
                    <input type="file" id="image" name="image" class="form-control" accept="image/*" onchange="previewImage(this)">
                    <div id="image-preview" style="margin-top:0.75rem; display:none">
                        <img id="preview-img" style="width:100%; border-radius:10px; border:1px solid var(--border)">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label" for="order">Display Order</label>
                    <input type="number" id="order" name="order" class="form-control" value="{{ old('order', $project->order) }}" min="0">
                </div>
                <div class="form-check">
                    <input type="checkbox" id="featured" name="featured" value="1" {{ old('featured', $project->featured) ? 'checked' : '' }}>
                    <label for="featured">⭐ Featured project</label>
                </div>
            </div>

            @if($technologies->count())
            <div class="form-card" style="margin-bottom:1.5rem">
                <label class="form-label">Technologies</label>
                <div style="display:flex; flex-wrap:wrap; gap:0.5rem">
                    @foreach($technologies as $tech)
                    @php $isSelected = in_array($tech->id, old('technologies', $selectedTechnologies)); @endphp
                    <label style="display:flex; align-items:center; gap:0.4rem; padding:0.3rem 0.6rem; border-radius:8px; border:1px solid var(--border); cursor:pointer; font-size:0.85rem; transition: all 0.2s; user-select:none {{ $isSelected ? '; background:rgba(124,58,237,0.15); border-color:var(--accent); color:var(--accent-light)' : '' }}"
                        class="{{ $isSelected ? 'active-tech' : '' }}"
                        onclick="this.classList.toggle('active-tech')">
                        <input type="checkbox" name="technologies[]" value="{{ $tech->id }}"
                            {{ $isSelected ? 'checked' : '' }}
                            style="display:none">
                        {{ $tech->name }}
                    </label>
                    @endforeach
                </div>
            </div>
            @endif

            <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center">
                <i class="fas fa-save"></i> Save Changes
            </button>
        </div>
    </div>
</form>

<style>
    label.active-tech { background: rgba(124,58,237,0.15) !important; border-color: var(--accent) !important; color: var(--accent-light) !important; }
</style>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const preview = document.getElementById('image-preview');
            const img = document.getElementById('preview-img');
            const reader = new FileReader();
            reader.onload = e => { img.src = e.target.result; preview.style.display = 'block'; };
            reader.readAsDataURL(input.files[0]);
        }
    }
    document.querySelectorAll('input[name="technologies[]"]').forEach(cb => {
        const label = cb.closest('label');
        cb.addEventListener('change', () => label.classList.toggle('active-tech', cb.checked));
    });
</script>
@endsection
