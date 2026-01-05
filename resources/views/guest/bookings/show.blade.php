@extends('Layouts.app')
@section('title', 'Booking Details')

@section('content')
    <!-- Header Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <h4 class="card-title mb-1">Booking #{{ $booking->kode_booking }}</h4>
                            <p class="text-muted mb-0">
                                <i class="bx bx-door-open me-1"></i> {{ $booking->room->nama_ruangan }}
                                • {{ \Carbon\Carbon::parse($booking->tanggal_mulai)->format('d M Y') }}
                            </p>
                        </div>
                        <div class="d-flex gap-2">
                            @if($booking->status == 'pending')
                            <a href="{{ route('guest.bookings.edit', $booking->id) }}" class="btn btn-primary">
                                <i class="bx bx-edit me-1"></i> Edit
                            </a>
                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
                                <i class="bx bx-trash me-1"></i> Cancel
                            </button>
                            @endif
                            <a href="{{ route('guest.bookings.history') }}" class="btn btn-outline-primary">
                                <i class="bx bx-arrow-back me-1"></i> Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column: Booking Details -->
        <div class="col-lg-8 mb-4">
            <!-- Room Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Room Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="room-image">
                                <!-- TAMPILKAN GAMBAR DARI ROOM -->
                                @if($booking->room->gambar)
                                <img src="{{ asset('storage/' . $booking->room->gambar) }}"
                                     alt="{{ $booking->room->nama_ruangan }}"
                                     class="img-fluid rounded"
                                     style="width: 100%; height: 200px; object-fit: cover;">
                                @else
                                <div class="no-image bg-light d-flex align-items-center justify-content-center rounded"
                                     style="width: 100%; height: 200px;">
                                    <i class="bx bx-door-open bx-lg text-muted"></i>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="room-info">
                                <h5 class="mb-2">{{ $booking->room->nama_ruangan }}</h5>
                                <p class="text-muted mb-3">
                                    <i class="bx bx-building me-1"></i> {{ $booking->room->building->nama_gedung ?? 'N/A' }}
                                    • Floor {{ $booking->room->lantai ?? 'N/A' }}
                                </p>

                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <span class="text-muted d-block">Capacity</span>
                                        <strong>{{ $booking->room->kapasitas }} people</strong>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <span class="text-muted d-block">Room Type</span>
                                        <strong>{{ $booking->room->tipe_ruangan ?? 'Standard' }}</strong>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <a href="{{ route('guest.rooms.show', $booking->room->id) }}"
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="bx bx-show me-1"></i> View Room Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Timeline -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Booking Timeline</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <strong>Booking Created</strong>
                                <p class="text-muted mb-1">You submitted the booking request</p>
                                <small class="text-muted">
                                    {{ $booking->created_at->format('d M Y, H:i') }}
                                </small>
                            </div>
                        </div>

                        @if($booking->status_updated_at)
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <strong>Booking {{ ucfirst($booking->status) }}</strong>
                                <p class="text-muted mb-1">Booking has been {{ $booking->status }} by admin</p>
                                <small class="text-muted">
                                    {{ $booking->status_updated_at->format('d M Y, H:i') }}
                                </small>
                            </div>
                        </div>
                        @endif

                        @if($booking->status == 'pending')
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <strong>Awaiting Approval</strong>
                                <p class="text-muted mb-1">Your booking is being reviewed</p>
                                <small class="text-muted">
                                    Expected response within 24 hours
                                </small>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Booking Info & Actions -->
        <div class="col-lg-4 mb-4">
            <!-- Status Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Booking Status</h5>
                </div>
                <div class="card-body">
                    <div class="status-badge mb-3">
                        @if($booking->status == 'approved')
                        <span class="badge bg-success">
                            <i class="bx bx-check-circle me-1"></i> Approved
                        </span>
                        @elseif($booking->status == 'pending')
                        <span class="badge bg-warning">
                            <i class="bx bx-time-five me-1"></i> Pending
                        </span>
                        @elseif($booking->status == 'rejected')
                        <span class="badge bg-danger">
                            <i class="bx bx-x-circle me-1"></i> Rejected
                        </span>
                        @elseif($booking->status == 'cancelled')
                        <span class="badge bg-secondary">
                            <i class="bx bx-block me-1"></i> Cancelled
                        </span>
                        @endif
                    </div>

                    <div class="booking-details">
                        <div class="detail-item">
                            <i class="bx bx-calendar"></i>
                            <div>
                                <span class="text-muted">Date</span>
                                <strong>{{ \Carbon\Carbon::parse($booking->tanggal_mulai)->format('l, d F Y') }}</strong>
                            </div>
                        </div>

                        <div class="detail-item">
                            <i class="bx bx-time"></i>
                            <div>
                                <span class="text-muted">Time</span>
                                <strong>{{ $booking->waktu_mulai }} - {{ $booking->waktu_selesai }}</strong>
                            </div>
                        </div>

                        <div class="detail-item">
                            <i class="bx bx-user"></i>
                            <div>
                                <span class="text-muted">Participants</span>
                                <strong>{{ $booking->jumlah_peserta ?? 1 }} people</strong>
                            </div>
                        </div>

                        <div class="detail-item">
                            <i class="bx bx-bullseye"></i>
                            <div>
                                <span class="text-muted">Purpose</span>
                                <strong>{{ $booking->tujuan ?? 'Not specified' }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Cancel Modal -->
@if($booking->status == 'pending')
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cancel Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to cancel this booking?</p>
                <div class="alert alert-warning">
                    <i class="bx bx-error-circle me-2"></i>
                    This action cannot be undone.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">No</button>
                <form action="{{ route('guest.bookings.cancel', $booking->id) }}" method="POST">
                    @csrf
                    @method('POST')
                    <button type="submit" class="btn btn-danger">Yes, Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
