@extends('Layouts.app')
@section('title', 'Room')
@section('content')
<div class="card">
    <h5 class="card-header d-flex justify-content-between align-items-center">
        <span>Daftar Ruangan</span>

        <div class="d-flex gap-2">
            <!-- Search Form -->
            <form action="{{ route('admin.rooms.index') }}" method="GET" class="d-flex gap-2 align-items-center">
                <input type="text"
                       name="search"
                       class="form-control form-control-sm"
                       placeholder="Cari ruangan..."
                       value="{{ request('search') }}"
                       style="width: 200px;">
                <button type="submit" class="btn btn-sm btn-outline-primary">
                    <i class="bx bx-search"></i>
                </button>
                @if(request('search') || request('status') || request('building_id'))
                    <a href="{{ route('admin.rooms.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bx bx-x"></i>
                    </a>
                @endif
            </form>

            <!-- Filter Button -->
            <button class="btn btn-sm btn-outline-info" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                <i class="bx bx-filter"></i>
            </button>

            <!-- Add Button -->
            <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary btn-sm">
                <i class="bx bx-plus me-1"></i> Tambah Room
            </a>
        </div>
    </h5>

    <!-- Filter Collapse -->
    <div class="collapse" id="filterCollapse">
        <div class="card-body border-top">
            <form action="{{ route('admin.rooms.index') }}" method="GET" class="row g-3">
                <input type="hidden" name="search" value="{{ request('search') }}">

                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="tersedia" {{ request('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="tidak tersedia" {{ request('status') == 'tidak tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
                        <option value="dalam perbaikan" {{ request('status') == 'dalam perbaikan' ? 'selected' : '' }}>Dalam Perbaikan</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Gedung</label>
                    <select name="building_id" class="form-select form-select-sm">
                        <option value="">Semua Gedung</option>
                        @foreach($buildings as $building)
                            <option value="{{ $building->id }}" {{ request('building_id') == $building->id ? 'selected' : '' }}>
                                {{ $building->nama_gedung }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-sm me-2">
                        <i class="bx bx-filter-alt me-1"></i> Filter
                    </button>
                    @if(request('status') || request('building_id'))
                        <a href="{{ route('admin.rooms.index') }}?search={{ request('search') }}" class="btn btn-secondary btn-sm">
                            Reset Filter
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    @if(request('search') || request('status') || request('building_id'))
    <div class="alert alert-info alert-dismissible fade show m-3 mb-0" role="alert">
        <i class="bx bx-info-circle me-2"></i>
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
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show m-3 mb-0" role="alert">
        <i class="bx bx-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="table-responsive text-nowrap">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Kode Ruangan</th>
                    <th>Gambar</th>
                    <th>Nama Ruangan</th>
                    <th>Gedung</th>
                    <th>Kapasitas</th>
                    <th>Fasilitas</th>
                    <th>Status</th>
                    <th width="120">Aksi</th>
                </tr>
            </thead>

            <tbody class="table-border-bottom-0">
                @forelse ($rooms as $room)
                <tr>
                    <td>
                        <strong>{{ $room->kode_ruangan }}</strong>
                        <br>
                        <small class="text-muted">ID: {{ $room->id }}</small>
                    </td>
                    <td>
                        @if ($room->gambar)
                        <img src="{{ asset('storage/' . $room->gambar) }}"
                            width="50"
                            height="50"
                            class="rounded"
                            style="object-fit: cover; border: 1px solid #e0e0e0;">
                        @else
                        <div style="width: 50px; height: 50px; background: #f1f1f1; border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                            <i class="bx bx-door-open text-muted"></i>
                        </div>
                        @endif
                    </td>
                    <td>{{ $room->nama_ruangan }}</td>
                    <td>
                        {{ $room->building->nama_gedung ?? '-' }}
                        <br>
                        <small class="text-muted">Lantai {{ $room->lantai }}</small>
                    </td>
                    <td>
                        <span class="badge bg-label-info">
                            {{ $room->kapasitas }} orang
                        </span>
                    </td>
                    <td>
                        @forelse ($room->facilities->take(3) as $facility)
                            <span class="badge bg-label-secondary mb-1">
                                {{ $facility->nama_fasilitas }}
                            </span>
                        @empty
                            <span class="text-muted">-</span>
                        @endforelse
                        @if($room->facilities->count() > 3)
                            <span class="badge bg-light text-dark">
                                +{{ $room->facilities->count() - 3 }} lagi
                            </span>
                        @endif
                    </td>
                    <td>
                        @php
                            $statusColors = [
                                'tersedia' => 'bg-label-success',
                                'tidak tersedia' => 'bg-label-danger',
                                'dalam perbaikan' => 'bg-label-warning'
                            ];
                            $statusText = [
                                'tersedia' => 'Tersedia',
                                'tidak tersedia' => 'Tidak Tersedia',
                                'dalam perbaikan' => 'Dalam Perbaikan'
                            ];
                        @endphp
                        <span class="badge {{ $statusColors[$room->status] ?? 'bg-label-secondary' }}">
                            {{ $statusText[$room->status] ?? ucfirst($room->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="dropdown">
                            <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>

                            <div class="dropdown-menu">
                                <a class="dropdown-item"
                                   href="{{ route('admin.rooms.edit', $room->id) }}">
                                    <i class="bx bx-edit-alt me-1"></i> Edit
                                </a>

                                <form action="{{ route('admin.rooms.destroy', $room->id) }}"
                                      method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="dropdown-item"
                                            onclick="return confirm('Yakin hapus room ini?')">
                                        <i class="bx bx-trash me-1"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4">
                        @if(request('search') || request('status') || request('building_id'))
                            <div>
                                <i class="bx bx-search-alt mb-2" style="font-size: 2rem; color: #697a8d;"></i>
                                <p class="mb-0" style="color: #697a8d;">
                                    Tidak ditemukan ruangan
                                    @if(request('search'))
                                        dengan kata kunci "{{ request('search') }}"
                                    @endif
                                    @if(request('status'))
                                        {{ request('search') ? 'dan' : '' }} dengan status "{{ ucfirst(request('status')) }}"
                                    @endif
                                    @if(request('building_id'))
                                        @php
                                            $selectedBuilding = $buildings->firstWhere('id', request('building_id'));
                                        @endphp
                                        {{ (request('search') || request('status')) ? 'dan' : '' }} di gedung "{{ $selectedBuilding->nama_gedung ?? '' }}"
                                    @endif
                                </p>
                                <a href="{{ route('admin.rooms.index') }}" class="btn btn-outline-primary btn-sm mt-2">
                                    <i class="bx bx-arrow-back me-1"></i> Tampilkan Semua Ruangan
                                </a>
                            </div>
                        @else
                            <div>
                                <i class="bx bx-door-open mb-2" style="font-size: 2rem; color: #697a8d;"></i>
                                <p class="mb-0" style="color: #697a8d;">Belum ada ruangan yang terdaftar</p>
                                <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary btn-sm mt-2">
                                    <i class="bx bx-plus me-1"></i> Tambah Ruangan Pertama
                                </a>
                            </div>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($rooms->hasPages())
    <div class="card-footer">
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="mb-0 text-muted">
                    Menampilkan
                    <strong>{{ $rooms->firstItem() ?? 0 }}</strong>
                    sampai
                    <strong>{{ $rooms->lastItem() ?? 0 }}</strong>
                    dari
                    <strong>{{ $rooms->total() }}</strong>
                    data
                </p>
            </div>
            <div class="col-md-6">
                <nav aria-label="Page navigation" class="d-flex justify-content-end">
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
        </div>
    </div>
    @endif
</div>
@endsection
