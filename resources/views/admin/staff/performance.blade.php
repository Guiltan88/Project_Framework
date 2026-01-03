@extends('layouts.app')
@section('title', 'Staff Performance')

@section('content')

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-2">Total Staff</h6>
                            <h4 class="mb-0">{{ $totalStaff }}</h4>
                        </div>
                        <div class="avatar">
                            <div class="avatar-initial bg-label-primary rounded-circle">
                                <i class="bx bx-user"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-2">Total Approvals</h6>
                            <h4 class="mb-0">{{ $totalApprovals }}</h4>
                        </div>
                        <div class="avatar">
                            <div class="avatar-initial bg-label-success rounded-circle">
                                <i class="bx bx-check-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-2">Response Time</h6>
                            <h4 class="mb-0">{{ $avgResponseTime }}m</h4>
                        </div>
                        <div class="avatar">
                            <div class="avatar-initial bg-label-info rounded-circle">
                                <i class="bx bx-time"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-2">Rejection Rate</h6>
                            <h4 class="mb-0">{{ $rejectionRate }}%</h4>
                        </div>
                        <div class="avatar">
                            <div class="avatar-initial bg-label-warning rounded-circle">
                                <i class="bx bx-x-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Performers & Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-8">
            <!-- Summary Stats Cards -->
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Avg. Daily Approvals</h6>
                                    <h4 class="mb-0">{{ $avgDailyApprovals }}</h4>
                                </div>
                                <div class="avatar">
                                    <div class="avatar-initial bg-label-success rounded-circle">
                                        <i class="bx bx-trending-up"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <small class="text-muted">
                                    <i class="bx bx-calendar me-1"></i> Per day average
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Fastest Response</h6>
                                    <h4 class="mb-0">{{ $fastestResponseTime }}m</h4>
                                </div>
                                <div class="avatar">
                                    <div class="avatar-initial bg-label-info rounded-circle">
                                        <i class="bx bx-timer"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <small class="text-muted">
                                    <i class="bx bx-medal me-1"></i> Best performance
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Pending Actions</h6>
                                    <h4 class="mb-0">{{ $pendingActions }}</h4>
                                </div>
                                <div class="avatar">
                                    <div class="avatar-initial bg-label-warning rounded-circle">
                                        <i class="bx bx-time-five"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <small class="text-muted">
                                    <i class="bx bx-alarm-exclamation me-1"></i> Needs attention
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Completion Rate</h6>
                                    <h4 class="mb-0">{{ $completionRate }}%</h4>
                                </div>
                                <div class="avatar">
                                    <div class="avatar-initial bg-label-primary rounded-circle">
                                        <i class="bx bx-task"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-primary" style="width: {{ $completionRate }}%"></div>
                                </div>
                                <small class="text-muted">Task completion</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Top Performers</h5>
                    <span class="badge bg-primary">This Month</span>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @foreach($topPerformers as $index => $staff)
                        <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <!-- Rank Badge -->
                                <span class="badge
                                    {{ $index == 0 ? 'bg-warning' :
                                       ($index == 1 ? 'bg-secondary' :
                                       ($index == 2 ? 'bg-info' : 'bg-light text-dark')) }}
                                    me-2" style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center;">
                                    {{ $index + 1 }}
                                </span>

                                <!-- Staff Avatar -->
                                @if($staff->photo)
                                <img src="{{ asset('storage/' . $staff->photo) }}"
                                     alt="{{ $staff->name }}"
                                     class="rounded-circle me-2"
                                     width="32" height="32"
                                     style="object-fit: cover;">
                                @else
                                <div class="avatar avatar-sm me-2">
                                    <span class="avatar-initial rounded-circle bg-label-primary">
                                        {{ strtoupper(substr($staff->name, 0, 1)) }}
                                    </span>
                                </div>
                                @endif

                                <!-- Staff Info -->
                                <div>
                                    <small class="fw-bold d-block">{{ $staff->name }}</small>
                                    <small class="text-muted">{{ $staff->department ?? 'No Department' }}</small>
                                </div>
                            </div>

                            <!-- Performance Stats -->
                            <div class="text-end">
                                <span class="badge bg-success">{{ $staff->total_approved }} approvals</span>
                                <br>
                                <small class="text-muted">{{ $staff->avg_response_time }}m avg</small>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    @if($topPerformers->isEmpty())
                    <div class="text-center py-4">
                        <i class="bx bx-user-x bx-lg text-muted mb-3"></i>
                        <p class="text-muted mb-0">No performance data available</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Staff Performance Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Staff Performance Details</h5>
            <div class="d-flex gap-2 align-items-center">
                <!-- Period Filter -->
                <div class="d-flex align-items-center">
                    <span class="me-2 text-muted small">Period:</span>
                    <select id="periodFilter" class="form-select form-select-sm w-auto">
                        <option value="today">Today</option>
                        <option value="week">This Week</option>
                        <option value="month" selected>This Month</option>
                        <option value="quarter">This Quarter</option>
                        <option value="year">This Year</option>
                    </select>
                </div>

                <!-- Search & Sort -->
                <div class="d-flex gap-2">
                    <input type="text" id="searchStaff" class="form-control form-control-sm"
                           placeholder="Cari staff..." style="width: 200px;">
                    <select id="sortBy" class="form-select form-select-sm w-auto">
                        <option value="total_approved">Approval Terbanyak</option>
                        <option value="response_time">Response Tercepat</option>
                        <option value="rejection_rate">Rejection Terendah</option>
                        <option value="name">Nama A-Z</option>
                    </select>
                </div>

                <!-- Export Button -->
                <button class="btn btn-sm btn-outline-success" onclick="exportToExcel()">
                    <i class="bx bx-export me-1"></i> Export
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Staff</th>
                        <th>Department</th>
                        <th>Total Processed</th>
                        <th>Approved</th>
                        <th>Rejected</th>
                        <th>Avg. Response</th>
                        <th>Rejection Rate</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($staffs as $staff)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($staff->photo)
                                <img src="{{ asset('storage/' . $staff->photo) }}"
                                     alt="{{ $staff->name }}"
                                     class="rounded-circle me-2"
                                     width="40" height="40"
                                     style="object-fit: cover;">
                                @else
                                <div class="avatar avatar-sm me-2">
                                    <span class="avatar-initial rounded-circle bg-label-primary">
                                        {{ strtoupper(substr($staff->name, 0, 1)) }}
                                    </span>
                                </div>
                                @endif
                                <div>
                                    <strong>{{ $staff->name }}</strong><br>
                                    <small class="text-muted">{{ $staff->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-label-secondary">{{ $staff->department ?? '-' }}</span>
                        </td>
                        <td>
                            <strong>{{ $staff->total_processed ?? 0 }}</strong>
                            <br>
                            <small class="text-muted">Total diproses</small>
                        </td>
                        <td>
                            <span class="badge bg-success">{{ $staff->total_approved ?? 0 }}</span>
                        </td>
                        <td>
                            <span class="badge bg-danger">{{ $staff->total_rejected ?? 0 }}</span>
                        </td>
                        <td>
                            @php
                                $responseTime = $staff->avg_response_time ?? 0;
                                $responseClass = $responseTime < 30 ? 'bg-success' :
                                                ($responseTime < 60 ? 'bg-warning' : 'bg-danger');
                            @endphp
                            <span class="badge {{ $responseClass }}">{{ $responseTime }}m</span>
                        </td>
                        <td>
                            @php
                                $rate = $staff->rejection_rate ?? 0;
                                $badgeClass = $rate < 10 ? 'bg-success' :
                                            ($rate < 20 ? 'bg-warning' : 'bg-danger');
                            @endphp
                            <div class="d-flex align-items-center">
                                <span class="badge {{ $badgeClass }} me-2">{{ $rate }}%</span>
                                <div class="progress flex-grow-1" style="height: 6px; width: 60px;">
                                    <div class="progress-bar {{ $rate < 20 ? 'bg-success' : 'bg-danger' }}"
                                         style="width: {{ min($rate, 100) }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-{{ $staff->status == 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($staff->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <i class="bx bx-user-voice bx-lg text-muted mb-3"></i>
                            <p class="text-muted mb-0">No staff performance data available</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($staffs->hasPages())
        <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">
                        Showing {{ $staffs->firstItem() }} to {{ $staffs->lastItem() }} of {{ $staffs->total() }} entries
                    </small>
                </div>
                <div>
                    {{ $staffs->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Modal for Staff Details -->
<div class="modal fade" id="staffDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Staff Performance Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="staffDetailContent">
                <!-- Content will be loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .progress {
        border-radius: 10px;
    }
    .avatar-initial {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
    }
    .badge.bg-label-primary {
        background-color: rgba(105, 108, 255, 0.1);
        color: #696cff;
    }
    .badge.bg-label-secondary {
        background-color: rgba(108, 117, 125, 0.1);
        color: #6c757d;
    }
    .badge.bg-label-success {
        background-color: rgba(40, 167, 69, 0.1);
        color: #28a745;
    }
    .badge.bg-label-info {
        background-color: rgba(13, 202, 240, 0.1);
        color: #0dcaf0;
    }
    .badge.bg-label-warning {
        background-color: rgba(255, 193, 7, 0.1);
        color: #ffc107;
    }
    .list-group-item:hover {
        background-color: rgba(105, 108, 255, 0.04);
    }
</style>
@endpush

@push('scripts')
<script>
    // Filter functionality
    document.getElementById('periodFilter')?.addEventListener('change', function() {
        const period = this.value;
        window.location.href = `{{ route('admin.staff.performance') }}?period=${period}`;
    });

    // Search functionality
    document.getElementById('searchStaff')?.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });

    // Sort functionality
    document.getElementById('sortBy')?.addEventListener('change', function() {
        const sortBy = this.value;
        window.location.href = `{{ route('admin.staff.performance') }}?sort=${sortBy}`;
    });

    // View staff details
    function viewStaffDetails(staffId) {
        fetch(`/admin/staff/${staffId}/performance-details`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('staffDetailContent').innerHTML = html;
                const modal = new bootstrap.Modal(document.getElementById('staffDetailModal'));
                modal.show();

                // Initialize tooltips in modal
                const tooltipTriggerList = [].slice.call(
                    document.querySelectorAll('#staffDetailModal [data-bs-toggle="tooltip"]')
                );
                const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to load staff details');
            });
    }

    // Export to Excel
    function exportToExcel() {
        const period = document.getElementById('periodFilter')?.value || 'month';
        const sortBy = document.getElementById('sortBy')?.value || 'total_approved';

        window.open(
            `{{ route('admin.staff.performance') }}?export=excel&period=${period}&sort=${sortBy}`,
            '_blank'
        );
    }

    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Auto-hide alerts
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const closeBtn = alert.querySelector('.btn-close');
                if (closeBtn) {
                    closeBtn.click();
                }
            });
        }, 5000);
    });
</script>
@endpush
@endsection
