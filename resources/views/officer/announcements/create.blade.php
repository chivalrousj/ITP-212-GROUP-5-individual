@extends('layouts.app')

@section('title', 'New Announcement')
@section('subtitle', 'Post an announcement')

@section('content')

@include('admin.users._style')

    <section class="panel">
        @include('officer.announcements._form', [
            'announcement' => null,
            'action' => route('officer.announcements.store'),
            'button' => 'Post Announcement',
        ])
    </section>
@endsection
