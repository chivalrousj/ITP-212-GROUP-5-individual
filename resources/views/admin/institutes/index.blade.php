@extends('layouts.app')

@section('title', 'Institutes')
@section('subtitle', 'Manage the institutes of the school')

@section('content')

@include('admin.users._style')

    <div class="page-bar">
        <form method="GET" action="{{ route('admin.institutes.index') }}" class="filters">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search code or name">
            <button type="submit" class="btn btn-light">Search</button>
        </form>

        <a href="{{ route('admin.institutes.create') }}" class="btn">+ Add Institute</a>
    </div>

    <section class="panel">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>CODE</th>
                        <th>NAME</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($institutes as $institute)
                        <tr>
                            <td>{{ $institute->code }}</td>
                            <td>{{ $institute->name }}</td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('admin.institutes.edit', $institute->id) }}" class="btn btn-small btn-light">Edit</a>

                                    <form method="POST" action="{{ route('admin.institutes.destroy', $institute->id) }}"
                                          onsubmit="return confirm('Delete this institute?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-small btn-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">No institutes found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($institutes->hasPages())
            <div class="pager">
                @if ($institutes->onFirstPage())
                    <span>&laquo; Prev</span>
                @else
                    <a href="{{ $institutes->previousPageUrl() }}">&laquo; Prev</a>
                @endif

                <span>Page {{ $institutes->currentPage() }} of {{ $institutes->lastPage() }}</span>

                @if ($institutes->hasMorePages())
                    <a href="{{ $institutes->nextPageUrl() }}">Next &raquo;</a>
                @else
                    <span>Next &raquo;</span>
                @endif
            </div>
        @endif
    </section>

@endsection
