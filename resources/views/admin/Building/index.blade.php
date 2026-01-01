@extends('admin.Layouts.app')
@section('title', 'Gedung')
@section('content')
<div class="card">
    <h5 class="card-header d-flex justify-content-between align-items-center">
        Daftar Gedung
        <a href="{{ route('Building.create') }}" class="btn btn-primary btn-sm">
            + Tambah Gedung
        </a>
    </h5>
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
                @foreach ($buildings as $building)
                <tr>
                    <td><strong>{{ $building->kode_gedung }}</strong></td>
                    <td>
                        @if ($building->gambar)
                        <img src="{{ asset($building->gambar) }}"
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
                                   href="{{ route('Building.edit', $building->id) }}">
                                    <i class="bx bx-edit-alt me-1"></i> Edit
                                </a>

                                <form action="{{ route('Building.destroy', $building->id) }}"
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
                @endforeach
            </tbody>

        </table>
    </div>
</div>
@endsection
