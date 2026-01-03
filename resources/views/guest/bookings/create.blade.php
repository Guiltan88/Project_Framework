@extends('layouts.app')
@section('title', 'Create New Booking')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/guest/booking-create.css') }}">
@endpush

@section('content')
<div class="guest-booking-create">
    <!-- Breadcrumb -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('guest.dashboard') }}">
                            <i class="bx bx-home me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('guest.rooms.index') }}">
                            <i class="bx bx-door-open me-1"></i> Rooms
                        </a>
                    </li>
                    @if(isset($room) && $room)
                    <li class="breadcrumb-item">
                        <a href="{{ route('guest.rooms.show', $room->id) }}">
                            {{ $room->nama_ruangan }}
                        </a>
                    </li>
                    @endif
                    <li class="breadcrumb-item active">Create Booking</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <!-- Left Column: Room Info -->
        <div class="col-lg-4 mb-4">
            <!-- Selected Room Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-door-open me-2"></i> Selected Room
                    </h5>
                </div>
                <div class="card-body">
                    @if(isset($room) && $room)
                    <div class="room-info">
                        <div class="room-image mb-3">
                            @if($room->foto)
                            <img src="{{ asset('storage/' . $room->foto) }}"
                                 alt="{{ $room->nama_ruangan }}"
                                 class="img-fluid rounded"
                                 onerror="this.src='{{ asset('assets-admin/img/rooms/default-room.jpg') }}'">
                            @else
                            <div class="no-photo bg-light rounded d-flex align-items-center justify-content-center py-5">
                                <i class="bx bx-door-open bx-lg text-muted"></i>
                            </div>
                            @endif
                        </div>

                        <h5 class="mb-2">{{ $room->nama_ruangan }}</h5>

                        <div class="room-details mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bx bx-building text-muted me-2"></i>
                                <span>{{ $room->building->nama_gedung ?? 'N/A' }}</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="bx bx-layers text-muted me-2"></i>
                                <span>Floor: {{ $room->lantai ?? 'N/A' }}</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="bx bx-user text-muted me-2"></i>
                                <span>Capacity: {{ $room->kapasitas }} people</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="bx bx-info-circle text-muted me-2"></i>
                                <span class="badge {{ $room->status == 'tersedia' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $room->status == 'tersedia' ? 'Available' : 'Unavailable' }}
                                </span>
                            </div>
                        </div>

                        @if($room->deskripsi)
                        <p class="text-muted small mb-0">{{ Str::limit($room->deskripsi, 100) }}</p>
                        @endif
                    </div>
                    @else
                    <div class="alert alert-info">
                        <i class="bx bx-info-circle me-2"></i>
                        Please select a room from the form.
                    </div>
                    @endif
                </div>
            </div>

            <!-- Booking Tips -->
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

        <!-- Right Column: Booking Form -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-calendar-plus me-2"></i> Booking Details
                    </h5>
                </div>
                <div class="card-body">
                    <form id="bookingForm" action="{{ route('bookings.store') }}" method="POST">
                        @csrf

                        <!-- Hidden room input if preselected -->
                        @if(isset($room) && $room)
                        <input type="hidden" name="room_id" value="{{ $room->id }}">
                        @endif

                        <!-- Room Selection -->
                        <div class="mb-4">
                            <label class="form-label">Select Room <span class="text-danger">*</span></label>
                            <select class="form-select @error('room_id') is-invalid @enderror"
                                    name="room_id"
                                    id="roomSelect"
                                    {{ isset($room) && $room ? 'disabled' : 'required' }}>
                                <option value="">-- Select a room --</option>
                                @foreach($rooms as $availableRoom)
                                <option value="{{ $availableRoom->id }}"
                                        {{ (isset($room) && $room->id == $availableRoom->id) || old('room_id') == $availableRoom->id ? 'selected' : '' }}
                                        data-capacity="{{ $availableRoom->kapasitas }}">
                                    {{ $availableRoom->nama_ruangan }}
                                    - {{ $availableRoom->building->nama_gedung ?? 'N/A' }}
                                    (Capacity: {{ $availableRoom->kapasitas }})
                                </option>
                                @endforeach
                            </select>
                            @if(isset($room) && $room)
                            <input type="hidden" name="room_id" value="{{ $room->id }}">
                            <small class="text-muted">Room is pre-selected from previous page</small>
                            @endif
                            @error('room_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Event Details -->
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Event Purpose <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control @error('tujuan') is-invalid @enderror"
                                       name="tujuan"
                                       value="{{ old('tujuan') }}"
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
                                       id="jumlahPeserta"
                                       value="{{ old('jumlah_peserta', 1) }}"
                                       min="1"
                                       required>
                                @error('jumlah_peserta')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted" id="capacityInfo">Maximum capacity: --</small>
                            </div>
                        </div>

                        <!-- Date and Time -->
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Start Date <span class="text-danger">*</span></label>
                                <input type="date"
                                       class="form-control @error('tanggal_mulai') is-invalid @enderror"
                                       name="tanggal_mulai"
                                       id="tanggalMulai"
                                       value="{{ old('tanggal_mulai') }}"
                                       min="{{ date('Y-m-d') }}"
                                       required>
                                @error('tanggal_mulai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Cannot book for past dates</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">End Date <span class="text-danger">*</span></label>
                                <input type="date"
                                       class="form-control @error('tanggal_selesai') is-invalid @enderror"
                                       name="tanggal_selesai"
                                       id="tanggalSelesai"
                                       value="{{ old('tanggal_selesai') }}"
                                       min="{{ date('Y-m-d') }}"
                                       required>
                                @error('tanggal_selesai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted" id="dateDurationInfo">Duration: -- days</small>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Start Time <span class="text-danger">*</span></label>
                                <input type="time"
                                       class="form-control @error('waktu_mulai') is-invalid @enderror"
                                       name="waktu_mulai"
                                       id="waktuMulai"
                                       value="{{ old('waktu_mulai', '08:00') }}"
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
                                       id="waktuSelesai"
                                       value="{{ old('waktu_selesai', '10:00') }}"
                                       required>
                                @error('waktu_selesai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted" id="timeDurationInfo">Duration: 2 hours</small>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="mb-4">
                            <label class="form-label">Special Requirements</label>
                            <textarea class="form-control @error('kebutuhan_khusus') is-invalid @enderror"
                                      name="kebutuhan_khusus"
                                      rows="2"
                                      placeholder="Any special requirements...">{{ old('kebutuhan_khusus') }}</textarea>
                            @error('kebutuhan_khusus')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control @error('catatan') is-invalid @enderror"
                                      name="catatan"
                                      rows="3"
                                      placeholder="Additional notes...">{{ old('catatan') }}</textarea>
                            @error('catatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="form-check mb-4">
                            <input class="form-check-input @error('terms') is-invalid @enderror"
                                   type="checkbox"
                                   name="terms"
                                   id="termsCheckbox"
                                   {{ old('terms') ? 'checked' : '' }}
                                   required>
                            <label class="form-check-label" for="termsCheckbox">
                                I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">terms and conditions</a> *
                            </label>
                            @error('terms')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ isset($room) && $room ? route('guest.rooms.show', $room->id) : route('guest.rooms.index') }}"
                               class="btn btn-outline-secondary">
                                <i class="bx bx-arrow-back me-1"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="bx bx-calendar-check me-1"></i> Submit Booking Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Terms and Conditions Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Terms and Conditions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>Booking Policy</h6>
                <ol>
                    <li>Bookings must be made at least 24 hours in advance.</li>
                    <li>Maximum booking duration is 8 hours per day.</li>
                    <li>Room must be left in clean condition after use.</li>
                    <li>Any damages will be charged to the booking party.</li>
                    <li>Cancellation must be made at least 2 hours before the booking time.</li>
                    <li>Repeated no-shows may result in booking privileges being suspended.</li>
                    <li>Administration reserves the right to cancel bookings with 24 hours notice.</li>
                </ol>

                <h6>Usage Guidelines</h6>
                <ul>
                    <li>Food and drinks are not permitted in meeting rooms.</li>
                    <li>Smoking is strictly prohibited in all rooms.</li>
                    <li>Please report any issues with facilities immediately.</li>
                    <li>Do not move furniture without permission.</li>
                    <li>Ensure all equipment is turned off after use.</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/guest/booking-create.js') }}"></script>
@endpush
