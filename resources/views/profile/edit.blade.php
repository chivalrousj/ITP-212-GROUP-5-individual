<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPAMAST SSG - My Profile</title>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            min-height: 100vh;
            padding: 30px 20px;
            background: #eef6fd;
            color: #17324d;
        }

        .container { max-width: 560px; margin: 0 auto; }

        .back { display: inline-block; margin-bottom: 15px; color: #0b5cab; text-decoration: none; font-weight: bold; font-size: 14px; }

        .card { background: white; padding: 32px; border-radius: 16px; box-shadow: 0 20px 50px rgba(8, 122, 202, 0.12); }

        h1 { color: #103b60; font-size: 26px; margin-bottom: 6px; }
        .sub { color: #71869a; font-size: 14px; margin-bottom: 22px; }
        h2 { color: #103b60; font-size: 17px; margin: 24px 0 4px; }
        .hint { color: #71869a; font-size: 12px; margin-bottom: 12px; }

        .form-group { margin-bottom: 16px; }
        label { display: block; margin-bottom: 6px; font-size: 14px; font-weight: bold; }

        input {
            width: 100%; padding: 12px 14px;
            border: 1px solid #d5e3ed; border-radius: 8px;
            font-size: 14px; outline: none; color: #17324d;
        }
        input:focus { border-color: #0b5cab; box-shadow: 0 0 0 3px rgba(11, 92, 171, 0.1); }

        .btn {
            width: 100%; margin-top: 10px; padding: 13px;
            border: none; border-radius: 8px;
            background: #0b5cab; color: white;
            font-size: 15px; font-weight: bold; cursor: pointer;
        }
        .btn:hover { background: #094a8c; }

        .success { padding: 12px; margin-bottom: 18px; background: #e7f7ee; color: #168447; border-radius: 8px; font-size: 14px; }
        .errors { padding: 12px 12px 12px 30px; margin-bottom: 18px; background: #fdeaea; color: #dc3545; border-radius: 8px; font-size: 13px; }
    </style>
</head>

<body>
    <div class="container">

        <a href="{{ route('main') }}" class="back">&larr; Back to Dashboard</a>

        <div class="card">
            <h1>My Profile</h1>
            <p class="sub">Update your account details.</p>

            {{-- Success message --}}
            @if (session('success'))
                <div class="success">{{ session('success') }}</div>
            @endif

            {{-- Validation errors --}}
            @if ($errors->any())
                <ul class="errors">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}">
                </div>

                <div class="form-group">
                    <label for="middle_name">Middle Name (optional)</label>
                    <input type="text" id="middle_name" name="middle_name" value="{{ old('middle_name', $user->middle_name) }}">
                </div>

                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}">
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}">
                </div>

                <h2>Change Password</h2>
                <p class="hint">Leave these blank if you don't want to change your password.</p>

                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password">
                </div>

                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" id="password" name="password">
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm New Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation">
                </div>

                <button type="submit" class="btn">Save Changes</button>
            </form>
        </div>

    </div>
</body>
</html>