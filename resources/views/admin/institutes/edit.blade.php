@extends('layouts.app')

@section('title', 'Edit Institute')
@section('subtitle', 'Change the code or name of this institute')

@section('content')

@include('admin.users._style')

    <section class="panel">
        <form method="POST" action="{{ route('admin.institutes.update', $institute->id) }}" class="user-form">
            @csrf
            @method('PUT')

            @include('admin.institutes._form')

            <button type="submit" class="btn">Save Changes</button>
            <a href="{{ route('admin.institutes.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </section>

@endsection
