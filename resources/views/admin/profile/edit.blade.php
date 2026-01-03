@extends('Layouts.app')
@section('title', 'Edit Admin Profile')

@section('content')
    <!-- Navigation Pills -->
    <ul class="nav nav-pills flex-column flex-md-row mb-3">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.profile.index') }}">
                <i class="bx bx-user me-1"></i> Profile
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('admin.profile.edit') }}">
                <i class="bx bx-edit me-1"></i> Edit Profile
            </a>
        </li>
    </ul>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Profile Details</h5>

                <!-- Photo Upload Section -->
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block">
                            <img
                                src="{{ $user->photo ? asset('storage/' . $user->photo) : asset('assets-admin/img/avatars/default.png') }}"
                                alt="user-avatar"
                                class="d-block rounded-circle mb-3"
                                height="150"
                                width="150"
                                id="editProfileAvatar"
                            />
                            <label for="photoUpload" class="btn btn-sm btn-primary position-absolute bottom-0 end-0 rounded-circle">
                                <i class="bx bx-camera"></i>
                            </label>
                        </div>
                        <input
                            type="file"
                            id="photoUpload"
                            class="d-none"
                            accept="image/*"
                        >
                        <p class="text-muted small mt-2">Click camera icon to change profile photo</p>
                    </div>
                </div>

                <hr class="my-0">

                <!-- Edit Form -->
                <div class="card-body">
                    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" id="editProfileForm">
                        @csrf
                        @method('PUT')

                        <!-- Hidden photo input for form submission -->
                        <input type="file" name="photo" id="photoInput" class="d-none">

                        <div class="row">
                            <!-- Name Field -->
                            <div class="mb-3 col-md-6">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text">
                                        <i class="bx bx-user"></i>
                                    </span>
                                    <input
                                        type="text"
                                        class="form-control @error('name') is-invalid @enderror"
                                        id="name"
                                        name="name"
                                        value="{{ old('name', $user->name) }}"
                                        placeholder="Enter your full name"
                                        required
                                    />
                                </div>
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email Field -->
                            <div class="mb-3 col-md-6">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text">
                                        <i class="bx bx-envelope"></i>
                                    </span>
                                    <input
                                        type="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        id="email"
                                        name="email"
                                        value="{{ old('email', $user->email) }}"
                                        placeholder="Enter your email address"
                                        required
                                    />
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Password Change Section -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Change Password (Optional)</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label for="password" class="form-label">New Password</label>
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text">
                                                <i class="bx bx-lock"></i>
                                            </span>
                                            <input
                                                type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                id="password"
                                                name="password"
                                                placeholder="Leave blank to keep current"
                                            />
                                            <button class="btn btn-outline-secondary" type="button" id="togglePasswordBtn">
                                                <i class="bx bx-hide" id="passwordToggleIcon"></i>
                                            </button>
                                        </div>
                                        @error('password')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3 col-md-6">
                                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text">
                                                <i class="bx bx-lock-alt"></i>
                                            </span>
                                            <input
                                                type="password"
                                                class="form-control"
                                                id="password_confirmation"
                                                name="password_confirmation"
                                                placeholder="Confirm new password"
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-text">
                                    <i class="bx bx-info-circle me-1"></i>
                                    Password must be at least 6 characters long
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-2">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.profile.index') }}" class="btn btn-outline-secondary">
                                    <i class="bx bx-arrow-back me-2"></i>Back to Profile
                                </a>
                                <div>
                                    <button type="reset" class="btn btn-outline-warning me-2">
                                        <i class="bx bx-reset me-2"></i>Reset
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bx bx-save me-2"></i>Save Changes
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Error Alert -->
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                            <i class="bx bx-error-circle me-2"></i>
                            Please check the form for errors
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>


<script>
// Photo upload preview
document.getElementById('photoUpload')?.addEventListener('change', function(e) {
    if (e.target.files && e.target.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('editProfileAvatar').src = e.target.result;
        }
        reader.readAsDataURL(e.target.files[0]);

        // Set file to hidden input for form submission
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(e.target.files[0]);
        document.getElementById('photoInput').files = dataTransfer.files;
    }
});

// Camera button click
document.querySelector('.btn-sm.btn-primary.position-absolute')?.addEventListener('click', function() {
    document.getElementById('photoUpload').click();
});

// Password toggle
document.getElementById('togglePasswordBtn')?.addEventListener('click', function() {
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    const toggleIcon = document.getElementById('passwordToggleIcon');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        if (confirmInput) confirmInput.type = 'text';
        toggleIcon.className = 'bx bx-show';
    } else {
        passwordInput.type = 'password';
        if (confirmInput) confirmInput.type = 'password';
        toggleIcon.className = 'bx bx-hide';
    }
});

// Form validation
document.getElementById('editProfileForm')?.addEventListener('submit', function(e) {
    const password = document.getElementById('password')?.value;
    const confirmPassword = document.getElementById('password_confirmation')?.value;

    if (password && password !== confirmPassword) {
        e.preventDefault();
        alert('Passwords do not match!');
        return false;
    }

    if (password && password.length < 6) {
        e.preventDefault();
        alert('Password must be at least 6 characters long!');
        return false;
    }

    return true;
});

// Auto-hide alerts
document.addEventListener('DOMContentLoaded', function() {
    // Hide success alert after 5 seconds
    const successAlert = document.querySelector('.alert-success');
    if (successAlert) {
        setTimeout(() => {
            const closeBtn = successAlert.querySelector('.btn-close');
            if (closeBtn) closeBtn.click();
        }, 5000);
    }

    // Hide error alert after 7 seconds
    const errorAlert = document.querySelector('.alert-danger');
    if (errorAlert) {
        setTimeout(() => {
            const closeBtn = errorAlert.querySelector('.btn-close');
            if (closeBtn) closeBtn.click();
        }, 7000);
    }
});
</script>
@endsection
