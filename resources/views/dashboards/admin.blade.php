@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('subtitle', 'Manage accounts and the system')

@section('content')
@include('partials.weather')

             <!-- STAT CARDS (real numbers) -->
            <section class="stats">

                <div class="stat">
                    <div class="stat-top">
                        <div class="stat-icon">👥</div>
                    </div>
                    <h2>{{ number_format($stats['total_users']) }}</h2>
                    <p>Total Users</p>
                </div>

                <div class="stat">
                    <div class="stat-top">
                        <div class="stat-icon">⏳</div>
                    </div>
                    <h2>{{ number_format($stats['pending']) }}</h2>
                    <p>Awaiting Approval</p>
                </div>

                <div class="stat">
                    <div class="stat-top">
                        <div class="stat-icon">🏫</div>
                    </div>
                    <h2>{{ number_format($stats['institutes']) }}</h2>
                    <p>Institutes</p>
                </div>

                <div class="stat">
                    <div class="stat-top">
                        <div class="stat-icon">📚</div>
                    </div>
                    <h2>{{ number_format($stats['programs']) }}</h2>
                    <p>Programs</p>
                </div>

            </section>

            <section class="panel" style="margin-bottom: 18px;">
                <div class="panel-head">
                    <div>
                        <h3>Users per Role</h3>
                        <small>Number of accounts for each role</small>
                    </div>
                </div>

                <table>
                    <thead>
                        <tr><th>ROLE</th><th>USERS</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($stats['roles'] as $role => $total)
                            <tr><td>{{ $role }}</td><td>{{ number_format($total) }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </section>

            <section class="panel">

                <div class="panel-head">
                    <div>
                        <h3>Recent Registrations</h3>
                        <small>Latest account requests</small>
                    </div>
                    <a href="#">View all</a>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>USER</th>
                            <th>ACTIVITY</th>
                            <th>STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="person">
                                    <div class="avatar" style="background:#0b5cab">JD</div>
                                    <div>Juan Dela Cruz<small>BSIT · 1st Year</small></div>
                                </div>
                            </td>
                            <td>Registered, awaiting approval</td>
                            <td><span class="status pending">Pending</span></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="person">
                                    <div class="avatar" style="background:#1a9e8f">MS</div>
                                    <div>Maria Santos<small>BSED · 2nd Year</small></div>
                                </div>
                            </td>
                            <td>Account approved</td>
                            <td><span class="status ok">Approved</span></td>
                        </tr>
                    </tbody>
                </table>

            </section>

@endsection
