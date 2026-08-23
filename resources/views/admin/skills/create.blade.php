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
    <form method="POST" action="{{ route('admin.skills.store') }}" class="form-card">
        @csrf
        <div class="form-group">
            <label class="form-label" for="name">Skill Name *</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" placeholder="e.g. Laravel" required>
            @error('name')<span style="color:#ef4444; font-size:0.8rem">{{ $message }}</span>@enderror
        </div>
        
        <div class="form-group">
            <label class="form-label" for="category">Category *</label>
            <input type="text" id="category" name="category" class="form-control" value="{{ old('category', 'Frontend') }}" placeholder="e.g. Frontend, Backend, Tools" required>
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
@endsection
