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
                    <textarea id="description" name="description" class="form-control" style="min-height:200px; line-height:1.6" required>{{ old('description', $project->description) }}</textarea>
                    <span style="font-size:0.8rem; color:var(--text-muted); display:block; margin-top:0.35rem">
                        <i class="fas fa-info-circle"></i> Supports multiple lines, bullet points (e.g. <code>+</code> or <code>-</code>) and sections. Preserved cleanly on public pages.
                    </span>
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

            <div class="form-card" style="margin-bottom:1.5rem">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.75rem">
                    <label class="form-label" style="margin-bottom:0">Technologies</label>
                    <a href="{{ route('admin.technologies.index') }}" target="_blank" style="font-size:0.78rem; color:var(--accent-light); text-decoration:none">Manage →</a>
                </div>

                <div id="tech-container" style="display:flex; flex-wrap:wrap; gap:0.5rem; max-height:220px; overflow-y:auto; padding-bottom:0.5rem">
                    @foreach($technologies as $tech)
                    @php $isSelected = in_array($tech->id, old('technologies', $selectedTechnologies)); @endphp
                    <label class="tech-tag-label {{ $isSelected ? 'active-tech' : '' }}">
                        <input type="checkbox" name="technologies[]" value="{{ $tech->id }}"
                            {{ $isSelected ? 'checked' : '' }}
                            style="display:none">
                        {{ $tech->name }}
                    </label>
                    @endforeach
                </div>

                <!-- Quick Add Tech -->
                <div style="margin-top:0.75rem; padding-top:0.75rem; border-top:1px solid var(--border)">
                    <div style="font-size:0.78rem; color:var(--text-muted); margin-bottom:0.35rem; font-weight:600">+ Quick Add Technology:</div>
                    <div style="display:flex; gap:0.4rem">
                        <input type="text" id="quick-tech-name" class="form-control" style="padding:0.4rem 0.65rem; font-size:0.85rem" placeholder="e.g. Laravel, Docker" onkeydown="if(event.key==='Enter'){event.preventDefault();quickAddTech();}">
                        <button type="button" class="btn btn-secondary btn-sm" onclick="quickAddTech()" style="white-space:nowrap">
                            <i class="fas fa-plus"></i> Add
                        </button>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center">
                <i class="fas fa-save"></i> Save Changes
            </button>
        </div>
    </div>
</form>

<style>
    .tech-tag-label {
        display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.35rem 0.7rem;
        border-radius: 8px; border: 1px solid var(--border); cursor: pointer;
        font-size: 0.85rem; transition: all 0.2s; user-select: none; background: var(--bg);
    }
    .tech-tag-label:hover { border-color: var(--accent); }
    .tech-tag-label.active-tech {
        background: rgba(124,58,237,0.15) !important;
        border-color: var(--accent) !important;
        color: var(--accent-light) !important;
        font-weight: 600;
    }
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

    // Attach change listener to all tech checkboxes
    function bindTechCheckboxes() {
        document.querySelectorAll('#tech-container input[type="checkbox"]').forEach(cb => {
            const label = cb.closest('label');
            if (cb.checked) label.classList.add('active-tech');
            cb.onchange = () => label.classList.toggle('active-tech', cb.checked);
        });
    }
    bindTechCheckboxes();

    // Quick Add Technology via Ajax
    function quickAddTech() {
        const input = document.getElementById('quick-tech-name');
        const name = input.value.trim();
        if (!name) return;

        fetch("{{ route('admin.technologies.quick') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ name: name })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success && data.technology) {
                const container = document.getElementById('tech-container');
                let existing = container.querySelector(`input[value="${data.technology.id}"]`);
                if (!existing) {
                    const label = document.createElement('label');
                    label.className = 'tech-tag-label active-tech';
                    label.innerHTML = `<input type="checkbox" name="technologies[]" value="${data.technology.id}" checked style="display:none"> ${data.technology.name}`;
                    container.appendChild(label);
                } else {
                    existing.checked = true;
                    existing.closest('label').classList.add('active-tech');
                }
                bindTechCheckboxes();
                input.value = '';
            }
        })
        .catch(err => {
            console.error(err);
            alert('Failed to add technology. Please try again.');
        });
    }
</script>
@endsection
