@extends('layouts.admin')

@section('title', 'Message from ' . $message->name)

@section('content')
<div class="page-header">
    <div>
        <h1>Message Details</h1>
        <span style="color: var(--text-muted); font-size: 0.9rem">Received on {{ $message->created_at->format('d/m/Y H:i:s') }}</span>
    </div>
    <div style="display:flex; gap:0.5rem">
        <a href="mailto:{{ $message->email }}?subject=Re: {{ rawurlencode($message->subject ?: 'Your inquiry') }}" class="btn btn-primary">
            <i class="fas fa-reply"></i> Reply via Email
        </a>
        <a href="{{ route('admin.messages.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="form-card" style="max-width:800px">
    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.25rem; padding-bottom:1.5rem; border-bottom:1px solid var(--border); margin-bottom:1.5rem">
        <div>
            <div style="font-size:0.8rem; color:var(--text-muted); text-transform:uppercase; font-weight:700">Sender</div>
            <div style="font-size:1.1rem; font-weight:700; margin-top:0.25rem">{{ $message->name }}</div>
            <a href="mailto:{{ $message->email }}" style="color:var(--accent); font-size:0.95rem; text-decoration:none">{{ $message->email }}</a>
        </div>
        <div>
            <div style="font-size:0.8rem; color:var(--text-muted); text-transform:uppercase; font-weight:700">Metadata</div>
            <div style="font-size:0.9rem; color:var(--text-muted); margin-top:0.25rem">
                IP: <code>{{ $message->ip_address ?: 'Unknown' }}</code>
            </div>
            <div style="font-size:0.9rem; color:var(--text-muted); margin-top:0.25rem">
                Status:
                @if($message->is_read)
                    <span class="badge badge-success">Read</span>
                @else
                    <span class="badge badge-danger">Unread</span>
                @endif
            </div>
        </div>
    </div>

    <div style="margin-bottom:1.5rem">
        <div style="font-size:0.8rem; color:var(--text-muted); text-transform:uppercase; font-weight:700; margin-bottom:0.35rem">Subject</div>
        <div style="font-size:1.15rem; font-weight:700; color:var(--text)">
            {{ $message->subject ?: '(No Subject)' }}
        </div>
    </div>

    <div>
        <div style="font-size:0.8rem; color:var(--text-muted); text-transform:uppercase; font-weight:700; margin-bottom:0.5rem">Message Content</div>
        <div style="background:var(--bg); border:1px solid var(--border); border-radius:12px; padding:1.25rem; font-size:0.95rem; line-height:1.7; white-space:pre-wrap; color:var(--text)">{{ $message->message }}</div>
    </div>

    <div style="margin-top:2rem; display:flex; justify-content:space-between; align-items:center; padding-top:1.5rem; border-top:1px solid var(--border)">
        <form method="POST" action="{{ route('admin.messages.toggle-read', $message->id) }}">
            @csrf
            <button type="submit" class="btn btn-secondary">
                <i class="fas {{ $message->is_read ? 'fa-envelope' : 'fa-envelope-open' }}"></i>
                Mark as {{ $message->is_read ? 'Unread' : 'Read' }}
            </button>
        </form>

        <form method="POST" action="{{ route('admin.messages.destroy', $message->id) }}" onsubmit="return confirm('Delete this message permanently?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash"></i> Delete Message
            </button>
        </form>
    </div>
</div>
@endsection
