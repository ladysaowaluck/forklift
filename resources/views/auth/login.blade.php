@extends('layouts.app')

@section('content')
<div class="login-container d-flex align-items-center justify-content-center min-vh-100 py-3 py-md-5 px-2 px-md-3">
    <div class="login-card card shadow-lg">
        <div class="card-body p-3 p-sm-4 p-md-5 text-center">
            
            <img src="{{ asset('images/Logo_Nissin.png') }}" alt="Company Logo" class="login-logo mb-3 mb-md-4">
            
            <h2 class="fw-bolder fs-3 fs-md-2" style="color: var(--color-secondary);">Welcome Back</h2>
            <p class="text-muted small fs-md-6 mb-3 mb-md-4">Sign in to continue to the dashboard</p>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Email Input --}}
                <div class="form-floating mb-2 mb-md-3">
                    <input id="email" type="email" class="form-control form-control-sm form-control-md-normal @error('email') is-invalid @enderror" 
                           name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="name@example.com">
                    <label for="email">Email Address</label>
                    @error('email')
                        <div class="invalid-feedback text-start">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password Input --}}
                <div class="form-floating mb-2 mb-md-3">
                    <input id="password" type="password" class="form-control form-control-sm form-control-md-normal @error('password') is-invalid @enderror" 
                           name="password" required autocomplete="current-password" placeholder="Password">
                    <label for="password">Password</label>
                     @error('password')
                        <div class="invalid-feedback text-start">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Remember Me & Forgot Password --}}
                <div class="d-flex justify-content-between align-items-center mb-3 mb-md-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label small" for="remember">Remember Me</label>
                    </div>
                    @if (Route::has('password.request'))
                        <a class="small text-decoration-none" href="{{ route('password.request') }}" style="color: var(--color-secondary);">Forgot Password?</a>
                    @endif
                </div>

                {{-- Login Button --}}
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-sm btn-md-normal text-white fw-bold py-2" style="background-color: var(--color-primary); border-color: var(--color-primary);">
                        <i class="fas fa-sign-in-alt me-2"></i>Login
                    </button>
                </div>
            </form>
            

        </div>
    </div>
</div>

<style>
    /* Adding theme colors locally for this standalone page */
    :root {
        --color-primary: #8B0019;
        --color-secondary: #004B8D;
    }

    /* Remove Navbar for login page if it's rendered by layouts.app */
    #app > nav.navbar {
        display: none;
    }

    body {
        background: url('{{ asset('images/siam-nistran.jpg') }}') no-repeat center center fixed;
        background-size: cover;
        font-size: clamp(0.875rem, 1.5vw, 1rem);
    }

    .login-card {
        width: 100%;
        max-width: 450px;
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 1rem;
        border-top: 5px solid var(--color-primary);
    }

    .login-logo {
        width: clamp(70px, 15vw, 100px);
        height: auto;
    }

    /* Lady:
    .form-floating > .form-control {
        height: calc(3.5rem + 2px);
        line-height: 1.25;
    }
    .form-floating > label {
        padding: 1rem 1.25rem;
    }
    */
    /* unnecessary */

    /* Gemini: Scaled down form floating height on mobile touch screens */
    .form-floating > .form-control {
        height: calc(3rem + 2px);
        line-height: 1.25;
        font-size: 0.875rem;
    }

    @media (min-width: 768px) {
        .form-floating > .form-control {
            height: calc(3.5rem + 2px);
            font-size: 1rem;
        }
    }

    .form-floating > label {
        padding: 0.75rem 1rem;
        font-size: 0.85rem;
    }

    @media (min-width: 768px) {
        .form-floating > label {
            padding: 1rem 1.25rem;
            font-size: 1rem;
        }
    }

    .form-control:focus {
        border-color: var(--color-primary);
        box-shadow: 0 0 0 0.25rem rgba(230, 0, 18, 0.25);
    }
</style>
@endsection