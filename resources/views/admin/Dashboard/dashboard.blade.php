@extends('admin.Layouts.app')
@section('title', 'Dashboard')
@section('content')

                <!-- Total Revenue -->
                <div class="col-12 ">
                  <div class="row">
                    <div class="col-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                            <img src="{{ asset('assets-admin/img/icons/unicons/wallet.png') }}" alt="Peminjaman" class="rounded">
                            </div>
                        </div>

                        <span>Peminjaman Aktif</span>
                        <h3>{{ $activeBookings }}</h3>

                        <small class="text-warning fw-semibold">
                            <i class="bx bx-time-five"></i> Sedang berjalan
                        </small>
                        </div>
                    </div>
                    </div>

                    <div class="col-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                            <img src="{{ asset('assets-admin/img/icons/unicons/building.png') }}" alt="Ruangan" class="rounded">
                            </div>
                        </div>

                        <span>Total Ruangan</span>
                        <h3>{{ $totalRooms }}</h3>

                        <small class="text-success fw-semibold">
                            <i class="bx bx-building"></i> Data aktif
                        </small>
                        </div>
                    </div>
                    </div>


                <div class="col-4 mb-4">
                <div class="card">
                    <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                        <img src="{{ asset('assets-admin/img/icons/unicons/chart.png') }}" alt="Users" class="rounded">
                        </div>
                    </div>

                    <span>Total User</span>
                    <h3>{{ $totalUsers }}</h3>

                    <small class="text-info fw-semibold">
                        <i class="bx bx-user"></i> Admin, Staff & Guest
                    </small>
                    </div>
                </div>
                </div>


            <div class="col-12 mb-4">
            <div class="card h-100">

                <!-- CARD HEADER -->
                <div class="card-header">
                <h5 class="card-title mb-0">Expense Overview</h5>
                </div>

                <!-- CARD BODY -->
                <div class="card-body">
                <div class="row align-items-stretch">

                    <!-- LEFT : EXPENSE -->
                    <div class="col-lg-8 col-md-12">
                    <div id="incomeChart"></div>
                    </div>

                    <!-- DIVIDER -->
                    <div class="col-lg-1 d-none d-lg-flex justify-content-center">
                    <div class="vr"></div>
                    </div>

                    <!-- RIGHT : ANALYTICS -->
                    <div class="col-lg-3 col-md-12">

                    <!-- ANALYTICS HEADER -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">Analytics</h6>

                        <div class="dropdown">
                        <button
                            class="btn btn-sm btn-outline-primary dropdown-toggle"
                            type="button"
                            data-bs-toggle="dropdown"
                        >
                            2022
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#">2021</a></li>
                            <li><a class="dropdown-item" href="#">2020</a></li>
                            <li><a class="dropdown-item" href="#">2019</a></li>
                        </ul>
                        </div>
                    </div>

                    <!-- GROWTH CHART -->
                    <div id="growthChart"></div>

                    <div class="text-center fw-semibold pt-3 mb-3">
                        62% Company Growth
                    </div>

                    </div>
                </div>

                <!-- HORIZONTAL DIVIDER (MOBILE) -->
                <hr class="d-lg-none mt-4">
                </div>

            </div>
            </div>


        <!-- Striped Rows -->
              <div class="card mt-4">
                    <h5 class="card-header">Peminjaman Terbaru</h5>
                    <div class="table-responsive text-nowrap">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Ruangan</th>
                                    <th>User</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($recentBookings as $item)
                                <tr>
                                    <td>{{ $item->ruangan->nama_ruangan }}</td>
                                    <td>{{ $item->user->name }}</td>
                                    <td>{{ $item->created_at->format('d M Y') }}</td>
                                    <td>
                                        <span class="badge bg-label-
                                            {{ $item->status == 'approved' ? 'success' :
                                            ($item->status == 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Belum ada data</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>


              <!--/ Striped Rows -->
            <!-- / Content -->
@endsection
