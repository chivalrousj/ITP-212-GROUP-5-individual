@extends('layouts.app')

@section('title', 'Officer Dashboard')
@section('subtitle', 'Welcome back, here is your overview')

@section('content')

            <!-- STAT CARDS (placeholder numbers for now) -->
            <section class="stats">

                <div class="stat">
                    <div class="stat-top">
                        <div class="stat-icon">🎓</div>
                        <span class="tag up">+12.4%</span>
                    </div>
                    <h2>1,284</h2>
                    <p>Total Students</p>
                </div>

                <div class="stat">
                    <div class="stat-top">
                        <div class="stat-icon">📅</div>
                        <span class="tag up">+3</span>
                    </div>
                    <h2>8</h2>
                    <p>Upcoming Events</p>
                </div>

                <div class="stat">
                    <div class="stat-top">
                        <div class="stat-icon">✅</div>
                        <span class="tag down">-2.1%</span>
                    </div>
                    <h2>92.6%</h2>
                    <p>Attendance Rate</p>
                </div>

                <div class="stat">
                    <div class="stat-top">
                        <div class="stat-icon">⚠️</div>
                        <span class="tag warn">3 new</span>
                    </div>
                    <h2>7</h2>
                    <p>Pending Sanctions</p>
                </div>

            </section>

            <section class="panel">

                <div class="panel-head">
                    <div>
                        <h3>Recent Activity</h3>
                        <small>Latest registrations &amp; check-ins</small>
                    </div>
                    <a href="#">View all</a>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>STUDENT</th>
                            <th>ACTIVITY</th>
                            <th>STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="person">
                                    <div class="avatar" style="background:#0b5cab">AM</div>
                                    <div>Ana Marie Reyes<small>BSIT · 3rd Year</small></div>
                                </div>
                            </td>
                            <td>Registered for Tech Summit</td>
                            <td><span class="status ok">Confirmed</span></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="person">
                                    <div class="avatar" style="background:#1a9e8f">CR</div>
                                    <div>Carlos Rivera<small>BSED · 2nd Year</small></div>
                                </div>
                            </td>
                            <td>Checked in — Leadership Seminar</td>
                            <td><span class="status present">Present</span></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="person">
                                    <div class="avatar" style="background:#6a4fc7">LG</div>
                                    <div>Liza Gonzales<small>BSBA · 4th Year</small></div>
                                </div>
                            </td>
                            <td>Submitted appeal — Sanction #204</td>
                            <td><span class="status pending">Pending</span></td>
                        </tr>
                    </tbody>
                </table>

            </section>

@endsection
