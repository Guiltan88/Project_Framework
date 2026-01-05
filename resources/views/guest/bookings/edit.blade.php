@extends('layouts.app')
@section('title', 'Edit Booking')

@section('content')

<div class="mb-4">
    <a href="{{ route('guest.bookings.show', $booking->id) }}"
       class="btn btn-label-secondary">
        <i class="bx bx-arrow-back me-1"></i> Back
    </a>
</div>

<div class="row">
    <div class="col-lg-4 mb-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bx bx-door-open me-2"></i> Current Room
                </h5>
            </div>
            <div class="card-body">
                <div class="room-info">
                    <div class="mb-3">
                        @if($booking->room->gambar)
                        <div class="text-center">
                            <img src="{{ asset('storage/' . $booking->room->gambar) }}"
                                 alt="{{ $booking->room->nama_ruangan }}"
                                 class="img-fluid rounded"
                                 style="max-height: 200px; object-fit: cover; width: 100%;">
                        </div>
                        @else
                        <div class="d-flex align-items-center justify-content-center bg-light rounded" style="height: 150px;">
                            <i class="bx bx-door-open bx-lg text-muted"></i>
                        </div>
                        @endif
                    </div>

                    <h5 class="mb-2">{{ $booking->room->nama_ruangan }}</h5>

                    <div class="mb-3">
                        <p class="mb-2">
                            <i class="bx bx-building me-1 text-muted"></i>
                            {{ $booking->room->building->nama_gedung ?? 'N/A' }}
                        </p>
                        <p class="mb-2">
                            <i class="bx bx-layers me-1 text-muted"></i>
                            Floor: {{ $booking->room->lantai ?? 'N/A' }}
                        </p>
                        <p class="mb-2">
                            <i class="bx bx-user me-1 text-muted"></i>
                            Capacity: {{ $booking->room->kapasitas }} people
                        </p>
                        <p class="mb-0">
                            <span class="badge
                                @if($booking->room->status == 'tersedia') bg-success
                                @elseif($booking->room->status == 'terisi') bg-danger
                                @else bg-secondary @endif">
                                {{ $booking->room->status == 'tersedia' ? 'Available' : 'Unavailable' }}
                            </span>
                        </p>
                    </div>

                    @if($booking->room->deskripsi)
                    <div class="alert alert-light mt-3">
                        <p class="mb-0 small">{{ $booking->room->deskripsi }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bx bx-info-circle me-2"></i> Booking Tips
                </h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="bx bx-check-circle text-success me-2"></i>
                        Book at least 1 day in advance
                    </li>
                    <li class="mb-2">
                        <i class="bx bx-check-circle text-success me-2"></i>
                        Maximum booking duration: 8 hours
                    </li>
                    <li class="mb-2">
                        <i class="bx bx-check-circle text-success me-2"></i>
                        Check room availability before booking
                    </li>
                    <li class="mb-2">
                        <i class="bx bx-check-circle text-success me-2"></i>
                        You'll receive email confirmation
                    </li>
                    <li>
                        <i class="bx bx-check-circle text-success me-2"></i>
                        Cancellation allowed up to 2 hours before
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bx bx-edit me-2"></i> Edit Booking Details
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('guest.bookings.update', $booking->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="room_id" value="{{ $booking->room_id }}">

                    <div class="mb-4">
                        <label class="form-label">Room</label>
                        <input type="text"
                               class="form-control"
                               value="{{ $booking->room->nama_ruangan }} - {{ $booking->room->building->nama_gedung ?? 'N/A' }}"
                               readonly>
                        <small class="text-muted">Room cannot be changed after booking is created</small>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Event Purpose <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('tujuan') is-invalid @enderror"
                                   name="tujuan"
                                   value="{{ old('tujuan', $booking->tujuan) }}"
                                   placeholder="Team Meeting"
                                   required>
                            @error('tujuan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Number of Participants <span class="text-danger">*</span></label>
                            <input type="number"
                                   class="form-control @error('jumlah_peserta') is-invalid @enderror"
                                   name="jumlah_peserta"
                                   value="{{ old('jumlah_peserta', $booking->jumlah_peserta) }}"
                                   min="1"
                                   required>
                            @error('jumlah_peserta')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Maximum capacity: {{ $booking->room->kapasitas }} people</small>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Start Date <span class="text-danger">*</span></label>
                            <input type="date"
                                   class="form-control @error('tanggal_mulai') is-invalid @enderror"
                                   name="tanggal_mulai"
                                   value="{{ old('tanggal_mulai', $booking->tanggal_mulai->format('Y-m-d')) }}"
                                   min="{{ date('Y-m-d') }}"
                                   required>
                            @error('tanggal_mulai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">End Date <span class="text-danger">*</span></label>
                            <input type="date"
                                   class="form-control @error('tanggal_selesai') is-invalid @enderror"
                                   name="tanggal_selesai"
                                   value="{{ old('tanggal_selesai', $booking->tanggal_selesai->format('Y-m-d')) }}"
                                   min="{{ date('Y-m-d') }}"
                                   required>
                            @error('tanggal_selesai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Start Time <span class="text-danger">*</span></label>
                            <input type="time"
                                   class="form-control @error('waktu_mulai') is-invalid @enderror"
                                   name="waktu_mulai"
                                   value="{{ old('waktu_mulai', $booking->waktu_mulai->format('H:i')) }}"
                                   required>
                            @error('waktu_mulai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">End Time <span class="text-danger">*</span></label>
                            <input type="time"
                                   class="form-control @error('waktu_selesai') is-invalid @enderror"
                                   name="waktu_selesai"
                                   value="{{ old('waktu_selesai', $booking->waktu_selesai->format('H:i')) }}"
                                   required>
                            @error('waktu_selesai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Special Requirements</label>
                        <textarea class="form-control @error('kebutuhan_khusus') is-invalid @enderror"
                                  name="kebutuhan_khusus"
                                  rows="2"
                                  placeholder="Any special requirements...">{{ old('kebutuhan_khusus', $booking->kebutuhan_khusus) }}</textarea>
                        @error('kebutuhan_khusus')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control @error('catatan') is-invalid @enderror"
                                  name="catatan"
                                  rows="3"
                                  placeholder="Additional notes...">{{ old('catatan', $booking->catatan) }}</textarea>
                        @error('catatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('guest.bookings.show', $booking->id) }}"
                           class="btn btn-outline-secondary">
                            <i class="bx bx-arrow-back me-1"></i> Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-save me-1"></i> Update Booking
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
