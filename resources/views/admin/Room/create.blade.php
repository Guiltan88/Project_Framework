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
                        <label class="col-sm-2 col-form-label">Lokasi</label>
                        <div class="col-sm-10">
                            <input type="text"
                                   name="lokasi"
                                   class="form-control"
                                   value="{{ old('lokasi') }}"
                                   placeholder="Gedung A - Lantai 2">
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

                    {{-- Fasilitas --}}
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Fasilitas</label>
                        <div class="col-sm-10">
                            <textarea name="fasilitas"
                                      class="form-control"
                                      rows="3"
                                      placeholder="Proyektor, AC, Whiteboard">{{ old('fasilitas') }}</textarea>
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
