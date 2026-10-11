@extends('layouts.admin')

@section('title', 'Add Skill')

@section('content')
<div class="page-header">
    <h1>Add Skill</h1>
    <a href="{{ route('admin.skills.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

<div style="max-width:600px">
    <form method="POST" action="{{ route('admin.skills.store') }}" class="form-card" id="skill-form">
        @csrf
        <div class="form-group">
            <label class="form-label" for="name">Skill Name *</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" placeholder="e.g. Laravel" required>
            @error('name')<span style="color:#ef4444; font-size:0.8rem">{{ $message }}</span>@enderror
        </div>
        
        @php
            $currentCategory = old('category', 'Frontend');
            $categoriesList = $categories ?? collect(['Frontend', 'Backend', 'Database', 'Tools & Practices', 'DevOps & Cloud']);
            $isCustom = !in_array($currentCategory, $categoriesList->toArray()) && !empty($currentCategory);
        @endphp

        <div class="form-group">
            <label class="form-label" for="category_select">Category *</label>
            <select id="category_select" class="form-control" style="cursor:pointer;" onchange="handleCategoryChange(this.value)">
                @foreach($categoriesList as $cat)
                    <option value="{{ $cat }}" {{ $currentCategory === $cat ? 'selected' : '' }}>
                        {{ $cat }}
                    </option>
                @endforeach
                <option value="__custom__" {{ $isCustom ? 'selected' : '' }}>
                    + Enter new category (Nhập loại mới)...
                </option>
            </select>
            
            <div id="custom_category_group" style="margin-top:0.6rem; display: {{ $isCustom ? 'block' : 'none' }};">
                <input type="text" id="custom_category_input" class="form-control" 
                    placeholder="e.g. Mobile, Testing, Security..." 
                    value="{{ $isCustom ? $currentCategory : '' }}"
                    oninput="syncCategoryInput(this.value)">
                <small style="color:var(--text-muted); font-size:0.78rem; margin-top:0.25rem; display:block">
                    Enter your custom category name above.
                </small>
            </div>

            <input type="hidden" id="category" name="category" value="{{ $currentCategory }}">
            @error('category')<span style="color:#ef4444; font-size:0.8rem">{{ $message }}</span>@enderror
        </div>
        
        <div class="form-group">
            <label class="form-label" for="order">Display Order</label>
            <input type="number" id="order" name="order" class="form-control" value="{{ old('order', 0) }}">
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Save Skill
        </button>
    </form>
</div>

<script>
    function handleCategoryChange(val) {
        const customGroup = document.getElementById('custom_category_group');
        const customInput = document.getElementById('custom_category_input');
        const hiddenCategory = document.getElementById('category');

        if (val === '__custom__') {
            customGroup.style.display = 'block';
            customInput.focus();
            hiddenCategory.value = customInput.value.trim();
        } else {
            customGroup.style.display = 'none';
            hiddenCategory.value = val;
        }
    }

    function syncCategoryInput(val) {
        document.getElementById('category').value = val.trim();
    }

    document.getElementById('skill-form').addEventListener('submit', function(e) {
        const sel = document.getElementById('category_select');
        const customInput = document.getElementById('custom_category_input');
        const hiddenCategory = document.getElementById('category');

        if (sel.value === '__custom__') {
            const val = customInput.value.trim();
            if (!val) {
                e.preventDefault();
                alert('Please enter a custom category name (Vui lòng nhập tên loại category mới).');
                customInput.focus();
                return false;
            }
            hiddenCategory.value = val;
        } else {
            hiddenCategory.value = sel.value;
        }
    });
</script>
@endsection
