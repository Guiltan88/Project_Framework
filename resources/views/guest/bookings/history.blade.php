@extends('layouts.app')
@section('title', 'Booking History')

@section('content')
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <h4 class="card-title mb-1">My Booking History</h4>
                            <p class="text-muted mb-0">
                                Track all your room booking requests and their status
                            </p>
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
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class="bx bx-calendar-check bx-sm"></i>
                            </span>
                        </div>
                        <div>
                            <span class="fw-semibold d-block mb-1">Total Bookings</span>
                            <h3 class="card-title mb-0">{{ $bookings->total() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="bx bx-check-circle bx-sm"></i>
                            </span>
                        </div>
                        <div>
                            <span class="fw-semibold d-block mb-1">Approved</span>
                            <h3 class="card-title mb-0">{{ \App\Models\Booking::where('user_id', auth()->id())->where('status', 'approved')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-warning">
                                <i class="bx bx-time-five bx-sm"></i>
                            </span>
                        </div>
                        <div>
                            <span class="fw-semibold d-block mb-1">Pending</span>
                            <h3 class="card-title mb-0">{{ \App\Models\Booking::where('user_id', auth()->id())->where('status', 'pending')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-danger">
                                <i class="bx bx-x-circle bx-sm"></i>
                            </span>
                        </div>
                        <div>
                            <span class="fw-semibold d-block mb-1">Cancelled/Rejected</span>
                            <h3 class="card-title mb-0">{{ \App\Models\Booking::where('user_id', auth()->id())->whereIn('status', ['cancelled', 'rejected'])->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('guest.bookings.history') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date Range</label>
                            <select class="form-select" name="date_range">
                                <option value="">All Dates</option>
                                <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
                                <option value="this_week" {{ request('date_range') == 'this_week' ? 'selected' : '' }}>This Week</option>
                                <option value="this_month" {{ request('date_range') == 'this_month' ? 'selected' : '' }}>This Month</option>
                                <option value="upcoming" {{ request('date_range') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                <option value="past" {{ request('date_range') == 'past' ? 'selected' : '' }}>Past Bookings</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Search</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bx bx-search"></i></span>
                                <input type="text" class="form-control" name="search"
                                       value="{{ request('search') }}" placeholder="Search by booking code, room name, or purpose...">
                            </div>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <div class="d-grid w-100">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-filter me-1"></i> Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Results Header -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <span class="text-primary">{{ $bookings->count() }}</span> bookings found
                    @if(request()->has('search') && request('search'))
                        <span class="text-muted">for "{{ request('search') }}"</span>
                    @endif
                </h5>

                @if($bookings->total() > 0)
                <div class="d-flex align-items-center">
                    <span class="text-muted me-3 small">Sort by:</span>
                    <select class="form-select form-select-sm w-auto" onchange="window.location.href = this.value">
                        @php
                            $currentUrl = route('guest.bookings.history');
                        @endphp
                        <option value="{{ $currentUrl }}?sort=latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>
                            Latest First
                        </option>
                        <option value="{{ $currentUrl }}?sort=oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>
                            Oldest First
                        </option>
                        <option value="{{ $currentUrl }}?sort=date" {{ request('sort') == 'date' ? 'selected' : '' }}>
                            Booking Date
                        </option>
                        <option value="{{ $currentUrl }}?sort=status" {{ request('sort') == 'status' ? 'selected' : '' }}>
                            Status
                        </option>
                    </select>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Booking Code</th>
                                    <th>Room</th>
                                    <th>Purpose</th>
                                    <th>Date & Time</th>
                                    <th>Participants</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bookings as $booking)
                                <tr>
                                    <td>
                                        <strong>{{ $booking->kode_booking }}</strong>
                                        <div class="text-muted small">
                                            {{ $booking->created_at->format('d M Y') }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($booking->room->foto)
                                            <img src="{{ asset('storage/' . $booking->room->foto) }}"
                                                 class="rounded me-2"
                                                 alt="{{ $booking->room->nama_ruangan }}"
                                                 style="width: 40px; height: 40px; object-fit: cover;"
                                                 onerror="this.src='{{ asset('assets-admin/img/rooms/default-room.jpg') }}'">
                                            @else
                                            <div class="rounded bg-light d-flex align-items-center justify-content-center me-2"
                                                 style="width: 40px; height: 40px;">
                                                <i class="bx bx-door-open text-muted"></i>
                                            </div>
                                            @endif
                                            <div>
                                                <div>{{ $booking->room->nama_ruangan }}</div>
                                                <small class="text-muted">
                                                    {{ $booking->room->building->nama_gedung ?? 'No Building' }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 200px;">
                                            {{ $booking->tujuan }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-semibold">
                                                {{ $booking->tanggal_mulai->format('d M Y') }}
                                            </span>
                                            <small class="text-muted">
                                                {{ $booking->waktu_mulai }} - {{ $booking->waktu_selesai }}
                                            </small>
                                            <small class="text-muted">
                                                Duration:
                                                @php
                                                    $start = $booking->tanggal_mulai->copy()->setTimeFromTimeString($booking->waktu_mulai);
                                                    $end = $booking->tanggal_selesai->copy()->setTimeFromTimeString($booking->waktu_selesai);
                                                    $diff = $start->diff($end);
                                                @endphp
                                                @if($diff->days > 0)
                                                    {{ $diff->days }} days
                                                @else
                                                    {{ $diff->h }}h {{ $diff->i }}m
                                                @endif
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bx bx-user me-2 text-primary"></i>
                                            <span>{{ $booking->jumlah_peserta }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'approved' => 'success',
                                                'rejected' => 'danger',
                                                'cancelled' => 'secondary'
                                            ];
                                            $statusTexts = [
                                                'pending' => 'Pending',
                                                'approved' => 'Approved',
                                                'rejected' => 'Rejected',
                                                'cancelled' => 'Cancelled'
                                            ];
                                        @endphp
                                        <span class="badge bg-label-{{ $statusColors[$booking->status] ?? 'secondary' }}">
                                            {{ $statusTexts[$booking->status] ?? ucfirst($booking->status) }}
                                        </span>
                                        @if($booking->status == 'approved')
                                        <div class="text-success small mt-1">
                                            <i class="bx bx-check"></i> Confirmed
                                        </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('guest.bookings.show', $booking->id) }}"
                                               class="btn btn-sm btn-outline-primary"
                                               title="View Details">
                                                <i class="bx bx-show"></i>
                                            </a>

                                            @if($booking->isPending())
                                            <a href="{{ route('guest.bookings.edit', $booking->id) }}"
                                               class="btn btn-sm btn-outline-warning"
                                               title="Edit Booking">
                                                <i class="bx bx-edit"></i>
                                            </a>

                                            <form action="{{ route('guest.bookings.cancel', $booking->id) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Are you sure you want to cancel this booking?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        title="Cancel Booking">
                                                    <i class="bx bx-x"></i>
                                                </button>
                                            </form>
                                            @endif

                                            @if($booking->isApproved())
                                            <a href="#" class="btn btn-sm btn-success" title="Download Receipt">
                                                <i class="bx bx-download"></i>
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="bx bx-calendar-x bx-lg text-muted mb-3"></i>
                                        <h5 class="text-muted">No Bookings Found</h5>
                                        <p class="text-muted mb-4">
                                            @if(request()->has('search'))
                                                No bookings match your search criteria.
                                            @else
                                                You haven't made any bookings yet.
                                            @endif
                                        </p>
                                        <a href="{{ route('bookings.create') }}" class="btn btn-primary">
                                            <i class="bx bx-plus me-1"></i> Make Your First Booking
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
    </div>

    <!-- Pagination -->
    @if($bookings->hasPages())
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">
                                Showing {{ $bookings->firstItem() ?? 0 }} to {{ $bookings->lastItem() ?? 0 }}
                                of {{ $bookings->total() }} entries
                                @if(request()->has('search') && request('search'))
                                    <span class="ms-2">
                                        <i class="bx bx-search me-1"></i>
                                        Search: "{{ request('search') }}"
                                    </span>
                                @endif
                            </small>
                        </div>

                        <nav aria-label="Page navigation">
                            <ul class="pagination pagination-sm mb-0">
                                {{-- Previous Page Link --}}
                                @if($bookings->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link">
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
                                @foreach($bookings->getUrlRange(1, $bookings->lastPage()) as $page => $url)
                                    @if($page == $bookings->currentPage())
                                        <li class="page-item active">
                                            <span class="page-link">{{ $page }}</span>
                                        </li>
                                    @elseif(
                                        $page == 1 ||
                                        $page == $bookings->lastPage() ||
                                        ($page >= $bookings->currentPage() - 1 && $page <= $bookings->currentPage() + 1)
                                    )
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                        </li>
                                    @elseif(
                                        $page == $bookings->currentPage() - 2 ||
                                        $page == $bookings->currentPage() + 2
                                    )
                                        <li class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                    @endif
                                @endforeach

                                {{-- Next Page Link --}}
                                @if($bookings->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $bookings->nextPageUrl() }}" aria-label="Next">
                                            <i class="bx bx-chevron-right"></i>
                                        </a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link">
                                            <i class="bx bx-chevron-right"></i>
                                        </span>
                                    </li>
                                @endif
                            </ul>
                        </nav>

                        <div class="d-flex align-items-center">
                            <small class="text-muted me-3">Items per page:</small>
                            <select class="form-select form-select-sm w-auto" onchange="updatePerPage(this.value)">
                                <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                                <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                                <option value="30" {{ request('per_page') == 30 ? 'selected' : '' }}>30</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    @push('styles')
    <style>
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid #e9ecef;
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
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
        .badge.bg-label-secondary {
            background-color: rgba(108, 117, 125, 0.1);
            color: #6c757d;
        }
        .pagination .page-link {
            border-radius: 4px;
            margin: 0 2px;
            min-width: 32px;
            text-align: center;
        }
        .pagination .page-item.active .page-link {
            background-color: #696cff;
            border-color: #696cff;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(105, 108, 255, 0.05);
        }
        .text-truncate {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
    @endpush

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Update items per page
        window.updatePerPage = function(perPage) {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', perPage);
            url.searchParams.set('page', 1);
            window.location.href = url.toString();
        };

        // Auto-refresh status (optional)
        function refreshBookingStatus() {
            const pendingRows = document.querySelectorAll('tr:has(.badge.bg-label-warning)');
            if (pendingRows.length > 0) {
                console.log('Auto-refresh pending bookings...');
                // You could implement AJAX refresh here
            }
        }

        // Refresh every 30 seconds if there are pending bookings
        setInterval(refreshBookingStatus, 30000);
    });
    </script>
@endsection
