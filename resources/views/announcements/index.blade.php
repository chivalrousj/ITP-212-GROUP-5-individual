@extends('layouts.app')

@section('title', 'Announcements')
@section('subtitle', 'Latest news from the SSG')

@section('content')

@include('admin.users._style')

    <div class="page-bar">
        <span></span>
        <div class="actions">
            @can('manage-ssg')
                <a href="{{ route('officer.announcements.create') }}" class="btn">+ New Announcement</a>
            @endcan
        </div>
    </div>

    <section class="panel">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Preview</th>
                        <th>Posted by</th>
                        <th>Date</th>
                        <th>Attachment</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($announcements as $announcement)
                        <tr>
                            <td><a href="{{ route('announcements.show', $announcement) }}"><strong>{{ $announcement->title }}</strong></a></td>
                            <td>{{ Str::limit($announcement->body, 120) }}</td>
                            <td>{{ $announcement->user?->full_name ?? 'Deleted user' }}</td>
                            <td>{{ $announcement->created_at->format('M d, Y') }}</td>
                            <td>
                                @if ($announcement->attachment)
                                    <a href="{{ asset('storage/' . $announcement->attachment) }}" target="_blank">View file</a>
                                @else
                                    &mdash;
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('announcements.show', $announcement) }}" class="btn btn-small btn-light">Open</a>

                                @can('manage-ssg')
                                    <a href="{{ route('officer.announcements.edit', $announcement) }}" class="btn btn-small">Edit</a>

                                    <form method="POST" action="{{ route('officer.announcements.destroy', $announcement) }}"
                                          style="display:inline;" onsubmit="return confirm('Delete this announcement?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-small btn-danger">Delete</button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6">No announcements yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 14px;">{{ $announcements->links() }}</div>
    </section>
@endsection
