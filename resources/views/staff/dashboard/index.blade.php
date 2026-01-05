@extends('Layouts.app')
@section('title', 'Staff Dashboard')
@section('content')

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <!-- Pending Bookings -->
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-warning">
                                <i class="bx bx-time bx-sm"></i>
                            </span>
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1">Pending Bookings</span>
                    <h3 class="card-title mb-2">{{ $stats['pending_bookings'] }}</h3>
                    <small class="text-warning fw-semibold">
                        <i class="bx bx-time-five"></i> Need approval
                    </small>
                </div>
            </div>
        </div>

        <!-- Today's Bookings -->
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class="bx bx-calendar bx-sm"></i>
                            </span>
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1">Today's Bookings</span>
                    <h3 class="card-title mb-2">{{ $stats['today_bookings'] }}</h3>
                    <small class="text-primary fw-semibold">
                        <i class="bx bx-calendar-check"></i> Running today
                    </small>
                </div>
            </div>
        </div>

        <!-- Available Rooms -->
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="bx bx-door-open bx-sm"></i>
                            </span>
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1">Available Rooms</span>
                    <h3 class="card-title mb-2">{{ $stats['available_rooms'] }}</h3>
                    <small class="text-success fw-semibold">
                        <i class="bx bx-check-circle"></i> Ready to book
                    </small>
                </div>
            </div>
        </div>

        <!-- My Approvals Today -->
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-info">
                                <i class="bx bx-check-shield bx-sm"></i>
                            </span>
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1">My Approvals</span>
                    <h3 class="card-title mb-2">{{ $stats['my_approvals'] }}</h3>
                    <small class="text-info fw-semibold">
                        <i class="bx bx-user-check"></i> Approved today
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Bookings Table -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Pending Bookings Requiring Action</h5>
                    <a href="{{ route('staff.bookings.index') }}" class="btn btn-primary btn-sm">
                        <i class="bx bx-list-ul me-1"></i> View All
                    </a>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Booking Code</th>
                                <th>Room</th>
                                <th>User</th>
                                <th>Date & Time</th>
                                <th>Purpose</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse($pendingBookings as $booking)
                            <tr>
                                <td>
                                    <strong class="text-primary">#{{ $booking->kode_booking }}</strong>
                                </td>
                                <td>
                                    <strong>{{ $booking->room->nama_ruangan ?? 'N/A' }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $booking->room->building->nama_gedung ?? '' }}</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-xs me-2">
                                            <span class="avatar-initial rounded-circle bg-label-primary">
                                                {{ strtoupper(substr($booking->user->name ?? 'U', 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <strong>{{ $booking->user->name ?? 'N/A' }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $booking->user->email ?? '' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ $booking->tanggal_mulai->format('d M Y') }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        {{ $booking->waktu_mulai }} - {{ $booking->waktu_selesai }}
                                    </small>
                                </td>
                                <td>
                                    {{ Str::limit($booking->tujuan, 30) }}
                                    @if($booking->jumlah_peserta)
                                    <br>
                                    <small class="text-muted">{{ $booking->jumlah_peserta }} people</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('staff.bookings.show', $booking->id) }}">
                                                <i class="bx bx-show me-1"></i> View Details
                                            </a>
                                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('approve-form-{{ $booking->id }}').submit();">
                                                <i class="bx bx-check me-1 text-success"></i> Approve
                                            </a>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $booking->id }}">
                                                <i class="bx bx-x me-1 text-danger"></i> Reject
                                            </a>
                                        </div>
                                    </div>

                                    <form id="approve-form-{{ $booking->id }}" action="{{ route('staff.bookings.approve', $booking->id) }}" method="POST" class="d-none">
                                        @csrf
                                    </form>

                                    <!-- Reject Modal -->
                                    <div class="modal fade" id="rejectModal{{ $booking->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('staff.bookings.reject', $booking->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Reject Booking #{{ $booking->kode_booking }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="alasan_penolakan" class="form-label">Rejection Reason</label>
                                                            <textarea class="form-control" id="alasan_penolakan" name="alasan_penolakan" rows="3" required placeholder="Please provide a reason for rejection"></textarea>
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
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="bx bx-check-circle bx-lg text-muted"></i>
                                    <p class="text-muted mt-3 mb-0">No pending bookings requiring action</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
