@extends('layouts.app')
@section('title', 'Available Rooms')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/guest/rooms.css') }}">
@endpush

@section('content')
<div class="guest-rooms">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <h4 class="card-title mb-1">Available Rooms</h4>
                            <p class="text-muted mb-0">
                                Browse and book available meeting rooms
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
                                <i class="bx bx-door-open bx-sm"></i>
                            </span>
                        </div>
                        <div>
                            <span class="fw-semibold d-block mb-1">Total Rooms</span>
                            <h3 class="card-title mb-0">{{ $totalRooms }}</h3>
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
                            <span class="fw-semibold d-block mb-1">Available</span>
                            <h3 class="card-title mb-0">{{ $availableRooms }}</h3>
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
                            <span class="fw-semibold d-block mb-1">Occupied</span>
                            <h3 class="card-title mb-0">{{ $occupiedRooms }}</h3>
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
                                <i class="bx bx-wrench bx-sm"></i>
                            </span>
                        </div>
                        <div>
                            <span class="fw-semibold d-block mb-1">Maintenance</span>
                            <h3 class="card-title mb-0">{{ $maintenanceRooms }}</h3>
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
                    <form action="{{ route('guest.rooms.index') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Building</label>
                            <select class="form-select" name="building">
                                <option value="">All Buildings</option>
                                @foreach($buildings as $building)
                                <option value="{{ $building->id }}" {{ request('building') == $building->id ? 'selected' : '' }}>
                                    {{ $building->nama_gedung }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="">All Status</option>
                                <option value="tersedia" {{ request('status') == 'tersedia' ? 'selected' : '' }}>Available</option>
                                <option value="terisi" {{ request('status') == 'terisi' ? 'selected' : '' }}>Occupied</option>
                                <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Search</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bx bx-search"></i></span>
                                <input type="text" class="form-control" name="search"
                                       value="{{ request('search') }}" placeholder="Search by room name...">
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
                    <span class="text-primary">{{ $rooms->count() }}</span> rooms found
                    @if(request()->has('search') && request('search'))
                        <span class="text-muted">for "{{ request('search') }}"</span>
                    @endif
                </h5>

                @if($rooms->total() > 0)
                <div class="d-flex align-items-center">
                    <span class="text-muted me-3 small">Sort by:</span>
                    <select class="form-select form-select-sm w-auto" onchange="window.location.href = this.value">
                        @php
                            $currentUrl = route('guest.rooms.index');
                        @endphp
                        <option value="{{ $currentUrl }}?sort=available" {{ request('sort') == 'available' ? 'selected' : '' }}>
                            Available First
                        </option>
                        <option value="{{ $currentUrl }}?sort=name" {{ request('sort') == 'name' ? 'selected' : '' }}>
                            Room Name
                        </option>
                        <option value="{{ $currentUrl }}?sort=capacity" {{ request('sort') == 'capacity' ? 'selected' : '' }}>
                            Capacity
                        </option>
                        <option value="{{ $currentUrl }}?sort=latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>
                            Latest Added
                        </option>
                    </select>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Rooms Grid -->
    <div class="row">
        @forelse($rooms as $room)
        <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
            <div class="card h-100 room-card">
                <div class="card-img-container">
                    @if($room->foto)
                    <img src="{{ asset('storage/' . $room->foto) }}"
                         class="card-img-top"
                         alt="{{ $room->nama_ruangan }}"
                         style="height: 200px; object-fit: cover;">
                    @else
                    <div class="card-img-top d-flex align-items-center justify-content-center bg-light"
                         style="height: 200px;">
                        <i class="bx bx-door-open bx-lg text-muted"></i>
                    </div>
                    @endif

                    <!-- Status Badge -->
                    <div class="status-badge">
                        @if($room->status == 'tersedia')
                        <span class="badge bg-success">
                            <i class="bx bx-check-circle me-1"></i> Available
                        </span>
                        @elseif($room->status == 'terisi')
                        <span class="badge bg-danger">
                            <i class="bx bx-x-circle me-1"></i> Occupied
                        </span>
                        @elseif($room->status == 'maintenance')
                        <span class="badge bg-warning">
                            <i class="bx bx-wrench me-1"></i> Maintenance
                        </span>
                        @endif
                    </div>
                </div>

                <div class="card-body d-flex flex-column">
                    <h5 class="card-title mb-2">{{ $room->nama_ruangan }}</h5>

                    <div class="room-info mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bx bx-building text-primary me-2"></i>
                            <small class="text-muted">{{ $room->building->nama_gedung ?? 'No Building' }}</small>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="bx bx-user text-primary me-2"></i>
                            <small class="text-muted">Capacity: {{ $room->kapasitas }} people</small>
                        </div>
                        @if($room->tipe_ruangan)
                        <div class="d-flex align-items-center mb-2">
                            <i class="bx bx-category text-primary me-2"></i>
                            <small class="text-muted">{{ ucfirst($room->tipe_ruangan) }}</small>
                        </div>
                        @endif
                    </div>

                    <div class="mt-auto">
                        <div class="d-flex gap-2">
                            <a href="{{ route('guest.rooms.show', $room->id) }}"
                               class="btn btn-outline-primary btn-sm flex-fill">
                                <i class="bx bx-show me-1"></i> View
                            </a>
                            @if($room->status == 'tersedia')
                            <a href="{{ route('bookings.create') }}?room={{ $room->id }}"
                               class="btn btn-primary btn-sm flex-fill">
                                <i class="bx bx-calendar-plus me-1"></i> Book
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bx bx-door-open bx-lg text-muted mb-3"></i>
                    <h5 class="text-muted">No Rooms Found</h5>
                    <p class="text-muted mb-4">
                        @if(request()->has('search'))
                            No rooms match your search criteria.
                        @else
                            No rooms are currently available.
                        @endif
                    </p>
                    @if(request()->has('search') || request()->has('building') || request()->has('status'))
                    <a href="{{ route('guest.rooms.index') }}" class="btn btn-primary">
                        <i class="bx bx-refresh me-1"></i> Clear Filters
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($rooms->hasPages())
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">
                                Showing {{ $rooms->firstItem() ?? 0 }} to {{ $rooms->lastItem() ?? 0 }}
                                of {{ $rooms->total() }} entries
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
                                @if($rooms->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link">
                                            <i class="bx bx-chevron-left"></i>
                                        </span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $rooms->previousPageUrl() }}" aria-label="Previous">
                                            <i class="bx bx-chevron-left"></i>
                                        </a>
                                    </li>
                                @endif

                                {{-- Pagination Elements --}}
                                @foreach($rooms->getUrlRange(1, $rooms->lastPage()) as $page => $url)
                                    @if($page == $rooms->currentPage())
                                        <li class="page-item active">
                                            <span class="page-link">{{ $page }}</span>
                                        </li>
                                    @elseif(
                                        $page == 1 ||
                                        $page == $rooms->lastPage() ||
                                        ($page >= $rooms->currentPage() - 1 && $page <= $rooms->currentPage() + 1)
                                    )
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                        </li>
                                    @elseif(
                                        $page == $rooms->currentPage() - 2 ||
                                        $page == $rooms->currentPage() + 2
                                    )
                                        <li class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                    @endif
                                @endforeach

                                {{-- Next Page Link --}}
                                @if($rooms->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $rooms->nextPageUrl() }}" aria-label="Next">
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
                                <option value="12" {{ request('per_page', 12) == 12 ? 'selected' : '' }}>12</option>
                                <option value="24" {{ request('per_page') == 24 ? 'selected' : '' }}>24</option>
                                <option value="36" {{ request('per_page') == 36 ? 'selected' : '' }}>36</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@push('styles')
<style>
    .room-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 1px solid #e9ecef;
    }
    .room-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .card-img-container {
        position: relative;
    }
    .status-badge {
        position: absolute;
        top: 10px;
        right: 10px;
    }
    .room-info {
        flex-grow: 1;
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
});
</script>
@endsection
