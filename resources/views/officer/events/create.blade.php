@extends('layouts.app')

@section('title', 'Add Event')
@section('subtitle', 'Create an event and set its sessions')

@section('content')

@include('admin.users._style')

    <section class="panel">
        <form method="POST" action="{{ route('officer.events.store') }}" class="user-form">
            @csrf

            @include('officer.events._form', ['event' => null])

            <button type="submit" class="btn">Save Event</button>
            <a href="{{ route('events.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </section>

@endsection
