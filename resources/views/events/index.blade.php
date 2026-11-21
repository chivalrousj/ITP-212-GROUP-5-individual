@extends('layouts.app')

@section('title', 'Events')
@section('subtitle', 'All school events')

@section('content')

@include('admin.users._style')

    @php $canSeeCounts = auth()->user()->hasRole('Officer', 'Adviser'); @endphp

    <div class="page-bar">
        <div></div>

        {{-- Only Officers can add events --}}
        @if (auth()->user()->hasRole('Officer'))
            <a href="{{ route('officer.events.create') }}" class="btn">+ Add Event</a>
        @endif
    </div>

    <section class="panel">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>TITLE</th>
                        <th>DATE</th>
                        <th>VENUE</th>
                        <th>TYPE</th>
                        <th>AVAILABILITY</th>
                        @if ($canSeeCounts)
                            <th>ATTENDANCE ROWS</th>
                        @endif
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($events as $event)
                        <tr>
                            <td>{{ $event->title }}</td>
                            <td>{{ $event->event_date->format('M d, Y') }}</td>
                            <td>{{ $event->venue }}</td>
                            <td>{{ $event->day_type === 'whole_day' ? 'Whole day' : 'Half day' }}</td>
                            <td>{{ ucfirst($event->availability) }}</td>
                            @if ($canSeeCounts)
                                <td>{{ $event->attendances_count }}</td>
                            @endif
                            <td>
                                <div class="actions">
                                    <a href="{{ route('events.show', $event) }}" class="btn btn-small btn-light">View</a>

                                    @can('update', $event)
                                        <a href="{{ route('officer.events.edit', $event) }}" class="btn btn-small btn-light">Edit</a>
                                    @endcan

                                    {{-- Delete shows only when EventPolicy allows it --}}
                                    @can('delete', $event)
                                        <form method="POST" action="{{ route('officer.events.destroy', $event) }}"
                                              onsubmit="return confirm('Delete this event?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-small btn-danger">Delete</button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $canSeeCounts ? 7 : 6 }}">No events yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($events->hasPages())
            <div class="pager">
                @if ($events->onFirstPage())
                    <span>&laquo; Prev</span>
                @else
                    <a href="{{ $events->previousPageUrl() }}">&laquo; Prev</a>
                @endif

                <span>Page {{ $events->currentPage() }} of {{ $events->lastPage() }}</span>

                @if ($events->hasMorePages())
                    <a href="{{ $events->nextPageUrl() }}">Next &raquo;</a>
                @else
                    <span>Next &raquo;</span>
                @endif
            </div>
        @endif
    </section>

@endsection
