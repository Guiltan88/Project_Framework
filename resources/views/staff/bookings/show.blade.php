@extends('Layouts.app')
@section('title', 'Booking Details')

@section('content')
        <!-- Back Button -->
        <div class="mb-4">
            <a href="{{ route('staff.bookings.index') }}" class="btn btn-label-secondary">
                <i class="bx bx-arrow-back me-1"></i> Back to List
            </a>
        </div>

        <div class="row">
            <!-- Left Column: Booking Details -->
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Booking Details</h5>
                        <span class="badge bg-{{ $booking->status == 'pending' ? 'warning' : ($booking->status == 'approved' ? 'success' : 'danger') }}">
                            {{ strtoupper($booking->status) }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="text-muted">Booking Information</h6>
                                <dl class="row">
                                    <dt class="col-sm-4">Booking Code</dt>
                                    <dd class="col-sm-8">
                                        <strong class="text-primary">#{{ $booking->kode_booking }}</strong>
                                    </dd>

                                    <dt class="col-sm-4">Booking Date</dt>
                                    <dd class="col-sm-8">{{ $booking->created_at->format('d F Y H:i') }}</dd>

                                    <dt class="col-sm-4">Event Date</dt>
                                    <dd class="col-sm-8">{{ $booking->tanggal_mulai->format('d F Y') }}</dd>

                                    <dt class="col-sm-4">Time</dt>
                                    <dd class="col-sm-8">{{ $booking->waktu_mulai }} - {{ $booking->waktu_selesai }}</dd>

                                    <dt class="col-sm-4">Participants</dt>
                                    <dd class="col-sm-8">{{ $booking->jumlah_peserta ?? 0 }} people</dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted">Room Information</h6>
                                <dl class="row">
                                    <dt class="col-sm-4">Room</dt>
                                    <dd class="col-sm-8">{{ $booking->room->nama_ruangan ?? 'N/A' }}</dd>

                                    <dt class="col-sm-4">Building</dt>
                                    <dd class="col-sm-8">{{ $booking->room->building->nama_gedung ?? 'N/A' }}</dd>

                                    <dt class="col-sm-4">Capacity</dt>
                                    <dd class="col-sm-8">{{ $booking->room->kapasitas ?? 0 }} people</dd>

                                    <dt class="col-sm-4">Room Type</dt>
                                    <dd class="col-sm-8">{{ $booking->room->tipe_ruangan ?? 'N/A' }}</dd>
                                </dl>
                            </div>
                        </div>

                        <h6 class="text-muted mb-3">Purpose</h6>
                        <div class="alert alert-primary">
                            {{ $booking->tujuan }}
                        </div>

                        @if($booking->alasan_penolakan)
                        <h6 class="text-muted mb-3">Rejection Reason</h6>
                        <div class="alert alert-danger">
                            {{ $booking->alasan_penolakan }}
                        </div>
                        @endif
                    </div>
                </div>

                <!-- User Information -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">User Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <div class="avatar avatar-xl me-3">
                                <span class="avatar-initial rounded-circle bg-label-primary">
                                    {{ strtoupper(substr($booking->user->name ?? 'U', 0, 1)) }}
                                </span>
                            </div>
                            <div>
                                <h5 class="mb-1">{{ $booking->user->name ?? 'N/A' }}</h5>
                                <p class="text-muted mb-0">{{ $booking->user->email ?? '' }}</p>
                            </div>
                        </div>
                        <dl class="row">
                            <dt class="col-sm-3">Phone</dt>
                            <dd class="col-sm-9">{{ $booking->user->phone ?? '-' }}</dd>

                            <dt class="col-sm-3">Department</dt>
                            <dd class="col-sm-9">{{ $booking->user->department ?? '-' }}</dd>

                            <dt class="col-sm-3">Member Since</dt>
                            <dd class="col-sm-9">{{ $booking->user->created_at->format('d F Y') ?? '-' }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Right Column: Actions -->
            <div class="col-lg-4">
                <!-- Approval Actions -->
                @if($booking->status == 'pending')
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Approval Actions</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('staff.bookings.approve', $booking->id) }}" method="POST" class="mb-3">
                            @csrf
                            <button type="submit" class="btn btn-success w-100 mb-2" onclick="return confirm('Approve this booking?')">
                                <i class="bx bx-check me-1"></i> Approve Booking
                            </button>
                        </form>

                        <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class="bx bx-x me-1"></i> Reject Booking
                        </button>
                    </div>
                </div>
                @endif

                <!-- Booking Timeline -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Booking Timeline</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <div class="badge badge-dot bg-primary me-2"></div>
                                <h6 class="mb-0">Booking Created</h6>
                            </div>
                            <p class="text-muted mb-3 ps-4">{{ $booking->created_at->format('d M Y H:i') }}</p>

                            @if($booking->status == 'approved')
                            <div class="d-flex align-items-center mb-2">
                                <div class="badge badge-dot bg-success me-2"></div>
                                <h6 class="mb-0">Booking Approved</h6>
                            </div>
                            <p class="text-muted mb-3 ps-4">{{ $booking->approved_at->format('d M Y H:i') }}</p>
                            <p class="ps-4 mb-0">Approved by: {{ $booking->approver->name ?? 'System' }}</p>
                            @elseif($booking->status == 'rejected')
                            <div class="d-flex align-items-center mb-2">
                                <div class="badge badge-dot bg-danger me-2"></div>
                                <h6 class="mb-0">Booking Rejected</h6>
                            </div>
                            <p class="text-muted mb-3 ps-4">{{ $booking->approved_at->format('d M Y H:i') }}</p>
                            <p class="ps-4 mb-0">Rejected by: {{ $booking->approver->name ?? 'System' }}</p>
                            @else
                            <div class="d-flex align-items-center mb-2">
                                <div class="badge badge-dot bg-warning me-2"></div>
                                <h6 class="mb-0">Pending Approval</h6>
                            </div>
                            <p class="text-muted ps-4 mb-0">Waiting for staff approval</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
<!-- Content wrapper -->

<!-- Reject Modal -->
@if($booking->status == 'pending')
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('staff.bookings.reject', $booking->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Reject Booking #{{ $booking->kode_booking }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to reject this booking?</p>
                    <div class="mb-3">
                        <label for="alasan_penolakan" class="form-label">Rejection Reason *</label>
                        <textarea class="form-control" name="alasan_penolakan" rows="3" required placeholder="Please provide a reason for rejection"></textarea>
                        <small class="text-muted">This reason will be visible to the user.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Booking</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
