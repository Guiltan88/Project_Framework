@extends('layouts.app')
@section('title', 'My Profile')

@push('styles')
    <!-- Guest Profile CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/guest/profile.css') }}">
@endpush

@section('content')
<div class="guest-profile">
    <!-- Navigation -->
    <ul class="nav nav-pills flex-column flex-md-row mb-4">
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

    <div class="row">
        <!-- Left Column - Profile Card & Stats -->
        <div class="col-md-4 mb-4">
            <!-- Profile Card -->
            <div class="card mb-4 profile-card">
                <div class="card-body text-center">
                    <!-- Profile Photo -->
                    @php
                        $avatar = $user->photo ? asset('storage/' . $user->photo) :
                        'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=696cff&color=fff&size=150';
                    @endphp
                    <img src="{{ $avatar }}" alt="Profile" class="rounded-circle profile-photo mb-3"
                         onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=696cff&color=fff&size=150'">

                    <h4 class="card-title mb-1">{{ $user->name }}</h4>
                    <p class="text-muted mb-3">{{ $user->email }}</p>

                    <div class="d-flex justify-content-center mb-3">
                        <span class="badge bg-label-primary">
                            <i class="bx bx-user me-1"></i> Guest User
                        </span>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ route('bookings.create') }}" class="btn btn-primary">
                            <i class="bx bx-plus me-1"></i> Create New Booking
                        </a>
                        <a href="{{ route('guest.rooms.index') }}" class="btn btn-outline-primary">
                            <i class="bx bx-search me-1"></i> Browse Rooms
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card">
                <div class="card-header">
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
                        <span>{{ $stat['label'] }}</span>
                        <span class="badge bg-{{ $stat['color'] }} rounded-pill">{{ $stat['count'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right Column - Profile Info & Recent Bookings -->
        <div class="col-md-8 mb-4">
            <!-- Profile Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Profile Information</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody>
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
                                    <td>{{ $user->created_at->format('d F Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Last Updated</strong></td>
                                    <td>{{ $user->updated_at->format('d F Y H:i') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Recent Bookings -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Bookings</h5>
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
                                                <span class="badge bg-success">Approved</span>
                                                @break
                                            @case('pending')
                                                <span class="badge bg-warning">Pending</span>
                                                @break
                                            @case('rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                                @break
                                            @case('cancelled')
                                                <span class="badge bg-secondary">Cancelled</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ ucfirst($booking->status) }}</span>
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
</div>
@endsection

@push('scripts')
    <!-- Guest Profile JavaScript -->
    <script src="{{ asset('assets/js/guest/profile.js') }}"></script>
@endpush
