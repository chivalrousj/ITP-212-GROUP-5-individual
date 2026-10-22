{{-- Page: Register | Author: Jemarie Manggarai | ITP 212 Group 5 --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register - SPAMAST SSG Event Management System</title>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            background: linear-gradient(135deg, #eef8ff, #ffffff);
            color: #17324d;
        }

        /* ================= CARD ================= */

        .register-card {
            width: 100%;
            max-width: 650px;
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 50px rgba(8, 122, 202, 0.15);
        }

        /* ================= HEADER ================= */

        .header { text-align: center; margin-bottom: 30px; }

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

        .header h1 { color: #103b60; font-size: 30px; margin-bottom: 10px; }
        .header p { color: #71869a; font-size: 14px; }

        /* ================= FORM ================= */

        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
        .form-group { display: flex; flex-direction: column; }
        .full-width { grid-column: 1 / -1; }

        label { margin-bottom: 7px; font-size: 14px; font-weight: bold; color: #17324d; }
        label span { color: #087aca; }

        input, select {
            width: 100%;
            padding: 13px 14px;
            border: 1px solid #d5e3ed;
            border-radius: 8px;
            font-size: 14px;
            font-family: Arial, sans-serif;
            outline: none;
            background: white;
            color: #17324d;
            transition: 0.2s;
        }

        input:focus, select:focus {
            border-color: #087aca;
            box-shadow: 0 0 0 3px rgba(8, 122, 202, 0.1);
        }

        input.error, select.error { border-color: #dc3545; }

        .error-message { color: #dc3545; font-size: 12px; margin-top: 5px; min-height: 14px; }

        /* ================= PASSWORD ================= */

        .password-container { position: relative; }
        .password-container input { padding-right: 70px; }

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

        /* ================= STRENGTH ================= */

        .strength-container { margin-top: 8px; }
        .strength-bar { height: 5px; background: #e5edf3; border-radius: 10px; overflow: hidden; }
        .strength-fill { height: 100%; width: 0%; background: #087aca; transition: 0.3s; }
        .strength-text { margin-top: 5px; font-size: 12px; color: #71869a; }

        /* ================= BUTTON ================= */

        .register-btn {
            width: 100%;
            margin-top: 10px;
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

        .register-btn:hover { background: #075e9d; transform: translateY(-1px); }

        /* ================= LOGIN ================= */

        .login-link { text-align: center; margin-top: 22px; font-size: 14px; color: #71869a; }
        .login-link a { color: #087aca; text-decoration: none; font-weight: bold; }
        .login-link a:hover { text-decoration: underline; }

        /* ================= RESPONSIVE ================= */

        @media (max-width: 600px) {
            body { padding: 20px 15px; }
            .register-card { padding: 28px 22px; }
            .form-grid { grid-template-columns: 1fr; gap: 15px; }
            .full-width { grid-column: auto; }
            .header h1 { font-size: 25px; }
        }
    </style>
</head>

<body>

    <div class="register-card">

        <!-- HEADER -->
        <div class="header">
            <div class="logo">SPAMAST SSG</div>
            <h1>Create an Account</h1>
            <p>Register to access the SPAMAST SSG Event Management System.</p>
        </div>

        <!-- REGISTRATION FORM -->
        <form id="registerForm" method="POST" action="{{ route('register') }}" novalidate>
            @csrf

            <div class="form-grid">

                <!-- FIRST NAME -->
                <div class="form-group">
                    <label for="firstName">First Name <span>*</span></label>
                    <input type="text" id="firstName" name="first_name"
                           value="{{ old('first_name') }}" placeholder="Enter first name">
                    <div class="error-message" id="firstNameError">{{ $errors->first('first_name') }}</div>
                </div>

                <!-- MIDDLE NAME -->
                <div class="form-group">
                    <label for="middleName">Middle Name</label>
                    <input type="text" id="middleName" name="middle_name"
                           value="{{ old('middle_name') }}" placeholder="Enter middle name">
                    <div class="error-message">{{ $errors->first('middle_name') }}</div>
                </div>

                <!-- LAST NAME -->
                <div class="form-group">
                    <label for="lastName">Last Name <span>*</span></label>
                    <input type="text" id="lastName" name="last_name"
                           value="{{ old('last_name') }}" placeholder="Enter last name">
                    <div class="error-message" id="lastNameError">{{ $errors->first('last_name') }}</div>
                </div>

                <!-- EMAIL -->
                <div class="form-group">
                    <label for="email">Email <span>*</span></label>
                    <input type="email" id="email" name="email"
                           value="{{ old('email') }}" placeholder="Enter email address">
                    <div class="error-message" id="emailError">{{ $errors->first('email') }}</div>
                </div>

                <!-- ROLE -->
                <div class="form-group full-width">
                    <label for="role">Role <span>*</span></label>
                    <select id="role" name="role">
                        <option value="">Select your role</option>
                        <option value="Student" {{ old('role') == 'Student' ? 'selected' : '' }}>Student</option>
                        <option value="Office" {{ old('role') == 'Office' ? 'selected' : '' }}>Office</option>
                        <option value="Adviser" {{ old('role') == 'Adviser' ? 'selected' : '' }}>Adviser</option>
                    </select>
                    <div class="error-message" id="roleError"></div>
                </div>

                <!-- PASSWORD -->
                <div class="form-group">
                    <label for="password">Password <span>*</span></label>

                    <div class="password-container">
                        <input type="password" id="password" name="password" placeholder="Create password">
                        <button type="button" class="show-password"
                                onclick="togglePassword('password', this)">Show</button>
                    </div>

                    <!-- PASSWORD STRENGTH -->
                    <div class="strength-container">
                        <div class="strength-bar">
                            <div class="strength-fill" id="strengthFill"></div>
                        </div>
                        <div class="strength-text" id="strengthText">Enter a password</div>
                    </div>

                    <div class="error-message" id="passwordError">{{ $errors->first('password') }}</div>
                </div>

                <!-- CONFIRM PASSWORD -->
                <div class="form-group">
                    <label for="confirmPassword">Confirm Password <span>*</span></label>

                    <div class="password-container">
                        <input type="password" id="confirmPassword" name="password_confirmation"
                               placeholder="Confirm password">
                        <button type="button" class="show-password"
                                onclick="togglePassword('confirmPassword', this)">Show</button>
                    </div>

                    <div class="error-message" id="confirmPasswordError"></div>
                </div>

                <!-- REGISTER BUTTON -->
                <div class="form-group full-width">
                    <button type="submit" class="register-btn">Create Account</button>
                </div>

            </div>
        </form>

        <!-- LOGIN LINK -->
        <div class="login-link">
            Already have an account?
            <a href="{{ route('login') }}">Login here</a>
        </div>

    </div>


    <script>

        // ==================================================
        // GET ELEMENTS
        // ==================================================

        const form = document.getElementById("registerForm");

        const firstName = document.getElementById("firstName");
        const lastName = document.getElementById("lastName");
        const email = document.getElementById("email");
        const role = document.getElementById("role");

        const password = document.getElementById("password");
        const confirmPassword = document.getElementById("confirmPassword");

        const strengthFill = document.getElementById("strengthFill");
        const strengthText = document.getElementById("strengthText");


        // ==================================================
        // PASSWORD STRENGTH
        // ==================================================

        password.addEventListener("input", function () {

            const value = password.value;
            let strength = 0;

            if (value.length >= 8) strength++;
            if (/[A-Z]/.test(value)) strength++;
            if (/[a-z]/.test(value)) strength++;
            if (/[0-9]/.test(value)) strength++;
            if (/[^A-Za-z0-9]/.test(value)) strength++;

            if (value.length === 0) {
                strengthFill.style.width = "0%";
                strengthText.textContent = "Enter a password";
            } else if (strength <= 2) {
                strengthFill.style.width = "35%";
                strengthText.textContent = "Weak password";
            } else if (strength === 3 || strength === 4) {
                strengthFill.style.width = "70%";
                strengthText.textContent = "Medium password";
            } else {
                strengthFill.style.width = "100%";
                strengthText.textContent = "Strong password";
            }

        });


        // ==================================================
        // SHOW / HIDE PASSWORD
        // ==================================================

        function togglePassword(inputId, button) {

            const input = document.getElementById(inputId);

            if (input.type === "password") {
                input.type = "text";
                button.textContent = "Hide";
            } else {
                input.type = "password";
                button.textContent = "Show";
            }

        }


        // ==================================================
        // ERROR MESSAGE
        // ==================================================

        function showError(input, errorId, message) {
            input.classList.add("error");
            document.getElementById(errorId).textContent = message;
        }

        function clearError(input, errorId) {
            input.classList.remove("error");
            document.getElementById(errorId).textContent = "";
        }


        // ==================================================
        // FORM VALIDATION
        // (only blocks the submit if something is invalid;
        //  valid forms are sent to Laravel)
        // ==================================================

        form.addEventListener("submit", function (event) {

            let valid = true;

            // FIRST NAME
            if (firstName.value.trim() === "") {
                showError(firstName, "firstNameError", "First name is required.");
                valid = false;
            } else {
                clearError(firstName, "firstNameError");
            }

            // LAST NAME
            if (lastName.value.trim() === "") {
                showError(lastName, "lastNameError", "Last name is required.");
                valid = false;
            } else {
                clearError(lastName, "lastNameError");
            }

            // EMAIL
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (email.value.trim() === "") {
                showError(email, "emailError", "Email is required.");
                valid = false;
            } else if (!emailPattern.test(email.value)) {
                showError(email, "emailError", "Please enter a valid email.");
                valid = false;
            } else {
                clearError(email, "emailError");
            }

            // ROLE
            if (role.value === "") {
                showError(role, "roleError", "Please select a role.");
                valid = false;
            } else {
                clearError(role, "roleError");
            }

            // PASSWORD
            if (password.value === "") {
                showError(password, "passwordError", "Password is required.");
                valid = false;
            } else if (password.value.length < 8) {
                showError(password, "passwordError", "Password must be at least 8 characters.");
                valid = false;
            } else {
                clearError(password, "passwordError");
            }

            // CONFIRM PASSWORD
            if (confirmPassword.value === "") {
                showError(confirmPassword, "confirmPasswordError", "Please confirm your password.");
                valid = false;
            } else if (confirmPassword.value !== password.value) {
                showError(confirmPassword, "confirmPasswordError", "Passwords do not match.");
                valid = false;
            } else {
                clearError(confirmPassword, "confirmPasswordError");
            }

            // STOP ONLY IF INVALID
            if (!valid) {
                event.preventDefault();
            }

        });

    </script>

</body>
</html>