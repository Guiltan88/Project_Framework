@extends('layouts.app')
@section('title', 'Edit Profile - Guest')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/guest/profile-edit.css') }}">
@endpush

@section('content')
    <!-- Navigation Pills -->
    <ul class="nav nav-pills flex-column flex-md-row mb-4">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('guest.profile.index') }}">
                <i class="bx bx-user me-1"></i> View Profile
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('guest.profile.edit') }}">
                <i class="bx bx-edit me-1"></i> Edit Profile
            </a>
        </li>
    </ul>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Edit Profile Details</h5>
                    <span class="badge bg-label-primary">
                        <i class="bx bx-user-circle me-1"></i> Guest Account
                    </span>
                </div>

                <!-- Photo Upload Section -->
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block">
                            <img
                                src="{{ $avatar }}"
                                alt="{{ $user->name }}"
                                class="d-block rounded-circle mb-3"
                                height="150"
                                width="150"
                                id="editProfileAvatar"
                                style="object-fit: cover;"
                                onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=696cff&color=fff&size=150'"
                            />
                            <label for="photoUpload" class="btn btn-sm btn-primary position-absolute bottom-0 end-0 rounded-circle"
                                   style="width: 36px; height: 36px;">
                                <i class="bx bx-camera fs-5"></i>
                            </label>
                        </div>
                        <input
                            type="file"
                            id="photoUpload"
                            class="d-none"
                            accept="image/*"
                        >
                        <p class="text-muted small mt-2">Click camera icon to change profile photo</p>

                        <!-- Remove Photo Button (if has photo) -->
                        @if($user->photo)
                        <div class="mt-2">
                            <form action="{{ route('guest.profile.remove-photo') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Remove profile photo?')">
                                    <i class="bx bx-trash me-1"></i> Remove Photo
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>

                <hr class="my-0">

                <!-- Edit Form -->
                <div class="card-body">
                    <form action="{{ route('guest.profile.update') }}" method="POST" enctype="multipart/form-data" id="editProfileForm">
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

                            <!-- Phone Field -->
                            <div class="mb-3 col-md-6">
                                <label for="phone" class="form-label">Phone Number</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text">
                                        <i class="bx bx-phone"></i>
                                    </span>
                                    <input
                                        type="tel"
                                        class="form-control @error('phone') is-invalid @enderror"
                                        id="phone"
                                        name="phone"
                                        value="{{ old('phone', $user->phone) }}"
                                        placeholder="e.g., 0812-3456-7890"
                                    />
                                </div>
                                @error('phone')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Position Field (Optional for Guest) -->
                            <div class="mb-3 col-md-6">
                                <label for="position" class="form-label">Position/Role</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text">
                                        <i class="bx bx-briefcase"></i>
                                    </span>
                                    <input
                                        type="text"
                                        class="form-control @error('position') is-invalid @enderror"
                                        id="position"
                                        name="position"
                                        value="{{ old('position', $user->position) }}"
                                        placeholder="e.g., Student, Lecturer"
                                    />
                                </div>
                                @error('position')
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
                                <div class="alert alert-info mb-3">
                                    <i class="bx bx-info-circle me-2"></i>
                                    Leave blank if you don't want to change the password
                                </div>

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
                                    <i class="bx bx-check-circle me-1"></i>
                                    Password must be at least 6 characters long
                                </div>
                            </div>
                        </div>

                        <!-- File Upload Info -->
                        <div class="alert alert-warning mb-4">
                            <div class="d-flex align-items-center">
                                <i class="bx bx-info-circle fs-4 me-2"></i>
                                <div>
                                    <strong>Profile Photo Requirements:</strong>
                                    <ul class="mb-0 mt-1">
                                        <li>Maximum file size: 2MB</li>
                                        <li>Allowed formats: JPG, PNG, GIF, WebP</li>
                                        <li>Recommended size: 150x150 pixels</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-2">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('guest.profile.index') }}" class="btn btn-outline-secondary">
                                    <i class="bx bx-arrow-back me-2"></i> Back to Profile
                                </a>
                                <div>
                                    <button type="reset" class="btn btn-outline-warning me-2">
                                        <i class="bx bx-reset me-2"></i> Reset
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bx bx-save me-2"></i> Save Changes
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Success/Error Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                            <i class="bx bx-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                            <i class="bx bx-error-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

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

    <!-- Success Toast -->
    @if(session('success') && session('show_toast'))
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="3000">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bx bx-check-circle me-2"></i>
                    {{ session('success') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
    @endif
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/guest/profile-edit.js') }}"></script>
@endpush

