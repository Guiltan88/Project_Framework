@extends('layouts.app')
@section('title', 'Booking Details - ' . $booking->kode_booking)

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.bookings.index') }}" class="btn btn-label-secondary">
        <i class="bx bx-arrow-back me-1"></i> Back to Bookings
    </a>
</div>

<div class="row">
    <!-- Booking Details -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="bx bx-calendar-event me-2"></i> Booking Details
                </h5>
                <span class="badge
                    @if($booking->status == 'pending') bg-warning
                    @elseif($booking->status == 'approved') bg-success
                    @elseif($booking->status == 'rejected') bg-danger
                    @elseif($booking->status == 'cancelled') bg-secondary
                    @else bg-info @endif">
                    {{ ucfirst($booking->status) }}
                </span>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Booking Code</h6>
                        <p class="mb-0 fw-bold">{{ $booking->kode_booking }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Created At</h6>
                        <p class="mb-0">{{ $booking->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Event Purpose</h6>
                        <p class="mb-0">{{ $booking->tujuan }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Number of Participants</h6>
                        <p class="mb-0">{{ $booking->jumlah_peserta }} people</p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Start Date & Time</h6>
                        <p class="mb-0">
                            <i class="bx bx-calendar me-1"></i>
                            {{ $booking->tanggal_mulai->format('d M Y') }}<br>
                            <i class="bx bx-time me-1"></i>
                            {{ $booking->waktu_mulai->format('H:i') }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">End Date & Time</h6>
                        <p class="mb-0">
                            <i class="bx bx-calendar me-1"></i>
                            {{ $booking->tanggal_selesai->format('d M Y') }}<br>
                            <i class="bx bx-time me-1"></i>
                            {{ $booking->waktu_selesai->format('H:i') }}
                        </p>
                    </div>
                </div>

                @if($booking->kebutuhan_khusus)
                <div class="mb-4">
                    <h6 class="text-muted mb-2">Special Requirements</h6>
                    <p class="mb-0">{{ $booking->kebutuhan_khusus }}</p>
                </div>
                @endif

                @if($booking->catatan)
                <div class="mb-4">
                    <h6 class="text-muted mb-2">Notes</h6>
                    <p class="mb-0">{{ $booking->catatan }}</p>
                </div>
                @endif

                @if($booking->status == 'rejected' && $booking->alasan_penolakan)
                <div class="alert alert-danger">
                    <h6 class="alert-heading mb-2">
                        <i class="bx bx-error-circle me-1"></i> Rejection Reason
                    </h6>
                    <p class="mb-0">{{ $booking->alasan_penolakan }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- User & Room Info -->
    <div class="col-lg-4 mb-4">
        <!-- User Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bx bx-user me-2"></i> Booked By
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    @if($booking->user->profile_photo_path)
                    <img src="{{ asset('storage/' . $booking->user->profile_photo_path) }}"
                         alt="{{ $booking->user->name }}"
                         class="rounded-circle me-3"
                         style="width: 50px; height: 50px; object-fit: cover;">
                    @else
                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3"
                         style="width: 50px; height: 50px;">
                        <i class="bx bx-user bx-sm text-muted"></i>
                    </div>
                    @endif
                    <div>
                        <h6 class="mb-0">{{ $booking->user->name }}</h6>
                        <small class="text-muted">{{ $booking->user->email }}</small>
                    </div>
                </div>

                @if($booking->user->phone)
                <div class="mb-2">
                    <i class="bx bx-phone me-1 text-muted"></i>
                    {{ $booking->user->phone }}
                </div>
                @endif

                @if($booking->user->department)
                <div class="mb-0">
                    <i class="bx bx-briefcase me-1 text-muted"></i>
                    {{ $booking->user->department }}
                </div>
                @endif
            </div>
        </div>

        <!-- Room Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bx bx-door-open me-2"></i> Room Details
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    @if($booking->room->gambar)
                    <div class="text-center">
                        <img src="{{ asset('storage/' . $booking->room->gambar) }}"
                             alt="{{ $booking->room->nama_ruangan }}"
                             class="img-fluid rounded mb-3"
                             style="max-height: 150px; object-fit: cover; width: 100%;">
                    </div>
                    @endif
                    <h6 class="mb-2">{{ $booking->room->nama_ruangan }}</h6>
                </div>

                <div class="mb-3">
                    <p class="mb-2">
                        <i class="bx bx-building me-1 text-muted"></i>
                        {{ $booking->room->building->nama_gedung ?? 'N/A' }}
                    </p>
                    <p class="mb-2">
                        <i class="bx bx-layers me-1 text-muted"></i>
                        Floor: {{ $booking->room->lantai ?? 'N/A' }}
                    </p>
                    <p class="mb-2">
                        <i class="bx bx-user me-1 text-muted"></i>
                        Capacity: {{ $booking->room->kapasitas }} people
                    </p>
                    <p class="mb-0">
                        <span class="badge
                            @if($booking->room->status == 'tersedia') bg-success
                            @elseif($booking->room->status == 'terisi') bg-danger
                            @else bg-secondary @endif">
                            {{ $booking->room->status == 'tersedia' ? 'Available' : 'Unavailable' }}
                        </span>
                    </p>
                </div>

                @if($booking->room->facilities->count() > 0)
                <div>
                    <h6 class="text-muted mb-2">Facilities</h6>
                    <div class="d-flex flex-wrap gap-1">
                        @foreach($booking->room->facilities as $facility)
                        <span class="badge bg-light text-dark">{{ $facility->nama_fasilitas }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        @if($booking->status == 'pending')
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bx bx-cog me-2"></i> Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <form action="{{ route('admin.bookings.approve', $booking) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bx bx-check me-1"></i> Approve Booking
                        </button>
                    </form>

                    <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                        <i class="bx bx-x me-1"></i> Reject Booking
                    </button>

                    <form action="{{ route('admin.bookings.cancel', $booking) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-warning w-100"
                                onclick="return confirm('Are you sure you want to cancel this booking?')">
                            <i class="bx bx-time me-1"></i> Cancel Booking
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Reject Modal -->
@if($booking->status == 'pending')
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.bookings.reject', $booking) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="alasan_penolakan" class="form-label">Reason for Rejection <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="alasan_penolakan" name="alasan_penolakan" rows="3"
                                  placeholder="Please provide a reason for rejecting this booking..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Booking</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
