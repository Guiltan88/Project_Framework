@extends('layouts.app')
@section('title', 'My Profile')

@section('content')


        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-pills flex-column flex-md-row mb-3">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('guest.profile.index') }}">
                            <i class="bx bx-user me-1"></i> Profile
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('guest.profile.edit') }}">
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

                <div class="card mb-4">
                    <h5 class="card-header">Profile Details</h5>
                    <div class="card-body">
                        @php
                            $avatar = $user->photo ? asset('storage/' . $user->photo) :
                                     'https://ui-avatars.com/api/?name=' . urlencode($user->name) .
                                     '&background=696cff&color=fff&size=150';
                        @endphp

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
                                <a href="{{ route('guest.profile.edit') }}" class="btn btn-primary me-2 mb-2">
                                    <i class="bx bx-edit me-1"></i> Edit Profile
                                </a>
                                <p class="text-muted mb-0 small">{{ $user->email }}</p>
                                <p class="text-muted mb-0 small">
                                    <span class="badge bg-primary">Guest User</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <hr class="my-0" />

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
                                                <td><strong>Phone Number</strong></td>
                                                <td>{{ $user->phone ?? 'Not set' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Position/Role</strong></td>
                                                <td>{{ $user->position ?? 'Not set' }}</td>
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
                                <div class="card mb-3">
                                    <div class="card-header d-flex align-items-center">
                                        <i class="bx bx-stats me-2"></i>
                                        <h5 class="card-title mb-0">Booking Stats</h5>
                                    </div>
                                    <div class="card-body">
                                        @foreach([
                                            ['label' => 'Total Bookings', 'count' => $user->bookings()->count(), 'color' => 'primary'],
                                            ['label' => 'Approved', 'count' => $user->bookings()->where('status', 'approved')->count(), 'color' => 'success'],
                                            ['label' => 'Pending', 'count' => $user->bookings()->where('status', 'pending')->count(), 'color' => 'warning'],
                                            ['label' => 'Cancelled', 'count' => $user->bookings()->where('status', 'cancelled')->count(), 'color' => 'secondary']
                                        ] as $stat)
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="text-muted">{{ $stat['label'] }}</span>
                                            <span class="badge bg-{{ $stat['color'] }}">{{ $stat['count'] }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header d-flex align-items-center">
                                        <i class="bx bx-rocket me-2"></i>
                                        <h5 class="card-title mb-0">Quick Actions</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-grid gap-3">
                                            <a href="{{ route('guest.profile.edit') }}" class="btn btn-outline-primary">
                                                <i class="bx bx-edit me-2"></i> Edit Profile
                                            </a>
                                            <button class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                                <i class="bx bx-key me-2"></i> Change Password
                                            </button>
                                            <a href="{{ route('guest.dashboard') }}" class="btn btn-outline-secondary">
                                                <i class="bx bx-home me-2"></i> Back to Dashboard
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Bookings -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bx bx-history me-2"></i>
                            <h5 class="card-title mb-0 d-inline">Recent Bookings</h5>
                        </div>
                        <a href="{{ route('guest.bookings.history') }}" class="btn btn-sm btn-outline-primary">
                            View All
                        </a>
                    </div>
                    <div class="card-body">
                        @if($bookings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Room</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bookings as $booking)
                                    <tr>
                                        <td>
                                            <strong class="text-primary">#{{ $booking->kode_booking }}</strong>
                                        </td>
                                        <td>
                                            <div>{{ $booking->room->nama_ruangan ?? 'N/A' }}</div>
                                            <small class="text-muted">{{ $booking->room->building->nama_gedung ?? '' }}</small>
                                        </td>
                                        <td>
                                            {{ $booking->tanggal_mulai->format('d M Y') }}
                                        </td>
                                        <td>
                                            <small>{{ $booking->waktu_mulai }} - {{ $booking->waktu_selesai }}</small>
                                        </td>
                                        <td>
                                            @switch($booking->status)
                                                @case('approved')
                                                    <span class="badge bg-label-success">Approved</span>
                                                    @break
                                                @case('pending')
                                                    <span class="badge bg-label-warning">Pending</span>
                                                    @break
                                                @case('rejected')
                                                    <span class="badge bg-label-danger">Rejected</span>
                                                    @break
                                                @case('cancelled')
                                                    <span class="badge bg-label-secondary">Cancelled</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-label-secondary">{{ ucfirst($booking->status) }}</span>
                                            @endswitch
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-4">
                            <i class="bx bx-calendar bx-lg text-muted mb-3"></i>
                            <p class="text-muted mb-0">No bookings yet</p>
                            <a href="{{ route('bookings.create') }}" class="btn btn-primary mt-3">
                                <i class="bx bx-plus me-1"></i> Create First Booking
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
<!-- Content wrapper -->

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('guest.profile.update') }}" method="POST">
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
</div>
@endsection
