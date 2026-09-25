    @extends('layouts.app')

    @section('content')
    <div class="register-container d-flex align-items-center justify-content-center min-vh-100 py-5">
        <div class="register-card card shadow-lg">
            <div class="card-body p-4 p-md-5 text-center">
                
                <img src="{{ asset('images/logo.png') }}" alt="Company Logo" class="register-logo mb-4">
                
                <h2 class="fw-bolder" style="color: var(--color-secondary);">Create an Account</h2>
                <p class="text-muted mb-4">Sign up to get started</p>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    {{-- Name Input --}}
                    <div class="form-floating mb-3">
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Full Name">
                        <label for="name">Full Name</label>
                        @error('name')
                            <div class="invalid-feedback text-start"><strong>{{ $message }}</strong></div>
                        @enderror
                    </div>

                    {{-- Email Input --}}
                    <div class="form-floating mb-3">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email Address">
                        <label for="email">Email Address</label>
                        @error('email')
                            <div class="invalid-feedback text-start"><strong>{{ $message }}</strong></div>
                        @enderror
                    </div>

                    {{-- Password Input --}}
                    <div class="form-floating mb-3">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Password">
                        <label for="password">Password</label>
                        @error('password')
                            <div class="invalid-feedback text-start"><strong>{{ $message }}</strong></div>
                        @enderror
                    </div>

                    {{-- Confirm Password Input --}}
                    <div class="form-floating mb-3">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password">
                        <label for="password-confirm">Confirm Password</label>
                    </div>

                    {{-- Role Selection --}}
                    <div class="form-floating mb-3">
                        <select id="role" class="form-select @error('role') is-invalid @enderror" name="role" required>
                            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="driver" {{ old('role') == 'driver' ? 'selected' : '' }}>Driver</option>
                            <option value="checker" {{ old('role') == 'checker' ? 'selected' : '' }}>Checker</option>
                        </select>
                        <label for="role">Select Role</label>
                        @error('role')
                            <div class="invalid-feedback text-start"><strong>{{ $message }}</strong></div>
                        @enderror
                    </div>

                    <!-- Warehouse Selection (Conditional) -->
                    <div id="warehouse-field" class="form-floating mb-3" style="display: none;">
                        <select id="warehouse_id" class="form-select @error('warehouse_id') is-invalid @enderror" name="warehouse_id">
                            <option value="" disabled selected>-- Select Warehouse --</option>
                            @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->warehouse_id }}" {{ old('warehouse_id') == $warehouse->warehouse_id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                        <label for="warehouse_id">Assign to Warehouse</label>
                        @error('warehouse_id')
                            <div class="invalid-feedback text-start"><strong>{{ $message }}</strong></div>
                        @enderror
                    </div>

                    <!-- Driver Fields (Conditional) -->
                    <div id="driver-fields" style="display: none;">
                        <div class="form-floating mb-3">
                            <input id="license_no" type="text" class="form-control @error('license_no') is-invalid @enderror" name="license_no" value="{{ old('license_no') }}" autocomplete="license_no" placeholder="License Number">
                            <label for="license_no">License Number</label>
                            @error('license_no')
                                <div class="invalid-feedback text-start"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div>
                        <div class="form-floating mb-3">
                            <select id="status" class="form-select @error('status') is-invalid @enderror" name="status">
                                <option value="Active" {{ old('status', 'Active') == 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="Suspended" {{ old('status') == 'Suspended' ? 'selected' : '' }}>Suspended</option>
                                <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <label for="status">Driver Status</label>
                            @error('status')
                                <div class="invalid-feedback text-start"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div>
                    </div>
                    
                    {{-- Register Button --}}
                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-lg text-white" style="background-color: var(--color-primary);">
                            <i class="fas fa-user-plus me-2"></i>Register
                        </button>
                    </div>
                </form>
                
                {{-- Link to Login Page --}}
                <div class="mt-4">
                    <p class="text-muted mb-0">Already have an account? <a href="{{ route('login') }}" class="fw-bold text-decoration-none" style="color: var(--color-secondary);">Sign in</a></p>
                </div>

            </div>
        </div>
    </div>

    <style>
        /* Adding theme colors locally for this standalone page */
        :root {
            --color-primary: #E60012;
            --color-secondary: #004B8D;
        }

        /* Remove Navbar for register page if it's rendered by layouts.app */
        #app > nav.navbar {
            display: none;
        }

        body {
            background: url('{{ asset('images/siam-nistran.jpg') }}') no-repeat center center fixed;
            background-size: cover;
        }

        .register-card {
            width: 100%;
            max-width: 480px; /* Slightly wider for more fields */
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 1rem;
            border-top: 5px solid var(--color-primary);
        }

        .register-logo {
            width: 100px;
            height: auto;
        }

        .form-floating > .form-control,
        .form-floating > .form-select {
            height: calc(3.5rem + 2px);
            line-height: 1.25;
        }
        .form-floating > label {
            padding: 1rem 1.25rem;
        }
        .form-control:focus,
        .form-select:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 0.25rem rgba(230, 0, 18, 0.25);
        }
    </style>

    {{-- UNCHANGED SCRIPT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const roleSelect = document.getElementById('role');
            const driverFields = document.getElementById('driver-fields');
            const warehouseField = document.getElementById('warehouse-field');

            function toggleFields() {
                const role = roleSelect.value;
                driverFields.style.display = (role === 'driver') ? 'block' : 'none';
                warehouseField.style.display = (role === 'checker') ? 'block' : 'none';
            }

            // Run on page load in case of old() values
            toggleFields();

            // Run on role change
            roleSelect.addEventListener('change', toggleFields);
        });
    </script>
    @endsection
