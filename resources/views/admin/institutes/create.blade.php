@extends('layouts.app')

@section('title', 'Add Institute')
@section('subtitle', 'Create a new institute')

@section('content')

@include('admin.users._style')

    <section class="panel">
        <form method="POST" action="{{ route('admin.institutes.store') }}" class="user-form">
            @csrf

            @include('admin.institutes._form', ['institute' => null])

            <button type="submit" class="btn">Save Institute</button>
            <a href="{{ route('admin.institutes.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </section>

@endsection
