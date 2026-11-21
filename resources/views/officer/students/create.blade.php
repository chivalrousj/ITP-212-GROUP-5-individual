@extends('layouts.app')

@section('title', 'Add Student')
@section('subtitle', 'The student number is the temporary password')

@section('content')

@include('admin.users._style')

    <section class="panel">
        <form method="POST" action="{{ route('officer.students.store') }}" class="user-form">
            @csrf

            @include('officer.students._form', ['student' => null])

            <button type="submit" class="btn">Save Student</button>
            <a href="{{ route('officer.students.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </section>

@endsection
