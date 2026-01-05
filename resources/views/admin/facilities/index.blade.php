@extends('Layouts.app')
@section('title', 'Fasilitas')

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
        Daftar Fasilitas
        <a href="{{ route('admin.facilities.create') }}" class="btn btn-primary btn-sm">
            + Tambah Fasilitas
        </a>
    </h5>

    <div class="table-responsive text-nowrap">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Fasilitas</th>
                    <th>Keterangan</th>
                    <th>Status</th>
                    <th>Jumlah Ruangan</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody class="table-border-bottom-0">
                @forelse ($facilities as $facility)
                <tr>
                    <td>{{ $loop->iteration }}</td>

                    <td>
                        <strong>{{ $facility->nama_fasilitas }}</strong>
                    </td>

                    <td>
                        {{ $facility->keterangan ?? '-' }}
                    </td>

                    <td>
                        @if($facility->status == 'active' || $facility->status == 1)
                        <span class="badge bg-label-success">Aktif</span>
                        @else
                        <span class="badge bg-label-secondary">Non-Aktif</span>
                        @endif
                    </td>

                    <td>
                        <span class="badge bg-label-info">
                            {{ $facility->rooms_count ?? $facility->rooms->count() }} Ruangan
                        </span>
                    </td>

                    <td>
                        <div class="dropdown">
                            <button type="button"
                                class="btn p-0 dropdown-toggle hide-arrow"
                                data-bs-toggle="dropdown">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>

                            <div class="dropdown-menu">
                                <a class="dropdown-item"
                                   href="{{ route('admin.facilities.edit', $facility->id) }}">
                                    <i class="bx bx-edit-alt me-1"></i> Edit
                                </a>

                                <form action="{{ route('admin.facilities.destroy', $facility->id) }}"
                                      method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="dropdown-item"
                                        onclick="return confirm('Hapus fasilitas ini?')">
                                        <i class="bx bx-trash me-1"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">
                        <i class="bx bx-package bx-lg text-muted"></i>
                        <p class="text-muted mt-2">Data fasilitas belum tersedia</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
