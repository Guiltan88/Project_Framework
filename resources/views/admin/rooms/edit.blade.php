@extends('Layouts.app')
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
                <form action="{{ route('admin.rooms.update', $room->id) }}"
                      method="POST"
                      enctype="multipart/form-data"
                      id="roomEditForm">
                    @csrf
                    @method('PUT')

                    {{-- Kode Ruangan --}}
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Kode Ruangan</label>
                        <div class="col-sm-10">
                            <input type="text"
                                   name="kode_ruangan"
                                   class="form-control @error('kode_ruangan') is-invalid @enderror"
                                   value="{{ old('kode_ruangan', $room->kode_ruangan) }}">
                            @error('kode_ruangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Nama Ruangan --}}
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Nama Ruangan</label>
                        <div class="col-sm-10">
                            <input type="text"
                                   name="nama_ruangan"
                                   class="form-control @error('nama_ruangan') is-invalid @enderror"
                                   value="{{ old('nama_ruangan', $room->nama_ruangan) }}">
                            @error('nama_ruangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- GEDUNG --}}
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Gedung</label>
                        <div class="col-sm-10">
                            <select name="gedung_id"
                                    id="gedungSelectEdit"
                                    class="form-select @error('gedung_id') is-invalid @enderror"
                                    required>
                                @foreach ($buildings as $gedung)
                                    <option value="{{ $gedung->id }}"
                                        {{ $room->gedung_id == $gedung->id ? 'selected' : '' }}
                                        data-lantai="{{ $gedung->jumlah_lantai }}">
                                        {{ $gedung->nama_gedung }} ({{ $gedung->jumlah_lantai }} lantai)
                                    </option>
                                @endforeach
                            </select>
                            @error('gedung_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Tetap tampilkan di edit, tapi sebagai display bukan input --}}
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Lantai</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <input type="number"
                                    name="lantai"
                                    class="form-control @error('lantai') is-invalid @enderror"
                                    value="{{ old('lantai', $room->lantai) }}"
                                    min="1"
                                    max="{{ $room->building->jumlah_lantai ?? 99 }}">
                                <span class="input-group-text">
                                    / {{ $room->building->jumlah_lantai ?? '?' }} lantai
                                </span>
                            </div>
                            @error('lantai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                Hanya bisa diubah oleh Staff
                            </small>
                        </div>
                    </div>

                    {{-- Kapasitas --}}
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Kapasitas</label>
                        <div class="col-sm-10">
                            <input type="number"
                                   name="kapasitas"
                                   class="form-control @error('kapasitas') is-invalid @enderror"
                                   value="{{ old('kapasitas', $room->kapasitas) }}"
                                   min="1">
                            @error('kapasitas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Status</label>
                        <div class="col-sm-10">
                            <select name="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="tersedia" {{ old('status', $room->status) == 'tersedia' ? 'selected' : '' }}>
                                    Tersedia
                                </option>
                                <option value="tidak tersedia" {{ old('status', $room->status) == 'tidak tersedia' ? 'selected' : '' }}>
                                    Tidak Tersedia
                                </option>
                                <option value="dalam perbaikan" {{ old('status', $room->status) == 'dalam perbaikan' ? 'selected' : '' }}>
                                    Dalam Perbaikan
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="remove_image" id="removeImage">
                                    <label class="form-check-label" for="removeImage">
                                        Hapus gambar saat ini
                                    </label>
                                </div>
                            @endif
                            <input type="file"
                                   name="gambar"
                                   class="form-control @error('gambar') is-invalid @enderror"
                                   accept="image/*">
                            @error('gambar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar</small>
                        </div>
                    </div>

                    {{-- Fasilitas --}}
                    <div class="mb-3">
                        <label class="form-label">Fasilitas</label>
                        <div class="row">
                            @foreach ($facilities as $facility)
                                <div class="col-md-4 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               name="facilities[]"
                                               value="{{ $facility->id }}"
                                               id="facility{{ $facility->id }}"
                                               {{ in_array($facility->id, $selectedFacilities) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="facility{{ $facility->id }}">
                                            {{ $facility->nama_fasilitas }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('facilities')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Button --}}
                    <div class="row justify-content-end">
                        <div class="col-sm-10">
                            <a href="{{ route('admin.rooms.index') }}"
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
