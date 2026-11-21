@extends('layouts.app')

@section('title', 'Edit Event')
@section('subtitle', 'Change the details or times of this event')

@section('content')

@include('admin.users._style')

    <section class="panel">
        <form method="POST" action="{{ route('officer.events.update', $event) }}" class="user-form">
            @csrf
            @method('PUT')

            @include('officer.events._form')

            <button type="submit" class="btn">Save Changes</button>
            <a href="{{ route('events.show', $event) }}" class="btn btn-light">Cancel</a>
        </form>
    </section>

@endsection
