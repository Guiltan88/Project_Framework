@extends('Layouts.app')
@section('title', 'Tambah Fasilitas')

@section('content')
<div class="card">
    <h5 class="card-header">Tambah Fasilitas</h5>

    <div class="card-body">
        <form action="{{ route('admin.facilities.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Nama Fasilitas</label>
                <input type="text"
                       name="nama_fasilitas"
                       class="form-control"
                       placeholder="Proyektor"
                       value="{{ old('nama_fasilitas') }}"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">Keterangan</label>
                <textarea name="keterangan"
                          class="form-control"
                          placeholder="Penyejuk Ruangan"
                          rows="3">{{ old('keterangan') }}</textarea>

            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" required>
                    <option value="">-- Pilih Status --</option>
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>
            </div>

            <div class="mt-3">
                <button class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.facilities.index') }}" class="btn btn-secondary">
                    Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
