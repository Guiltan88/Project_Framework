<!DOCTYPE html>
<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Register - Room Management System</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets-admin/img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets-admin/vendor/fonts/boxicons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets-admin/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets-admin/vendor/css/theme-default.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets-admin/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets-admin/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('assets-admin/vendor/css/pages/page-auth.css') }}" />

    <style>
        .auth-logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        .auth-logo img {
            height: 60px;
        }
        .system-name {
            font-size: 1.5rem;
            font-weight: 600;
            color: #696cff;
            margin-top: 0.5rem;
        }
        .password-strength {
            height: 5px;
            margin-top: 5px;
            border-radius: 2px;
        }
        .strength-weak { background-color: #ff3e1d; width: 25%; }
        .strength-fair { background-color: #ffa500; width: 50%; }
        .strength-good { background-color: #ffd700; width: 75%; }
        .strength-strong { background-color: #28a745; width: 100%; }
    </style>
</head>
<body>
    <!-- Content -->
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <!-- Register Card -->
                <div class="card">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="auth-logo">
                            <div class="app-brand justify-content-center">
                                <a href="{{ url('/') }}" class="app-brand-link gap-2">
                                    <span class="app-brand-logo demo">
                                        <!-- SVG Logo sama dengan login -->
                                        <svg width="25" viewBox="0 0 25 42" version="1.1" xmlns="http://www.w3.org/2000/svg">
                                            <!-- SVG Code sama seperti di login -->
                                        </svg>
                                    </span>
                                    <span class="app-brand-text demo text-body fw-bolder">Room Management</span>
                                </a>
                            </div>
                            <div class="system-name">Management System</div>
                        </div>
                        <!-- /Logo -->

                        <h4 class="mb-2">Daftar Akun Baru 🚀</h4>
                        <p class="mb-4">Mulai kelola ruangan dengan mudah!</p>

                        <!-- Validation Errors -->
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name"
                                           value="{{ old('name') }}"
                                           placeholder="Enter your full name" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           id="email" name="email"
                                           value="{{ old('email') }}"
                                           placeholder="Enter your email" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">No. Telepon (Opsional)</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                           id="phone" name="phone"
                                           value="{{ old('phone') }}"
                                           placeholder="081234567890">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="department" class="form-label">Departemen (Opsional)</label>
                                    <input type="text" class="form-control @error('department') is-invalid @enderror"
                                           id="department" name="department"
                                           value="{{ old('department') }}"
                                           placeholder="IT Department">
                                    @error('department')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3 form-password-toggle">
                                <label class="form-label" for="password">Password</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           name="password"
                                           placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                           required>
                                    <span class="input-group-text cursor-pointer toggle-password">
                                        <i class="bx bx-hide"></i>
                                    </span>
                                </div>
                                <div class="password-strength" id="passwordStrength"></div>
                                <small class="form-text text-muted">Minimal 8 karakter dengan kombinasi huruf dan angka</small>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 form-password-toggle">
                                <label class="form-label" for="password_confirmation">Konfirmasi Password</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password_confirmation"
                                           class="form-control @error('password_confirmation') is-invalid @enderror"
                                           name="password_confirmation"
                                           placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                           required>
                                    <span class="input-group-text cursor-pointer toggle-confirm-password">
                                        <i class="bx bx-hide"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input @error('terms') is-invalid @enderror"
                                           type="checkbox" id="terms" name="terms" {{ old('terms') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="terms">
                                        Saya setuju dengan
                                        <a href="javascript:void(0);">kebijakan privasi & persyaratan</a>
                                    </label>
                                    @error('terms')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <button class="btn btn-primary d-grid w-100" type="submit">Daftar</button>
                        </form>

                        <p class="text-center mt-3">
                            <span>Sudah punya akun?</span>
                            <a href="{{ route('login') }}">
                                <span>Login disini</span>
                            </a>
                        </p>
                    </div>
                </div>
                <!-- Register Card -->
            </div>
        </div>
    </div>
    <!-- / Content -->

    <!-- Core JS -->
    <script src="{{ asset('assets-admin/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets-admin/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets-admin/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets-admin/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets-admin/vendor/js/menu.js') }}"></script>
    <!-- Main JS -->
    <script src="{{ asset('assets-admin/js/main.js') }}"></script>

    <!-- Page JS -->
    <script>
        // Toggle password visibility
        document.querySelectorAll('.toggle-password, .toggle-confirm-password').forEach(function(element) {
            element.addEventListener('click', function() {
                const inputId = this.classList.contains('toggle-password') ? 'password' : 'password_confirmation';
                const passwordInput = document.getElementById(inputId);
                const icon = this.querySelector('i');

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    icon.classList.remove('bx-hide');
                    icon.classList.add('bx-show');
                } else {
                    passwordInput.type = 'password';
                    icon.classList.remove('bx-show');
                    icon.classList.add('bx-hide');
                }
            });
        });

        // Password strength indicator
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('passwordStrength');
            let strength = 0;

            // Length check
            if (password.length >= 8) strength += 25;
            if (password.length >= 12) strength += 25;

            // Character variety checks
            if (/[A-Z]/.test(password)) strength += 25;
            if (/[0-9]/.test(password)) strength += 25;
            if (/[^A-Za-z0-9]/.test(password)) strength += 25;

            // Cap at 100
            strength = Math.min(strength, 100);

            // Update strength bar
            strengthBar.className = 'password-strength';
            if (strength < 25) {
                strengthBar.classList.add('strength-weak');
            } else if (strength < 50) {
                strengthBar.classList.add('strength-fair');
            } else if (strength < 75) {
                strengthBar.classList.add('strength-good');
            } else {
                strengthBar.classList.add('strength-strong');
            }
        });
    </script>
</body>
</html>
