@extends('layouts.app')
@section('title', 'Booking Management')

@section('content')
<div class="card">
    <h5 class="card-header d-flex justify-content-between align-items-center">
        <span>Daftar Booking</span>

        <div class="d-flex gap-2">
            <!-- Search Form -->
            <form action="{{ route('admin.bookings.index') }}" method="GET" class="d-flex gap-2">
                <input type="text"
                       name="search"
                       class="form-control form-control-sm"
                       placeholder="Cari booking..."
                       value="{{ request('search') }}"
                       style="width: 200px;">
                <button type="submit" class="btn btn-sm btn-outline-primary">
                    <i class="bx bx-search"></i>
                </button>
                @if(request('search') || request('status') || request('date_from') || request('date_to'))
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bx bx-x"></i>
                    </a>
                @endif
            </form>

            <!-- Filter Button -->
            <button class="btn btn-sm btn-outline-info" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                <i class="bx bx-filter"></i>
            </button>
        </div>
    </h5>

    <!-- Statistics Cards -->
    <div class="row m-3">
        <div class="col-md-2 col-sm-6 mb-2">
            <div class="card border">
                <div class="card-body text-center p-3">
                    <h4 class="mb-0">{{ $stats['total'] }}</h4>
                    <small class="text-muted">Total</small>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-6 mb-2">
            <div class="card border border-warning">
                <div class="card-body text-center p-3">
                    <h4 class="mb-0 text-warning">{{ $stats['pending'] }}</h4>
                    <small class="text-muted">Pending</small>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-6 mb-2">
            <div class="card border border-success">
                <div class="card-body text-center p-3">
                    <h4 class="mb-0 text-success">{{ $stats['approved'] }}</h4>
                    <small class="text-muted">Approved</small>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-6 mb-2">
            <div class="card border border-danger">
                <div class="card-body text-center p-3">
                    <h4 class="mb-0 text-danger">{{ $stats['rejected'] }}</h4>
                    <small class="text-muted">Rejected</small>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-6 mb-2">
            <div class="card border border-secondary">
                <div class="card-body text-center p-3">
                    <h4 class="mb-0 text-secondary">{{ $stats['cancelled'] }}</h4>
                    <small class="text-muted">Cancelled</small>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-6 mb-2">
            <div class="card border border-info">
                <div class="card-body text-center p-3">
                    <h4 class="mb-0 text-info">{{ $stats['today'] }}</h4>
                    <small class="text-muted">Today</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Collapse -->
    <div class="collapse" id="filterCollapse">
        <div class="card-body border-top">
            <form action="{{ route('admin.bookings.index') }}" method="GET" class="row g-3">
                <input type="hidden" name="search" value="{{ request('search') }}">

                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="all">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="date_from" class="form-control form-control-sm"
                           value="{{ request('date_from') }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="date_to" class="form-control form-control-sm"
                           value="{{ request('date_to') }}">
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-sm me-2">
                        <i class="bx bx-filter-alt me-1"></i> Filter
                    </button>
                    @if(request('status') || request('date_from') || request('date_to'))
                        <a href="{{ route('admin.bookings.index') }}?search={{ request('search') }}" class="btn btn-secondary btn-sm">
                            Reset Filter
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    @if(request('search') || request('status') || request('date_from') || request('date_to'))
    <div class="alert alert-info alert-dismissible fade show m-3 mb-0" role="alert">
        <i class="bx bx-info-circle me-2"></i>
        Menampilkan hasil
        @if(request('search'))
            pencarian untuk "<strong>{{ request('search') }}</strong>"
        @endif
        @if(request('status') && request('status') != 'all')
            {{ request('search') ? 'dengan' : '' }} status "<strong>{{ ucfirst(request('status')) }}</strong>"
        @endif
        @if(request('date_from') || request('date_to'))
            {{ (request('search') || request('status')) ? 'dan' : '' }}
            tanggal
            @if(request('date_from') && request('date_to'))
                <strong>{{ \Carbon\Carbon::parse(request('date_from'))->format('d M Y') }}</strong> s/d <strong>{{ \Carbon\Carbon::parse(request('date_to'))->format('d M Y') }}</strong>
            @elseif(request('date_from'))
                dari <strong>{{ \Carbon\Carbon::parse(request('date_from'))->format('d M Y') }}</strong>
            @elseif(request('date_to'))
                sampai <strong>{{ \Carbon\Carbon::parse(request('date_to'))->format('d M Y') }}</strong>
            @endif
        @endif
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show m-3 mb-0" role="alert">
        <i class="bx bx-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="table-responsive text-nowrap">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Kode Booking</th>
                    <th>User</th>
                    <th>Ruangan</th>
                    <th>Tanggal & Waktu</th>
                    <th>Tujuan</th>
                    <th>Status</th>
                    <th width="120">Aksi</th>
                </tr>
            </thead>

            <tbody class="table-border-bottom-0">
                @forelse($bookings as $booking)
                <tr>
                    <td>
                        <strong>{{ $booking->kode_booking }}</strong>
                        <br>
                        <small class="text-muted">{{ $booking->created_at->format('d M Y') }}</small>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            @if($booking->user->photo)
                            <img src="{{ asset('storage/' . $booking->user->photo) }}"
                                 width="40" height="40"
                                 class="rounded-circle me-2"
                                 style="object-fit: cover;">
                            @else
                            <div style="width: 40px; height: 40px; background: #f1f1f1; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 10px;">
                                <span style="color: #697a8d; font-weight: 600;">
                                    {{ strtoupper(substr($booking->user->name, 0, 1)) }}
                                </span>
                            </div>
                            @endif
                            <div>
                                <strong class="d-block">{{ $booking->user->name }}</strong>
                                <small class="text-muted">{{ $booking->user->email }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <strong>{{ $booking->room->nama_ruangan }}</strong>
                        <br>
                        <small class="text-muted">{{ $booking->room->building->nama_gedung ?? '-' }}</small>
                    </td>
                    <td>
                        <strong>{{ \Carbon\Carbon::parse($booking->tanggal_mulai)->format('d M Y') }}</strong>
                        <br>
                        <small class="text-muted">
                            {{ $booking->waktu_mulai }} - {{ $booking->waktu_selesai }}
                        </small>
                    </td>
                    <td>
                        {{ \Illuminate\Support\Str::limit($booking->tujuan, 30) }}
                        @if($booking->jumlah_peserta)
                        <br>
                        <small class="text-muted">{{ $booking->jumlah_peserta }} orang</small>
                        @endif
                    </td>
                    <td>
                        @php
                            $statusColors = [
                                'pending' => 'bg-label-warning',
                                'approved' => 'bg-label-success',
                                'rejected' => 'bg-label-danger',
                                'cancelled' => 'bg-label-secondary'
                            ];
                            $statusText = [
                                'pending' => 'Pending',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                                'cancelled' => 'Cancelled'
                            ];
                        @endphp
                        <span class="badge {{ $statusColors[$booking->status] ?? 'bg-label-secondary' }}">
                            {{ $statusText[$booking->status] ?? ucfirst($booking->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="dropdown">
                            <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>

                            <div class="dropdown-menu">
                                <a class="dropdown-item"
                                   href="{{ route('admin.bookings.show', $booking->id) }}">
                                    <i class="bx bx-show me-1"></i> View
                                </a>

                                @if($booking->status == 'pending')
                                <form action="{{ route('admin.bookings.approve', $booking->id) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    <button type="submit"
                                            class="dropdown-item"
                                            onclick="return confirm('Approve booking ini?')">
                                        <i class="bx bx-check me-1"></i> Approve
                                    </button>
                                </form>

                                <button type="button"
                                        class="dropdown-item"
                                        data-bs-toggle="modal"
                                        data-bs-target="#rejectModal"
                                        data-booking-id="{{ $booking->id }}">
                                    <i class="bx bx-x me-1"></i> Reject
                                </button>
                                @endif

                                @if(in_array($booking->status, ['pending', 'approved']))
                                <form action="{{ route('admin.bookings.cancel', $booking->id) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    <button type="submit"
                                            class="dropdown-item"
                                            onclick="return confirm('Cancel booking ini?')">
                                        <i class="bx bx-stop-circle me-1"></i> Cancel
                                    </button>
                                </form>
                                @endif

                                @if(in_array($booking->status, ['cancelled', 'rejected']))
                                <form action="{{ route('admin.bookings.destroy', $booking->id) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="dropdown-item"
                                            onclick="return confirm('Hapus booking ini?')">
                                        <i class="bx bx-trash me-1"></i> Delete
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4">
                        @if(request('search') || request('status') || request('date_from') || request('date_to'))
                            <div>
                                <i class="bx bx-search-alt mb-2" style="font-size: 2rem; color: #697a8d;"></i>
                                <p class="mb-0" style="color: #697a8d;">
                                    Tidak ditemukan booking
                                    @if(request('search'))
                                        dengan kata kunci "{{ request('search') }}"
                                    @endif
                                    @if(request('status') && request('status') != 'all')
                                        {{ request('search') ? 'dan' : '' }} dengan status "{{ ucfirst(request('status')) }}"
                                    @endif
                                    @if(request('date_from') || request('date_to'))
                                        {{ (request('search') || request('status')) ? 'dan' : '' }}
                                        @if(request('date_from') && request('date_to'))
                                            pada tanggal {{ \Carbon\Carbon::parse(request('date_from'))->format('d M Y') }} s/d {{ \Carbon\Carbon::parse(request('date_to'))->format('d M Y') }}
                                        @elseif(request('date_from'))
                                            dari tanggal {{ \Carbon\Carbon::parse(request('date_from'))->format('d M Y') }}
                                        @elseif(request('date_to'))
                                            sampai tanggal {{ \Carbon\Carbon::parse(request('date_to'))->format('d M Y') }}
                                        @endif
                                    @endif
                                </p>
                                <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-primary btn-sm mt-2">
                                    <i class="bx bx-arrow-back me-1"></i> Tampilkan Semua Booking
                                </a>
                            </div>
                        @else
                            <div>
                                <i class="bx bx-calendar mb-2" style="font-size: 2rem; color: #697a8d;"></i>
                                <p class="mb-0" style="color: #697a8d;">Belum ada booking yang terdaftar</p>
                            </div>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($bookings->hasPages())
    <div class="card-footer">
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="mb-0 text-muted">
                    Menampilkan
                    <strong>{{ $bookings->firstItem() ?? 0 }}</strong>
                    sampai
                    <strong>{{ $bookings->lastItem() ?? 0 }}</strong>
                    dari
                    <strong>{{ $bookings->total() }}</strong>
                    data
                </p>
            </div>
            <div class="col-md-6">
                <nav aria-label="Page navigation" class="d-flex justify-content-end">
                    <ul class="pagination mb-0">
                        {{-- First Page Link --}}
                        <li class="page-item first {{ $bookings->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $bookings->url(1) }}">
                                <i class="tf-icon bx bx-chevrons-left"></i>
                            </a>
                        </li>

                        {{-- Previous Page Link --}}
                        <li class="page-item prev {{ $bookings->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $bookings->previousPageUrl() }}">
                                <i class="tf-icon bx bx-chevron-left"></i>
                            </a>
                        </li>

                        {{-- Pagination Elements --}}
                        @php
                            $currentPage = $bookings->currentPage();
                            $lastPage = $bookings->lastPage();
                            $start = max(1, $currentPage - 2);
                            $end = min($lastPage, $currentPage + 2);
                        @endphp

                        @for($i = $start; $i <= $end; $i++)
                            <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                                <a class="page-link" href="{{ $bookings->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor

                        {{-- Next Page Link --}}
                        <li class="page-item next {{ !$bookings->hasMorePages() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $bookings->nextPageUrl() }}">
                                <i class="tf-icon bx bx-chevron-right"></i>
                            </a>
                        </li>

                        {{-- Last Page Link --}}
                        <li class="page-item last {{ !$bookings->hasMorePages() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $bookings->url($lastPage) }}">
                                <i class="tf-icon bx bx-chevrons-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="booking_id" id="rejectBookingId">
                    <div class="mb-3">
                        <label class="form-label">Alasan Penolakan</label>
                        <textarea name="alasan_penolakan" class="form-control" rows="3" placeholder="Masukkan alasan penolakan..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Reject Booking</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Reject modal setup
    document.addEventListener('DOMContentLoaded', function() {
        const rejectModal = document.getElementById('rejectModal');
        rejectModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const bookingId = button.getAttribute('data-booking-id');
            document.getElementById('rejectBookingId').value = bookingId;

            // Set form action
            const form = document.getElementById('rejectForm');
            form.action = `/admin/bookings/${bookingId}/reject`;
        });
    });
</script>
@endsection
