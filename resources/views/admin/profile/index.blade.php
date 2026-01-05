@extends('Layouts.app')
@section('title', 'View Admin Profile')

@section('content')


    <!-- Navigation Pills -->
    <ul class="nav nav-pills flex-column flex-md-row mb-3">
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('admin.profile.index') }}">
                <i class="bx bx-user me-1"></i> Profile
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.profile.edit') }}">
                <i class="bx bx-edit me-1"></i> Edit Profile
            </a>
        </li>
    </ul>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Profile Details</h5>
                <div class="card-body">
                    <!-- Profile Photo -->
                    <div class="d-flex align-items-start align-items-sm-center gap-4 mb-4">
                        <img
                            src="{{ $user->photo ? asset('storage/' . $user->photo) : asset('assets-admin/img/avatars/default.png') }}"
                            alt="user-avatar"
                            class="d-block rounded"
                            height="120"
                            width="120"
                        />
                    </div>
                </div>

                <hr class="my-0">

                <!-- Profile Information -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header d-flex align-items-center">
                                    <i class="bx bx-info-circle me-2"></i>
                                    <h5 class="card-title mb-0">Account Information</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td width="40%"><strong>Full Name</strong></td>
                                            <td>{{ $user->name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email Address</strong></td>
                                            <td>{{ $user->email }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Role</strong></td>
                                            <td>
                                                <span class="badge bg-label-primary">Administrator</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Account Created</strong></td>
                                            <td>{{ $user->created_at->format('d F Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Last Updated</strong></td>
                                            <td>{{ $user->updated_at->format('d F Y H:i') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header d-flex align-items-center">
                                    <i class="bx bx-rocket me-2"></i>
                                    <h5 class="card-title mb-0">Quick Actions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-3">
                                        <a href="{{ route('admin.profile.edit') }}" class="btn btn-outline-primary">
                                            <i class="bx bx-edit me-2"></i>Edit Profile
                                        </a>
                                        <button class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                            <i class="bx bx-key me-2"></i>Change Password
                                        </button>
                                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                                            <i class="bx bx-home me-2"></i>Back to Dashboard
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Success Alert -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bx bx-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Change Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="password_confirmation" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Change Password</button>
                </div>
            </form>
        </div>
    </div>
@endsection
