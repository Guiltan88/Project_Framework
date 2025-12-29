@extends('admin.Layouts.app')
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
                <form action="{{ route('Room.store') }}"
                    method="POST"
                    enctype="multipart/form-data">
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

                    {{-- Lokasi --}}
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Gedung</label>
                        <div class="col-sm-10">
                            <select name="gedung_id"
                                    class="form-select @error('gedung_id') is-invalid @enderror"
                                    required>
                                <option value="">-- Pilih Gedung --</option>
                                @foreach($buildings as $gedung)
                                    <option value="{{ $gedung->id }}"
                                        {{ old('gedung_id') == $gedung->id ? 'selected' : '' }}>
                                        {{ $gedung->nama_gedung }}
                                    </option>
                                @endforeach
                            </select>
                            @error('gedung_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Pilih Lantai --}}
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Lantai</label>
                        <div class="col-sm-10">
                            <select name="lantai_id"
                                    class="form-select @error('lantai_id') is-invalid @enderror"
                                    required>
                                <option value="">-- Pilih Lantai --</option>
                                @foreach($floors as $lantai)
                                    <option value="{{ $lantai->id }}"
                                        {{ old('lantai_id') == $lantai->id ? 'selected' : '' }}>
                                        Lantai {{ $lantai->nomor_lantai }}
                                    </option>
                                @endforeach
                            </select>
                            @error('lantai_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Kapasitas --}}
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Kapasitas</label>
                        <div class="col-sm-10">
                            <input type="number"
                                   name="kapasitas"
                                   class="form-control"
                                   value="{{ old('kapasitas') }}"
                                   placeholder="40">
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Status</label>
                        <div class="col-sm-10">
                            <select name="status" class="form-select">
                                <option value="tersedia">Tersedia</option>
                                <option value="tidak tersedia">Tidak Tersedia</option>
                            </select>
                        </div>
                    </div>

                    {{-- Gambar Ruangan --}}
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

                    <!-- Checkbox fasilitas -->
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
                                    id="facility{{ $facility->id }}">
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
                            <a href="{{ route('Room.index') }}"
                               class="btn btn-secondary me-2">Batal</a>

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
