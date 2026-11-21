@extends('layouts.app')

@section('title', 'Edit Program')
@section('subtitle', 'Change the institute, code or name of this program')

@section('content')

@include('admin.users._style')

    <section class="panel">
        <form method="POST" action="{{ route('admin.programs.update', $program->id) }}" class="user-form">
            @csrf
            @method('PUT')

            @include('admin.programs._form')

            <button type="submit" class="btn">Save Changes</button>
            <a href="{{ route('admin.programs.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </section>

@endsection
