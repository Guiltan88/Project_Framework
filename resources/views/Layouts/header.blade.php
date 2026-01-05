<!-- Navbar -->
<nav class="layout-navbar container-xxl navbar-detached navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
            <i class="icon-base bx bx-menu icon-md"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center justify-content-end" id="navbar-collapse">

        <ul class="navbar-nav flex-row align-items-center ms-md-auto">
            <!-- Notifications Bell -->
            @php
                $user = Auth::user();

                // Get pending bookings count based on role
                $pendingCount = 0;
                if ($user->hasRole('staff')) {
                    $pendingCount = \App\Models\Booking::where('status', 'pending')->count();
                } elseif ($user->hasRole('guest')) {
                    $pendingCount = $user->bookings()->where('status', 'pending')->count();
                }

                // Get today's bookings
                $todayBookings = 0;
                if ($user->hasRole('guest')) {
                    $todayBookings = $user->bookings()
                        ->where('status', 'approved')
                        ->whereDate('tanggal_mulai', today())
                        ->count();
                }
            @endphp

            @if($pendingCount > 0 || $todayBookings > 0)
            <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-2">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <i class="bx bx-bell bx-sm"></i>
                    @if($pendingCount > 0)
                    <span class="badge bg-danger badge-dot position-absolute top-0 start-100 translate-middle"></span>
                    @endif
                </a>
                <ul class="dropdown-menu dropdown-menu-end py-0">
                    <li class="dropdown-menu-header border-bottom">
                        <div class="dropdown-header d-flex align-items-center py-3">
                            <h5 class="text-body mb-0 me-auto">Notifications</h5>
                            @if($pendingCount > 0)
                            <span class="badge rounded-pill bg-label-primary">{{ $pendingCount }}</span>
                            @endif
                        </div>
                    </li>
                    <li class="dropdown-notifications-list">
                        <div class="list-group">
                            @if($user->hasRole('staff') && $pendingCount > 0)
                            <a href="{{ route('staff.bookings.index') }}" class="list-group-item list-group-item-action dropdown-notifications-item">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar">
                                            <span class="avatar-initial rounded-circle bg-label-warning">
                                                <i class="bx bx-time-five"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $pendingCount }} Pending Approvals</h6>
                                        <p class="mb-0">Bookings awaiting your review</p>
                                        <small class="text-muted">Just now</small>
                                    </div>
                                </div>
                            </a>
                            @endif

                            @if($user->hasRole('guest') && $pendingCount > 0)
                            <a href="{{ route('guest.bookings.history') }}" class="list-group-item list-group-item-action dropdown-notifications-item">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar">
                                            <span class="avatar-initial rounded-circle bg-label-warning">
                                                <i class="bx bx-time-five"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $pendingCount }} Pending Bookings</h6>
                                        <p class="mb-0">Waiting for staff approval</p>
                                        <small class="text-muted">Recently</small>
                                    </div>
                                </div>
                            </a>
                            @endif

                            @if($user->hasRole('guest') && $todayBookings > 0)
                            <a href="{{ route('guest.bookings.history') }}" class="list-group-item list-group-item-action dropdown-notifications-item">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar">
                                            <span class="avatar-initial rounded-circle bg-label-success">
                                                <i class="bx bx-calendar-check"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $todayBookings }} Today's Bookings</h6>
                                        <p class="mb-0">You have booking(s) scheduled today</p>
                                        <small class="text-muted">Today</small>
                                    </div>
                                </div>
                            </a>
                            @endif
                        </div>
                    </li>
                    <li class="dropdown-menu-footer border-top">
                        <a href="javascript:void(0);" class="dropdown-item d-flex justify-content-center text-primary p-2 h-100">
                            View all notifications
                        </a>
                    </li>
                </ul>
            </li>
            @endif

            <!-- User Dropdown Navbar -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar {{ $user->hasRole('admin') ? 'avatar-online' : ($user->hasRole('staff') ? 'avatar-busy' : 'avatar-away') }}">
                        @php
                            // Helper function untuk foto profile
                            function getUserPhoto($user) {
                                // Jika user punya foto di database
                                if ($user->photo) {
                                    $photoPath = 'storage/' . $user->photo;
                                    if (file_exists(public_path($photoPath))) {
                                        return asset($photoPath);
                                    }
                                }

                                // Generate avatar berdasarkan role dan nama
                                $role = $user->getRoleNames()->first() ?? 'guest';
                                $name = urlencode($user->name);

                                // Warna berbeda berdasarkan role
                                $colors = [
                                    'admin' => 'DC3545',    // Merah untuk admin
                                    'staff' => '0D6EFD',    // Biru untuk staff
                                    'guest' => '198754',    // Hijau untuk guest
                                ];

                                $color = $colors[$role] ?? '696CFF'; // Default ungu

                                return "https://ui-avatars.com/api/?name={$name}&background={$color}&color=fff&size=150&bold=true&format=svg";
                            }

                            $photoUrl = getUserPhoto($user);
                            $roleName = $user->getRoleNames()->first() ?? 'Guest';
                            $roleBadgeColor = $user->hasRole('admin') ? 'danger' : ($user->hasRole('staff') ? 'primary' : 'success');
                        @endphp

                        <img
                            src="{{ $photoUrl }}"
                            alt="{{ $user->name }}"
                            class="rounded-circle"
                            height="40"
                            width="40"
                            style="object-fit: cover; border: 2px solid var(--bs-{{ $roleBadgeColor }});"
                            onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=696CFF&color=fff&size=150'"
                        />

                        <!-- Role indicator dot -->
                        <span class="avatar-status {{ $user->hasRole('admin') ? 'bg-danger' : ($user->hasRole('staff') ? 'bg-primary' : 'bg-success') }}"></span>
                    </div>
                </a>

                <ul class="dropdown-menu dropdown-menu-end">
                    <!-- User Info with Role Badge -->
                    <li class="dropdown-header">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar {{ $user->hasRole('admin') ? 'avatar-online' : ($user->hasRole('staff') ? 'avatar-busy' : 'avatar-away') }}">
                                    <img
                                        src="{{ $photoUrl }}"
                                        alt="{{ $user->name }}"
                                        class="rounded-circle"
                                        height="48"
                                        width="48"
                                        style="object-fit: cover; border: 2px solid var(--bs-{{ $roleBadgeColor }});"
                                    />
                                    <span class="avatar-status {{ $user->hasRole('admin') ? 'bg-danger' : ($user->hasRole('staff') ? 'bg-primary' : 'bg-success') }}"></span>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <span class="fw-semibold d-block">{{ $user->name }}</span>
                                <div class="d-flex align-items-center mt-1">
                                    <span class="badge bg-{{ $roleBadgeColor }} rounded-pill">
                                        {{ ucfirst($roleName) }}
                                    </span>
                                </div>
                                <small class="text-muted d-block mt-1">{{ $user->email }}</small>
                            </div>
                        </div>
                    </li>

                    <li><div class="dropdown-divider my-2"></div></li>

                    <!-- MY PROFILE - Dynamic based on role -->
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{
                            $user->hasRole('admin') ? route('admin.profile.index') :
                            ($user->hasRole('staff') ? route('staff.profile.index') :
                            route('guest.profile.index'))
                        }}">
                            <i class="bx bx-user me-2"></i>
                            <span>My Profile</span>

                        </a>
                    </li>

                    <!-- Dashboard Link -->
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('dashboard') }}">
                            <i class="bx bx-home me-2"></i>
                            <span>Dashboard</span>

                        </a>
                    </li>

                    @if($user->hasRole('admin'))
                    <!-- Admin Specific Links -->
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.users.index') }}">
                            <i class="bx bx-group me-2"></i>
                            <span>Manage Users</span>
                            <span class="badge bg-danger rounded-pill ms-auto">{{ \App\Models\User::count() }}</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.bookings.index') }}">
                            <i class="bx bx-calendar me-2"></i>
                            <span>All Bookings</span>
                            <span class="badge bg-primary rounded-pill ms-auto">{{ \App\Models\Booking::count() }}</span>
                        </a>
                    </li>
                    @endif

                    @if($user->hasRole('staff'))
                    <!-- Staff Specific Links -->
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('staff.bookings.index') }}">
                            <i class="bx bx-check-circle me-2"></i>
                            <span>Pending Approvals</span>
                            @php
                                $pendingCount = \App\Models\Booking::where('status', 'pending')->count();
                            @endphp
                            @if($pendingCount > 0)
                            <span class="badge bg-danger rounded-pill ms-auto">{{ $pendingCount }}</span>
                            @else
                            <span class="badge bg-success rounded-pill ms-auto">0</span>
                            @endif
                        </a>
                    </li>
                    @endif

                    @if($user->hasRole('guest'))
                    <!-- Guest Specific Links -->
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('guest.bookings.history') }}">
                            <i class="bx bx-history me-2"></i>
                            <span>My Bookings</span>
                            <span class="badge bg-warning rounded-pill ms-auto">{{ $user->bookings()->count() }}</span>
                        </a>
                    </li>
                    @endif

                    <li><div class="dropdown-divider my-2"></div></li>

                    <!-- LOGOUT -->
                    <li>
                        <a class="dropdown-item d-flex align-items-center text-danger" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bx bx-power-off me-2"></i>
                            <span>Log Out</span>
                            <span class="badge bg-label-danger rounded-pill ms-auto">Exit</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Logout Form Hidden -->
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </ul>
    </div>
</nav>
<!-- / Navbar -->

@push('styles')
<style>
    .avatar-online .avatar-status {
        background-color: #28a745 !important;
        border: 2px solid #fff;
        bottom: 0;
        right: 0;
        width: 10px;
        height: 10px;
    }

    .avatar-busy .avatar-status {
        background-color: #ffc107 !important;
        border: 2px solid #fff;
        bottom: 0;
        right: 0;
        width: 10px;
        height: 10px;
    }

    .avatar-away .avatar-status {
        background-color: #0d6efd !important;
        border: 2px solid #fff;
        bottom: 0;
        right: 0;
        width: 10px;
        height: 10px;
    }

    .badge.bg-label-danger {
        background-color: rgba(220, 53, 69, 0.1);
        color: #dc3545;
    }

    .badge.bg-label-primary {
        background-color: rgba(13, 110, 253, 0.1);
        color: #0d6efd;
    }

    .badge.bg-label-success {
        background-color: rgba(25, 135, 84, 0.1);
        color: #198754;
    }

    .badge.bg-label-warning {
        background-color: rgba(255, 193, 7, 0.1);
        color: #ffc107;
    }

    .badge.bg-label-info {
        background-color: rgba(13, 202, 240, 0.1);
        color: #0dcaf0;
    }

    .badge.bg-label-secondary {
        background-color: rgba(108, 117, 125, 0.1);
        color: #6c757d;
    }

    .dropdown-notifications-item:hover {
        background-color: rgba(105, 108, 255, 0.04);
    }

    .cursor-pointer {
        cursor: pointer;
    }
</style>
@endpush

@push('scripts')
<script>
    // Dark mode toggle
    function toggleDarkMode() {
        const html = document.documentElement;
        const toggle = document.getElementById('darkModeToggle');

        if (html.getAttribute('data-bs-theme') === 'dark') {
            html.setAttribute('data-bs-theme', 'light');
            toggle.checked = false;
            localStorage.setItem('theme', 'light');
        } else {
            html.setAttribute('data-bs-theme', 'dark');
            toggle.checked = true;
            localStorage.setItem('theme', 'dark');
        }
    }

    // Load saved theme
    document.addEventListener('DOMContentLoaded', function() {
        const savedTheme = localStorage.getItem('theme') || 'light';
        const html = document.documentElement;
        const toggle = document.getElementById('darkModeToggle');

        html.setAttribute('data-bs-theme', savedTheme);
        toggle.checked = savedTheme === 'dark';

        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush
