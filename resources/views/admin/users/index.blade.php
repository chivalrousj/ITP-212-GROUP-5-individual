@extends('layouts.app')

@section('title', 'Manage Users')
@section('subtitle', 'Create accounts and approve registrations')

@section('content')

@include('admin.users._style')

    <div class="page-bar">

        {{-- Search and role filter (GET, so the filter stays in the page links) --}}
        <form method="GET" action="{{ route('admin.users.index') }}" class="filters">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or email">

            <select name="role">
                <option value="">All roles</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-light">Filter</button>
        </form>

        <a href="{{ route('admin.users.create') }}" class="btn">+ Add User</a>
    </div>

    <section class="panel">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>NAME</th>
                        <th>EMAIL</th>
                        <th>ROLE</th>
                        <th>STATUS</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $u)
                        <tr>
                            <td>{{ $u->first_name }} {{ $u->last_name }}</td>
                            <td>{{ $u->email }}</td>
                            <td>{{ $u->role->name ?? 'None' }}</td>
                            <td>
                                @if ($u->is_active)
                                    <span class="status ok">Active</span>
                                @else
                                    <span class="status off">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="actions">

                                    {{-- Admin accounts are not edited here --}}
                                    @unless ($u->hasRole('Admin'))
                                        <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-small btn-light">Edit</a>
                                    @endunless

                                    {{-- Activate or deactivate (not for your own account) --}}
                                    @if ($u->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.toggle', $u) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-small btn-light">
                                                {{ $u->is_active ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Delete is shown only when UserPolicy allows it --}}
                                    @can('delete', $u)
                                        <form method="POST" action="{{ route('admin.users.destroy', $u) }}"
                                              onsubmit="return confirm('Delete this user?');">
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
                            <td colspan="5">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Simple pagination --}}
        @if ($users->hasPages())
            <div class="pager">
                @if ($users->onFirstPage())
                    <span>&laquo; Prev</span>
                @else
                    <a href="{{ $users->previousPageUrl() }}">&laquo; Prev</a>
                @endif

                <span>Page {{ $users->currentPage() }} of {{ $users->lastPage() }}</span>

                @if ($users->hasMorePages())
                    <a href="{{ $users->nextPageUrl() }}">Next &raquo;</a>
                @else
                    <span>Next &raquo;</span>
                @endif
            </div>
        @endif
    </section>

@endsection
