@extends('layouts.app')
@section('title', 'Staff Profile')

@section('content')
    <!-- Navigation Pills -->
    <ul class="nav nav-pills flex-column flex-md-row mb-3">
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('staff.profile.index') }}">
                <i class="bx bx-user me-1"></i> Profile
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('staff.profile.edit') }}">
                <i class="bx bx-edit me-1"></i> Edit Profile
            </a>
        </li>
    </ul>

    @php
        // Helper function untuk foto profile
        function getProfilePhoto($user) {
            if ($user->photo) {
                // Cek file di storage
                $storagePath = 'storage/' . $user->photo;
                $publicPath = public_path($storagePath);

                if (file_exists($publicPath)) {
                    return asset($storagePath);
                }

                // Cek di storage link
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($user->photo)) {
                    return asset('storage/' . $user->photo);
                }
            }

            // Fallback ke avatar generator
            return 'https://ui-avatars.com/api/?name=' . urlencode($user->name) .
                   '&background=696cff&color=fff&size=150&bold=true&format=svg';
        }

        $avatar = getProfilePhoto($user);
    @endphp

    <div class="row">
        <div class="col-md-12">
            <!-- Success Alert -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bx bx-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Error Alert -->
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
                    <!-- Profile Photo with Quick Upload -->
                    <div class="d-flex align-items-start align-items-sm-center gap-4 mb-4">
                        <div class="position-relative">
                            <img
                                src="{{ $avatar }}"
                                alt="{{ $user->name }}"
                                class="d-block rounded-circle"
                                height="120"
                                width="120"
                                style="object-fit: cover;"
                                onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=696cff&color=fff&size=150'"
                            />
                            <span class="position-absolute bottom-0 end-0 bg-success rounded-circle border border-3 border-white"
                                  style="width: 20px; height: 20px;"
                                  title="Online">
                            </span>
                        </div>
                        <div class="button-wrapper">
                            <a href="{{ route('staff.profile.edit') }}" class="btn btn-primary me-2 mb-2">
                                <i class="bx bx-edit me-1"></i> Edit Profile
                            </a>
                            <p class="text-muted mb-0 small">{{ $user->email }}</p>
                            <p class="text-muted mb-0 small">
                                <span class="badge bg-label-primary">{{ ucfirst($user->getRoleNames()->first() ?? 'Staff') }}</span>
                            </p>
                        </div>
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
                                            <td><strong>Phone Number</strong></td>
                                            <td>{{ $user->phone ?? 'Not set' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Department</strong></td>
                                            <td>{{ $user->department ?? 'Not set' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Role</strong></td>
                                            <td>
                                                <span class="badge bg-label-primary">{{ ucfirst($user->getRoleNames()->first() ?? 'Staff') }}</span>
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
                            <div class="card mb-3">
                                <div class="card-header d-flex align-items-center">
                                    <i class="bx bx-stats me-2"></i>
                                    <h5 class="card-title mb-0">Quick Stats</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-muted">Approvals Today</span>
                                        <span class="badge bg-primary">
                                            {{ \App\Models\Booking::where('approved_by', $user->id)->whereDate('approved_at', today())->count() }}
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-muted">Total Approvals</span>
                                        <span class="badge bg-success">
                                            {{ \App\Models\Booking::where('approved_by', $user->id)->count() }}
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-muted">Pending Actions</span>
                                        <span class="badge bg-warning">
                                            {{ \App\Models\Booking::where('status', 'pending')->count() }}
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-muted">Member Since</span>
                                        <span>{{ $user->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header d-flex align-items-center">
                                    <i class="bx bx-rocket me-2"></i>
                                    <h5 class="card-title mb-0">Quick Actions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-3">
                                        <a href="{{ route('staff.profile.edit') }}" class="btn btn-outline-primary">
                                            <i class="bx bx-edit me-2"></i> Edit Profile
                                        </a>
                                        <a href="{{ route('staff.bookings.index') }}" class="btn btn-outline-warning">
                                            <i class="bx bx-calendar-check me-2"></i> Pending Approvals
                                            @php
                                                $pendingCount = \App\Models\Booking::where('status', 'pending')->count();
                                            @endphp
                                            @if($pendingCount > 0)
                                            <span class="badge bg-danger rounded-pill ms-auto">{{ $pendingCount }}</span>
                                            @endif
                                        </a>
                                        <a href="{{ route('staff.dashboard') }}" class="btn btn-outline-secondary">
                                            <i class="bx bx-home me-2"></i> Back to Dashboard
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Card -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="bx bx-history me-2"></i>
                        <h5 class="card-title mb-0 d-inline">Recent Activity</h5>
                    </div>
                    <a href="{{ route('staff.bookings.index') }}" class="btn btn-sm btn-outline-primary">
                        View All
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Booking Code</th>
                                    <th>Room</th>
                                    <th>User</th>
                                    <th>Date & Time</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $recentApprovals = \App\Models\Booking::where('approved_by', $user->id)
                                        ->with(['room', 'user'])
                                        ->latest()
                                        ->limit(5)
                                        ->get();
                                @endphp

                                @forelse($recentApprovals as $approval)
                                <tr>
                                    <td>
                                        <strong class="text-primary">#{{ $approval->kode_booking }}</strong>
                                    </td>
                                    <td>
                                        <div>{{ $approval->room->nama_ruangan ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $approval->room->building->nama_gedung ?? '' }}</small>
                                    </td>
                                    <td>
                                        <div>{{ $approval->user->name ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $approval->user->email ?? '' }}</small>
                                    </td>
                                    <td>
                                        <div>{{ $approval->tanggal_mulai->format('d M Y') }}</div>
                                        <small class="text-muted">{{ $approval->waktu_mulai }} - {{ $approval->waktu_selesai }}</small>
                                    </td>
                                    <td>
                                        @if($approval->status == 'approved')
                                        <span class="badge bg-label-success">Approved</span>
                                        @elseif($approval->status == 'rejected')
                                        <span class="badge bg-label-danger">Rejected</span>
                                        @else
                                        <span class="badge bg-label-warning">{{ ucfirst($approval->status) }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <i class="bx bx-history bx-lg text-muted"></i>
                                        <p class="text-muted mt-3 mb-0">No recent activity</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .badge.bg-label-primary {
            background-color: rgba(105, 108, 255, 0.1);
            color: #696cff;
        }

        .badge.bg-label-success {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        .badge.bg-label-danger {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .badge.bg-label-warning {
            background-color: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }

        .button-wrapper .btn {
            min-width: 140px;
        }

        .table-borderless td {
            padding: 0.75rem 0;
            vertical-align: top;
        }

        .table-borderless tr:not(:last-child) {
            border-bottom: 1px solid #dee2e6;
        }
    </style>
    @endpush

    <script>
    // Auto-hide alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                const closeBtn = alert.querySelector('.btn-close');
                if (closeBtn) {
                    closeBtn.click();
                }
            }, 5000);
        });
    });
    </script>
@endsection
