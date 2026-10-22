{{-- Page: Dashboard | Author: Jemarie Manggarai | ITP 212 Group 5 --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - SPAMAST SSG Event Management System</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            min-height: 100vh;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 20px;

            background: linear-gradient(135deg, #eef8ff, #ffffff);

            color: #17324d;
        }

        /* ================= CARD ================= */

        .login-card {
            width: 100%;
            max-width: 430px;

            background: white;

            padding: 40px;

            border-radius: 20px;

            box-shadow:
                0 20px 50px rgba(8, 122, 202, 0.15);
        }

        /* ================= HEADER ================= */

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            display: inline-block;

            padding: 8px 15px;
            margin-bottom: 15px;

            background: #e2f3ff;
            color: #087aca;

            border-radius: 30px;

            font-size: 14px;
            font-weight: bold;
        }

        .header h1 {
            color: #103b60;
            font-size: 30px;
            margin-bottom: 10px;
        }

        .header p {
            color: #71869a;
            font-size: 14px;
            line-height: 1.5;
        }

        /* ================= FORM ================= */

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;

            margin-bottom: 7px;

            font-size: 14px;
            font-weight: bold;

            color: #17324d;
        }

        label span {
            color: #087aca;
        }

        input[type="email"],
        input[type="password"],
        input[type="text"] {
            width: 100%;

            padding: 13px 14px;

            border: 1px solid #d5e3ed;
            border-radius: 8px;

            font-family: Arial, sans-serif;
            font-size: 14px;

            outline: none;

            color: #17324d;

            transition: 0.2s;
        }

        input:focus {
            border-color: #087aca;

            box-shadow:
                0 0 0 3px rgba(8, 122, 202, 0.1);
        }

        input.error {
            border-color: #dc3545;
        }

        .error-message {
            color: #dc3545;

            font-size: 12px;

            margin-top: 5px;

            min-height: 14px;
        }

        /* ================= PASSWORD ================= */

        .password-container {
            position: relative;
        }

        .password-container input {
            padding-right: 65px;
        }

        .show-password {
            position: absolute;

            right: 12px;
            top: 50%;

            transform: translateY(-50%);

            border: none;
            background: none;

            color: #087aca;

            cursor: pointer;

            font-size: 12px;
            font-weight: bold;
        }

        /* ================= REMEMBER / FORGOT ================= */

        .login-options {
            display: flex;

            justify-content: space-between;
            align-items: center;

            margin-bottom: 25px;

            font-size: 13px;
        }

        .remember {
            display: flex;
            align-items: center;

            gap: 7px;

            color: #71869a;

            cursor: pointer;
        }

        .remember input {
            width: 15px;
            height: 15px;

            accent-color: #087aca;

            cursor: pointer;
        }

        .forgot-link {
            color: #087aca;

            text-decoration: none;

            font-weight: bold;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        /* ================= LOGIN BUTTON ================= */

        .login-btn {
            width: 100%;

            padding: 14px;

            border: none;
            border-radius: 8px;

            background: #087aca;
            color: white;

            font-size: 15px;
            font-weight: bold;

            cursor: pointer;

            transition: 0.3s;
        }

        .login-btn:hover {
            background: #075e9d;

            transform: translateY(-1px);
        }

        /* ================= SUCCESS ================= */

        .success-message {
            display: none;

            padding: 12px;

            margin-bottom: 20px;

            background: #e7f7ee;
            color: #168447;

            border-radius: 8px;

            text-align: center;

            font-size: 14px;
        }

        /* ================= REGISTER ================= */

        .register-link {
            text-align: center;

            margin-top: 25px;

            font-size: 14px;

            color: #71869a;
        }

        .register-link a {
            color: #087aca;

            text-decoration: none;

            font-weight: bold;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        /* ================= FOOTER ================= */

        .footer {
            text-align: center;

            margin-top: 25px;

            font-size: 12px;

            color: #9aabb8;
        }

        /* ================= RESPONSIVE ================= */

        @media (max-width: 500px) {

            body {
                padding: 15px;
            }

            .login-card {
                padding: 30px 22px;
            }

            .header h1 {
                font-size: 26px;
            }

            .login-options {
                align-items: flex-start;

                gap: 15px;
            }
        }
    </style>
</head>

<body>

    <!-- ================= LOGIN CARD ================= -->

    <div class="login-card">

        <!-- HEADER -->

        <div class="header">

            <div class="logo">
                SPAMAST SSG
            </div>

            <h1>Welcome Back</h1>

            <p>
                Login to access the SPAMAST SSG Event Management System.
            </p>

        </div>


        <!-- VALIDATION / AUTH ERROR -->

        @if ($errors->any())
            <div class="error-message" style="text-align:center; margin-bottom: 15px;">
                {{ $errors->first() }}
            </div>
        @endif


        <!-- LOGIN FORM -->

        <form
            id="loginForm"
            method="POST"
            action="{{ route('login') }}"
            novalidate
        >
            @csrf

            <!-- EMAIL -->

            <div class="form-group">

                <label for="email">
                    Email <span>*</span>
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Enter your email"
                >

                <div
                    class="error-message"
                    id="emailError"
                ></div>

            </div>


            <!-- PASSWORD -->

            <div class="form-group">

                <label for="password">
                    Password <span>*</span>
                </label>

                <div class="password-container">

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter your password"
                    >

                    <button
                        type="button"
                        class="show-password"
                        onclick="togglePassword()"
                    >
                        Show
                    </button>

                </div>

                <div
                    class="error-message"
                    id="passwordError"
                ></div>

            </div>


            <!-- REMEMBER ME / FORGOT PASSWORD -->

            <div class="login-options">

                <label class="remember">

                    <input
                        type="checkbox"
                        id="rememberMe"
                        name="remember"
                    >

                    Remember Me

                </label>


                <a
                    href="forgot-password.html"
                    class="forgot-link"
                >
                    Forgot Password?
                </a>

            </div>


            <!-- LOGIN BUTTON -->

            <button
                type="submit"
                class="login-btn"
            >
                Login
            </button>

        </form>


        <!-- REGISTER LINK -->

        <div class="register-link">

            Don't have an account?

            <a href="{{ route('register') }}">
                Register here
            </a>

        </div>


        <!-- FOOTER -->

        <div class="footer">

            © 2026 SPAMAST SSG Event Management System

        </div>

    </div>


    <!-- ================= JAVASCRIPT ================= -->

    <script>

        // ==================================================
        // GET ELEMENTS
        // ==================================================

        const loginForm =
            document.getElementById("loginForm");

        const email =
            document.getElementById("email");

        const password =
            document.getElementById("password");

        const rememberMe =
            document.getElementById("rememberMe");


        // ==================================================
        // LOAD SAVED EMAIL
        // ==================================================

        const savedEmail =
            localStorage.getItem("rememberedEmail");

        if (savedEmail) {

            email.value = savedEmail;

            rememberMe.checked = true;

        }


        // ==================================================
        // SHOW / HIDE PASSWORD
        // ==================================================

        function togglePassword() {

            const button =
                document.querySelector(".show-password");

            if (password.type === "password") {

                password.type = "text";

                button.textContent = "Hide";

            } else {

                password.type = "password";

                button.textContent = "Show";

            }

        }


        // ==================================================
        // CLEAR ERROR
        // ==================================================

        function clearError(input, errorId) {

            input.classList.remove("error");

            document.getElementById(errorId).textContent = "";

        }


        // ==================================================
        // FORM VALIDATION
        // ==================================================

        loginForm.addEventListener(
            "submit",
            function(event) {

                let valid = true;


                // ------------------------------
                // EMAIL VALIDATION
                // ------------------------------

                const emailPattern =
                    /^[^\s@]+@[^\s@]+\.[^\s@]+$/;


                if (email.value.trim() === "") {

                    email.classList.add("error");

                    document.getElementById(
                        "emailError"
                    ).textContent =
                        "Email is required.";

                    valid = false;

                } else if (
                    !emailPattern.test(email.value.trim())
                ) {

                    email.classList.add("error");

                    document.getElementById(
                        "emailError"
                    ).textContent =
                        "Please enter a valid email.";

                    valid = false;

                } else {

                    clearError(
                        email,
                        "emailError"
                    );

                }


                // ------------------------------
                // PASSWORD VALIDATION
                // ------------------------------

                if (password.value.trim() === "") {

                    password.classList.add("error");

                    document.getElementById(
                        "passwordError"
                    ).textContent =
                        "Password is required.";

                    valid = false;

                } else {

                    clearError(
                        password,
                        "passwordError"
                    );

                }


                // ==================================================
                // IF INVALID, STOP SUBMISSION
                // ==================================================

                if (!valid) {

                    event.preventDefault();

                } else {

                    // Remember email
                    if (rememberMe.checked) {

                        localStorage.setItem(
                            "rememberedEmail",
                            email.value.trim()
                        );

                    } else {

                        localStorage.removeItem(
                            "rememberedEmail"
                        );

                    }

                    // Form proceeds to submit normally to Laravel

                }

            }
        );

    </script>

</body>
</html>