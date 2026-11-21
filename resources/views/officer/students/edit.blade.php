@extends('layouts.app')

@section('title', 'Edit Student')
@section('subtitle', 'Change the details of this student')

@section('content')

@include('admin.users._style')

    <section class="panel">
        <form method="POST" action="{{ route('officer.students.update', $student) }}" class="user-form">
            @csrf
            @method('PUT')

            @include('officer.students._form')

            <button type="submit" class="btn">Save Changes</button>
            <a href="{{ route('officer.students.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </section>

@endsection
