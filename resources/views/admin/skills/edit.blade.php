@extends('layouts.admin')

@section('title', 'Edit Skill')

@section('content')
<div class="page-header">
    <h1>Edit Skill</h1>
    <a href="{{ route('admin.skills.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

<div style="max-width:600px">
    <form method="POST" action="{{ route('admin.skills.update', $skill->id) }}" class="form-card">
        @csrf @method('PUT')
        
        <div class="form-group">
            <label class="form-label" for="name">Skill Name *</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $skill->name) }}" required>
            @error('name')<span style="color:#ef4444; font-size:0.8rem">{{ $message }}</span>@enderror
        </div>
        
        <div class="form-group">
            <label class="form-label" for="category">Category *</label>
            <input type="text" id="category" name="category" list="category-options" class="form-control" value="{{ old('category', $skill->category) }}" required>
            <datalist id="category-options">
                <option value="Frontend">
                <option value="Backend">
                <option value="Database">
                <option value="Tools & Practices">
                <option value="DevOps & Cloud">
            </datalist>
            @error('category')<span style="color:#ef4444; font-size:0.8rem">{{ $message }}</span>@enderror
        </div>
        
        <div class="form-group">
            <label class="form-label" for="order">Display Order</label>
            <input type="number" id="order" name="order" class="form-control" value="{{ old('order', $skill->order) }}">
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Save Changes
        </button>
    </form>
</div>
@endsection
