@extends('layouts.app')

@section('title', $event->title)
@section('subtitle', 'Event details and sessions')

@section('content')

@include('admin.users._style')

    @php $isStudent = auth()->user()->hasRole('Student'); @endphp

    {{-- A Student account with no linked student record cannot check in --}}
    @if ($isStudent && !$student)
        <div class="flash flash-error">Your account has no student record yet. Please ask an Officer to register you.</div>
    @endif

    <div class="page-bar">
        <a href="{{ route('events.index') }}" class="btn btn-light">&larr; Back to Events</a>

        <div class="actions">
            {{-- Officers and Advisers can open the attendance records --}}
            @can('view-reports')
                <a href="{{ route('attendance.index', $event) }}" class="btn btn-light">Attendance</a>
                <a href="{{ route('penalties.index', $event) }}" class="btn btn-light">Fines</a>
                <a href="{{ route('reports.show', $event) }}" class="btn btn-light">Report</a>
            @endcan

            @can('update', $event)
                <a href="{{ route('officer.events.edit', $event) }}" class="btn">Edit Event</a>
            @endcan

            @can('delete', $event)
                <form method="POST" action="{{ route('officer.events.destroy', $event) }}"
                      onsubmit="return confirm('Delete this event?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            @endcan
        </div>
    </div>

    <section class="panel">
        <div class="panel-head">
            <div>
                <h3>{{ $event->title }}</h3>
                <small>{{ $event->event_date->format('F d, Y') }} &middot; {{ $event->venue }}</small>
            </div>
        </div>

        @if ($event->description)
            <p style="margin-bottom: 14px; font-size: 14px;">{{ $event->description }}</p>
        @endif

        <table>
            <tbody>
                <tr><td><strong>Day type</strong></td><td>{{ Str::headline($event->day_type) }}</td></tr>
                <tr><td><strong>Availability</strong></td><td>{{ Str::headline($event->availability) }}</td></tr>
                <tr><td><strong>Fine per missed scan</strong></td><td>&#8369;{{ number_format($event->fine_per_miss, 2) }}</td></tr>
                <tr><td><strong>Created by</strong></td><td>{{ $event->creator?->full_name ?? 'Deleted user' }}</td></tr>
            </tbody>
        </table>
    </section>

    <section class="panel" style="margin-top: 18px;">
        <div class="panel-head">
            <div>
                <h3>Sessions</h3>
                <small>{{ $event->day_type === 'whole_day' ? '4 scans are required (2 sessions)' : '2 scans are required (1 session)' }}</small>
            </div>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>SESSION</th>
                        <th>CHECK-IN</th>
                        <th>CHECK-OUT</th>
                        @if ($isStudent)
                            <th>MY ATTENDANCE</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($event->sessions as $session)
                        <tr>
                            <td>{{ Str::headline($session->period) }}</td>

                            {{-- CHECK-IN: the window (or Manual), the status now, and Open/Close for Officers --}}
                            <td>
                                @if ($event->availability === 'scheduled')
                                    {{ substr($session->check_in_start, 0, 5) }} to {{ substr($session->check_in_end, 0, 5) }}
                                @else
                                    Manual
                                @endif

                                @if ($session->isCheckInOpen())
                                    <span class="status ok">Open</span>
                                @else
                                    <span class="status off">Closed</span>
                                @endif

                                @can('manage-ssg')
                                    @if ($event->availability === 'manual')
                                        <form method="POST" action="{{ route('officer.sessions.toggle', $session) }}" style="display:inline;">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="type" value="check_in">
                                            <button type="submit" class="btn btn-small btn-light">
                                                {{ $session->check_in_open ? 'Close' : 'Open' }}
                                            </button>
                                        </form>
                                    @endif
                                @endcan
                            </td>

                            {{-- CHECK-OUT: same as check-in --}}
                            <td>
                                @if ($event->availability === 'scheduled')
                                    {{ substr($session->check_out_start, 0, 5) }} to {{ substr($session->check_out_end, 0, 5) }}
                                @else
                                    Manual
                                @endif

                                @if ($session->isCheckOutOpen())
                                    <span class="status ok">Open</span>
                                @else
                                    <span class="status off">Closed</span>
                                @endif

                                @can('manage-ssg')
                                    @if ($event->availability === 'manual')
                                        <form method="POST" action="{{ route('officer.sessions.toggle', $session) }}" style="display:inline;">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="type" value="check_out">
                                            <button type="submit" class="btn btn-small btn-light">
                                                {{ $session->check_out_open ? 'Close' : 'Open' }}
                                            </button>
                                        </form>
                                    @endif
                                @endcan
                            </td>

                            {{-- MY ATTENDANCE: only for Students. Shows my times and only the buttons that are open now. --}}
                            @if ($isStudent)
                                <td>
                                    @php $mine = $myAttendances->get($session->id); @endphp

                                    @if ($student)
                                        <div style="font-size: 12px; margin-bottom: 6px;">
                                            In: {{ $mine?->check_in_at ? $mine->check_in_at->format('M d, h:i A') : '-' }}<br>
                                            Out: {{ $mine?->check_out_at ? $mine->check_out_at->format('M d, h:i A') : '-' }}
                                        </div>

                                        @if ($session->isCheckInOpen() && !$mine?->check_in_at)
                                            <form method="POST" action="{{ route('student.attendance.check-in', $session) }}" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-small">Check in</button>
                                            </form>
                                        @endif

                                        @if ($session->isCheckOutOpen() && $mine?->check_in_at && !$mine?->check_out_at)
                                            <form method="POST" action="{{ route('student.attendance.check-out', $session) }}" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-small">Check out</button>
                                            </form>
                                        @endif
                                    @else
                                        No student record
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

@endsection
