@extends('layouts.app')

@section('title', 'Add User')
@section('subtitle', 'Create an Officer, Adviser or Student account')

@section('content')

@include('admin.users._style')

    <section class="panel">
        <form method="POST" action="{{ route('admin.users.store') }}" class="user-form">
            @csrf

            @include('admin.users._form', ['user' => null])

            <button type="submit" class="btn">Create User</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </section>

@endsection
