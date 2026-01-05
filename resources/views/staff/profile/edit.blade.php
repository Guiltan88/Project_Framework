@extends('Layouts.app')
@section('title', 'Edit Profile')

@section('content')
<!-- Content wrapper -->

        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-pills flex-column flex-md-row mb-3">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('staff.profile.index') }}">
                            <i class="bx bx-user me-1"></i> Profile
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('staff.profile.edit') }}">
                            <i class="bx bx-edit me-1"></i> Edit Profile
                        </a>
                    </li>
                </ul>

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bx bx-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bx bx-error-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bx bx-error-circle me-2"></i>
                    Please check the form for errors
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <div class="card mb-4">
                    <h5 class="card-header">Profile Details</h5>

                    @php
                        // Sederhanakan function foto
                        $avatar = $user->photo ? asset('storage/' . $user->photo) :
                                 'https://ui-avatars.com/api/?name=' . urlencode($user->name) .
                                 '&background=696cff&color=fff&size=150';
                    @endphp

                    <div class="card-body">
                        <div class="d-flex align-items-start align-items-sm-center gap-4 mb-4">
                            <img
                                src="{{ $avatar }}"
                                alt="user-avatar"
                                class="d-block rounded"
                                height="100"
                                width="100"
                                onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=696cff&color=fff&size=150'"
                            />
                            <div class="button-wrapper">
                                <p class="text-muted mb-0">Current profile photo</p>
                                <p class="text-muted mb-0">Allowed JPG, PNG, GIF or WebP. Max size of 2MB</p>

                                @if($user->photo)
                                <form action="{{ route('staff.profile.remove-photo') }}" method="POST" class="d-inline mt-2">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger"
                                            onclick="return confirm('Remove profile photo?')">
                                        <i class="bx bx-trash me-1"></i> Remove Photo
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>

                    <hr class="my-0" />

                    <div class="card-body">
                        <form id="formAccountSettings" action="{{ route('staff.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input
                                        class="form-control @error('name') is-invalid @enderror"
                                        type="text"
                                        id="name"
                                        name="name"
                                        value="{{ old('name', $user->name) }}"
                                        placeholder="Enter your full name"
                                        required
                                    />
                                    @error('name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="email" class="form-label">E-mail <span class="text-danger">*</span></label>
                                    <input
                                        class="form-control @error('email') is-invalid @enderror"
                                        type="email"
                                        id="email"
                                        name="email"
                                        value="{{ old('email', $user->email) }}"
                                        placeholder="john.doe@example.com"
                                        required
                                    />
                                    @error('email')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text">ID (+62)</span>
                                        <input
                                            type="text"
                                            id="phone"
                                            name="phone"
                                            class="form-control @error('phone') is-invalid @enderror"
                                            placeholder="812-3456-7890"
                                            value="{{ old('phone', $user->phone) }}"
                                        />
                                    </div>
                                    @error('phone')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="department" class="form-label">Department</label>
                                    <input
                                        type="text"
                                        class="form-control @error('department') is-invalid @enderror"
                                        id="department"
                                        name="department"
                                        value="{{ old('department', $user->department) }}"
                                        placeholder="IT Department"
                                    />
                                    @error('department')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Photo Upload Field -->
                                <div class="mb-3 col-md-12">
                                    <label for="photo" class="form-label">Upload New Profile Photo</label>
                                    <input
                                        type="file"
                                        class="form-control @error('photo') is-invalid @enderror"
                                        id="photo"
                                        name="photo"
                                        accept="image/*"
                                    />
                                    @error('photo')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="bx bx-info-circle me-1"></i>
                                        Maximum file size: 2MB | Allowed formats: JPG, PNG, GIF, WebP
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <h6 class="mb-3">Change Password (Optional)</h6>
                                <div class="alert alert-info mb-4">
                                    <i class="bx bx-info-circle me-2"></i>
                                    Leave blank if you don't want to change the password
                                </div>

                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label for="password" class="form-label">New Password</label>
                                        <div class="input-group input-group-merge">
                                            <input
                                                type="password"
                                                id="password"
                                                name="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                            />
                                        </div>
                                        @error('password')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3 col-md-6">
                                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                        <div class="input-group input-group-merge">
                                            <input
                                                type="password"
                                                id="password_confirmation"
                                                name="password_confirmation"
                                                class="form-control"
                                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                            />
                                        </div>
                                    </div>
                                </div>

                                <div class="form-text">
                                    <i class="bx bx-check-circle me-1"></i>
                                    Password must be at least 6 characters long
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="bx bx-save me-1"></i> Save Changes
                                </button>
                                <button type="reset" class="btn btn-outline-secondary">
                                    <i class="bx bx-reset me-1"></i> Reset
                                </button>

                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
<!-- Content wrapper -->
@endsection
