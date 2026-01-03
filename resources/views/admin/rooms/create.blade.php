@extends('Layouts.app')
@section('title', 'Create Room')

@section('content')
<div class="row">
    <div class="col-xxl">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Tambah Ruangan</h5>
                <small class="text-muted float-end">Form Ruangan</small>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.rooms.store') }}"
                      method="POST"
                      enctype="multipart/form-data"
                      id="roomForm">
                    @csrf

                    {{-- Kode Ruangan --}}
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Kode Ruangan</label>
                        <div class="col-sm-10">
                            <input type="text"
                                   name="kode_ruangan"
                                   class="form-control @error('kode_ruangan') is-invalid @enderror"
                                   value="{{ old('kode_ruangan') }}"
                                   placeholder="R-101">
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
                                   class="form-control"
                                   value="{{ old('nama_ruangan') }}"
                                   placeholder="Ruang Kelas A">
                        </div>
                    </div>

                    {{-- GANTI BAGIAN GEDUNG DENGAN INI --}}
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Gedung</label>
                        <div class="col-sm-10">
                            <select name="gedung_id"
                                    id="gedungSelect"
                                    class="form-select @error('gedung_id') is-invalid @enderror"
                                    required
                                    onchange="loadFloors(this.value)">
                                <option value="">-- Pilih Gedung --</option>
                                @foreach ($buildings as $gedung)
                                    <option value="{{ $gedung->id }}"
                                        {{ old('gedung_id') == $gedung->id ? 'selected' : '' }}>
                                        {{ $gedung->nama_gedung }} ({{ $gedung->jumlah_lantai }} lantai)
                                    </option>
                                @endforeach
                            </select>
                            @error('gedung_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- GANTI DENGAN INI (optional hidden field jika perlu) --}}
                    <input type="hidden" name="lantai" value="1">

                    {{-- Kapasitas --}}
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Kapasitas</label>
                        <div class="col-sm-10">
                            <input type="number"
                                   name="kapasitas"
                                   class="form-control"
                                   value="{{ old('kapasitas') }}"
                                   placeholder="40"
                                   min="1">
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Status</label>
                        <div class="col-sm-10">
                            <select name="status" class="form-select">
                                <option value="tersedia" {{ old('status') == 'tersedia' ? 'selected' : '' }}>
                                    Tersedia
                                </option>
                                <option value="tidak tersedia" {{ old('status') == 'tidak tersedia' ? 'selected' : '' }}>
                                    Tidak Tersedia
                                </option>
                                <option value="dalam perbaikan" {{ old('status') == 'dalam perbaikan' ? 'selected' : '' }}>
                                    Dalam Perbaikan
                                </option>
                            </select>
                        </div>
                    </div>

                    {{-- Gambar --}}
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Gambar</label>
                        <div class="col-sm-10">
                            <input type="file"
                                   name="gambar"
                                   class="form-control @error('gambar') is-invalid @enderror"
                                   accept="image/*">
                            @error('gambar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                                               id="facility{{ $facility->id }}"
                                               {{ is_array(old('facilities')) && in_array($facility->id, old('facilities')) ? 'checked' : '' }}>
                                        <label class="form-check-label"
                                               for="facility{{ $facility->id }}">
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
                            <a href="{{ route('admin.rooms.index') }}"
                               class="btn btn-secondary me-2">
                                Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Simpan
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection
