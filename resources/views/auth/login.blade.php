@extends('layouts.app')

@section('content')
    <div class="login-container d-flex align-items-center justify-content-center min-vh-100 px-3 py-4">
        <div class="login-card card shadow-lg w-100">
            <div class="card-body p-4 p-md-5 text-center">

                <img src="{{ asset('images/SNC_logo_black.png') }}" alt="Company Logo" class="login-logo mb-3 mb-md-4">

                <h2 class="login-title fw-bolder mb-1" style="color: var(--color-secondary);">Welcome Back</h2>
                <p class="text-body-secondary small mb-4">Sign in to continue to the dashboard</p>

                <form method="POST" action="{{ route('login') }}" novalidate>
                    @csrf

                    <!-- {{-- Email Input --}} -->
                    <div class="input-group has-validation mb-3">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input id="email" type="email" name="email" value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror" placeholder="Email address"
                            aria-label="Email address" required autocomplete="email" autofocus>
                        @error('email')
                            <div class="invalid-feedback text-start">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- {{-- Password Input --}} -->
                    <div class="input-group has-validation mb-3">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input id="password" type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror" placeholder="Password"
                            aria-label="Password" required autocomplete="current-password">
                        <button class="btn btn-toggle-pw" type="button" id="togglePassword" aria-label="Show password">
                            <i class="fas fa-eye"></i>
                        </button>
                        @error('password')
                            <div class="invalid-feedback text-start">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- {{-- Remember Me & Forgot Password --}} -->
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                        <div class="form-check text-start">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label small" for="remember">Remember Me</label>
                        </div>
                        @if (Route::has('password.request'))
                            <a class="small text-decoration-none" href="{{ route('password.request') }}"
                                style="color: var(--color-secondary);">Forgot Password?</a>
                        @endif
                    </div>

                    <!-- {{-- Login Button --}} -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-login btn-lg fw-bold">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </button>
                    </div>
                </form>


            </div>
        </div>
    </div>

    @if (session('login_error_type'))
    <div class="modal fade" id="loginErrorModal" tabindex="-1" aria-labelledby="loginErrorModalLabel" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

                <!-- Header stripe coloured by error type -->
                <div class="modal-header border-0 pb-0
                    {{ session('login_error_type') === 'user_not_found' ? 'bg-warning-subtle' : 'bg-danger-subtle' }}">
                    <div class="w-100 text-center pt-3">
                        <div class="error-icon-wrap mb-2">
                            @if (session('login_error_type') === 'user_not_found')
                                <i class="fas fa-user-slash fa-2x text-warning"></i>
                            @else
                                <i class="fas fa-lock fa-2x text-danger"></i>
                            @endif
                        </div>
                        <h5 class="modal-title fw-bold" id="loginErrorModalLabel">
                            @if (session('login_error_type') === 'user_not_found')
                                User Not Found
                            @else
                                Incorrect Password
                            @endif
                        </h5>
                    </div>
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-2"
                        data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body text-center px-4 py-3">
                    <p class="text-body-secondary mb-0">
                        {{ session('login_error_message') }}
                    </p>
                </div>

                <div class="modal-footer border-0 justify-content-center pb-4">
                    <button type="button" class="btn btn-login px-4 fw-bold" data-bs-dismiss="modal">
                        Try Again
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const input = document.getElementById('password');
            const icon = this.querySelector('i');
            const show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            icon.classList.toggle('fa-eye', !show);
            icon.classList.toggle('fa-eye-slash', show);
            this.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
        });
    </script>

    @if (session('login_error_type'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var attempts = 0;
            function triggerModal() {
                var modalEl = document.getElementById('loginErrorModal');
                if (!modalEl) return;

                if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    var loginErrorModal = new bootstrap.Modal(modalEl, { backdrop: 'static', keyboard: false });
                    loginErrorModal.show();
                } else if (attempts < 20) {
                    attempts++;
                    setTimeout(triggerModal, 100);
                }
            }
            triggerModal();
        });
    </script>
    @endif

    <style>
        /* Adding theme colors locally for this standalone page */
        :root {
            --color-primary: #8B0019;
            --color-secondary: #004B8D;
        }

        #app>nav.navbar {
            display: none !important;
        }

        /* #app>nav.navbar {
                    visibility: hidden;
                } */
        /* #app[data-route="login"]>nav.navbar,
        body.page-login #app>nav.navbar {
            display: none !important;
        } */

        body {
            background: url('{{ asset('images/siam-nistran.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            font-size: clamp(0.875rem, 1.5vw, 1rem);
        }

        .login-card {
            max-width: 420px;
            border: 0;
            border-radius: 1rem;
        }

        .login-logo {
            display: block;
            width: 100%;
            max-width: 229px;
            /* size on mobile */
            height: auto;
            margin-left: auto;
            margin-right: auto;
        }

        @media (min-width: 768px) {
            .login-logo {
                max-width: 248px;
            }
        }

        .login-title {
            font-size: clamp(1.4rem, 5vw, 1.75rem);
        }

        .login-link {
            color: var(--color-secondary);
        }

        .form-floating>.form-control {
            height: calc(3rem + 2px);
            line-height: 1.25;
            font-size: 0.875rem;
        }

        @media (min-width: 768px) {
            .form-floating>.form-control {
                height: calc(3.5rem + 2px);
                font-size: 1rem;
            }
        }

        .form-floating>label {
            padding: 0.75rem 1rem;
            font-size: 0.85rem;
        }

        @media (min-width: 768px) {
            .form-floating>label {
                padding: 1rem 1.25rem;
                font-size: 1rem;
            }
        }

        .form-control:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 0.25rem rgba(230, 0, 18, 0.25);
        }

        .input-group-text {
            min-width: 2.75rem;
            justify-content: center;
            color: var(--color-secondary);
            background-color: var(--bs-tertiary-bg);
        }

        .input-group .form-control,
        .btn-toggle-pw {
            padding-top: 0.7rem;
            padding-bottom: 0.7rem;
        }

        .btn-toggle-pw {
            color: var(--bs-secondary-color);
            background-color: var(--bs-body-bg);
            border: 1px solid var(--bs-border-color);
        }

        .input-group:focus-within .input-group-text {
            border-color: var(--color-primary);
        }

        .input-group .form-control:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 0.2rem color-mix(in srgb, var(--color-primary) 25%, transparent);
        }

        /* Button */
        .btn-login {
            background-color: var(--color-primary);
            border-color: var(--color-primary);
            color: #fff;
            border-radius: 0.6rem;
        }

        .btn-login:hover,
        .btn-login:focus {
            background-color: var(--color-primary);
            border-color: var(--color-primary);
            color: #fff;
            filter: brightness(1.1);
        }
    </style>
@endsection