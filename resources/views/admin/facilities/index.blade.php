@extends('Layouts.app')
@section('title', 'Fasilitas')

@section('content')
<div class="card">
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
                        <span class="badge bg-label-success">
                            Aktif
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-label-info">
                            {{ $facility->rooms->count() }} Ruangan
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
                    <td colspan="5" class="text-center">
                        Data fasilitas belum tersedia
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>
</div>
@endsection
