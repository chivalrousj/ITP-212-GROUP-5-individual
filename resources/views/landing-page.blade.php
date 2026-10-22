{{-- Page: Landing | Author: Jemarie Manggarai | ITP 212 Group 5 --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SPAMAST SSG Event Management System</title>
    <style>
        :root {
            --navy: #0b2a4a;
            --blue: #0b5cab;
            --sky: #e8f1fb;
            --line: #d3e2f3;
            --text: #1d3348;
            --muted: #5b7089;
            --white: #ffffff;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            font-family: "Segoe UI", system-ui, -apple-system, Arial, sans-serif;
            color: var(--text);
            background: var(--white);
            line-height: 1.6;
        }
        a { text-decoration: none; }
        a:focus-visible { outline: 3px solid var(--blue); outline-offset: 3px; }

        .container { max-width: 1080px; margin: 0 auto; padding: 0 24px; }

        /* Header */
        header { border-bottom: 1px solid var(--line); background: var(--white); }
        .nav { display: flex; align-items: center; justify-content: space-between; height: 72px; }
        .brand { font-weight: 800; font-size: 1.25rem; color: var(--navy); letter-spacing: .3px; }
        .nav-actions { display: flex; gap: 10px; }

        .btn {
            display: inline-block;
            padding: 11px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            border: 2px solid var(--blue);
            transition: background .15s, color .15s;
        }
        .btn-primary { background: var(--blue); color: var(--white); }
        .btn-primary:hover { background: var(--navy); border-color: var(--navy); }
        .btn-outline { background: var(--white); color: var(--blue); }
        .btn-outline:hover { background: var(--sky); }

        /* Hero */
        .hero { background: var(--sky); padding: 88px 0 96px; }
        .hero h1 {
            font-size: clamp(2.2rem, 5.5vw, 3.8rem);
            line-height: 1.1;
            color: var(--navy);
            max-width: 14ch;
            margin-bottom: 20px;
        }
        .hero p { font-size: 1.15rem; color: var(--muted); max-width: 52ch; margin-bottom: 32px; }
        .hero-actions { display: flex; flex-wrap: wrap; gap: 12px; }

        /* Features */
        .features { padding: 80px 0; }
        .features h2 { font-size: 1.9rem; color: var(--navy); margin-bottom: 8px; }
        .features .lead { color: var(--muted); margin-bottom: 40px; max-width: 56ch; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; }
        .feature { border: 1px solid var(--line); border-radius: 12px; padding: 24px; background: var(--white); }
        .feature h3 { color: var(--blue); font-size: 1.1rem; margin-bottom: 8px; }
        .feature p { color: var(--muted); font-size: .96rem; }

        /* Call to action */
        .cta { background: var(--navy); color: var(--white); padding: 64px 0; text-align: center; }
        .cta h2 { font-size: 1.7rem; margin-bottom: 8px; }
        .cta p { color: #b8cbe0; margin-bottom: 28px; }
        .cta .btn-primary { background: var(--white); color: var(--navy); border-color: var(--white); }
        .cta .btn-primary:hover { background: var(--sky); }
        .cta .btn-outline { background: transparent; color: var(--white); border-color: var(--white); }
        .cta .btn-outline:hover { background: rgba(255,255,255,.12); }
        .cta .hero-actions { justify-content: center; }

        /* Footer */
        footer { padding: 28px 0; text-align: center; color: var(--muted); font-size: .9rem; border-top: 1px solid var(--line); }

        @media (max-width: 560px) {
            .hero { padding: 56px 0 64px; }
            .nav-actions .btn-outline { display: none; }
        }
    </style>
</head>
<body>

    <header>
        <div class="container nav">
            <a href="/" class="brand">SPAMAST SSG</a>
            <div class="nav-actions">
                <a href="{{ route('register') }}" class="btn btn-outline">Register</a>
                <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
            </div>
        </div>
    </header>

    <main>
        <section class="hero">
            <div class="container">
                <h1>SPAMAST SSG Event Management System</h1>
                <p>Register students, run events, record attendance, and track sanctions in one place.</p>
                <div class="hero-actions">
                    <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-outline">Register</a>
                </div>
            </div>
        </section>

        <section class="features" id="features">
            <div class="container">
                <h2>What you can do</h2>
                <p class="lead">Everything the Supreme Student Government needs to manage campus events.</p>
                <div class="grid">
                    <div class="feature">
                        <h3>Register Students</h3>
                        <p>Add student accounts and keep one organized list of every member.</p>
                    </div>
                    <div class="feature">
                        <h3>Manage Events</h3>
                        <p>Create events, set schedules, and see who is joining.</p>
                    </div>
                    <div class="feature">
                        <h3>Attendance</h3>
                        <p>Record who attended each event and review the logs anytime.</p>
                    </div>
                    <div class="feature">
                        <h3>Sanctions</h3>
                        <p>Track absences and apply sanctions with a clear record per student.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="cta">
            <div class="container">
                <h2>Ready to get started?</h2>
                <p>Log in to your account or create a new one.</p>
                <div class="hero-actions">
                    <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-outline">Register</a>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            &copy; {{ date('Y') }} SPAMAST SSG Event Management System. All rights reserved.
        </div>
    </footer>

</body>
</html>