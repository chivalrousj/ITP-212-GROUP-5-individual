@extends('layouts.app')

@section('title', 'Edit Announcement')
@section('subtitle', 'Update an announcement')

@section('content')

@include('admin.users._style')

    <section class="panel">
        @include('officer.announcements._form', [
            'announcement' => $announcement,
            'action' => route('officer.announcements.update', $announcement),
            'button' => 'Save Changes',
        ])
    </section>
@endsection
