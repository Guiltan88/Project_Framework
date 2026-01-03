@extends('layouts.app')
@section('title', $room->nama_ruangan)

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/guest/room-show.css') }}">
@endpush

@section('content')
<div class="guest-room-show">
    <!-- Breadcrumb -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('guest.dashboard') }}">
                            <i class="bx bx-home me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('guest.rooms.index') }}">
                            <i class="bx bx-door-open me-1"></i> Rooms
                        </a>
                    </li>
                    <li class="breadcrumb-item active">{{ $room->nama_ruangan }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <!-- Left Column: Room Info & Facilities -->
        <div class="col-md-8 mb-4">
            <!-- Room Photo -->
            <div class="card mb-4">
                <div class="card-body p-0">
                    <div class="room-photo-container" style="min-height: 400px; max-height: 500px; overflow: hidden;">
                        @if($room->foto)
                        <img src="{{ asset('storage/' . $room->foto) }}"
                            alt="{{ $room->nama_ruangan }}"
                            class="img-fluid w-100 h-100 object-fit-cover"
                            style="object-fit: cover;"
                            data-bs-toggle="tooltip"
                            title="Click to enlarge"
                            onerror="this.src='{{ asset('assets-admin/img/rooms/default-room.jpg') }}'">
                        @else
                        <div class="no-photo-placeholder d-flex flex-column align-items-center justify-content-center" style="min-height: 400px; background-color: #f8f9fa;">
                            <i class="bx bx-door-open bx-lg text-muted mb-3" style="font-size: 4rem;"></i>
                            <p class="text-muted mb-0 fs-5">No photo available</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Room Details -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-info-circle me-2"></i> Room Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Description</h6>
                        <p class="card-text">{{ $room->deskripsi ?? 'No description available.' }}</p>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Basic Information</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td width="40%" class="text-muted">Room Type:</td>
                                    <td class="fw-medium">{{ $room->tipe ?? 'Standard' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Capacity:</td>
                                    <td class="fw-medium">{{ $room->kapasitas }} people</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Status:</td>
                                    <td>
                                        @if($room->status == 'tersedia')
                                        <span class="badge bg-success">Available</span>
                                        @else
                                        <span class="badge bg-danger">Unavailable</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Location Information</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td width="40%" class="text-muted">Building:</td>
                                    <td class="fw-medium">{{ $room->building->nama_gedung ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Floor:</td>
                                    <td class="fw-medium">{{ $room->lantai ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Last Updated:</td>
                                    <td class="fw-medium">{{ $room->updated_at->format('d M Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Facilities Card - Minimalis & Modern -->
            @if($room->facilities && $room->facilities->count() > 0)
            <div class="card facilities-modern-card">
                <div class="card-header">
                    <h5 class="mb-0"> Facilities</h5>
                </div>
                <div class="card-body p-0">
                    <div class="facilities-table-container">
                        <table class="table facilities-modern-table mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 60%">Facility</th>
                                    <th style="width: 40%">Availability</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($room->facilities as $facility)
                                <tr class="facility-row">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="facility-modern-icon me-3">
                                                <i class="bx bx-{{ $facility->icon ?? 'check' }}"></i>
                                            </div>
                                            <div>
                                                <strong class="d-block facility-modern-name">{{ $facility->nama_fasilitas }}</strong>
                                                @if($facility->deskripsi)
                                                <small class="facility-modern-desc">{{ $facility->deskripsi }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="availability-badge">
                                            <i class="bx bx-check-circle me-1"></i> Available
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column: Booking Info & Actions -->
        <div class="col-md-4 mb-4">
            <!-- Booking Status -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-calendar me-2"></i> Booking Status
                    </h5>
                </div>
                <div class="card-body">
                    @if($bookedToday)
                    <div class="alert alert-warning mb-3">
                        <i class="bx bx-time-five me-2"></i>
                        This room is booked for today.
                    </div>
                    @elseif($room->status != 'tersedia')
                    <div class="alert alert-danger mb-3">
                        <i class="bx bx-x-circle me-2"></i>
                        This room is currently unavailable for booking.
                    </div>
                    @else
                    <div class="alert alert-success mb-3">
                        <i class="bx bx-check-circle me-2"></i>
                        This room is available for booking.
                    </div>
                    @endif

                    <div class="d-grid">
                        @if($room->status == 'tersedia')
                        <a href="{{ route('bookings.create') }}?room={{ $room->id }}"
                           class="btn btn-primary btn-book-room">
                            <i class="bx bx-calendar-plus me-1"></i> Book This Room
                        </a>
                        @else
                        <button class="btn btn-secondary" disabled>
                            <i class="bx bx-calendar-x me-1"></i> Not Available
                        </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-stats me-2"></i> Room Statistics
                    </h5>
                </div>
                <div class="card-body">
                    @foreach([
                        ['label' => 'Today\'s Bookings', 'count' => $todayBookings, 'color' => 'primary'],
                        ['label' => 'This Week', 'count' => $weekBookings, 'color' => 'info'],
                        ['label' => 'This Month', 'count' => $monthBookings, 'color' => 'success'],
                        ['label' => 'Total Bookings', 'count' => $totalBookings, 'color' => 'warning']
                    ] as $stat)
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>{{ $stat['label'] }}</span>
                        <span class="badge bg-{{ $stat['color'] }} stats-badge">
                            {{ $stat['count'] }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Building Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-building me-2"></i> Building Information
                    </h5>
                </div>
                <div class="card-body">
                    @if($room->building)
                    <div class="building-info mb-3">
                        <div class="building-icon d-inline-block me-2">
                            <i class="bx bx-building"></i>
                        </div>
                        <div class="d-inline-block">
                            <div class="building-name fw-medium">{{ $room->building->nama_gedung }}</div>
                            @if($room->building->alamat)
                            <div class="building-address text-muted small">{{ $room->building->alamat }}</div>
                            @endif
                        </div>
                    </div>

                    @if($room->building->deskripsi)
                    <p class="mb-3 small text-muted">{{ Str::limit($room->building->deskripsi, 100) }}</p>
                    @endif

                    <div class="d-grid gap-2">
                        <a href="{{ route('guest.rooms.index') }}?building={{ $room->building->id }}"
                           class="btn btn-outline-primary btn-sm">
                            <i class="bx bx-door-open me-1"></i> View Other Rooms
                        </a>
                        @if($room->building->location_url)
                        <a href="{{ $room->building->location_url }}"
                           target="_blank"
                           class="btn btn-outline-secondary btn-sm">
                            <i class="bx bx-map me-1"></i> View Location
                        </a>
                        @endif
                    </div>
                    @else
                    <p class="text-muted mb-0">
                        <i class="bx bx-info-circle me-1"></i>
                        No building information available.
                    </p>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary btn-sm" data-copy-link>
                            <i class="bx bx-link me-1"></i> Copy Room Link
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-favorite-room data-room-id="{{ $room->id }}">
                            <i class="bx bx-heart me-1"></i> Add to Favorites
                        </button>
                        <button type="button" class="btn btn-outline-info btn-sm" data-view-calendar>
                            <i class="bx bx-calendar me-1"></i> View Availability
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/guest/room-show.js') }}"></script>
@endpush
