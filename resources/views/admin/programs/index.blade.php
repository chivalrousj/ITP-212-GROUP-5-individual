@extends('layouts.app')

@section('title', 'Programs')
@section('subtitle', 'Manage the programs of each institute')

@section('content')

@include('admin.users._style')

    <div class="page-bar">
        <form method="GET" action="{{ route('admin.programs.index') }}" class="filters">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search code, name or institute">
            <button type="submit" class="btn btn-light">Search</button>
        </form>

        <a href="{{ route('admin.programs.create') }}" class="btn">+ Add Program</a>
    </div>

    <section class="panel">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>CODE</th>
                        <th>NAME</th>
                        <th>INSTITUTE</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($programs as $program)
                        <tr>
                            <td>{{ $program->code }}</td>
                            <td>{{ $program->name }}</td>
                            {{-- institute_code comes from the join in ProgramController --}}
                            <td>{{ $program->institute_code }}</td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('admin.programs.edit', $program->id) }}" class="btn btn-small btn-light">Edit</a>

                                    <form method="POST" action="{{ route('admin.programs.destroy', $program->id) }}"
                                          onsubmit="return confirm('Delete this program?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-small btn-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No programs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($programs->hasPages())
            <div class="pager">
                @if ($programs->onFirstPage())
                    <span>&laquo; Prev</span>
                @else
                    <a href="{{ $programs->previousPageUrl() }}">&laquo; Prev</a>
                @endif

                <span>Page {{ $programs->currentPage() }} of {{ $programs->lastPage() }}</span>

                @if ($programs->hasMorePages())
                    <a href="{{ $programs->nextPageUrl() }}">Next &raquo;</a>
                @else
                    <span>Next &raquo;</span>
                @endif
            </div>
        @endif
    </section>

@endsection
