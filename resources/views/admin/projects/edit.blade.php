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
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.35rem">
                    <label class="form-label" style="margin-bottom:0">Skills</label>
                    <a href="{{ route('admin.skills.index') }}" target="_blank" style="font-size:0.78rem; color:var(--accent-light); text-decoration:none">Manage Skills →</a>
                </div>
                <p style="font-size:0.75rem; color:var(--text-muted); margin-bottom:0.75rem">
                    Select skills applied in this project.
                </p>

                <!-- Search filter -->
                <div style="margin-bottom:0.6rem">
                    <input type="text" id="skill-search" class="form-control" style="padding:0.35rem 0.65rem; font-size:0.82rem" placeholder="🔍 Search skill..." oninput="filterSkillList()">
                </div>

                <!-- Category filter tabs -->
                @php
                    $uniqueCategories = $skills->pluck('category')->filter()->unique()->values();
                @endphp
                <div style="display:flex; gap:0.35rem; flex-wrap:wrap; margin-bottom:0.75rem">
                    <button type="button" class="cat-filter-btn active-cat" onclick="setCategoryFilter('all', this)">All</button>
                    @foreach($uniqueCategories as $cat)
                        <button type="button" class="cat-filter-btn" onclick="setCategoryFilter('{{ $cat }}', this)">{{ $cat }}</button>
                    @endforeach
                </div>

                <div id="skill-container" style="display:flex; flex-wrap:wrap; gap:0.45rem; max-height:220px; overflow-y:auto; padding-bottom:0.5rem">
                    @foreach($skills as $skill)
                    @php
                        $isSelected = in_array($skill->id, old('skills', $selectedSkills));
                        $categoryName = $skill->category ?: 'Other';
                    @endphp
                    <label class="tech-tag-label {{ $isSelected ? 'active-tech' : '' }}"
                           data-category="{{ $categoryName }}"
                           data-name="{{ $skill->name }}">
                        <input type="checkbox" name="skills[]" value="{{ $skill->id }}"
                            {{ $isSelected ? 'checked' : '' }}
                            style="display:none">
                        {{ $skill->name }}
                    </label>
                    @endforeach
                </div>

                <!-- Quick Add Skill -->
                <div style="margin-top:0.75rem; padding-top:0.75rem; border-top:1px solid var(--border)">
                    <div style="font-size:0.78rem; color:var(--text-muted); margin-bottom:0.35rem; font-weight:600">+ Quick Add Skill:</div>
                    <div style="display:flex; gap:0.4rem">
                        <input type="text" id="quick-skill-name" class="form-control" style="padding:0.4rem 0.65rem; font-size:0.85rem" placeholder="e.g. Next.js, Redis" onkeydown="if(event.key==='Enter'){event.preventDefault();quickAddSkill();}">
                        <button type="button" class="btn btn-secondary btn-sm" onclick="quickAddSkill()" style="white-space:nowrap">
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
    .cat-filter-btn {
        background: none; border: 1px solid var(--border); border-radius: 6px;
        font-size: 0.72rem; padding: 0.2rem 0.5rem; color: var(--text-muted);
        cursor: pointer; transition: all 0.2s; font-weight: 500;
    }
    .cat-filter-btn:hover {
        border-color: var(--accent); color: var(--text);
    }
    .cat-filter-btn.active-cat {
        background: var(--accent); border-color: var(--accent); color: #fff;
    }
    .tech-tag-label {
        display: inline-flex; align-items: center; padding: 0.3rem 0.65rem;
        background: var(--card-bg, #1e2230); border: 1px solid var(--border);
        border-radius: 999px; font-size: 0.78rem; font-weight: 500; cursor: pointer;
        transition: all 0.15s ease; user-select: none; color: var(--text-muted);
    }
    .tech-tag-label:hover {
        border-color: var(--accent); color: var(--text);
    }
    .tech-tag-label.active-tech {
        background: rgba(99, 102, 241, 0.2); border-color: var(--accent);
        color: var(--accent-light, #a5b4fc); font-weight: 600;
    }
</style>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-img').src = e.target.result;
                document.getElementById('image-preview').style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Toggle active state on skill labels
    function bindSkillCheckboxes() {
        document.querySelectorAll('#skill-container .tech-tag-label').forEach(label => {
            const input = label.querySelector('input[type="checkbox"]');
            label.onclick = function(e) {
                if (e.target !== input) {
                    input.checked = !input.checked;
                }
                label.classList.toggle('active-tech', input.checked);
            };
        });
    }
    bindSkillCheckboxes();

    // Category filter logic
    let currentCategoryFilter = 'all';
    function setCategoryFilter(category, btn) {
        currentCategoryFilter = category;
        document.querySelectorAll('.cat-filter-btn').forEach(b => b.classList.remove('active-cat'));
        btn.classList.add('active-cat');
        filterSkillList();
    }

    // Filter skill pills based on search & category
    function filterSkillList() {
        const searchVal = (document.getElementById('skill-search')?.value || '').toLowerCase().trim();
        document.querySelectorAll('#skill-container .tech-tag-label').forEach(label => {
            const cat = label.getAttribute('data-category') || 'Other';
            const name = (label.getAttribute('data-name') || '').toLowerCase();
            
            const matchesCategory = (currentCategoryFilter === 'all') || (cat === currentCategoryFilter);
            const matchesSearch = !searchVal || name.includes(searchVal);
            
            label.style.display = (matchesCategory && matchesSearch) ? 'inline-flex' : 'none';
        });
    }

    // Quick Add Skill via Ajax
    function quickAddSkill() {
        const input = document.getElementById('quick-skill-name');
        const name = input.value.trim();
        if (!name) return;

        const category = (currentCategoryFilter !== 'all') ? currentCategoryFilter : 'Backend';

        fetch("{{ route('admin.skills.quick') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ name: name, category: category })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success && data.skill) {
                const container = document.getElementById('skill-container');
                let existing = container.querySelector(`input[value="${data.skill.id}"]`);
                if (!existing) {
                    const label = document.createElement('label');
                    label.className = 'tech-tag-label active-tech';
                    label.setAttribute('data-category', data.skill.category || 'Other');
                    label.setAttribute('data-name', data.skill.name);
                    label.innerHTML = `<input type="checkbox" name="skills[]" value="${data.skill.id}" checked style="display:none"> ${data.skill.name}`;
                    container.appendChild(label);
                } else {
                    existing.checked = true;
                    existing.closest('label').classList.add('active-tech');
                }
                bindSkillCheckboxes();
                filterSkillList();
                input.value = '';
            }
        })
        .catch(err => {
            console.error(err);
            alert('Failed to add skill. Please try again.');
        });
    }
</script>
@endsection
