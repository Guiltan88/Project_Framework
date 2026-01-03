@extends('layouts.app')
@section('title', $room->nama_ruangan . ' - Detail Ruangan')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets-admin/css/staff/room-show.css') }}">
@endpush

@section('content')
<div class="container-xxl">
    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('staff.rooms.index') }}" class="btn btn-label-secondary">
            <i class="bx bx-arrow-back me-1"></i> Kembali ke Daftar Ruangan
        </a>
    </div>

    <div class="row">
        <!-- Left Column: Room Details -->
        <div class="col-lg-8">
            <!-- Room Information Card -->
            <div class="room-info-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Informasi Ruangan</h5>
                    <span class="status-badge-large
                        @if($room->status == 'tersedia') bg-label-success
                        @elseif($room->status == 'terisi') bg-label-danger
                        @elseif($room->status == 'maintenance') bg-label-warning
                        @else bg-label-secondary @endif">
                        <i class="bx bx-info-circle me-1"></i>
                        {{ ucfirst($room->status) }}
                    </span>
                </div>

                <div class="card-body">
                    <!-- Room Image -->
                    <div class="room-main-image-container">
                        @if($room->gambar)
                        <img src="{{ asset('storage/' . $room->gambar) }}"
                             class="room-main-image"
                             alt="{{ $room->nama_ruangan }}"
                             data-bs-toggle="tooltip"
                             title="Klik untuk memperbesar">
                        @else
                        <div class="room-image-placeholder">
                            <i class="bx bx-door-open"></i>
                        </div>
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="info-section-title">Informasi Dasar</h6>
                            <div class="info-grid">
                                <div class="info-item">
                                    <span class="info-label">Nama Ruangan</span>
                                    <span class="info-value">
                                        <strong>{{ $room->nama_ruangan }}</strong>
                                    </span>
                                </div>

                                <div class="info-item">
                                    <span class="info-label">Kode Ruangan</span>
                                    <span class="info-value room-code">{{ $room->kode_ruangan }}</span>
                                </div>

                                <div class="info-item">
                                    <span class="info-label">Gedung</span>
                                    <span class="info-value">
                                        {{ $room->building->nama_gedung ?? 'Tidak Ada Gedung' }}
                                        @if($room->building)
                                            <br>
                                            <small class="text-muted">
                                                Lantai {{ $room->lantai }} |
                                                Kode: {{ $room->building->kode_gedung ?? '-' }}
                                            </small>
                                        @endif
                                    </span>
                                </div>

                                <div class="info-item">
                                    <span class="info-label">Kapasitas</span>
                                    <span class="info-value">
                                        <span class="badge bg-label-primary">{{ $room->kapasitas }} orang</span>
                                    </span>
                                </div>

                                <div class="info-item">
                                    <span class="info-label">Tipe Ruangan</span>
                                    <span class="info-value">
                                        <span class="badge bg-label-secondary">{{ $room->tipe_ruangan ?? 'General' }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <h6 class="info-section-title">Status & Informasi</h6>
                            <div class="info-grid">
                                <div class="info-item">
                                    <span class="info-label">Status Saat Ini</span>
                                    <span class="info-value">
                                        <span class="badge
                                            @if($room->status == 'tersedia') bg-success
                                            @elseif($room->status == 'terisi') bg-danger
                                            @elseif($room->status == 'maintenance') bg-warning
                                            @else bg-secondary @endif">
                                            {{ ucfirst($room->status) }}
                                        </span>
                                    </span>
                                </div>

                                <div class="info-item">
                                    <span class="info-label">Dipesan Hari Ini</span>
                                    <span class="info-value">
                                        @if($bookedToday)
                                        <span class="badge bg-danger">
                                            <i class="bx bx-calendar-x me-1"></i> Ya
                                        </span>
                                        @else
                                        <span class="badge bg-success">
                                            <i class="bx bx-calendar-check me-1"></i> Tidak
                                        </span>
                                        @endif
                                    </span>
                                </div>

                                <div class="info-item">
                                    <span class="info-label">Terakhir Diperbarui</span>
                                    <span class="info-value">
                                        <i class="bx bx-time me-1"></i>
                                        {{ $room->updated_at->format('d F Y H:i') }}
                                    </span>
                                </div>

                                <div class="info-item">
                                    <span class="info-label">Dibuat Pada</span>
                                    <span class="info-value">
                                        <i class="bx bx-calendar-plus me-1"></i>
                                        {{ $room->created_at->format('d F Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    @if($room->deskripsi)
                    <div class="mt-4">
                        <h6 class="info-section-title">Deskripsi</h6>
                        <div class="card" style="background: #f8f9fa;">
                            <div class="card-body">
                                <p class="mb-0">{{ $room->deskripsi }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Facilities Card -->
            @if($room->facilities && $room->facilities->count() > 0)
            <div class="facilities-card">
                <div class="card-header">
                    <h5 class="mb-0">Fasilitas</h5>
                </div>
                <div class="card-body">
                    <div class="facilities-grid">
                        @foreach($room->facilities as $facility)
                        <div class="facility-item">
                            <div class="facility-icon-wrapper">
                                <i class="bx bx-{{ $facility->icon ?? 'check' }}"></i>
                            </div>
                            <div class="facility-content">
                                <strong class="facility-name">{{ $facility->nama_fasilitas }}</strong>
                                @if($facility->deskripsi)
                                <small class="facility-desc">{{ $facility->deskripsi }}</small>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column: Quick Info & Actions -->
        <div class="col-lg-4">
            <!-- Quick Stats -->
            <div class="quick-stats-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Statistik Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="stat-item">
                        <span class="stat-label">Kapasitas Maksimal</span>
                        <span class="stat-value">{{ $room->kapasitas }} orang</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Gedung</span>
                        <span class="stat-value">{{ $room->building->nama_gedung ?? '-' }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Jumlah Fasilitas</span>
                        <span class="stat-value">{{ $room->facilities->count() }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Lantai</span>
                        <span class="stat-value">{{ $room->lantai }}</span>
                    </div>
                </div>
            </div>

            <!-- Today's Status -->
            <div class="today-status-card">
                <div class="card-header">
                    <h5 class="mb-0">Status Hari Ini</h5>
                </div>
                <div class="card-body">
                    @if($bookedToday)
                    <div class="status-alert alert-danger">
                        <i class="bx bx-calendar-x"></i>
                        <div>
                            <strong>Dipesan Hari Ini</strong>
                            <p class="mb-0 mt-1">Ruangan ini sudah memiliki pemesanan untuk hari ini.</p>
                        </div>
                    </div>
                    @else
                    <div class="status-alert alert-success">
                        <i class="bx bx-calendar-check"></i>
                        <div>
                            <strong>Tersedia Hari Ini</strong>
                            <p class="mb-0 mt-1">Tidak ada pemesanan yang dijadwalkan untuk hari ini.</p>
                        </div>
                    </div>
                    @endif

                    <div class="text-center mt-3">
                        <a href="{{ route('staff.bookings.index') }}" class="btn btn-primary btn-sm">
                            <i class="bx bx-book me-1"></i> Lihat Semua Pemesanan
                        </a>
                    </div>
                </div>
            </div>

            <!-- Actions Card -->
            <div class="actions-card">
                <div class="card-header">
                    <h5 class="mb-0">Aksi</h5>
                </div>
                <div class="card-body">
                    <div class="action-buttons-grid">
                        @if($room->status == 'tersedia')
                        <a href="{{ route('bookings.create') }}?room_id={{ $room->id }}" class="btn btn-success action-btn">
                            <i class="bx bx-calendar-plus me-1"></i> Pesan Ruangan Ini
                        </a>
                        @else
                        <button class="btn btn-secondary action-btn" disabled>
                            <i class="bx bx-calendar-x me-1"></i> Tidak Tersedia untuk Pemesanan
                        </button>
                        @endif

                        <div class="d-flex gap-2">
                            <button id="copyRoomCodeBtn" class="btn btn-outline-secondary flex-fill">
                                <i class="bx bx-copy me-1"></i> Salin Kode
                            </button>
                            <button id="shareRoomBtn" class="btn btn-outline-info">
                                <i class="bx bx-share-alt me-1"></i> Bagikan
                            </button>
                        </div>

                        <a href="{{ route('staff.rooms.index') }}" class="btn btn-outline-secondary action-btn">
                            <i class="bx bx-list-ul me-1"></i> Lihat Semua Ruangan
                        </a>

                        <button id="printRoomBtn" class="btn btn-outline-primary action-btn">
                            <i class="bx bx-printer me-1"></i> Cetak Informasi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade image-modal" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">{{ $room->nama_ruangan }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="" class="img-fluid rounded">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bx bx-x me-1"></i> Tutup
                </button>
                @if($room->gambar)
                <a href="{{ asset('storage/' . $room->gambar) }}"
                   class="btn btn-primary"
                   target="_blank"
                   download="{{ $room->nama_ruangan }}.jpg">
                    <i class="bx bx-download me-1"></i> Download
                </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('assets-admin/js/staff/room-show.js') }}"></script>
@endpush
