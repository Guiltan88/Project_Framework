@extends('Layouts.app')
@section('title', 'Dashboard')
@section('content')
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ asset('assets-admin/img/icons/unicons/wallet.png') }}" alt="Peminjaman" class="rounded">
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Active Bookings</span>
                        <h3 class="card-title mb-2">{{ $stats['active_bookings'] }}</h3>
                        <small class="text-warning fw-semibold">
                            <i class="bx bx-time-five"></i> Currently running
                        </small>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ asset('assets-admin/img/icons/unicons/cc-warning.png') }}" alt="Ruangan" class="rounded">
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Total Rooms</span>
                        <h3 class="card-title mb-2">{{ $stats['total_rooms'] }}</h3>
                        <small class="text-success fw-semibold">
                            <i class="bx bx-building"></i> Available
                        </small>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ asset('assets-admin/img/icons/unicons/chart.png') }}" alt="Users" class="rounded">
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Total Users</span>
                        <h3 class="card-title mb-2">{{ $stats['total_users'] }}</h3>
                        <small class="text-info fw-semibold">
                            <i class="bx bx-user"></i> All roles
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Bookings Table (TANPA CODE & ACTION) -->
<div class="row">
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Recent Bookings</h5>
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-primary btn-sm">
                    <i class="bx bx-list-ul me-1"></i> View All Bookings
                </a>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Room</th>
                            <th>User</th>
                            <th>Date & Time</th>
                            <th>Purpose</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($recentBookings as $booking)
                        <tr>
                            <td>
                                <strong>{{ $booking->room->nama_ruangan ?? 'N/A' }}</strong>
                                @if($booking->room->building->nama_gedung ?? false)
                                <br>
                                <small class="text-muted">{{ $booking->room->building->nama_gedung }}</small>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($booking->user->photo ?? false)
                                    <img src="{{ asset('storage/' . $booking->user->photo) }}"
                                         class="rounded-circle me-2"
                                         width="32" height="32">
                                    @else
                                    <div class="avatar avatar-xs me-2">
                                        <span class="avatar-initial rounded-circle bg-label-primary">
                                            {{ strtoupper(substr($booking->user->name ?? 'U', 0, 1)) }}
                                        </span>
                                    </div>
                                    @endif
                                    <div>
                                        <strong>{{ $booking->user->name ?? 'N/A' }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $booking->user->email ?? '' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <strong>{{ $booking->tanggal_mulai->format('d M Y') ?? 'N/A' }}</strong>
                                <br>
                                <small class="text-muted">
                                    {{ $booking->waktu_mulai ?? '' }} - {{ $booking->waktu_selesai ?? '' }}
                                </small>
                            </td>
                            <td>
                                {{ Str::limit($booking->tujuan ?? 'N/A', 30) }}
                                @if($booking->jumlah_peserta ?? false)
                                <br>
                                <small class="text-muted">{{ $booking->jumlah_peserta }} people</small>
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusColors = [
                                        'pending' => 'warning',
                                        'approved' => 'success',
                                        'rejected' => 'danger',
                                        'cancelled' => 'secondary'
                                    ];
                                    $status = $booking->status ?? 'pending';
                                @endphp
                                <span class="badge bg-{{ $statusColors[$status] ?? 'secondary' }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="bx bx-calendar-x bx-lg text-muted"></i>
                                <p class="text-muted mt-3 mb-0">No recent bookings</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Tambahkan tombol lagi di footer untuk mobile -->
            <div class="card-footer text-center d-lg-none">
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-primary btn-sm">
                    <i class="bx bx-list-ul me-1"></i> View All Bookings
                </a>
            </div>
        </div>
    </div>
</div>
<!-- Content wrapper -->

@endsection
