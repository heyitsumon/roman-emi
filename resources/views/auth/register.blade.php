@extends('layouts.app')

@section('content')

<style>
    body {
        margin: 0;
        min-height: 100vh;
        background: #f5f7fb;
        font-family: "Inter", "Segoe UI", sans-serif;
    }

    .register-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 30px 15px;
        background:
            radial-gradient(circle at 10% 20%, rgba(102, 16, 242, .12), transparent 30%),
            radial-gradient(circle at 90% 80%, rgba(111, 66, 193, .12), transparent 30%),
            #f5f7fb;
    }

    .register-card {
        width: 100%;
        max-width: 500px;
        background: #fff;
        border-radius: 25px;
        padding: 45px 40px;
        box-shadow: 0 25px 70px rgba(31, 38, 135, .15);
    }

    .register-header {
        text-align: center;
        margin-bottom: 32px;
    }

    .register-header h3 {
        color: #172033;
        font-size: 30px;
        font-weight: 800;
        margin-bottom: 8px;
    }

    .register-header p {
        color: #7b8494;
        font-size: 14px;
        margin: 0;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        color: #293241;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 8px;
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
        font-size: 17px;
        z-index: 2;
    }

    .modern-input {
        width: 100%;
        height: 52px;
        border: 1px solid #e1e6ee;
        border-radius: 12px;
        background: #f9fafc;
        padding: 0 16px 0 47px;
        font-size: 14px;
        color: #212936;
        outline: none;
        transition: .25s ease;
    }

    .modern-input:focus {
        background: #fff;
        border-color: #6610f2;
        box-shadow: 0 0 0 4px rgba(102, 16, 242, .10);
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

    .error-message {
        margin-top: 6px;
        color: #dc3545;
        font-size: 12px;
        font-weight: 500;
    }

    .register-btn {
        width: 100%;
        height: 53px;
        border: none;
        border-radius: 12px;
        background: linear-gradient(135deg, #6610f2, #6f42c1);
        color: #fff;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        box-shadow: 0 10px 25px rgba(102, 16, 242, .25);
        transition: .25s ease;
    }

    .register-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 14px 30px rgba(102, 16, 242, .3);
    }

    @media (max-width: 576px) {
        .register-wrapper {
            padding: 15px;
        }

        .register-card {
            padding: 35px 25px;
            border-radius: 20px;
        }

        .register-header h3 {
            font-size: 26px;
        }
    }
</style>

<div class="register-wrapper">

    <div class="register-card">

        <div class="register-header">
            <h3>📝 Create Your Account</h3>
            <p>Register to use the EMI Management System</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div class="form-group">

                <label for="name" class="form-label">
                    {{ __('Name') }}
                </label>

                <div class="input-wrapper">

                    <span class="input-icon">👤</span>

                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        class="modern-input @error('name') is-invalid @enderror"
                        placeholder="Enter your name"
                        required
                        autofocus
                    >

                </div>

                @error('name')
                    <div class="error-message">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            <!-- Email -->
            <div class="form-group">

                <label for="email" class="form-label">
                    {{ __('Email Address') }}
                </label>

                <div class="input-wrapper">

                    <span class="input-icon">✉</span>

                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="modern-input @error('email') is-invalid @enderror"
                        placeholder="Enter your email"
                        required
                    >

                </div>

                @error('email')
                    <div class="error-message">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            <!-- Password -->
            <div class="form-group">

                <label for="password" class="form-label">
                    {{ __('Password') }}
                </label>

                <div class="input-wrapper">

                    <span class="input-icon">🔒</span>

                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="modern-input password-input @error('password') is-invalid @enderror"
                        placeholder="Create a password"
                        required
                    >

                    <button
                        type="button"
                        class="password-toggle"
                        onclick="togglePassword('password', this)"
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


            <!-- Confirm Password -->
            <div class="form-group">

                <label for="password-confirm" class="form-label">
                    {{ __('Confirm Password') }}
                </label>

                <div class="input-wrapper">

                    <span class="input-icon">🔐</span>

                    <input
                        id="password-confirm"
                        type="password"
                        name="password_confirmation"
                        class="modern-input password-input"
                        placeholder="Confirm your password"
                        required
                    >

                    <button
                        type="button"
                        class="password-toggle"
                        onclick="togglePassword('password-confirm', this)"
                    >
                        👁
                    </button>

                </div>

            </div>


            <!-- Register -->
            <div class="d-grid mt-4">

                <button type="submit" class="register-btn">
                    📝 {{ __('Register') }}
                </button>

            </div>

        </form>

    </div>

</div>


<script>
    function togglePassword(id, button) {

        const input = document.getElementById(id);

        if (input.type === 'password') {
            input.type = 'text';
            button.textContent = '🙈';
        } else {
            input.type = 'password';
            button.textContent = '👁';
        }
    }
</script>

@endsection