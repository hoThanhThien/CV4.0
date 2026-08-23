@extends('layouts.admin')

@section('title', 'Add Experience')

@section('content')
<div class="page-header">
    <h1>Add Work Experience</h1>
    <a href="{{ route('admin.experiences.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

<div style="max-width:800px">
    <form method="POST" action="{{ route('admin.experiences.store') }}" class="form-card">
        @csrf
        
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem">
            <div class="form-group">
                <label class="form-label" for="company">Company Name *</label>
                <input type="text" id="company" name="company" class="form-control" value="{{ old('company') }}" required>
                @error('company')<span style="color:#ef4444; font-size:0.8rem">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="position">Job Title / Position *</label>
                <input type="text" id="position" name="position" class="form-control" value="{{ old('position') }}" required>
                @error('position')<span style="color:#ef4444; font-size:0.8rem">{{ $message }}</span>@enderror
            </div>
        </div>
        
        <div class="form-group">
            <label class="form-label" for="description">Description *</label>
            <textarea id="description" name="description" class="form-control" style="min-height:120px" required>{{ old('description') }}</textarea>
            @error('description')<span style="color:#ef4444; font-size:0.8rem">{{ $message }}</span>@enderror
        </div>
        
        <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:1rem; align-items:end">
            <div class="form-group">
                <label class="form-label" for="start_date">Start Date *</label>
                <input type="date" id="start_date" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="end_date">End Date</label>
                <input type="date" id="end_date" name="end_date" class="form-control" value="{{ old('end_date') }}">
            </div>
            <div class="form-group">
                <label class="form-label" for="order">Display Order</label>
                <input type="number" id="order" name="order" class="form-control" value="{{ old('order', 0) }}">
            </div>
        </div>

        <div class="form-group">
            <div class="form-check" style="display:flex; align-items:center; gap:0.5rem">
                <input type="checkbox" id="current" name="current" value="1" {{ old('current') ? 'checked' : '' }} onchange="toggleEndDate(this)">
                <label for="current">I currently work here</label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Save Experience
        </button>
    </form>
</div>

<script>
    function toggleEndDate(checkbox) {
        document.getElementById('end_date').disabled = checkbox.checked;
    }
    // Initialize on load
    toggleEndDate(document.getElementById('current'));
</script>
@endsection
