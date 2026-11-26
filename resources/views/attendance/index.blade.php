@extends('layouts.app')

@section('title', 'Attendance')
@section('subtitle', $event->title . ' - ' . $event->event_date->format('M d, Y'))

@section('content')

@include('admin.users._style')

    <style>
        .panel { width: 100%; }
        .row-input { padding: 6px 8px; border: 1px solid #d5e3ed; border-radius: 6px; font-size: 12px; font-family: inherit; color: #17324d; background: white; }
        .add-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); gap: 12px; align-items: end; }
        .add-grid label { display: block; margin-bottom: 4px; font-size: 12px; font-weight: bold; }
        .add-grid select, .add-grid input { width: 100%; padding: 9px 10px; border: 1px solid #d5e3ed; border-radius: 8px; font-size: 13px; font-family: inherit; }
    </style>

    @if ($errors->any())
        <ul class="errors">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <div class="page-bar">
        <a href="{{ route('events.show', $event) }}" class="btn btn-light">&larr; Back to Event</a>

        {{-- Filters: session, institute and program --}}
        <form method="GET" action="{{ route('attendance.index', $event) }}" class="filters">
            <select name="session">
                <option value="">All sessions</option>
                @foreach ($sessions as $session)
                    <option value="{{ $session->id }}" {{ request('session') == $session->id ? 'selected' : '' }}>
                        {{ Str::headline($session->period) }}
                    </option>
                @endforeach
            </select>

            <select name="institute_id" id="filter_institute">
                <option value="">All institutes</option>
                @foreach ($institutes as $institute)
                    <option value="{{ $institute->id }}" {{ (string) $instituteId === (string) $institute->id ? 'selected' : '' }}>{{ $institute->code }}</option>
                @endforeach
            </select>

            <select name="program_id" id="filter_program">
                <option value="">All programs</option>
                @foreach ($programs as $program)
                    <option value="{{ $program->id }}" data-institute="{{ $program->institute_id }}"
                        {{ (string) $programId === (string) $program->id ? 'selected' : '' }}>{{ $program->code }}</option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-light">Filter</button>
            <a href="{{ route('attendance.index', [$event, 'clear' => 1]) }}" class="btn btn-light">Clear Filters</a>
        </form>
    </div>

    {{-- Add a missing record: Officers only --}}
    @can('manage-ssg')
        <section class="panel" style="margin-bottom: 18px;">
            <div class="panel-head">
                <div>
                    <h3>Add a missing record</h3>
                    <small>For a student who was not scanned. A student has only one record for each session.</small>
                </div>
            </div>

            <form method="POST" action="{{ route('officer.attendance.store', $event) }}" class="add-grid">
                @csrf

                <div>
                    <label for="event_session_id">Session</label>
                    <select id="event_session_id" name="event_session_id">
                        @foreach ($sessions as $session)
                            <option value="{{ $session->id }}" {{ old('event_session_id') == $session->id ? 'selected' : '' }}>
                                {{ Str::headline($session->period) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="student_id">Student</label>
                    <select id="student_id" name="student_id">
                        <option value="">Select a student</option>
                        @foreach ($students as $student)
                            <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                {{ $student->student_no }} - {{ $student->user->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="check_in_at">Check-in</label>
                    <input type="datetime-local" id="check_in_at" name="check_in_at" value="{{ old('check_in_at') }}">
                </div>

                <div>
                    <label for="check_out_at">Check-out (optional)</label>
                    <input type="datetime-local" id="check_out_at" name="check_out_at" value="{{ old('check_out_at') }}">
                </div>

                <div>
                    <button type="submit" class="btn">Add Record</button>
                </div>
            </form>
        </section>
    @endcan

    <section class="panel">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>STUDENT NO.</th>
                        <th>NAME</th>
                        <th>PROGRAM</th>
                        <th>INSTITUTE</th>
                        <th>SESSION</th>
                        <th>CHECK-IN</th>
                        <th>CHECK-OUT</th>
                        @can('manage-ssg')
                            <th>ACTIONS</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @forelse ($attendances as $attendance)
                        <tr>
                            <td>{{ $attendance->student->student_no }}</td>
                            <td>{{ $attendance->student->user->full_name }}</td>
                            <td>{{ $attendance->student->program->code }}</td>
                            <td>{{ $attendance->student->program->institute->code }}</td>
                            <td>{{ Str::headline($attendance->session->period) }}</td>

                            @can('manage-ssg')
                                {{-- Officers edit the times in the row. The inputs belong to the Save form below (form="...") --}}
                                <td>
                                    <input type="datetime-local" class="row-input" name="check_in_at"
                                           form="update-{{ $attendance->id }}"
                                           value="{{ $attendance->check_in_at?->format('Y-m-d\TH:i') }}">
                                </td>
                                <td>
                                    <input type="datetime-local" class="row-input" name="check_out_at"
                                           form="update-{{ $attendance->id }}"
                                           value="{{ $attendance->check_out_at?->format('Y-m-d\TH:i') }}">
                                </td>
                                <td>
                                    <div class="actions">
                                        <form id="update-{{ $attendance->id }}" method="POST" action="{{ route('officer.attendance.update', $attendance) }}">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-small">Save</button>
                                        </form>

                                        <form method="POST" action="{{ route('officer.attendance.destroy', $attendance) }}"
                                              onsubmit="return confirm('Delete this attendance record?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-small btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            @else
                                {{-- Advisers only view --}}
                                <td>{{ $attendance->check_in_at ? $attendance->check_in_at->format('M d, h:i A') : '-' }}</td>
                                <td>{{ $attendance->check_out_at ? $attendance->check_out_at->format('M d, h:i A') : '-' }}</td>
                            @endcan
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">No attendance records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($attendances->hasPages())
            <div class="pager">
                @if ($attendances->onFirstPage())
                    <span>&laquo; Prev</span>
                @else
                    <a href="{{ $attendances->previousPageUrl() }}">&laquo; Prev</a>
                @endif

                <span>Page {{ $attendances->currentPage() }} of {{ $attendances->lastPage() }}</span>

                @if ($attendances->hasMorePages())
                    <a href="{{ $attendances->nextPageUrl() }}">Next &raquo;</a>
                @else
                    <span>Next &raquo;</span>
                @endif
            </div>
        @endif
    </section>

    {{-- The same script the Officer's student page uses: the program list follows the institute --}}
    @include('officer.students._script')
    <script>linkSelects('filter_institute', 'filter_program');</script>

@endsection
