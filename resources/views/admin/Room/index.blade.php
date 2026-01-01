@extends('admin.Layouts.app')
@section('title', 'Room')
@section('content')
<div class="card">
    <h5 class="card-header d-flex justify-content-between align-items-center">
        Daftar Ruangan
        <a href="{{ route('Room.create') }}" class="btn btn-primary btn-sm">
            + Tambah Room
        </a>
    </h5>
    <div class="table-responsive text-nowrap">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Kode Ruangan</th>
                    <th>Gambar</th>
                    <th>Nama Ruangan</th>
                    <th>Kapasitas</th>
                    <th>Fasilitas</th>
                    <th>Status</th>
                    <th width="120">Aksi</th>
                </tr>
            </thead>

            <tbody class="table-border-bottom-0">
                @foreach ($rooms as $room)
                <tr>
                    <td><strong>{{ $room->kode_ruangan }}</strong></td>
                    <td>
                        @if ($room->gambar)
                        <img src="{{ asset('storage/' . $room->gambar) }}"
                            width="50"
                            height="50"
                            class="rounded"
                            style="object-fit: cover;">
                        @else
                            <span class="text-muted">No Image</span>
                        @endif
                    </td>
                    <td>{{ $room->nama_ruangan }}</td>
                    <td>{{ $room->kapasitas }}</td>
                    <td>
                    @foreach ($room->facilities as $facility)
                        <span class="badge bg-label-info">
                        {{ $facility->nama_fasilitas }}
                        </span>
                    @endforeach
                    </td>

                    <td>
                        <span class="badge
                            {{ $room->status === 'tersedia' ? 'bg-label-success' : 'bg-label-danger' }}">
                            {{ ucfirst($room->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="dropdown">
                            <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>

                            <div class="dropdown-menu">
                                <a class="dropdown-item"
                                   href="{{ route('Room.edit', $room->id) }}">
                                    <i class="bx bx-edit-alt me-1"></i> Edit
                                </a>

                                <form action="{{ route('Room.destroy', $room->id) }}"
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
                @endforeach
            </tbody>

        </table>
    </div>
</div>
@endsection
