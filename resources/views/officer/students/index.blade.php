@extends('layouts.app')

@section('title', 'Register Students')
@section('subtitle', 'Add, edit and remove student records')

@section('content')

@include('admin.users._style')

    <div class="page-bar">

        <form method="GET" action="{{ route('officer.students.index') }}" class="filters">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or student no.">

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

            <button type="submit" class="btn btn-light">Filter</button>
        </form>

        <a href="{{ route('officer.students.create') }}" class="btn">+ Add Student</a>
    </div>

    <section class="panel">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>STUDENT NO.</th>
                        <th>NAME</th>
                        <th>PROGRAM</th>
                        <th>INSTITUTE</th>
                        <th>YEAR</th>
                        <th>SEX</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($students as $student)
                        <tr>
                            <td>{{ $student->student_no }}</td>
                            <td>{{ $student->user->full_name }}</td>
                            <td>{{ $student->program->code }}</td>
                            <td>{{ $student->program->institute->code }}</td>
                            <td>{{ $student->year_level }}</td>
                            <td>{{ $student->sex }}</td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('officer.students.edit', $student) }}" class="btn btn-small btn-light">Edit</a>

                                    <form method="POST" action="{{ route('officer.students.destroy', $student) }}"
                                          onsubmit="return confirm('Delete this student and the login account?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-small btn-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">No students found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($students->hasPages())
            <div class="pager">
                @if ($students->onFirstPage())
                    <span>&laquo; Prev</span>
                @else
                    <a href="{{ $students->previousPageUrl() }}">&laquo; Prev</a>
                @endif

                <span>Page {{ $students->currentPage() }} of {{ $students->lastPage() }}</span>

                @if ($students->hasMorePages())
                    <a href="{{ $students->nextPageUrl() }}">Next &raquo;</a>
                @else
                    <span>Next &raquo;</span>
                @endif
            </div>
        @endif
    </section>

    @include('officer.students._script')
    <script>linkSelects('filter_institute', 'filter_program');</script>

@endsection
