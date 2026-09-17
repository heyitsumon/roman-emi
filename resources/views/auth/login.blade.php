@extends('layouts.app')

@section('content')

<style>
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        min-height: 100vh;
        background: #f4f7fb;
        font-family: "Inter", "Segoe UI", sans-serif;
    }

    .login-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 30px 15px;
        background:
            radial-gradient(circle at 10% 20%, rgba(13, 110, 253, .15), transparent 30%),
            radial-gradient(circle at 90% 80%, rgba(111, 66, 193, .15), transparent 30%),
            #f5f7fb;
    }

    .login-box {
        width: 100%;
        max-width: 1000px;
        min-height: 580px;
        display: flex;
        overflow: hidden;
        background: #fff;
        border-radius: 25px;
        box-shadow: 0 25px 70px rgba(31, 38, 135, .15);
    }

    /* LEFT SIDE */
    .login-brand {
        width: 45%;
        padding: 55px 45px;
        color: #fff;
        display: flex;
        flex-direction: column;
        justify-content: center;
        position: relative;
        overflow: hidden;
        background: linear-gradient(
            145deg,
            #0d6efd 0%,
            #4f46e5 50%,
            #6610f2 100%
        );
    }

    .login-brand::before {
        content: "";
        position: absolute;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        background: rgba(255,255,255,.08);
        top: -120px;
        right: -100px;
    }

    .login-brand::after {
        content: "";
        position: absolute;
        width: 250px;
        height: 250px;
        border-radius: 50%;
        background: rgba(255,255,255,.06);
        bottom: -100px;
        left: -100px;
    }

    .brand-content {
        position: relative;
        z-index: 2;
    }

    .brand-icon {
        width: 70px;
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 18px;
        background: rgba(255,255,255,.16);
        border: 1px solid rgba(255,255,255,.25);
        font-size: 34px;
        margin-bottom: 25px;
        backdrop-filter: blur(10px);
    }

    .login-brand h1 {
        font-size: 38px;
        font-weight: 800;
        margin-bottom: 15px;
        letter-spacing: -1px;
    }

    .login-brand p {
        font-size: 16px;
        line-height: 1.7;
        color: rgba(255,255,255,.85);
        max-width: 360px;
    }

    .feature-list {
        margin-top: 30px;
        padding: 0;
        list-style: none;
    }

    .feature-list li {
        margin-bottom: 15px;
        font-size: 14px;
        color: rgba(255,255,255,.9);
    }

    .feature-list i {
        margin-right: 10px;
    }

    /* RIGHT SIDE */
    .login-form-area {
        width: 55%;
        padding: 55px 60px;
        display: flex;
        align-items: center;
    }

    .login-form {
        width: 100%;
        max-width: 430px;
        margin: auto;
    }

    .login-header {
        margin-bottom: 35px;
    }

    .login-header h2 {
        color: #172033;
        font-size: 30px;
        font-weight: 800;
        margin-bottom: 8px;
    }

    .login-header p {
        color: #7b8494;
        margin: 0;
        font-size: 14px;
    }

    .form-group {
        margin-bottom: 22px;
    }

    .form-label {
        color: #293241;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 9px;
    }

    .input-wrapper {
        position: relative;
    }

    .input-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #9aa4b2;
        font-size: 18px;
        z-index: 3;
    }

    .modern-input {
        width: 100%;
        height: 52px;
        border: 1px solid #e1e6ee;
        border-radius: 12px;
        background: #f9fafc;
        padding: 0 16px 0 48px;
        font-size: 14px;
        color: #212936;
        outline: none;
        transition: all .25s ease;
    }

    .modern-input:focus {
        background: #fff;
        border-color: #0d6efd;
        box-shadow: 0 0 0 4px rgba(13,110,253,.10);
    }

    .password-toggle {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        border: 0;
        background: transparent;
        color: #8c96a5;
        cursor: pointer;
        font-size: 17px;
    }

    .password-input {
        padding-right: 48px;
    }

    .remember-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 5px 0 25px;
    }

    .remember-label {
        font-size: 13px;
        color: #697386;
        cursor: pointer;
    }

    .remember-label input {
        margin-right: 7px;
    }

    .forgot-link {
        color: #0d6efd;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
    }

    .forgot-link:hover {
        text-decoration: underline;
    }

    .login-btn {
        width: 100%;
        height: 53px;
        border: none;
        border-radius: 12px;
        background: linear-gradient(135deg, #0d6efd, #6610f2);
        color: #fff;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        box-shadow: 0 10px 25px rgba(13,110,253,.25);
        transition: all .25s ease;
    }

    .login-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 14px 30px rgba(13,110,253,.3);
    }

    .login-btn:active {
        transform: translateY(0);
    }

    .error-message {
        margin-top: 7px;
        color: #dc3545;
        font-size: 12px;
        font-weight: 500;
    }

    .security-text {
        text-align: center;
        margin-top: 25px;
        color: #9aa4b2;
        font-size: 12px;
    }

    /* MOBILE */
    @media (max-width: 768px) {

        .login-wrapper {
            padding: 15px;
        }

        .login-box {
            min-height: auto;
            flex-direction: column;
            border-radius: 20px;
        }

        .login-brand {
            width: 100%;
            padding: 35px 30px;
            text-align: center;
        }

        .brand-icon {
            margin: 0 auto 18px;
        }

        .login-brand h1 {
            font-size: 28px;
        }

        .login-brand p {
            margin: auto;
        }

        .feature-list {
            display: none;
        }

        .login-form-area {
            width: 100%;
            padding: 40px 25px;
        }

        .login-header h2 {
            font-size: 26px;
        }
    }
</style>


<div class="login-wrapper">

    <div class="login-box">

        <!-- LEFT BRANDING -->
        <div class="login-brand">

            <div class="brand-content">


                <h1>EMI Manager</h1>

               
            </div>

        </div>


        <!-- LOGIN FORM -->
        <div class="login-form-area">

            <div class="login-form">

                <div class="login-header">

                    <h2>Welcome Back 👋</h2>

                    <p>
                        Sign in to continue to your dashboard
                    </p>

                </div>


                <form method="POST" action="{{ route('login') }}">

                    @csrf


                    <!-- EMAIL -->
                    <div class="form-group">

                        <label for="email" class="form-label">
                            Email Address
                        </label>

                        <div class="input-wrapper">

                            <span class="input-icon">
                                ✉
                            </span>

                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="modern-input @error('email') is-invalid @enderror"
                                placeholder="Enter your email"
                                required
                                autocomplete="email"
                                autofocus
                            >

                        </div>

                        @error('email')
                            <div class="error-message">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <!-- PASSWORD -->
                    <div class="form-group">

                        <label for="password" class="form-label">
                            Password
                        </label>

                        <div class="input-wrapper">

                            <span class="input-icon">
                                🔒
                            </span>

                            <input
                                id="password"
                                type="password"
                                name="password"
                                class="modern-input password-input @error('password') is-invalid @enderror"
                                placeholder="Enter your password"
                                required
                                autocomplete="current-password"
                            >

                            <button
                                type="button"
                                class="password-toggle"
                                onclick="togglePassword()"
                                aria-label="Show password"
                            >
                                👁
                            </button>

                        </div>

                        @error('password')
                            <div class="error-message">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <!-- REMEMBER + FORGOT -->
                    <div class="remember-row">

                        <label class="remember-label">

                            <input
                                type="checkbox"
                                name="remember"
                                id="remember"
                                {{ old('remember') ? 'checked' : '' }}
                            >

                            Remember me

                        </label>


                        @if (Route::has('password.request'))

                            <a
                                href="{{ route('password.request') }}"
                                class="forgot-link"
                            >
                                Forgot Password?
                            </a>

                        @endif

                    </div>


                    <!-- LOGIN BUTTON -->
                    <button type="submit" class="login-btn">

                        🔐 Sign In

                    </button>


                    <div class="security-text">

                        🔒 Your information is securely protected

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>


<script>

    function togglePassword() {

        const password = document.getElementById('password');

        const button = document.querySelector('.password-toggle');

        if (password.type === 'password') {

            password.type = 'text';

            button.textContent = '🙈';

        } else {

            password.type = 'password';

            button.textContent = '👁';

        }

    }

</script>

@endsection