@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('subtitle', 'Manage accounts and the system')

@section('content')

            <!-- STAT CARDS (placeholder numbers for now) -->
            <section class="stats">

                <div class="stat">
                    <div class="stat-top">
                        <div class="stat-icon">👥</div>
                        <span class="tag up">+5</span>
                    </div>
                    <h2>42</h2>
                    <p>Total Users</p>
                </div>

                <div class="stat">
                    <div class="stat-top">
                        <div class="stat-icon">⏳</div>
                        <span class="tag warn">2 new</span>
                    </div>
                    <h2>3</h2>
                    <p>Awaiting Approval</p>
                </div>

                <div class="stat">
                    <div class="stat-top">
                        <div class="stat-icon">🎓</div>
                        <span class="tag up">+12</span>
                    </div>
                    <h2>38</h2>
                    <p>Students</p>
                </div>

                <div class="stat">
                    <div class="stat-top">
                        <div class="stat-icon">🧑</div>
                        <span class="tag up">+1</span>
                    </div>
                    <h2>4</h2>
                    <p>Officers &amp; Advisers</p>
                </div>

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
