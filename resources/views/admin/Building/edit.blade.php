@extends('admin.Layouts.app')
@section('title', 'Edit Gedung')

@section('content')
<div class="row">
    <div class="col-xxl">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Edit Gedung</h5>
                <small class="text-muted float-end">Form Gedung</small>
            </div>

            <div class="card-body">
                <form action="{{ route('Building.update', $building->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Kode Gedung --}}
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Kode Gedung</label>
                        <div class="col-sm-10">
                            <input type="text"
                                   name="kode_gedung"
                                   class="form-control @error('kode_gedung') is-invalid @enderror"
                                   value="{{ old('kode_gedung', $building->kode_gedung) }}"
                                   placeholder="G-101">
                            @error('kode_gedung')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Nama Gedung --}}
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Nama Gedung</label>
                        <div class="col-sm-10">
                            <input type="text"
                                   name="nama_gedung"
                                   class="form-control @error('nama_gedung') is-invalid @enderror"
                                   value="{{ old('nama_gedung', $building->nama_gedung) }}"
                                   placeholder="Gedung A">
                            @error('nama_gedung')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Jumlah Lantai --}}
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Jumlah Lantai</label>
                        <div class="col-sm-10">
                            <input type="number"
                                   name="jumlah_lantai"
                                   class="form-control @error('jumlah_lantai') is-invalid @enderror"
                                   value="{{ old('jumlah_lantai', $building->jumlah_lantai) }}"
                                   placeholder="3">
                            @error('jumlah_lantai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Keterangan --}}
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Keterangan</label>
                        <div class="col-sm-10">
                            <textarea name="keterangan"
                                      class="form-control @error('keterangan') is-invalid @enderror"
                                      placeholder="Keterangan tambahan">{{ old('keterangan', $building->keterangan) }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Gambar Gedung --}}
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Gambar</label>
                        <div class="col-sm-10">
                            @if($building->gambar)
                                <div class="mb-2">
                                    <img src="{{ asset($building->gambar) }}"
                                         alt="Gambar Gedung"
                                         width="120"
                                         class="rounded">
                                </div>
                            @endif
                            <input type="file"
                                   name="gambar"
                                   class="form-control @error('gambar') is-invalid @enderror"
                                   accept="image/*">
                            @error('gambar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Buttons --}}
                    <div class="row justify-content-end">
                        <div class="col-sm-10">
                            <a href="{{ route('Building.index') }}" class="btn btn-secondary me-2">
                                Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
