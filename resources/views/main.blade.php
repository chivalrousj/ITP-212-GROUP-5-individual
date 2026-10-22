{{-- Page: Dashboard | Author: Jemarie Manggarai | ITP 212 Group 5 --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPAMAST SSG - Dashboard</title>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            display: flex;
            min-height: 100vh;
            background: #eef6fd;
            color: #17324d;
        }

        /* ================= SIDEBAR ================= */
        .sidebar {
            width: 230px;
            height: 100vh;
            position: sticky;
            top: 0;
            display: flex;
            flex-direction: column;
            padding: 22px 16px;
            background: white;
            border-right: 1px solid #e1edf5;
        }

        .brand { display: flex; align-items: center; gap: 10px; margin-bottom: 26px; }

        .brand-logo {
            width: 34px; height: 34px;
            display: flex; align-items: center; justify-content: center;
            background: #0b5cab; color: white;
            border-radius: 9px; font-weight: bold;
        }

        .brand-text strong { display: block; font-size: 14px; color: #103b60; }
        .brand-text small { font-size: 9px; letter-spacing: 1px; color: #71869a; }

        .menu-label { margin: 16px 8px 8px; font-size: 10px; letter-spacing: 1px; color: #9aabb8; }

        .menu a {
            display: flex; align-items: center; gap: 10px;
            padding: 11px 12px; margin-bottom: 4px;
            border-radius: 8px;
            text-decoration: none; font-size: 13px; color: #4a647a;
        }

        .menu a:hover { background: #eef6fd; }
        .menu a.active { background: #0b5cab; color: white; font-weight: bold; }

        .badge {
            margin-left: auto;
            width: 18px; height: 18px;
            display: flex; align-items: center; justify-content: center;
            background: #dc3545; color: white;
            border-radius: 50%; font-size: 10px;
        }

        .sidebar-user {
            margin-top: auto;
            display: flex; align-items: center; gap: 10px;
            padding: 10px;
            background: #f3f9fe;
            border-radius: 10px;
        }

        .sidebar-user strong { display: block; font-size: 12px; }
        .sidebar-user small { font-size: 10px; color: #71869a; }

        .avatar {
            width: 32px; height: 32px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            background: #0b5cab; color: white;
            border-radius: 50%; font-size: 12px; font-weight: bold;
        }

        /* ================= MAIN ================= */
        .main { flex: 1; min-width: 0; }

        .topbar {
            display: flex; justify-content: space-between; align-items: center;
            padding: 16px 28px;
            background: white;
            border-bottom: 1px solid #e1edf5;
        }

        .topbar h1 { font-size: 20px; color: #103b60; }
        .topbar p { margin-top: 3px; font-size: 12px; color: #71869a; }

        .top-right { display: flex; align-items: center; gap: 18px; }

        .search {
            width: 220px; padding: 9px 14px;
            border: 1px solid #d5e3ed; border-radius: 20px;
            font-size: 12px; outline: none;
        }

        .user-chip { display: flex; align-items: center; gap: 10px; text-align: right; }
        .user-chip strong { display: block; font-size: 12px; }
        .user-chip small { font-size: 10px; color: #71869a; text-transform: uppercase; }

        .content { padding: 24px 28px; }

        /* ================= STATS ================= */
        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-bottom: 22px;
        }

        .stat {
            padding: 18px;
            background: white;
            border-radius: 14px;
            box-shadow: 0 4px 14px rgba(8, 122, 202, 0.08);
        }

        .stat-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; }

        .stat-icon {
            width: 34px; height: 34px;
            display: flex; align-items: center; justify-content: center;
            background: #e2f0fc; border-radius: 9px;
        }

        .tag { padding: 3px 8px; border-radius: 10px; font-size: 10px; font-weight: bold; }
        .tag.up { background: #e4f7ed; color: #159447; }
        .tag.down { background: #fde8ea; color: #dc3545; }
        .tag.warn { background: #fff1dc; color: #d98a00; }

        .stat h2 { font-size: 24px; color: #103b60; }
        .stat p { margin-top: 4px; font-size: 11px; color: #71869a; }

        /* ================= RECENT ACTIVITY ================= */
        .panel {
            width: 62%;
            padding: 20px;
            background: white;
            border-radius: 14px;
            box-shadow: 0 4px 14px rgba(8, 122, 202, 0.08);
        }

        .panel-head { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 14px; }
        .panel-head h3 { font-size: 14px; color: #103b60; }
        .panel-head small { font-size: 10px; color: #71869a; }
        .panel-head a { font-size: 11px; font-weight: bold; color: #087aca; text-decoration: none; }

        table { width: 100%; border-collapse: collapse; }
        th { padding: 8px 6px; text-align: left; font-size: 10px; letter-spacing: 1px; color: #9aabb8; }
        td { padding: 12px 6px; border-top: 1px solid #eef3f7; font-size: 12px; }

        .person { display: flex; align-items: center; gap: 10px; }
        .person .avatar { width: 28px; height: 28px; font-size: 10px; }
        .person small { display: block; font-size: 10px; color: #71869a; }

        .status { padding: 3px 9px; border-radius: 10px; font-size: 10px; font-weight: bold; }
        .status.ok { background: #e4f7ed; color: #159447; }
        .status.present { background: #e2f0fc; color: #0b5cab; }
        .status.pending { background: #fff1dc; color: #d98a00; }

        /* ================= RESPONSIVE ================= */
        @media (max-width: 900px) {
            .sidebar, .search { display: none; }
            .stats { grid-template-columns: repeat(2, 1fr); }
            .panel { width: 100%; }
        }
    </style>
</head>

<body>

@php
    $user = Auth::user();
    $fullName = $user->first_name . ' ' . $user->last_name;
    $initials = strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1));
    $role = $user->role->name ?? 'Student';
@endphp

    <!-- ================= SIDEBAR ================= -->
    <aside class="sidebar">

        <div class="brand">
            <div class="brand-logo">S</div>
            <div class="brand-text">
                <strong>SPAMAST SSG</strong>
                <small>EVENT MANAGEMENT</small>
            </div>
        </div>

        <div class="menu">
            <div class="menu-label">MAIN MENU</div>
            <a href="{{ route('main') }}" class="active">📊 Dashboard</a>
            <a href="#">🎓 Register Students</a>
            <a href="#">📅 Events</a>
            <a href="#">✅ Attendance</a>
            <a href="#">⚠️ Sanctions <span class="badge">3</span></a>

            <div class="menu-label">ACCOUNT</div>
            <a href="#">⚙️ Settings</a>
            <a href="{{ route('logout') }}">🚪 Logout</a>
        </div>

        <div class="sidebar-user">
            <div class="avatar">{{ $initials }}</div>
            <div>
                <strong>{{ $fullName }}</strong>
                <small>{{ $role }}</small>
            </div>
        </div>

    </aside>


    <!-- ================= MAIN ================= -->
    <div class="main">

        <header class="topbar">
            <div>
                <h1>Dashboard</h1>
                <p>Welcome back, here's your overview</p>
            </div>

            <div class="top-right">
                <input class="search" type="text" placeholder="🔍 Search students, events...">
                <span>🔔</span>
                <div class="user-chip">
                    <div>
                        <strong>{{ $fullName }}</strong>
                        <small>{{ $role }}</small>
                    </div>
                    <div class="avatar">{{ $initials }}</div>
                </div>
            </div>
        </header>

        <div class="content">

            <!-- STAT CARDS -->
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

            <!-- RECENT ACTIVITY -->
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

        </div>
    </div>

</body>
</html> 