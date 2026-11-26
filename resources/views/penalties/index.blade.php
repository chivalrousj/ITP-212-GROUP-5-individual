@extends('layouts.app')

@section('title', 'Fines and Sanctions')
@section('subtitle', $event->title . ' - ' . $event->event_date->format('M d, Y'))

@section('content')

@include('admin.users._style')

    <style>
        .panel { width: 100%; }
        .row-input { padding: 6px 8px; border: 1px solid #d5e3ed; border-radius: 6px; font-size: 12px; font-family: inherit; color: #17324d; background: white; width: 100%; }
        .total-box { margin-top: 14px; text-align: right; font-size: 15px; color: #103b60; }
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

        <div class="actions">
            {{-- Only Officers generate the fines --}}
            @can('manage-ssg')
                <form method="POST" action="{{ route('officer.penalties.generate', $event) }}"
                      onsubmit="return confirm('Generate the fines of all students for this event? The sanction and status you typed are kept.');">
                    @csrf
                    <button type="submit" class="btn">Generate / Regenerate Fines</button>
                </form>
            @endcan
        </div>
    </div>

    {{-- Filters: institute, program and status --}}
    <form method="GET" action="{{ route('penalties.index', $event) }}" class="filters" style="margin-bottom: 16px;">
        <select name="institute" id="filter_institute">
            <option value="">All institutes</option>
            @foreach ($institutes as $institute)
                <option value="{{ $institute->id }}" {{ request('institute') == $institute->id ? 'selected' : '' }}>{{ $institute->code }}</option>
            @endforeach
        </select>

        <select name="program" id="filter_program">
            <option value="">All programs</option>
            @foreach ($programs as $program)
                <option value="{{ $program->id }}" data-institute="{{ $program->institute_id }}"
                    {{ request('program') == $program->id ? 'selected' : '' }}>{{ $program->code }}</option>
            @endforeach
        </select>

        <select name="status">
            <option value="">All status</option>
            @foreach (['unpaid', 'paid', 'served'] as $status)
                <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
            @endforeach
        </select>

        <button type="submit" class="btn btn-light">Filter</button>
    </form>

    <section class="panel">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>STUDENT NO.</th>
                        <th>NAME</th>
                        <th>PROGRAM</th>
                        <th>INSTITUTE</th>
                        <th>MISSED</th>
                        <th>AMOUNT</th>
                        <th>SANCTION</th>
                        <th>STATUS</th>
                        @can('manage-ssg')
                            <th>ACTIONS</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @forelse ($penalties as $penalty)
                        <tr>
                            <td>{{ $penalty->student->student_no }}</td>
                            <td>{{ $penalty->student->user->full_name }}</td>
                            <td>{{ $penalty->student->program->code }}</td>
                            <td>{{ $penalty->student->program->institute->code }}</td>
                            <td>{{ $penalty->missed_scans }}</td>

                            @can('manage-ssg')
                                {{-- Officers edit in the row. The inputs belong to the Save form (form="...") --}}
                                <td><input type="number" step="0.01" min="0" class="row-input" name="amount" form="pen-{{ $penalty->id }}" value="{{ $penalty->amount }}"></td>
                                <td><input type="text" class="row-input" name="sanction" form="pen-{{ $penalty->id }}" value="{{ $penalty->sanction }}" placeholder="e.g. 2 hours community service"></td>
                                <td>
                                    <select class="row-input" name="status" form="pen-{{ $penalty->id }}">
                                        @foreach (['unpaid', 'paid', 'served'] as $status)
                                            <option value="{{ $status }}" {{ $penalty->status === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <div class="actions">
                                        <form id="pen-{{ $penalty->id }}" method="POST" action="{{ route('officer.penalties.update', $penalty) }}">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-small">Save</button>
                                        </form>

                                        <form method="POST" action="{{ route('officer.penalties.destroy', $penalty) }}"
                                              onsubmit="return confirm('Delete this penalty?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-small btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            @else
                                {{-- Advisers only view --}}
                                <td>&#8369;{{ number_format($penalty->amount, 2) }}</td>
                                <td>{{ $penalty->sanction ?: '-' }}</td>
                                <td>{{ ucfirst($penalty->status) }}</td>
                            @endcan
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9">No penalties yet. An Officer can generate them.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="total-box"><strong>Total fines: &#8369;{{ number_format($total, 2) }}</strong></div>

        @if ($penalties->hasPages())
            <div class="pager">
                @if ($penalties->onFirstPage())
                    <span>&laquo; Prev</span>
                @else
                    <a href="{{ $penalties->previousPageUrl() }}">&laquo; Prev</a>
                @endif

                <span>Page {{ $penalties->currentPage() }} of {{ $penalties->lastPage() }}</span>

                @if ($penalties->hasMorePages())
                    <a href="{{ $penalties->nextPageUrl() }}">Next &raquo;</a>
                @else
                    <span>Next &raquo;</span>
                @endif
            </div>
        @endif
    </section>

    @include('officer.students._script')
    <script>linkSelects('filter_institute', 'filter_program');</script>

@endsection
