@extends('layouts.app')
@section('title', 'Booking Approvals')

@section('content')
        <!-- Search Bar -->
        <div class="row mb-4">
            <div class="col-md-6">
                <form action="{{ route('staff.bookings.index') }}" method="GET">
                    <div class="input-group input-group-merge">
                        <span class="input-group-text"><i class="bx bx-search"></i></span>
                        <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Search by booking code or user name...">
                        <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Bookings Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Pending Bookings</h5>
                <span class="badge bg-warning">
                    {{ $bookings->total() }} Pending Approval
                </span>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Booking Code</th>
                            <th>Room</th>
                            <th>User</th>
                            <th>Date & Time</th>
                            <th>Purpose</th>
                            <th>Participants</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($bookings as $booking)
                        <tr>
                            <td>
                                <strong class="text-primary">#{{ $booking->kode_booking }}</strong>
                                <br>
                                <small class="text-muted">{{ $booking->created_at->format('d M Y H:i') }}</small>
                            </td>
                            <td>
                                <strong>{{ $booking->room->nama_ruangan ?? 'N/A' }}</strong>
                                <br>
                                <small class="text-muted">
                                    <i class="bx bx-building"></i> {{ $booking->room->building->nama_gedung ?? '' }}
                                </small>
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
                                    <i class="bx bx-time"></i> {{ $booking->waktu_mulai }} - {{ $booking->waktu_selesai }}
                                </small>
                            </td>
                            <td>
                                <div class="text-wrap" style="max-width: 200px;">
                                    {{ $booking->tujuan }}
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-label-primary">
                                    {{ $booking->jumlah_peserta ?? 0 }} people
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('staff.bookings.show', $booking->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bx bx-show"></i>
                                    </a>
                                    <form action="{{ route('staff.bookings.approve', $booking->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Approve this booking?')">
                                            <i class="bx bx-check"></i>
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $booking->id }}">
                                        <i class="bx bx-x"></i>
                                    </button>
                                </div>

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
                                                        <textarea class="form-control" name="alasan_penolakan" rows="3" required placeholder="Please provide a reason for rejection"></textarea>
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
                            <td colspan="7" class="text-center py-5">
                                <i class="bx bx-check-circle bx-lg text-muted"></i>
                                <p class="text-muted mt-3 mb-0">No pending bookings found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($bookings->hasPages())
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted">
                        Showing <strong>{{ $bookings->firstItem() ?? 0 }}</strong> to <strong>{{ $bookings->lastItem() ?? 0 }}</strong> of <strong>{{ $bookings->total() }}</strong> entries
                    </div>

                    <nav aria-label="Page navigation">
                        <ul class="pagination mb-0">
                            {{-- Previous Page Link --}}
                            @if($bookings->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link" aria-label="Previous">
                                    <i class="bx bx-chevron-left"></i>
                                </span>
                            </li>
                            @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $bookings->previousPageUrl() }}" aria-label="Previous">
                                    <i class="bx bx-chevron-left"></i>
                                </a>
                            </li>
                            @endif

                            {{-- Pagination Elements --}}
                            @php
                                // Calculate page range
                                $current = $bookings->currentPage();
                                $last = $bookings->lastPage();
                                $start = max(1, $current - 2);
                                $end = min($last, $current + 2);

                                // Adjust start and end if near boundaries
                                if ($current <= 3) {
                                    $end = min(5, $last);
                                }
                                if ($current >= $last - 2) {
                                    $start = max(1, $last - 4);
                                }
                            @endphp

                            {{-- First page link if not in range --}}
                            @if($start > 1)
                            <li class="page-item">
                                <a class="page-link" href="{{ $bookings->url(1) }}">1</a>
                            </li>
                            @if($start > 2)
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                            @endif
                            @endif

                            {{-- Page links --}}
                            @for($page = $start; $page <= $end; $page++)
                            @if($page == $bookings->currentPage())
                            <li class="page-item active" aria-current="page">
                                <span class="page-link">{{ $page }}</span>
                            </li>
                            @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $bookings->url($page) }}">{{ $page }}</a>
                            </li>
                            @endif
                            @endfor

                            {{-- Last page link if not in range --}}
                            @if($end < $last)
                            @if($end < $last - 1)
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                            @endif
                            <li class="page-item">
                                <a class="page-link" href="{{ $bookings->url($last) }}">{{ $last }}</a>
                            </li>
                            @endif

                            {{-- Next Page Link --}}
                            @if($bookings->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $bookings->nextPageUrl() }}" aria-label="Next">
                                    <i class="bx bx-chevron-right"></i>
                                </a>
                            </li>
                            @else
                            <li class="page-item disabled">
                                <span class="page-link" aria-label="Next">
                                    <i class="bx bx-chevron-right"></i>
                                </span>
                            </li>
                            @endif
                        </ul>
                    </nav>
                </div>
            </div>
            @endif
        </div>

<!-- Content wrapper -->
@endsection
