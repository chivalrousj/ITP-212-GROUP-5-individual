@extends('layouts.app')

@section('title', 'Reports')
@section('subtitle', 'Pick an event to open its report')

@section('content')

@include('admin.users._style')

    <style>.panel { width: 100%; }</style>

    <section class="panel">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>EVENT</th>
                        <th>DATE</th>
                        <th>VENUE</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($events as $event)
                        <tr>
                            <td>{{ $event->title }}</td>
                            <td>{{ $event->event_date->format('M d, Y') }}</td>
                            <td>{{ $event->venue }}</td>
                            <td>
                                <a href="{{ route('reports.show', $event) }}" class="btn btn-small">Open Report</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No events yet.</td>
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
