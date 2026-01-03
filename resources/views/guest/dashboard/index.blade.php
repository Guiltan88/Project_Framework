@extends('layouts.app')
@section('title', 'Guest Dashboard')

@push('styles')
    <!-- Guest Dashboard CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/guest/dashboard.css') }}">
@endpush

@section('content')
<div class="guest-dashboard">
    <!-- Welcome Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="avatar me-3">
                                @php
                                    $avatar = Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=696cff&color=fff&size=64';
                                @endphp
                                <img src="{{ $avatar }}" alt="Profile" class="rounded-circle" width="64" height="64"
                                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=696cff&color=fff&size=64'">
                            </div>
                            <div>
                                <h4 class="card-title mb-1">Welcome back, {{ Auth::user()->name }}! 👋</h4>
                                <p class="text-muted mb-0">
                                    Here's what's happening with your room bookings today.
                                    @if($stats['today_bookings'] > 0)
                                        <span class="text-primary">You have {{ $stats['today_bookings'] }} booking(s) today.</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('bookings.create') }}" class="btn btn-primary">
                            <i class="bx bx-plus me-1"></i> New Booking
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        @foreach([
            [
                'id' => 'total_bookings',
                'title' => 'Total Bookings',
                'value' => $stats['total_bookings'],
                'icon' => 'bx-calendar-check',
                'color' => 'primary',
                'route' => route('guest.bookings.history'),
                'description' => 'All your bookings'
            ],
            [
                'id' => 'approved_bookings',
                'title' => 'Approved',
                'value' => $stats['approved_bookings'],
                'icon' => 'bx-check-circle',
                'color' => 'success',
                'description' => 'Confirmed bookings'
            ],
            [
                'id' => 'pending_bookings',
                'title' => 'Pending',
                'value' => $stats['pending_bookings'],
                'icon' => 'bx-time-five',
                'color' => 'warning',
                'description' => 'Awaiting approval'
            ],
            [
                'id' => 'available_rooms',
                'title' => 'Available Rooms',
                'value' => $stats['available_rooms'],
                'icon' => 'bx-door-open',
                'color' => 'info',
                'route' => route('guest.rooms.index'),
                'description' => 'Ready to book'
            ]
        ] as $stat)
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-{{ $stat['color'] }}">
                                <i class="bx {{ $stat['icon'] }} bx-sm"></i>
                            </span>
                        </div>
                        @if(isset($stat['route']))
                        <div class="dropdown">
                            <button class="btn p-0" type="button" data-bs-toggle="dropdown">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{ $stat['route'] }}">View All</a>
                            </div>
                        </div>
                        @endif
                    </div>
                    <span class="fw-semibold d-block mb-1">{{ $stat['title'] }}</span>
                    <h3 class="card-title mb-2" id="stat-{{ $stat['id'] }}">{{ $stat['value'] }}</h3>
                    <small class="text-{{ $stat['color'] }} fw-semibold">
                        <i class="bx bx-up-arrow-alt"></i> {{ $stat['description'] }}
                    </small>
                </div>
            </div>
        </div>
        @endforeach
    </div>


    <!-- Recent Activity -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <h5 class="card-header d-flex justify-content-between align-items-center">
                    Recent Activity
                    <a href="{{ route('guest.bookings.history') }}" class="btn btn-sm btn-outline-primary">
                        View All Activity
                    </a>
                </h5>
                <div class="table-responsive text-nowrap">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Booking Code</th>
                                <th>Room</th>
                                <th>Date & Time</th>
                                <th>Purpose</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse($recentBookings as $activity)
                            <tr>
                                <td>
                                    <i class="bx bx-calendar me-3 text-primary"></i>
                                    <strong class="text-primary">#{{ $activity->kode_booking }}</strong>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($activity->room->foto)
                                        <img src="{{ asset('storage/' . $activity->room->foto) }}"
                                            alt="{{ $activity->room->nama_ruangan }}"
                                            class="avatar avatar-xs me-2 rounded"
                                            onerror="this.src='{{ asset('assets-admin/img/rooms/default-room.jpg') }}'">
                                        @else
                                        <div class="avatar avatar-xs me-2 bg-label-secondary rounded d-flex align-items-center justify-content-center">
                                            <i class="bx bx-door-open"></i>
                                        </div>
                                        @endif
                                        <div>
                                            <div>{{ $activity->room->nama_ruangan ?? 'N/A' }}</div>
                                            <small class="text-muted">{{ $activity->room->building->nama_gedung ?? '' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>{{ \Carbon\Carbon::parse($activity->tanggal_mulai)->format('d M Y') }}</div>
                                    <small class="text-muted">{{ $activity->waktu_mulai }} - {{ $activity->waktu_selesai }}</small>
                                </td>
                                <td>
                                    <div class="text-truncate" style="max-width: 150px;"
                                        data-bs-toggle="tooltip"
                                        title="{{ $activity->tujuan ?? 'No purpose' }}">
                                        {{ $activity->tujuan ?? 'No purpose' }}
                                    </div>
                                </td>
                                <td>
                                    @if($activity->status == 'approved')
                                    <span class="badge bg-label-success me-1">
                                        <i class="bx bx-check-circle me-1"></i> Approved
                                    </span>
                                    @elseif($activity->status == 'pending')
                                    <span class="badge bg-label-warning me-1">
                                        <i class="bx bx-time-five me-1"></i> Pending
                                    </span>
                                    @elseif($activity->status == 'rejected')
                                    <span class="badge bg-label-danger me-1">
                                        <i class="bx bx-x-circle me-1"></i> Rejected
                                    </span>
                                    @elseif($activity->status == 'cancelled')
                                    <span class="badge bg-label-secondary me-1">
                                        <i class="bx bx-block me-1"></i> Cancelled
                                    </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('guest.bookings.show', $activity->id) }}">
                                                <i class="bx bx-show me-1"></i> View Details
                                            </a>
                                            @if($activity->status == 'pending')
                                            <a class="dropdown-item" href="{{ route('guest.bookings.edit', $activity->id) }}">
                                                <i class="bx bx-edit-alt me-1"></i> Edit
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item text-danger"
                                            href="#"
                                            onclick="event.preventDefault(); document.getElementById('cancel-booking-{{ $activity->id }}').submit();">
                                                <i class="bx bx-trash me-1"></i> Cancel
                                            </a>
                                            <form id="cancel-booking-{{ $activity->id }}"
                                                action="{{ route('guest.bookings.cancel', $activity->id) }}"
                                                method="POST"
                                                style="display: none;">
                                                @csrf
                                                @method('POST')
                                            </form>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="bx bx-history bx-lg text-muted"></i>
                                    <p class="text-muted mt-3 mb-0">No recent activity</p>
                                    <a href="{{ route('bookings.create') }}" class="btn btn-sm btn-primary mt-2">
                                        <i class="bx bx-plus me-1"></i> Create First Booking
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Message Toast -->
    @if(session('success'))
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
</div>
@endsection

@push('scripts')
    <!-- Guest Dashboard JavaScript -->
    <script src="{{ asset('assets/js/guest/dashboard.js') }}"></script>
@endpush
