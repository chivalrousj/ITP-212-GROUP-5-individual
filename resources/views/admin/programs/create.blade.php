@extends('layouts.app')

@section('title', 'Add Program')
@section('subtitle', 'Create a new program')

@section('content')

@include('admin.users._style')

    <section class="panel">
        <form method="POST" action="{{ route('admin.programs.store') }}" class="user-form">
            @csrf

            @include('admin.programs._form', ['program' => null])

            <button type="submit" class="btn">Save Program</button>
            <a href="{{ route('admin.programs.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </section>

@endsection
