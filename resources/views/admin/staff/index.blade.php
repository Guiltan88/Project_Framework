@extends('Layouts.app')
@section('title', 'Staff')

@section('content')
<div class="card">
    <h5 class="card-header d-flex justify-content-between align-items-center">
        <span>Daftar Staff</span>

        <div class="d-flex gap-2">
            <!-- Search Form -->
            <form action="{{ route('admin.users.index') }}" method="GET" class="d-flex gap-2">
                <input type="text"
                       name="search"
                       class="form-control form-control-sm"
                       placeholder="Cari staff..."
                       value="{{ request('search') }}"
                       style="width: 200px;">
                <button type="submit" class="btn btn-sm btn-outline-primary">
                    <i class="bx bx-search"></i>
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bx bx-x"></i>
                    </a>
                @endif
            </form>

            <!-- Add Button -->
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                <i class="bx bx-plus me-1"></i> Tambah Staff
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
                    <th>Foto</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Jabatan</th>
                    <th>Telepon</th>
                    <th width="120">Aksi</th>
                </tr>
            </thead>

            <tbody class="table-border-bottom-0">
                @forelse ($staffs as $staff)
                <tr>
                    <td>
                        @if ($staff->photo)
                            <img src="{{ asset('storage/' . $staff->photo) }}"
                                 width="50" height="50"
                                 class="rounded-circle"
                                 style="object-fit: cover;">
                        @else
                            <div style="width: 50px; height: 50px; background: #f1f1f1; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="bx bx-user text-muted"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <strong class="d-block">{{ $staff->name }}</strong>
                        <small class="text-muted">ID: {{ $staff->id }}</small>
                    </td>
                    <td>{{ $staff->email }}</td>
                    <td>
                        @if($staff->department)
                            <span class="badge bg-label-info">{{ $staff->department }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>{{ $staff->phone ?? '-' }}</td>
                    <td>
                        <div class="dropdown">
                            <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item"
                                   href="{{ route('admin.users.edit', $staff->id) }}">
                                    <i class="bx bx-edit-alt me-1"></i> Edit
                                </a>
                                <form action="{{ route('admin.users.destroy', $staff->id) }}"
                                      method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="dropdown-item"
                                            onclick="return confirm('Yakin hapus staff ini?')">
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
                            <div>
                                <i class="bx bx-search-alt mb-2" style="font-size: 2rem; color: #697a8d;"></i>
                                <p class="mb-0" style="color: #697a8d;">
                                    Tidak ditemukan staff dengan kata kunci "{{ request('search') }}"
                                </p>
                            </div>
                        @else
                            <div>
                                <i class="bx bx-user-plus mb-2" style="font-size: 2rem; color: #697a8d;"></i>
                                <p class="mb-0" style="color: #697a8d;">Belum ada staff yang terdaftar</p>
                                <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm mt-2">
                                    <i class="bx bx-plus me-1"></i> Tambah Staff Pertama
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
    @if($staffs->hasPages())
    <div class="card-footer">
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="mb-0 text-muted">
                    Menampilkan
                    <strong>{{ $staffs->firstItem() ?? 0 }}</strong>
                    sampai
                    <strong>{{ $staffs->lastItem() ?? 0 }}</strong>
                    dari
                    <strong>{{ $staffs->total() }}</strong>
                    data
                </p>
            </div>
            <div class="col-md-6">
                <nav aria-label="Page navigation" class="d-flex justify-content-end">
                    <ul class="pagination mb-0">
                        {{-- First Page Link --}}
                        <li class="page-item first {{ $staffs->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $staffs->url(1) }}">
                                <i class="tf-icon bx bx-chevrons-left"></i>
                            </a>
                        </li>

                        {{-- Previous Page Link --}}
                        <li class="page-item prev {{ $staffs->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $staffs->previousPageUrl() }}">
                                <i class="tf-icon bx bx-chevron-left"></i>
                            </a>
                        </li>

                        {{-- Pagination Elements --}}
                        @php
                            $currentPage = $staffs->currentPage();
                            $lastPage = $staffs->lastPage();
                            $start = max(1, $currentPage - 2);
                            $end = min($lastPage, $currentPage + 2);
                        @endphp

                        @for($i = $start; $i <= $end; $i++)
                            <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                                <a class="page-link" href="{{ $staffs->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor

                        {{-- Next Page Link --}}
                        <li class="page-item next {{ !$staffs->hasMorePages() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $staffs->nextPageUrl() }}">
                                <i class="tf-icon bx bx-chevron-right"></i>
                            </a>
                        </li>

                        {{-- Last Page Link --}}
                        <li class="page-item last {{ !$staffs->hasMorePages() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $staffs->url($lastPage) }}">
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
