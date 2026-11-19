@extends('layouts.app')

@section('title', 'Edit User')
@section('subtitle', 'Change the role, status or details of this account')

@section('content')

@include('admin.users._style')

    <section class="panel">
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="user-form">
            @csrf
            @method('PUT')

            @include('admin.users._form')

            <button type="submit" class="btn">Save Changes</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </section>

@endsection
