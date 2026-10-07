@extends('layouts.admin')

@section('title', 'Messages')

@section('content')
<div class="page-header">
    <div>
        <h1>Contact Messages</h1>
        <span style="color: var(--text-muted); font-size: 0.9rem">
            Messages received through your portfolio contact form.
            @if($unreadCount > 0)
                <span class="badge badge-danger">{{ $unreadCount }} unread</span>
            @endif
        </span>
    </div>
</div>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th style="width:40px"></th>
                <th>Sender</th>
                <th>Subject</th>
                <th>Date</th>
                <th style="text-align:right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($messages as $msg)
            <tr style="{{ !$msg->is_read ? 'background: rgba(99,102,241,0.03); font-weight:600' : '' }}">
                <td style="text-align:center">
                    @if(!$msg->is_read)
                        <span style="display:inline-block; width:8px; height:8px; border-radius:50%; background:var(--accent)" title="Unread"></span>
                    @endif
                </td>
                <td>
                    <div style="font-size:0.95rem; color:var(--text)">{{ $msg->name }}</div>
                    <div style="font-size:0.8rem; color:var(--text-muted); font-weight:400">
                        <a href="mailto:{{ $msg->email }}" style="color:inherit; text-decoration:none">{{ $msg->email }}</a>
                    </div>
                </td>
                <td>
                    <a href="{{ route('admin.messages.show', $msg->id) }}" style="color:var(--text); text-decoration:none; display:block">
                        <div>{{ $msg->subject ?: '(No Subject)' }}</div>
                        <div style="font-size:0.8rem; color:var(--text-muted); font-weight:400; max-width:400px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis">
                            {{ Str::limit($msg->message, 80) }}
                        </div>
                    </a>
                </td>
                <td style="white-space:nowrap; font-size:0.85rem; color:var(--text-muted); font-weight:400">
                    {{ $msg->created_at->format('d/m/Y H:i') }}
                </td>
                <td style="text-align:right; white-space:nowrap">
                    <a href="{{ route('admin.messages.show', $msg->id) }}" class="btn btn-secondary btn-sm" title="View">
                        <i class="fas fa-eye"></i>
                    </a>
                    <form method="POST" action="{{ route('admin.messages.toggle-read', $msg->id) }}" style="display:inline">
                        @csrf
                        <button type="submit" class="btn btn-secondary btn-sm" title="{{ $msg->is_read ? 'Mark as Unread' : 'Mark as Read' }}">
                            <i class="fas {{ $msg->is_read ? 'fa-envelope' : 'fa-envelope-open' }}"></i>
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.messages.destroy', $msg->id) }}" style="display:inline" onsubmit="return confirm('Are you sure you want to delete this message?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center; color:var(--text-muted); padding:3rem">
                    <i class="fas fa-inbox" style="font-size:2.5rem; opacity:0.3; margin-bottom:0.75rem; display:block"></i>
                    No messages yet.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($messages->hasPages())
    <div style="margin-top:1.5rem">
        {{ $messages->links() }}
    </div>
@endif
@endsection
