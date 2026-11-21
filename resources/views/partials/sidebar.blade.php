{{-- Sidebar menu. What each role sees depends on Gates and hasRole(). --}}
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

        {{-- Everyone: their own dashboard --}}
        <a href="{{ route(auth()->user()->dashboardRoute()) }}"
           class="{{ request()->routeIs('*.dashboard') ? 'active' : '' }}">📊 Dashboard</a>

        {{-- Admin only --}}
        @if (auth()->user()->hasRole('Admin'))
            <a href="{{ route('admin.users.index') }}"
               class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">👥 Manage Users</a>
            <a href="{{ route('admin.institutes.index') }}"
               class="{{ request()->routeIs('admin.institutes.*') ? 'active' : '' }}">🏫 Institutes</a>
            <a href="{{ route('admin.programs.index') }}"
               class="{{ request()->routeIs('admin.programs.*') ? 'active' : '' }}">📚 Programs</a>
        @endif

        {{-- Officer (manage-ssg) --}}
        @can('manage-ssg')
            <a href="#">🎓 Register Students</a>
            <a href="#">📅 Events</a>
            <a href="#">✅ Attendance</a>
            <a href="#">⚠️ Sanctions <span class="badge">3</span></a>
            <a href="#">📢 Announcements</a>
        @endcan

        {{-- Adviser: view only --}}
        @if (auth()->user()->hasRole('Adviser'))
            <a href="#">📅 Events</a>
            <a href="#">✅ Attendance</a>
            <a href="#">⚠️ Sanctions</a>
        @endif

        {{-- Officer and Adviser (view-reports) --}}
        @can('view-reports')
            <a href="#">📈 Reports</a>
        @endcan

        {{-- Student (use-attendance) --}}
        @can('use-attendance')
            <a href="#">📅 Events</a>
            <a href="#">✅ Check-in / Check-out</a>
            <a href="#">⚠️ My Sanctions</a>
        @endcan

        <div class="menu-label">ACCOUNT</div>

        <a href="{{ route('profile.edit') }}">⚙️ Profile</a>

        {{-- Logout is a POST form with @csrf, not a plain link --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">🚪 Logout</button>
        </form>
    </div>

    <div class="sidebar-user">
        <div class="avatar">{{ $initials }}</div>
        <div>
            <strong>{{ $fullName }}</strong>
            <small>{{ $role }}</small>
        </div>
    </div>

</aside>
