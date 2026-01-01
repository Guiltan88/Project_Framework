@extends('admin.Layouts.app')
@section('title', 'Edit Room')

@section('content')
<div class="row">
    <div class="col-xxl">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Edit Ruangan</h5>
                <small class="text-muted">Update Data</small>
            </div>

            <div class="card-body">
                <form action="{{ route('Room.update', $room->id) }}"
                      method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Kode Ruangan --}}
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Kode Ruangan</label>
                        <div class="col-sm-10">
                            <input type="text"
                                   name="kode_ruangan"
                                   class="form-control"
                                   value="{{ old('kode_ruangan', $room->kode_ruangan) }}">
                        </div>
                    </div>

                    {{-- Nama Ruangan --}}
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Nama Ruangan</label>
                        <div class="col-sm-10">
                            <input type="text"
                                   name="nama_ruangan"
                                   class="form-control"
                                   value="{{ old('nama_ruangan', $room->nama_ruangan) }}">
                        </div>
                    </div>

                    {{-- GEDUNG --}}
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Gedung</label>
                        <div class="col-sm-10">
                            <select name="gedung_id" class="form-select" required>
                                @foreach ($buildings as $gedung)
                                    <option value="{{ $gedung->id }}"
                                        {{ $room->gedung_id == $gedung->id ? 'selected' : '' }}>
                                        {{ $gedung->nama_gedung }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Kapasitas --}}
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Kapasitas</label>
                        <div class="col-sm-10">
                            <input type="number"
                                   name="kapasitas"
                                   class="form-control"
                                   value="{{ old('kapasitas', $room->kapasitas) }}">
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Status</label>
                        <div class="col-sm-10">
                            <select name="status" class="form-select">
                                <option value="tersedia" {{ $room->status == 'tersedia' ? 'selected' : '' }}>
                                    Tersedia
                                </option>
                                <option value="tidak tersedia" {{ $room->status == 'tidak tersedia' ? 'selected' : '' }}>
                                    Tidak Tersedia
                                </option>
                            </select>
                        </div>
                    </div>

                    {{-- Gambar --}}
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Gambar</label>
                        <div class="col-sm-10">
                            @if ($room->gambar)
                                <img src="{{ asset('storage/' . $room->gambar) }}"
                                     width="120"
                                     class="mb-2 rounded d-block">
                            @endif
                            <input type="file" name="gambar" class="form-control">
                        </div>
                    </div>

                    {{-- Fasilitas --}}
                    <div class="mb-3">
                        <label class="form-label">Fasilitas</label>
                        <div class="row">
                            @foreach ($facilities as $facility)
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               name="facilities[]"
                                               value="{{ $facility->id }}"
                                               {{ in_array($facility->id, $selectedFacilities) ? 'checked' : '' }}>
                                        <label class="form-check-label">
                                            {{ $facility->nama_fasilitas }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Button --}}
                    <div class="row justify-content-end">
                        <div class="col-sm-10">
                            <a href="{{ route('Room.index') }}"
                               class="btn btn-secondary me-2">
                                Batal
                            </a>

                            <button type="submit" class="btn btn-primary">
                                Update
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
