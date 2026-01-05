@extends('layouts.app')
@section('title', 'Available Rooms')

@section('content')

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
                    <a href="{{ route('guest.rooms.index') }}" class="btn btn-primary">
                        <i class="bx bx-plus me-1"></i> New Booking
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

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
                        <div class="input-group input-group-merge">
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

@if($rooms->count() > 0)
<div class="row mb-3">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <span class="text-primary">{{ $rooms->count() }}</span> rooms found
                @if(request()->has('search') && request('search'))
                    <span class="text-muted">for "{{ request('search') }}"</span>
                @endif
            </h5>
        </div>
    </div>
</div>
@endif

<div class="row">
    @forelse($rooms as $room)
    <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
        <div class="card h-100">
            @if($room->gambar)
            <div style="height: 180px; overflow: hidden;">
                <img src="{{ asset('storage/' . $room->gambar) }}"
                     class="card-img-top"
                     alt="{{ $room->nama_ruangan }}"
                     style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            @else
            <div class="d-flex align-items-center justify-content-center bg-light" style="height: 180px;">
                <i class="bx bx-door-open bx-lg text-muted"></i>
            </div>
            @endif

            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h5 class="card-title mb-0">{{ $room->nama_ruangan }}</h5>
                    <span class="badge
                        @if($room->status == 'tersedia') bg-success
                        @elseif($room->status == 'tidak tersedia') bg-danger
                        @elseif($room->status == 'dalam perbaikan') bg-warning text-dark
                        @else bg-secondary @endif">
                        @if($room->status == 'tersedia') Available
                        @elseif($room->status == 'tidak tersedia') Occupied
                        @elseif($room->status == 'dalam perbaikan') Maintenance
                        @else {{ $room->status }}
                        @endif
                    </span>
                </div>

                <p class="card-text text-muted mb-2">
                    <i class="bx bx-building me-1"></i>
                    {{ $room->building->nama_gedung ?? 'No Building' }}
                </p>

                <div class="d-flex gap-2 mb-3">
                    <span class="badge bg-label-primary">
                        <i class="bx bx-user me-1"></i>
                        {{ $room->kapasitas }} people
                    </span>
                    @if($room->tipe_ruangan)
                    <span class="badge bg-label-secondary">
                        {{ ucfirst($room->tipe_ruangan) }}
                    </span>
                    @endif
                </div>

                @if($room->facilities && $room->facilities->count() > 0)
                <div class="mb-3">
                    <small class="text-muted d-block mb-1">Facilities:</small>
                    <div class="d-flex flex-wrap gap-1">
                        @foreach($room->facilities->take(3) as $facility)
                        <span class="badge bg-light text-dark">
                            {{ $facility->nama_fasilitas }}
                        </span>
                        @endforeach
                        @if($room->facilities->count() > 3)
                        <span class="badge bg-light text-muted">
                            +{{ $room->facilities->count() - 3 }}
                        </span>
                        @endif
                    </div>
                </div>
                @endif

                <div class="d-flex gap-2 mt-auto">
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

@if($rooms->hasPages())
<div class="d-flex justify-content-center mt-4">
    <nav>
        <ul class="pagination">
            @if($rooms->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link">Previous</span>
            </li>
            @else
            <li class="page-item">
                <a class="page-link" href="{{ $rooms->previousPageUrl() }}">Previous</a>
            </li>
            @endif

            @php
                $current = $rooms->currentPage();
                $last = $rooms->lastPage();
                $start = max(1, $current - 2);
                $end = min($last, $current + 2);

                if ($current <= 3) {
                    $end = min(5, $last);
                }
                if ($current >= $last - 2) {
                    $start = max(1, $last - 4);
                }
            @endphp

            @if($start > 1)
            <li class="page-item">
                <a class="page-link" href="{{ $rooms->url(1) }}">1</a>
            </li>
            @if($start > 2)
            <li class="page-item disabled">
                <span class="page-link">...</span>
            </li>
            @endif
            @endif

            @for($page = $start; $page <= $end; $page++)
            @if($page == $current)
            <li class="page-item active">
                <span class="page-link">{{ $page }}</span>
            </li>
            @else
            <li class="page-item">
                <a class="page-link" href="{{ $rooms->url($page) }}">{{ $page }}</a>
            </li>
            @endif
            @endfor

            @if($end < $last)
            @if($end < $last - 1)
            <li class="page-item disabled">
                <span class="page-link">...</span>
            </li>
            @endif
            <li class="page-item">
                <a class="page-link" href="{{ $rooms->url($last) }}">{{ $last }}</a>
            </li>
            @endif

            @if($rooms->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $rooms->nextPageUrl() }}">Next</a>
            </li>
            @else
            <li class="page-item disabled">
                <span class="page-link">Next</span>
            </li>
            @endif
        </ul>
    </nav>
</div>
@endif

@endsection
