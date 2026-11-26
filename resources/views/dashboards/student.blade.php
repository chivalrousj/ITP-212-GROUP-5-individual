@extends('layouts.app')

@section('title', 'Student Dashboard')
@section('subtitle', 'Your events and attendance')

@section('content')
@include('partials.weather')

            <!-- STAT CARDS (placeholder numbers for now) -->
            <section class="stats">

                <div class="stat">
                    <div class="stat-top">
                        <div class="stat-icon">📅</div>
                        <span class="tag up">+2</span>
                    </div>
                    <h2>3</h2>
                    <p>Upcoming Events</p>
                </div>

                <div class="stat">
                    <div class="stat-top">
                        <div class="stat-icon">✅</div>
                        <span class="tag up">+4%</span>
                    </div>
                    <h2>95%</h2>
                    <p>My Attendance</p>
                </div>

                <div class="stat">
                    <div class="stat-top">
                        <div class="stat-icon">⚠️</div>
                        <span class="tag warn">1 new</span>
                    </div>
                    <h2>1</h2>
                    <p>My Sanctions</p>
                </div>

                <div class="stat">
                    <div class="stat-top">
                        <div class="stat-icon">📢</div>
                        <span class="tag up">New</span>
                    </div>
                    <h2>2</h2>
                    <p>Announcements</p>
                </div>

            </section>

            <section class="panel">

                <div class="panel-head">
                    <div>
                        <h3>My Events</h3>
                        <small>Events you can attend</small>
                    </div>
                    <a href="#">View all</a>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>EVENT</th>
                            <th>ACTIVITY</th>
                            <th>STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="person">
                                    <div class="avatar" style="background:#0b5cab">TS</div>
                                    <div>Tech Summit<small>Sept 20 · Gym</small></div>
                                </div>
                            </td>
                            <td>Open for check-in</td>
                            <td><span class="status present">Open</span></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="person">
                                    <div class="avatar" style="background:#1a9e8f">LS</div>
                                    <div>Leadership Seminar<small>Sept 28 · Hall</small></div>
                                </div>
                            </td>
                            <td>Registered</td>
                            <td><span class="status ok">Registered</span></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="person">
                                    <div class="avatar" style="background:#6a4fc7">FD</div>
                                    <div>Foundation Day<small>Oct 10 · Campus</small></div>
                                </div>
                            </td>
                            <td>Not yet open</td>
                            <td><span class="status pending">Soon</span></td>
                        </tr>
                    </tbody>
                </table>

            </section>

@endsection
