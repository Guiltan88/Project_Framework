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
                        <h3></h3>

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
                            <img src="{{ asset('assets-admin/img/icons/unicons/cc-warning.png') }}" alt="Ruangan" class="rounded">
                            </div>
                        </div>

                        <span>Total Ruangan</span>
                        <h3></h3>

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
                    <h3></h3>

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
        <div class="row">
            <div class="col-12 mb-4">
            <div class="card">
                <h5 class="card-header">Striped rows</h5>
                <div class="table-responsive text-nowrap">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>Project</th>
                        <th>Client</th>
                        <th>Users</th>
                        <th>Status</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                      <tr>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong>Angular Project</strong></td>
                        <td>Albert Cook</td>
                        <td>
                          <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">
                            <li
                              data-bs-toggle="tooltip"
                              data-popup="tooltip-custom"
                              data-bs-placement="top"
                              class="avatar avatar-xs pull-up"
                              title="Lilian Fuller"
                            >
                              <img src="{{ asset('assets-admin/img/avatars/5.png') }}" alt="Avatar" class="rounded-circle" />
                            </li>
                            <li
                              data-bs-toggle="tooltip"
                              data-popup="tooltip-custom"
                              data-bs-placement="top"
                              class="avatar avatar-xs pull-up"
                              title="Sophia Wilkerson"
                            >
                              <img src="{{ asset('assets-admin/img/avatars/6.png') }}" alt="Avatar" class="rounded-circle" />
                            </li>
                            <li
                              data-bs-toggle="tooltip"
                              data-popup="tooltip-custom"
                              data-bs-placement="top"
                              class="avatar avatar-xs pull-up"
                              title="Christina Parker"
                            >
                              <img src="{{ asset('assets-admin/img/avatars/7.png') }}" alt="Avatar" class="rounded-circle" />
                            </li>
                          </ul>
                        </td>
                        <td><span class="badge bg-label-primary me-1">Active</span></td>
                        <td>
                          <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                              <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu">
                              <a class="dropdown-item" href="javascript:void(0);"
                                ><i class="bx bx-edit-alt me-1"></i> Edit</a
                              >
                              <a class="dropdown-item" href="javascript:void(0);"
                                ><i class="bx bx-trash me-1"></i> Delete</a
                              >
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td><i class="fab fa-react fa-lg text-info me-3"></i> <strong>React Project</strong></td>
                        <td>Barry Hunter</td>
                        <td>
                          <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">
                            <li
                              data-bs-toggle="tooltip"
                              data-popup="tooltip-custom"
                              data-bs-placement="top"
                              class="avatar avatar-xs pull-up"
                              title="Lilian Fuller"
                            >
                              <img src="{{ asset('assets-admin/img/avatars/5.png') }}" alt="Avatar" class="rounded-circle" />
                            </li>
                            <li
                              data-bs-toggle="tooltip"
                              data-popup="tooltip-custom"
                              data-bs-placement="top"
                              class="avatar avatar-xs pull-up"
                              title="Sophia Wilkerson"
                            >
                              <img src="{{ asset('assets-admin/img/avatars/6.png') }}" alt="Avatar" class="rounded-circle" />
                            </li>
                            <li
                              data-bs-toggle="tooltip"
                              data-popup="tooltip-custom"
                              data-bs-placement="top"
                              class="avatar avatar-xs pull-up"
                              title="Christina Parker"
                            >
                              <img src="{{ asset('assets-admin/img/avatars/7.png') }}" alt="Avatar" class="rounded-circle" />
                            </li>
                          </ul>
                        </td>
                        <td><span class="badge bg-label-success me-1">Completed</span></td>
                        <td>
                          <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                              <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu">
                              <a class="dropdown-item" href="javascript:void(0);"
                                ><i class="bx bx-edit-alt me-1"></i> Edit</a
                              >
                              <a class="dropdown-item" href="javascript:void(0);"
                                ><i class="bx bx-trash me-1"></i> Delete</a
                              >
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td><i class="fab fa-vuejs fa-lg text-success me-3"></i> <strong>VueJs Project</strong></td>
                        <td>Trevor Baker</td>
                        <td>
                          <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">
                            <li
                              data-bs-toggle="tooltip"
                              data-popup="tooltip-custom"
                              data-bs-placement="top"
                              class="avatar avatar-xs pull-up"
                              title="Lilian Fuller"
                            >
                              <img src="{{ asset('assets-admin/img/avatars/5.png') }}" alt="Avatar" class="rounded-circle" />
                            </li>
                            <li
                              data-bs-toggle="tooltip"
                              data-popup="tooltip-custom"
                              data-bs-placement="top"
                              class="avatar avatar-xs pull-up"
                              title="Sophia Wilkerson"
                            >
                              <img src="{{ asset('assets-admin/img/avatars/6.png') }}" alt="Avatar" class="rounded-circle" />
                            </li>
                            <li
                              data-bs-toggle="tooltip"
                              data-popup="tooltip-custom"
                              data-bs-placement="top"
                              class="avatar avatar-xs pull-up"
                              title="Christina Parker"
                            >
                              <img src="{{ asset('assets-admin/img/avatars/7.png') }}" alt="Avatar" class="rounded-circle" />
                            </li>
                          </ul>
                        </td>
                        <td><span class="badge bg-label-info me-1">Scheduled</span></td>
                        <td>
                          <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                              <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu">
                              <a class="dropdown-item" href="javascript:void(0);"
                                ><i class="bx bx-edit-alt me-1"></i> Edit</a
                              >
                              <a class="dropdown-item" href="javascript:void(0);"
                                ><i class="bx bx-trash me-1"></i> Delete</a
                              >
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <i class="fab fa-bootstrap fa-lg text-primary me-3"></i> <strong>Bootstrap Project</strong>
                        </td>
                        <td>Jerry Milton</td>
                        <td>
                          <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">
                            <li
                              data-bs-toggle="tooltip"
                              data-popup="tooltip-custom"
                              data-bs-placement="top"
                              class="avatar avatar-xs pull-up"
                              title="Lilian Fuller"
                            >
                              <img src="{{ asset('assets-admin/img/avatars/5.png') }}" alt="Avatar" class="rounded-circle" />
                            </li>
                            <li
                              data-bs-toggle="tooltip"
                              data-popup="tooltip-custom"
                              data-bs-placement="top"
                              class="avatar avatar-xs pull-up"
                              title="Sophia Wilkerson"
                            >
                              <img src="{{ asset('assets-admin/img/avatars/6.png') }}" alt="Avatar" class="rounded-circle" />
                            </li>
                            <li
                              data-bs-toggle="tooltip"
                              data-popup="tooltip-custom"
                              data-bs-placement="top"
                              class="avatar avatar-xs pull-up"
                              title="Christina Parker"
                            >
                              <img src="{{ asset('assets-admin/img/avatars/7.png') }}" alt="Avatar" class="rounded-circle" />
                            </li>
                          </ul>
                        </td>
                        <td><span class="badge bg-label-warning me-1">Pending</span></td>
                        <td>
                          <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                              <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu">
                              <a class="dropdown-item" href="javascript:void(0);"
                                ><i class="bx bx-edit-alt me-1"></i> Edit</a
                              >
                              <a class="dropdown-item" href="javascript:void(0);"
                                ><i class="bx bx-trash me-1"></i> Delete</a
                              >
                            </div>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
        </div>
        <!--/ Striped Rows -->
    <!-- / Content -->
    </div>
</div>

@endsection
