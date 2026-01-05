@extends('layouts.app')
@section('title', $room->nama_ruangan)

@section('content')
<!-- Content wrapper -->
        <!-- Back Button -->
        <div class="mb-4">
            <a href="{{ route('guest.rooms.index') }}" class="btn btn-label-secondary">
                <i class="bx bx-arrow-back me-1"></i> Back to Rooms
            </a>
        </div>

        <div class="row">
            <!-- Left Column: Room Info & Facilities -->
            <div class="col-lg-8 mb-4">
            <!-- Room Photo -->
            <div class="card mb-4">
                <div class="card-body">
                    <!-- GANTI DARI $room->gambar MENJADI $room->foto -->
                    @if($room->image_url)
                    <div class="text-center">
                        <img src="{{ $room->image_url }}"
                            alt="{{ $room->nama_ruangan }}"
                            class="img-fluid rounded"
                            style="max-height: 400px; object-fit: cover; width: 100%;">
                    </div>
                    @else
                    <div class="d-flex align-items-center justify-content-center bg-light rounded" style="height: 300px;">
                        <i class="bx bx-door-open bx-lg text-muted"></i>
                    </div>
                    @endif
                </div>
            </div>

                <!-- Room Details -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Room Details</h5>
                        <span class="badge
                            @if($room->status == 'tersedia') bg-success
                            @elseif($room->status == 'terisi') bg-danger
                            @elseif($room->status == 'maintenance') bg-warning text-dark
                            @else bg-secondary @endif">
                            @if($room->status == 'tersedia') Available
                            @elseif($room->status == 'terisi') Occupied
                            @elseif($room->status == 'maintenance') Maintenance
                            @endif
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-muted mb-3">Basic Information</h6>
                                <dl class="row">
                                    <dt class="col-sm-4">Name</dt>
                                    <dd class="col-sm-8">{{ $room->nama_ruangan }}</dd>

                                    <dt class="col-sm-4">Capacity</dt>
                                    <dd class="col-sm-8">{{ $room->kapasitas }} people</dd>

                                    <dt class="col-sm-4">Floor</dt>
                                    <dd class="col-sm-8">{{ $room->lantai }}</dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted mb-3">Location Information</h6>
                                <dl class="row">
                                    <dt class="col-sm-4">Building</dt>
                                    <dd class="col-sm-8">{{ $room->building->nama_gedung ?? 'N/A' }}</dd>

                                    <dt class="col-sm-4">Code</dt>
                                    <dd class="col-sm-8">{{ $room->building->kode_gedung ?? '-' }}</dd>

                                    <dt class="col-sm-4">Last Updated</dt>
                                    <dd class="col-sm-8">{{ $room->updated_at->format('d M Y H:i') }}</dd>
                                </dl>
                            </div>
                        </div>

                        @if($room->deskripsi)
                        <h6 class="text-muted mt-4 mb-2">Description</h6>
                        <div class="alert alert-light">
                            {{ $room->deskripsi }}
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Facilities Card -->
                @if($room->facilities && $room->facilities->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Facilities</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($room->facilities as $facility)
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="me-3 mt-1">
                                        <i class="bx bx-{{ $facility->icon ?? 'check' }} text-primary"></i>
                                    </div>
                                    <div>
                                        <strong class="d-block">{{ $facility->nama_fasilitas }}</strong>
                                        @if($facility->deskripsi)
                                        <small class="text-muted">{{ $facility->deskripsi }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Right Column: Booking Info & Actions -->
            <div class="col-lg-4">
                <!-- Booking Status -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Booking Status</h5>
                    </div>
                    <div class="card-body">
                        @if($bookedToday)
                        <div class="alert alert-warning mb-3">
                            <div class="d-flex align-items-center">
                                <i class="bx bx-time-five me-2"></i>
                                <div>
                                    <strong class="d-block">Booked Today</strong>
                                    <small>This room is booked for today</small>
                                </div>
                            </div>
                        </div>
                        @elseif($room->status != 'tersedia')
                        <div class="alert alert-danger mb-3">
                            <div class="d-flex align-items-center">
                                <i class="bx bx-x-circle me-2"></i>
                                <div>
                                    <strong class="d-block">Not Available</strong>
                                    <small>This room is currently unavailable</small>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="alert alert-success mb-3">
                            <div class="d-flex align-items-center">
                                <i class="bx bx-check-circle me-2"></i>
                                <div>
                                    <strong class="d-block">Available</strong>
                                    <small>This room is available for booking</small>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="d-grid">
                            @if($room->status == 'tersedia')
                            <a href="{{ route('bookings.create') }}?room_id={{ $room->id }}"
                               class="btn btn-primary">
                                <i class="bx bx-calendar-plus me-1"></i> Book This Room
                            </a>
                            @else
                            <button class="btn btn-secondary" disabled>
                                <i class="bx bx-calendar-x me-1"></i> Not Available for Booking
                            </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Room Statistics</h5>
                    </div>
                    <div class="card-body">
                        @foreach([
                            ['label' => "Today's Bookings", 'count' => $todayBookings ?? 0, 'color' => 'primary'],
                            ['label' => 'This Week', 'count' => $weekBookings ?? 0, 'color' => 'info'],
                            ['label' => 'This Month', 'count' => $monthBookings ?? 0, 'color' => 'success'],
                            ['label' => 'Total Bookings', 'count' => $totalBookings ?? 0, 'color' => 'warning']
                        ] as $stat)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">{{ $stat['label'] }}</span>
                            <span class="badge bg-{{ $stat['color'] }}">{{ $stat['count'] }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Building Info -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Building Information</h5>
                    </div>
                    <div class="card-body">
                        @if($room->building)
                        <div class="mb-3">
                            <div class="text-muted small">Building Name</div>
                            <div class="fw-bold">{{ $room->building->nama_gedung }}</div>
                        </div>

                        @if($room->building->alamat)
                        <div class="mb-3">
                            <div class="text-muted small">Address</div>
                            <div>{{ $room->building->alamat }}</div>
                        </div>
                        @endif

                        @if($room->building->kode_gedung)
                        <div class="mb-3">
                            <div class="text-muted small">Building Code</div>
                            <div>{{ $room->building->kode_gedung }}</div>
                        </div>
                        @endif

                        <div class="d-grid gap-2">
                            <a href="{{ route('guest.rooms.index') }}?building_id={{ $room->building->id }}"
                               class="btn btn-outline-primary btn-sm">
                                <i class="bx bx-door-open me-1"></i> View Other Rooms
                            </a>
                        </div>
                        @else
                        <p class="text-muted mb-0">
                            <i class="bx bx-info-circle me-1"></i>
                            No building information available.
                        </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

@endsection
