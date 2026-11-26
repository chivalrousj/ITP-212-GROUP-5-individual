@extends('layouts.app')

@section('title', $announcement->title)
@section('subtitle', 'Announcement')

@section('content')

@include('admin.users._style')

    <div class="page-bar">
        <a href="{{ route('announcements.index') }}" class="btn btn-light">&larr; Back to Announcements</a>
    </div>

    <section class="panel">
        <div class="panel-head">
            <div>
                <h3>{{ $announcement->title }}</h3>
                <small>
                    Posted by {{ $announcement->user?->full_name ?? 'Deleted user' }}
                    &middot; {{ $announcement->created_at->format('F d, Y') }}
                </small>
            </div>
        </div>

        <p style="font-size: 14px; white-space: pre-line;">{{ $announcement->body }}</p>

        @if ($announcement->attachment)
            <p style="margin-top: 14px;">
                <a href="{{ asset('storage/' . $announcement->attachment) }}" target="_blank" class="btn btn-small">Open attachment</a>
            </p>
        @endif
    </section>
@endsection
