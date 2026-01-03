@extends('layouts.app')
@section('title', 'Manajemen Ruangan - Staff')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/staff/rooms.css') }}">
@endpush

@section('content')
<div class="container-xxl">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Daftar Ruangan</h4>
        @if(auth()->user()->hasRole('admin'))
        <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> Tambah Ruangan
        </a>
        @endif
    </div>

    <!-- Search Bar -->
    <div class="rooms-search-container">
        <form action="{{ route('staff.rooms.index') }}" method="GET" class="search-form-inline">
            <div class="search-input-wrapper">
                <i class="bx bx-search"></i>
                <input type="text"
                       name="search"
                       class="search-input"
                       placeholder="Cari ruangan, gedung, atau fasilitas..."
                       value="{{ request('search') }}">
            </div>
            <button type="submit" class="search-btn">
                <i class="bx bx-search-alt me-1"></i> Cari
            </button>
            @if(request('search') || request('status') || request('building_id') || request('tipe_ruangan'))
                <button type="button" class="clear-btn" onclick="window.location.href='{{ route('staff.rooms.index') }}'">
                    <i class="bx bx-x me-1"></i> Reset
                </button>
            @endif
        </form>
    </div>

    <!-- Filter Section -->
    <div class="rooms-filter-section">
        <div class="filter-header">
            <h6 class="filter-title">Filter Ruangan</h6>
            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                <i class="bx bx-filter-alt me-1"></i> Filter
            </button>
        </div>

        <div class="collapse show" id="filterCollapse">
            <form action="{{ route('staff.rooms.index') }}" method="GET" class="filter-form-grid">
                <input type="hidden" name="search" value="{{ request('search') }}">

                <div class="filter-group">
                    <label class="filter-label">Status</label>
                    <select name="status" class="filter-select">
                        <option value="">Semua Status</option>
                        <option value="tersedia" {{ request('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="terisi" {{ request('status') == 'terisi' ? 'selected' : '' }}>Terisi</option>
                        <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label class="filter-label">Gedung</label>
                    <select name="building_id" class="filter-select">
                        <option value="">Semua Gedung</option>
                        @foreach($buildings as $building)
                            <option value="{{ $building->id }}" {{ request('building_id') == $building->id ? 'selected' : '' }}>
                                {{ $building->nama_gedung }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label class="filter-label">Tipe Ruangan</label>
                    <select name="tipe_ruangan" class="filter-select">
                        <option value="">Semua Tipe</option>
                        @foreach($roomTypes as $type)
                            <option value="{{ $type }}" {{ request('tipe_ruangan') == $type ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-filter-alt me-1"></i> Terapkan Filter
                    </button>
                    @if(request('status') || request('building_id') || request('tipe_ruangan'))
                        <a href="{{ route('staff.rooms.index') }}?search={{ request('search') }}" class="btn btn-outline-secondary">
                            Reset Filter
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Alerts -->
    @if(request('search') || request('status') || request('building_id') || request('tipe_ruangan'))
    <div class="rooms-alert alert-info">
        <div class="d-flex align-items-center">
            <i class="bx bx-info-circle me-2"></i>
            <div>
                Menampilkan hasil
                @if(request('search'))
                    pencarian untuk "<strong>{{ request('search') }}</strong>"
                @endif
                @if(request('status'))
                    {{ request('search') ? 'dengan' : '' }} status "<strong>{{ ucfirst(request('status')) }}</strong>"
                @endif
                @if(request('building_id'))
                    @php
                        $selectedBuilding = $buildings->firstWhere('id', request('building_id'));
                    @endphp
                    {{ (request('search') || request('status')) ? 'dan' : '' }} di gedung "<strong>{{ $selectedBuilding->nama_gedung ?? '' }}</strong>"
                @endif
                @if(request('tipe_ruangan'))
                    {{ (request('search') || request('status') || request('building_id')) ? 'dan' : '' }} tipe "<strong>{{ ucfirst(request('tipe_ruangan')) }}</strong>"
                @endif
            </div>
        </div>
    </div>
    @endif

    @if(session('success'))
    <div class="rooms-alert alert-success">
        <div class="d-flex align-items-center">
            <i class="bx bx-check-circle me-2"></i>
            <div>{{ session('success') }}</div>
        </div>
    </div>
    @endif

    <!-- Rooms Grid -->
    <div class="row g-4">
        @forelse($rooms as $room)
        <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="room-card-staff">
                <!-- Room Status Badge -->
                <span class="room-status-badge
                    @if($room->status == 'tersedia') bg-label-success
                    @elseif($room->status == 'terisi') bg-label-danger
                    @elseif($room->status == 'maintenance') bg-label-warning
                    @else bg-label-secondary @endif">
                    {{ ucfirst($room->status) }}
                </span>

                <!-- Room Image -->
                <div class="room-image-container-staff">
                    @if($room->gambar)
                    <img src="{{ asset('storage/' . $room->gambar) }}"
                         alt="{{ $room->nama_ruangan }}"
                         data-bs-toggle="tooltip"
                         title="Klik untuk detail ruangan">
                    @else
                    <div class="d-flex align-items-center justify-content-center h-100">
                        <i class="bx bx-door-open bx-lg text-muted"></i>
                    </div>
                    @endif
                </div>

                <!-- Room Details -->
                <div class="card-body">
                    <h6 class="room-title">{{ $room->nama_ruangan }}</h6>

                    <p class="room-building">
                        <i class="bx bx-building"></i>
                        {{ $room->building->nama_gedung ?? 'Tidak Ada Gedung' }}
                        @if($room->building)
                            | Lantai {{ $room->lantai }}
                        @endif
                    </p>

                    <div class="room-meta-badges">
                        <span class="capacity-badge">
                            <i class="bx bx-user me-1"></i> {{ $room->kapasitas }} orang
                        </span>
                        @if($room->tipe_ruangan)
                        <span class="type-badge">
                            {{ ucfirst($room->tipe_ruangan) }}
                        </span>
                        @endif
                    </div>

                    <!-- Facilities -->
                    @if($room->facilities && $room->facilities->count() > 0)
                    <div class="facilities-container">
                        <span class="facilities-label">Fasilitas:</span>
                        <div class="facilities-badges">
                            @foreach($room->facilities->take(3) as $facility)
                            <span class="facility-badge">
                                <i class="bx bx-{{ $facility->icon ?? 'check' }}"></i>
                                {{ $facility->nama_fasilitas }}
                            </span>
                            @endforeach
                            @if($room->facilities->count() > 3)
                            <span class="more-facilities">
                                +{{ $room->facilities->count() - 3 }} lagi
                            </span>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Description -->
                    @if($room->deskripsi)
                    <p class="room-description">
                        {{ \Illuminate\Support\Str::limit($room->deskripsi, 80) }}
                    </p>
                    @endif
                </div>

                <!-- Card Footer -->
                <div class="card-footer">
                    <div class="room-footer">
                        <a href="{{ route('staff.rooms.show', $room->id) }}"
                           class="btn btn-sm btn-outline-primary">
                            <i class="bx bx-show me-1"></i> Detail
                        </a>
                        <span class="room-updated" data-bs-toggle="tooltip" title="Terakhir diupdate">
                            {{ $room->updated_at->diffForHumans() }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="rooms-empty-state">
                <i class="bx bx-door-open empty-state-icon"></i>
                <h5 class="empty-state-title">
                    @if(request('search') || request('status') || request('building_id') || request('tipe_ruangan'))
                        Tidak Ditemukan
                    @else
                        Belum Ada Ruangan
                    @endif
                </h5>
                <p class="empty-state-text">
                    @if(request('search') || request('status') || request('building_id') || request('tipe_ruangan'))
                        Tidak ada ruangan yang sesuai dengan kriteria pencarian Anda.
                    @else
                        Belum ada ruangan yang terdaftar dalam sistem.
                    @endif
                </p>
                @if(!request('search') && !request('status') && !request('building_id') && !request('tipe_ruangan'))
                    @if(auth()->user()->hasRole('admin'))
                    <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary">
                        <i class="bx bx-plus me-1"></i> Tambah Ruangan Pertama
                    </a>
                    @endif
                @else
                    <a href="{{ route('staff.rooms.index') }}" class="btn btn-outline-primary">
                        <i class="bx bx-arrow-back me-1"></i> Tampilkan Semua Ruangan
                    </a>
                @endif
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($rooms->hasPages())
    <div class="pagination-container-staff d-flex justify-content-between align-items-center flex-wrap mt-4">
        <div class="pagination-info-staff">
            Menampilkan
            <strong>{{ $rooms->firstItem() ?? 0 }}</strong>
            sampai
            <strong>{{ $rooms->lastItem() ?? 0 }}</strong>
            dari
            <strong>{{ $rooms->total() }}</strong>
            data
        </div>

        <nav aria-label="Page navigation" class="pagination-staff">
            <ul class="pagination mb-0">
                {{-- First Page Link --}}
                <li class="page-item first {{ $rooms->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $rooms->url(1) }}">
                        <i class="tf-icon bx bx-chevrons-left"></i>
                    </a>
                </li>

                {{-- Previous Page Link --}}
                <li class="page-item prev {{ $rooms->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $rooms->previousPageUrl() }}">
                        <i class="tf-icon bx bx-chevron-left"></i>
                    </a>
                </li>

                {{-- Pagination Elements --}}
                @php
                    $currentPage = $rooms->currentPage();
                    $lastPage = $rooms->lastPage();
                    $start = max(1, $currentPage - 2);
                    $end = min($lastPage, $currentPage + 2);
                @endphp

                @for($i = $start; $i <= $end; $i++)
                    <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                        <a class="page-link" href="{{ $rooms->url($i) }}">{{ $i }}</a>
                    </li>
                @endfor

                {{-- Next Page Link --}}
                <li class="page-item next {{ !$rooms->hasMorePages() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $rooms->nextPageUrl() }}">
                        <i class="tf-icon bx bx-chevron-right"></i>
                    </a>
                </li>

                {{-- Last Page Link --}}
                <li class="page-item last {{ !$rooms->hasMorePages() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $rooms->url($lastPage) }}">
                        <i class="tf-icon bx bx-chevrons-right"></i>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    @endif
</div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/staff/rooms.js') }}"></script>
@endpush
