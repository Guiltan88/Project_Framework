@extends('Layouts.app')
@section('title', 'Gedung')

@section('content')
<div class="card">
    <!-- Alert untuk Success/Error -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show m-4" role="alert">
        <i class="bx bx-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show m-4" role="alert">
        <i class="bx bx-error-circle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <h5 class="card-header d-flex justify-content-between align-items-center">
        <span>Daftar Gedung</span>

        <div class="d-flex gap-2">
            <!-- Search Form -->
            <form action="{{ route('admin.buildings.index') }}" method="GET" class="d-flex gap-2">
                <input type="text"
                       name="search"
                       class="form-control form-control-sm"
                       placeholder="Cari gedung..."
                       value="{{ request('search') }}"
                       style="width: 200px;">
                <button type="submit" class="btn btn-sm btn-outline-primary">
                    <i class="bx bx-search"></i>
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.buildings.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bx bx-x"></i>
                    </a>
                @endif
            </form>

            <!-- Add Button -->
            <a href="{{ route('admin.buildings.create') }}" class="btn btn-primary btn-sm">
                <i class="bx bx-plus me-1"></i> Tambah Gedung
            </a>
        </div>
    </h5>

    @if(request('search'))
    <div class="alert alert-info alert-dismissible fade show m-3 mb-0" role="alert">
        <i class="bx bx-info-circle me-2"></i>
        Menampilkan hasil pencarian untuk "<strong>{{ request('search') }}</strong>"
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="table-responsive text-nowrap">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Kode Gedung</th>
                    <th>Gambar</th>
                    <th>Nama Gedung</th>
                    <th>Jumlah Lantai</th>
                    <th>Keterangan</th>
                    <th width="120">Aksi</th>
                </tr>
            </thead>

            <tbody class="table-border-bottom-0">
                @forelse ($buildings as $building)
                <tr>
                    <td><strong>{{ $building->kode_gedung }}</strong></td>
                    <td>
                        @if ($building->gambar)
                        <img src="{{ asset('storage/' . $building->gambar) }}"
                             width="50"
                             height="50"
                             class="rounded"
                             style="object-fit: cover;">
                        @else
                        <span class="text-muted">No Image</span>
                        @endif
                    </td>
                    <td>{{ $building->nama_gedung }}</td>
                    <td>{{ $building->jumlah_lantai }}</td>
                    <td>{{ $building->keterangan }}</td>
                    <td>
                        <div class="dropdown">
                            <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>

                            <div class="dropdown-menu">
                                <a class="dropdown-item"
                                   href="{{ route('admin.buildings.edit', $building->id) }}">
                                    <i class="bx bx-edit-alt me-1"></i> Edit
                                </a>

                                <form action="{{ route('admin.buildings.destroy', $building->id) }}"
                                      method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="dropdown-item"
                                            onclick="return confirm('Yakin hapus gedung ini?')">
                                        <i class="bx bx-trash me-1"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4">
                        @if(request('search'))
                            <div class="text-muted">
                                <i class="bx bx-search-alt mb-2" style="font-size: 2rem;"></i>
                                <p class="mb-0">Tidak ditemukan gedung dengan kata kunci "{{ request('search') }}"</p>
                            </div>
                        @else
                            <div class="text-muted">
                                <i class="bx bx-building mb-2" style="font-size: 2rem;"></i>
                                <p class="mb-0">Belum ada gedung yang terdaftar</p>
                                <a href="{{ route('admin.buildings.create') }}" class="btn btn-primary btn-sm mt-2">
                                    <i class="bx bx-plus me-1"></i> Tambah Gedung Pertama
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
    @if($buildings->hasPages())
    <div class="card-footer">
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="mb-0 text-muted">
                    Menampilkan
                    <strong>{{ $buildings->firstItem() ?? 0 }}</strong>
                    sampai
                    <strong>{{ $buildings->lastItem() ?? 0 }}</strong>
                    dari
                    <strong>{{ $buildings->total() }}</strong>
                    data
                </p>
            </div>
            <div class="col-md-6">
                <nav aria-label="Page navigation" class="d-flex justify-content-end">
                    <ul class="pagination mb-0">
                        {{-- First Page Link --}}
                        <li class="page-item first {{ $buildings->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $buildings->url(1) }}">
                                <i class="tf-icon bx bx-chevrons-left"></i>
                            </a>
                        </li>

                        {{-- Previous Page Link --}}
                        <li class="page-item prev {{ $buildings->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $buildings->previousPageUrl() }}">
                                <i class="tf-icon bx bx-chevron-left"></i>
                            </a>
                        </li>

                        {{-- Pagination Elements --}}
                        @php
                            $currentPage = $buildings->currentPage();
                            $lastPage = $buildings->lastPage();
                            $start = max(1, $currentPage - 2);
                            $end = min($lastPage, $currentPage + 2);
                        @endphp

                        @for($i = $start; $i <= $end; $i++)
                            <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                                <a class="page-link" href="{{ $buildings->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor

                        {{-- Next Page Link --}}
                        <li class="page-item next {{ !$buildings->hasMorePages() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $buildings->nextPageUrl() }}">
                                <i class="tf-icon bx bx-chevron-right"></i>
                            </a>
                        </li>

                        {{-- Last Page Link --}}
                        <li class="page-item last {{ !$buildings->hasMorePages() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $buildings->url($lastPage) }}">
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
