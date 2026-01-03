<!DOCTYPE html>
<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Reset Password - Room Management System</title>

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
        .success-message {
            color: #28a745;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }
    </style>
</head>
<body>
    <!-- Content -->
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-4">
                <!-- Reset Password Card -->
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

                        <h4 class="mb-2">Reset Password 🔒</h4>
                        <p class="mb-4">Masukkan password baru Anda</p>

                        <!-- Session Status -->
                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('status') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

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

                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf

                            <!-- Password Reset Token -->
                            <input type="hidden" name="token" value="{{ $request->route('token') }}">

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email"
                                       value="{{ old('email', $request->email) }}"
                                       placeholder="Enter your email"
                                       required autofocus>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password Baru</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                       id="password" name="password"
                                       placeholder="Enter new password"
                                       required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                <input type="password" class="form-control"
                                       id="password_confirmation" name="password_confirmation"
                                       placeholder="Confirm new password"
                                       required>
                            </div>

                            <button class="btn btn-primary d-grid w-100" type="submit">Reset Password</button>
                        </form>

                        <div class="text-center mt-3">
                            <a href="{{ route('login') }}" class="d-flex align-items-center justify-content-center">
                                <i class="bx bx-chevron-left scaleX-n1-rtl bx-sm"></i>
                                Kembali ke login
                            </a>
                        </div>
                    </div>
                </div>
                <!-- /Reset Password Card -->
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
</body>
</html>
